<?php

namespace Modules\Essentials\Http\Controllers;

use App\User;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Essentials\Entities\Training;
use Spatie\Permission\Models\Role;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class TrainingController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param  ModuleUtil  $moduleUtil
     * @return void
     */
    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $user_id = auth()->user()->id;
        $is_admin = $this->moduleUtil->is_admin(auth()->user(), $business_id);

        if (request()->ajax()) {
            $user_id = request()->session()->get('user.id');

            $trainings = Training::where('business_id', $business_id)
                ->select(['id', 'title'])->get();
            //print_r($trainings);
            return Datatables::of($trainings)
                ->addColumn(
                    'action',
                    '@can("training.create_training")
                        <a href="{{action(\'Modules\Essentials\Http\Controllers\TrainingController@edit\', [$id])}}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                        &nbsp;
                     @endcan
                     @can("training.view_training")
                     <a href="{{action(\'Modules\Essentials\Http\Controllers\TrainingController@show\', [$id])}}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang("messages.view")</a>
                     &nbsp;
                     @endcan
                     @can("training.create_training")
                       <button data-href="{{action(\'Modules\Essentials\Http\Controllers\TrainingController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_training_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                     @endcan
                    '
                )

                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('essentials::training.index')->with(compact('is_admin'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || auth()->user()->can('training.create_training') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $parent = null;
        $users = null;
        if (!empty(request()->input('parent'))) {
            $parent = Training::where('business_id', $business_id)
                ->findOrFail(request()->input('parent'));
        } else {
            $users = User::forDropdown($business_id, false);
        }
        $roles = Role::all()->pluck('name', 'id');

        return view('essentials::training.create')
            ->with(compact('parent', 'users', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || auth()->user()->can('training.create_training') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $user_id = $request->session()->get('user.id');
            // dd($request->input);
            $newTraining = new Training();
            $newTraining->business_id = $business_id;
            $newTraining->role_id = $request->input('role_id');
            $newTraining->title = $request->input('title');
            $newTraining->content = $request->input('content');
            $newTraining->attachment = json_decode($request->input('uploaded_docs'));
            $newTraining->status = 1;
            $newTraining->created_by = $user_id;
            $training = $newTraining->save();

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

        return redirect()->action([\Modules\Essentials\Http\Controllers\TrainingController::class, 'index'])->with('status', $output);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || auth()->user()->can('training.view_training') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $training = Training::where('business_id', $business_id)->find($id);
        //$training->documents();dd($training->documents());
        //dd($training->attachment);
        return view('essentials::training.show')->with(compact('training'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || auth()->user()->can('training.create_training') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
            abort(403, 'Unauthorized action.');
        }

        $training = Training::where('business_id', $business_id)->findOrFail($id);

        $users = [];
        $roles = Role::all()->pluck('name', 'id');
        return view('essentials::training.edit')->with(compact('training', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $business_id = $request->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || auth()->user()->can('training.create_training') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
            abort(403, 'Unauthorized action.');
        }

        try {

            $user_id = $request->session()->get('user.id');

            $newTraining = Training::where('business_id', $business_id)->findOrFail($id);
            //dd($newTraining);
            $newTraining->business_id = $business_id;
            $newTraining->role_id = $request->input('role_id');
            $newTraining->title = $request->input('title');
            $newTraining->content = $request->input('content');
            $newTraining->attachment = json_decode($request->input('uploaded_docs'));
            $newTraining->status = 1;
            $newTraining->created_by = $user_id;
            $training = $newTraining->save();

            $output = [
                'success' => true,
                'msg' => __('lang_v1.success'),
            ];

        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect()->action([\Modules\Essentials\Http\Controllers\TrainingController::class, 'index'])->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $business_id = $request->session()->get('user.business_id');
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $training = Training::where('business_id', $business_id)->find($id);
                foreach ($training->attachment as $document) {
                    if (\File::exists(public_path($document['path']))) {
                        \File::delete(public_path($document['path']));
                    }
                }

                Training::where('business_id', $business_id)
                    ->where('id', $id)
                    ->delete();

                $output = [
                    'success' => true,
                    'msg' => __('lang_v1.success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

                $output = [
                    'success' => false,
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
            'file' => 'required|mimes:png,jpg,jpeg,csv,txt,pdf,mp3,wav,3gp,3ga,3g2,xlsx,xls,wav,weba,txt,ods,odt,doc,docx,avi,MP4,mp4,OGG,ogg,MOV,mov,AVI,avi |max:1000000',
        ]);

        if ($validator->fails()) {

            $data['success'] = 0;
            $data['error'] = $validator->errors()->first('file'); // Error response
            //abort(403, $validator->errors()->first('file'));

        } else {
            if ($request->file('file')) {

                $file = $request->file('file');
                $file_size = $file->getSize();

                $filename = time() . '_' . $file->getClientOriginalName();

                // File extension
                $extension = $file->getClientOriginalExtension();

                // File upload location
                $location = public_path('uploads/training_docs');

                // Upload file
                $file->move($location, $filename);

                // File path
                $filepath = 'uploads/training_docs/' . $filename;
                $fileurl = url('uploads/training_docs/' . $filename);

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

}
