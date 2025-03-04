<?php

namespace Modules\DocumentSign\Http\Controllers;

use App\BusinessLocation;
use App\User;
use App\DocumentSignDocument;
use App\Utils\ModuleUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mail;
use Modules\DocumentSign\Emails\DocumentMail;
use Modules\DocumentSign\Entities\DocumentSign;
use Modules\DocumentSign\Entities\DocumentSignReceipt;
use Validator;
use Yajra\DataTables\Facades\DataTables;
use Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\HTML;
use PhpOffice\PhpWord\IOFactory;
use Dompdf;

class DocumentSignController extends Controller
{
    protected $moduleUtil;
    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;

    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || auth()->user()->can('documentsign.view_documents') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'documentsign'))) {
            abort(403, 'Unauthorized action.');
        }

        $baseUrl = url('/');
        $user_id = auth()->user()->id;
        $is_admin = $this->moduleUtil->is_admin(auth()->user(), $business_id);

        if (request()->ajax()) {
            $user_id = request()->session()->get('user.id');

            $documents = DocumentSign::where('business_id', $business_id)
                ->select(['id', 'title', 'document'])->get();
            //print_r($documents);
            return Datatables::of($documents)
                ->addColumn(
                    'action',
                    function ($row) {
                        //dd(row);
                        $html = '';
                        if (auth()->user()->can('documentsign.crud_documents')) {
                            if (empty($row->is_approved)) {
                                // $html .= '<a  class="btn btn-primary btn-xs" href="' . action([\Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'edit'], [$row->id]) . '" ><i class="fa fa-edit" aria-hidden="true"></i> </a> ';
                            }
                            $html .= '<a  class="btn btn-primary btn-xs" href="' . action([\Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'show'], [$row->id]) . '" ><i class="fa fa-eye" aria-hidden="true"></i> </a> ';

                            $html .= '&nbsp; <button data-href="' . action([\Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'destroy'], [$row->id]) . '" class="delete_document_button btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> </button>';

                        }
                        if (auth()->user()->can('documentsign.view_documents')) {
                            $html .= '&nbsp; <a target="_blank" href="' . url('uploads/documents/' . $row->document) . '" class="btn btn-success btn-xs"><i class="fa fa-download" aria-hidden="true"></i> </a>';
                        }

                        return $html;
                    }
                )

                ->removeColumn('id')
                ->removeColumn('document')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('documentsign::documentsign.index')->with(compact('is_admin'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || auth()->user()->can('documentsign.crud_documents') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'documentsign'))) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::forDropdown($business_id, false);
        $emailusers = User::forDropdownEmail($business_id, false);
        $business_locations = BusinessLocation::forDropdown($business_id, true);
        return view('documentsign::documentsign.create')
            ->with(compact('users', 'emailusers', 'business_locations'));

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
      // dd($request->all());
        $business_id = $request->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || auth()->user()->can('documentsign.crud_documents') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'documentsign'))) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $user_id = $request->session()->get('user.id');
            $documentPaths = [];

             // Save each uploaded document and store its path
             foreach ($request->document as $document) {
               if ($document->getClientOriginalExtension() === 'doc' || $document->getClientOriginalExtension() === 'docx') {
                 $fileName = md5(microtime() . $document->getClientOriginalName()).'.'.$document->extension();
        $document->move(public_path('uploads/documents'), $fileName);

        $domPdfPath = base_path('vendor/dompdf/dompdf');

        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
        $Content = \PhpOffice\PhpWord\IOFactory::load(public_path('uploads/documents/'.$fileName));
        $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'PDF');

        $PDFWriter->save(public_path('uploads/documents/'.$fileName));
        $destinationPath = public_path().'/uploads/documents/';
        // Add PDF path to array
        $documentPaths[] = $destinationPath . $fileName;
        }else {
          $document->getClientOriginalName();   // Get File Name
          $fileExtension = $document->getClientOriginalExtension();  // Get File Extension
          $document->getRealPath(); // Get File Real Path
          $document->getSize();   // Get File Size
          $document->getMimeType();  // Get File Mime Type
          $fileName = md5(microtime() . $document->getClientOriginalName()) . "." . $fileExtension; // Rename file name
          $destinationPath = public_path().'/uploads/documents/';
          $document->move($destinationPath, $fileName);
          $documentPaths[] = $destinationPath . $fileName;;

        }
             }
             // Merge the contents of the uploaded documents
             $mergedContents = '';
            foreach ($documentPaths as $path) {
                $contents = file_get_contents($path);
                $mergedContents .= $contents . "\n"; // Append contents with a newline separator
            }
            // Save the merged document
            $file_Path = uniqid() . '.pdf';
            $mergedDocumentPath = public_path('uploads/merged_documents/' . $file_Path);
            file_put_contents($mergedDocumentPath, $mergedContents);
            $newDocument = new DocumentSign();
            $newDocument->business_id = $business_id;
            $newDocument->location_id = $request->location_id;
            $newDocument->title = $request->title;
            $newDocument->description = $request->description;
            $newDocument->document = $file_Path ;
            $newDocument->status = 1;
            $newDocument->uploaded_by = $user_id;
            $newDocument->save();

            foreach ($documentPaths as $key => $path2) {

              $newDoc = new DocumentSignDocument();
              $newDoc->business_id = $business_id;
              $newDoc->location_id = $request->location_id;
              $newDoc->document_id = $newDocument->id;
              $newDoc->document = $path2;
              $newDoc->save();
            }
            $i = 1;
            foreach ($request->input('receipt') as $receipt) {
                $newDocumentReceipt = new DocumentSignReceipt();
                $newDocumentReceipt->business_id = $business_id;
                $newDocumentReceipt->location_id = $request->input('location_id');
                $newDocumentReceipt->document_id = $newDocument->id;
                $newDocumentReceipt->email = $receipt['email'];
                $newDocumentReceipt->user_id = $receipt['user_id'];
                $newDocumentReceipt->sequence = $receipt['sequence'];
                $newDocumentReceipt->type = $receipt['type'];
                $newDocumentReceipt->save();
                  $senderName = auth()->user()->first_name;
                  $senderEmail = 'sender@example.com';
                  $recipientEmail = $receipt['email'];
                  $mailData = [
                    'title' => $newDocument->title,
                    'user_name' => $receipt['email'],
                    'link' => url('documentsign/document-sign/' . $newDocument->id . '/' . $newDocumentReceipt->id),
                  ];

                  Mail::to($recipientEmail)->send(new DocumentMail($senderName, $senderEmail, $mailData));

                $i++;
            }

            $output = [
                'success' => true,
                'msg' => __('lang_v1.success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
            dd($e);
            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect()->action([\Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'index'])->with('status', $output);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $document = DocumentSign::with('receipters','documents')->findOrFail($id);
        // dd($document);
        return view('documentsign::documentsign.show')->with(compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || auth()->user()->can('documentsign.crud_documents') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'documentsign'))) {
            abort(403, 'Unauthorized action.');
        }

        $document = DocumentSign::with('receipters')->where('business_id', $business_id)->findOrFail($id);
        //dd($document->receipters);
        $users = User::forDropdown($business_id, false);
        $emailusers = User::forDropdownEmail($business_id, false);
        $business_locations = BusinessLocation::forDropdown($business_id, true);
        return view('documentsign::documentsign.edit')
            ->with(compact('users', 'emailusers', 'business_locations', 'document'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $business_id = $request->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || auth()->user()->can('documentsign.crud_documents') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'documentsign'))) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $user_id = $request->session()->get('user.id');

            $exitingDocument = DocumentSign::find($id);
            $exitingDocument->business_id = $business_id;
            $exitingDocument->location_id = $request->input('location_id');
            $exitingDocument->title = $request->input('title');
            $exitingDocument->description = $request->input('description');
            if ($request->hasFile('document') && \File::exists(public_path('uploads/documents/' . $exitingDocument->document))) {
                \File::delete(public_path('uploads/documents/' . $exitingDocument->document));
                $exitingDocument->document = $this->moduleUtil->uploadFile($request, 'document', 'documents');
            }

            $exitingDocument->status = 1;
            $exitingDocument->uploaded_by = $user_id;
            $exitingDocument->save();
            //delete all document recipts
            if (!empty($request->input('receipt'))) {
                DocumentSignReceipt::whereIn('document_id', [$exitingDocument->id])->delete();
            }
            foreach ($request->input('receipt') as $receipt) {
                $newDocumentReceipt = new DocumentSignReceipt();
                $newDocumentReceipt->business_id = $business_id;
                $newDocumentReceipt->location_id = $request->input('location_id');
                $newDocumentReceipt->document_id = $exitingDocument->id;
                $newDocumentReceipt->email = $receipt['email'];
                $newDocumentReceipt->user_id = $receipt['user_id'];
                $newDocumentReceipt->sequence = $receipt['sequence'];
                $newDocumentReceipt->save();
            }

            $output = [
                'success' => true,
                'msg' => __('lang_v1.success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
            dd($e);
            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect()->action([\Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'index'])->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || auth()->user()->can('documentsign.crud_documents') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'documentsign'))) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $exitingDocument = DocumentSign::find($id);

                //delete the document file
                if (\File::exists(public_path('uploads/documents/' . $exitingDocument->document))) {
                    \File::delete(public_path('uploads/documents/' . $exitingDocument->document));

                }
                DocumentSign::where('business_id', $business_id)
                    ->where('id', $id)
                    ->delete();
                //delete all document recipts
                DocumentSignReceipt::whereIn('document_id', [$id])->delete();
                $output = ['success' => true,
                    'msg' => __('lang_v1.deleted_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }

    }
    public function uploadDocs(Request $request)
    {

        $data = array();

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:avi,pdf,avi |max:1000000',
        ]);

        if ($validator->fails()) {

            $data['success'] = 0;
            $data['error'] = "Only pdf allow"; // Error response
            //abort(403, $validator->errors()->first('file'));

        } else {
            if ($request->file('file')) {

                $file = $request->file('file');
                $file_size = $file->getSize();

                $filename = time() . '_' . $file->getClientOriginalName();

                // File extension
                $extension = $file->getClientOriginalExtension();

                // File upload location
                $location = public_path('uploads/sign_documents');

                // Upload file
                $file->move($location, $filename);

                // File path
                $filepath = 'uploads/sign_documents/' . $filename;
                $fileurl = url('uploads/sign_documents/' . $filename);

                // Response
                $data['success'] = 1;
                $data['message'] = 'Uploaded Successfully!';
                $data['url'] = $fileurl;
                $data['filepath'] = $filepath;
                $data['extension'] = $extension;
                $data['size'] = $file_size;
            } else {
                // Response
                $data['success'] = 2;
                $data['message'] = 'File not uploaded.';
            }
        }

        return response()->json($data);
    }
    public function removeDocs(Request $request)
    {

        $data = array();

        $validator = Validator::make($request->all(), [
            'filepath' => 'required',
        ]);

        if ($validator->fails()) {

            $data['success'] = 0;
            $data['error'] = $validator->errors()->first('filepath'); // Error response

        } else {
            if (!empty($request->only(['filepath']))) {
                $allInput = $request->only(['filepath']);
                if (\File::exists(public_path($allInput['filepath']))) {
                    \File::delete(public_path($allInput['filepath']));
                    $data['success'] = 1;
                    $data['message'] = 'Deleted Successfully!';
                    $data['filepath'] = $allInput['filepath'];
                } else {
                    $data['success'] = 2;
                    $data['message'] = 'File not deleted!';

                }

            } else {
                // Response
                $data['success'] = 3;
                $data['message'] = 'File not deleted.';
            }
        }

        return response()->json($data);
    }
    public function sign($id, $receipt_id)
    {

        $document = DocumentSign::with('receipters','documents')->findOrFail($id);
        // dd($document->document);
        return view('documentsign::documentsign.sign')
            ->with(compact('document'));
    }
}
