<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\Notifications\UserNotification;
use App\User;
use App\UserDocument;
use App\Utils\ModuleUtil;
use App\Utils\Util;
use App\Utils\NotificationUtil;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Notification;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Modules\Essentials\Entities\EssentialsUserLeaveAndDeduction;
use Modules\Essentials\Entities\EssentialsUserLeaveAndDeductionsTransaction;

class ManageUserController extends Controller
{
    /**
     * Constructor
     *
     * @param
     * @return void
     */
     protected $notificationUtil;

    public function __construct(ModuleUtil $moduleUtil, Util $commonUtil,NotificationUtil $notificationUtil )
    {
        $this->moduleUtil = $moduleUtil;
        $this->commonUtil = $commonUtil;
        $this->notificationUtil = $notificationUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('user.view') && !auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $user_id = request()->session()->get('user.id');

            $users = User::where('business_id', $business_id)
                ->user()
                ->where('is_cmmsn_agnt', 0)
                ->select(['id', 'username',
                    DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as full_name"), 'email', 'allow_login']);

            return Datatables::of($users)
                ->editColumn('username', '{{$username}} @if(empty($allow_login)) <span class="label bg-gray">@lang("lang_v1.login_not_allowed")</span>@endif')
                ->addColumn(
                    'role',
                    function ($row) {
                        $role_name = $this->moduleUtil->getUserRoleName($row->id);

                        return $role_name;
                    }
                )
                ->addColumn(
                    'action',
                    '@can("user.update")
                        <a href="{{action(\'App\Http\Controllers\ManageUserController@edit\', [$id])}}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                        &nbsp;
                    @endcan
                    @can("user.view")
                    <a href="{{action(\'App\Http\Controllers\ManageUserController@show\', [$id])}}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang("messages.view")</a>
                    &nbsp;
                    @endcan
                    @can("user.delete")
                        <button data-href="{{action(\'App\Http\Controllers\ManageUserController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_user_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                      &nbsp;
                    @endcan
                    @can("document.view")
                    <a href="{{action(\'App\Http\Controllers\ManageUserController@docList\', [$id])}}" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-plus"></i> @lang("messages.doc")</a>
                    @endcan'
                )
                ->filterColumn('full_name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) like ?", ["%{$keyword}%"]);
                })
                ->removeColumn('id')
                ->rawColumns(['action', 'username'])
                ->make(true);
        }

        return view('manage_user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function docList($id)
     {
       if (!auth()->user()->can('user.view') && !auth()->user()->can('user.create')) {
         abort(403, 'Unauthorized action.');
       }

       if (request()->ajax()) {
         $data = UserDocument::where('user_id',$id)->get();

         return DataTables::of($data)
         ->addColumn(
             'action',
             '@can("document.delete")
             <button data-id="{{$id}}" class="btn btn-xs btn-danger delete_doc_button"><i class="glyphicon glyphicon-trash"></i></button>
             @endcan'
         )
         ->editColumn('doc_name', function ($row) {
            // Customize the content of the custom column
            $url = asset('uploads/user_documents/'.$row->document);
            return '<a href="'.$url.'" download>'.$row->doc_name.'</a>';
        })
         ->rawColumns(['action', 'username','doc_name'])
         ->make(true);
       }

       return view('manage_user.document_list');
     }

     public function downloadImage()
    {
        $filePath = public_path('uploads/user_documents'); // Adjust the path accordingly

        return response()->download($filePath, 'downloaded_image.jpg');
    }

    public function create()
    {

        if (!auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        //Check if subscribed or not, then check for users quota
        if (!$this->moduleUtil->isSubscribed($business_id)) {
            return $this->moduleUtil->expiredResponse();
        } elseif (!$this->moduleUtil->isQuotaAvailable('users', $business_id)) {
            return $this->moduleUtil->quotaExpiredResponse('users', $business_id, action([\App\Http\Controllers\ManageUserController::class, 'index']));
        }

        $roles = $this->getRolesArray($business_id);
        $mangers = $this->getUsersArray($business_id);

        $username_ext = $this->moduleUtil->getUsernameExtension();
        $locations = BusinessLocation::where('business_id', $business_id)
            ->Active()
            ->get();

        //Get user form part from modules
        $form_partials = $this->moduleUtil->getModuleData('moduleViewPartials', ['view' => 'manage_user.create']);

        return view('manage_user.create')
            ->with(compact('roles', 'username_ext', 'locations', 'form_partials', 'mangers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $business_id = request()->session()->get('user.business_id');
        if (!auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            if (!empty($request->input('dob'))) {
              // dd('slkdklf');
                $request['dob'] = date('Y-m-d',strtotime($request->input('dob')));
            }

            $request['cmmsn_percent'] = !empty($request->input('cmmsn_percent')) ? $this->moduleUtil->num_uf($request->input('cmmsn_percent')) : 0;

            $request['max_sales_discount_percent'] = !is_null($request->input('max_sales_discount_percent')) ? $this->moduleUtil->num_uf($request->input('max_sales_discount_percent')) : null;

            // $maildata = [
            //     'subject' => "Welcome Mail",
            //     'name' => $request->input('first_name') . '' . $request->input('last_name'),
            //     'username' => $request->input('username'),
            //     'password' => $request->input('password'),
            // ];
            // Notification::route('mail', [$request->input('email')])
            //     ->notify(new UserNotification($maildata));
            // dd($business_id);
            // dd($request->all());
            $whatsapp_link = $this->notificationUtil->autoSendWelcomeNotification($business_id, 'new_customer',$request);
            // dd($whatsapp_link);
            $user = $this->moduleUtil->createUser($request);
            // dd($user);
            if (!empty($request->input('leave_type'))) {
              // dd('dsklfskjf');
              $leave_ids = $request->input('leave_type');
              foreach ($leave_ids as  $key=>$leave_type) {
                     $checkData = EssentialsUserLeaveAndDeduction::where('user_id',$user->id)->where('leave_id',$leave_type)->first();
                     if(empty($checkData)){
                        EssentialsUserLeaveAndDeduction::insert(['user_id' => $user->id, 'leave_id' => $leave_type,'business_id'=>$business_id]);
                     }
               }
            }
            $output = ['success' => 1,
                'msg' => __('user.user_added'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect('users')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('user.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $user = User::where('business_id', $business_id)
            ->with(['contactAccess'])
            ->find($id);

        //Get user view part from modules
        $view_partials = $this->moduleUtil->getModuleData('moduleViewPartials', ['view' => 'manage_user.show', 'user' => $user]);

        $users = User::forDropdown($business_id, false);

        $activities = Activity::forSubject($user)
            ->with(['causer', 'subject'])
            ->latest()
            ->get();

        return view('manage_user.show')->with(compact('user', 'view_partials', 'users', 'activities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $user = User::where('business_id', $business_id)
            ->with(['contactAccess'])
            ->findOrFail($id);

        $roles = $this->getRolesArray($business_id);

        $contact_access = $user->contactAccess->pluck('name', 'id')->toArray();

        if ($user->status == 'active') {
            $is_checked_checkbox = true;
        } else {
            $is_checked_checkbox = false;
        }

        $locations = BusinessLocation::where('business_id', $business_id)
            ->get();

        $permitted_locations = $user->permitted_locations();
        $username_ext = $this->moduleUtil->getUsernameExtension();
        $mangers = $this->getUsersArray($business_id, $id);

        //Get user form part from modules
        $form_partials = $this->moduleUtil->getModuleData('moduleViewPartials', ['view' => 'manage_user.edit', 'user' => $user]);

        return view('manage_user.edit')
            ->with(compact('roles', 'mangers', 'user', 'contact_access', 'is_checked_checkbox', 'locations', 'permitted_locations', 'form_partials', 'username_ext'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $user_data = $request->only(['surname', 'first_name', 'last_name', 'email', 'parent_id', 'selected_contacts', 'marital_status',
                'blood_group', 'contact_number', 'fb_link', 'twitter_link', 'social_media_1',
                'social_media_2', 'permanent_address', 'current_address',
                'guardian_name', 'custom_field_1', 'custom_field_2',
                'custom_field_3', 'custom_field_4', 'id_proof_name', 'id_proof_number', 'cmmsn_percent', 'gender', 'max_sales_discount_percent', 'family_number', 'alt_number']);

            $user_data['status'] = !empty($request->input('is_active')) ? 'active' : 'inactive';
            $business_id = request()->session()->get('user.business_id');
            // dd($user_data);
            if (!isset($user_data['selected_contacts'])) {
                $user_data['selected_contacts'] = 0;
            }

            if (empty($request->input('allow_login'))) {
                $user_data['username'] = null;
                $user_data['password'] = null;
                $user_data['allow_login'] = 0;
            } else {
                $user_data['allow_login'] = 1;
            }

            if (!empty($request->input('password'))) {
                $user_data['password'] = $user_data['allow_login'] == 1 ? Hash::make($request->input('password')) : null;
            }

            //Sales commission percentage
            $user_data['cmmsn_percent'] = !empty($user_data['cmmsn_percent']) ? $this->moduleUtil->num_uf($user_data['cmmsn_percent']) : 0;

            $user_data['max_sales_discount_percent'] = !is_null($user_data['max_sales_discount_percent']) ? $this->moduleUtil->num_uf($user_data['max_sales_discount_percent']) : null;

            if (!empty($request->input('dob'))) {
                $user_data['dob'] = $request->input('dob');
            }

            if (!empty($request->input('bank_details'))) {
                $user_data['bank_details'] = json_encode($request->input('bank_details'));
            }
            if (!empty($request->input('leave_type'))) {
              $incomingLeaveTypeId = $request->input('leave_type');
              // Get all leave types for the user from the database
              $existingLeaveTypes = EssentialsUserLeaveAndDeduction::where('user_id', $id)->withTrashed()->get();
              // dd($existingLeaveTypes);
              if($existingLeaveTypes->isNotEmpty()){
                $existingLeaveTypeIds_old = $existingLeaveTypes->pluck('leave_id')->toArray();

                // Convert values to strings
                foreach ($existingLeaveTypeIds_old as $key => $value) {
                    $existingLeaveTypeIds[$key] = (string) $value;
                }
                // Leave types to be deleted (present in the database but not in the incoming array)
                $leaveTypesToDelete = array_diff($existingLeaveTypeIds, $incomingLeaveTypeId);
                EssentialsUserLeaveAndDeduction::whereIn('leave_id', $leaveTypesToDelete)->where('user_id',$id)->delete();
                $transaction_leave = EssentialsUserLeaveAndDeductionsTransaction::whereIn('leave_type_id', $leaveTypesToDelete)->where('user_id',$id)->get();
                if(!empty($transaction_leave)){
                  $transaction_leave->each(function ($transaction) {
                        $transaction->delete();
                      });
                }
                // dd($incomingLeaveTypeIds);
                foreach ($incomingLeaveTypeId as  $key=>$leave_type) {
                       $checkData = EssentialsUserLeaveAndDeduction::where('user_id',$id)->where('leave_id',$leave_type)->withTrashed()->first();
                       if($checkData){
                         $checkData->restore();
                       }elseif (empty($checkData)) {
                         $saveData = new EssentialsUserLeaveAndDeduction();
                         $saveData->user_id = $id;
                         $saveData->leave_id = $leave_type;
                         $saveData->business_id = $business_id;
                         $saveData->save();
                       }
                 }
              }else {
                foreach ($incomingLeaveTypeId as  $key=>$leave_type) {
                       $checkData = EssentialsUserLeaveAndDeduction::where('user_id',$id)->where('leave_id',$leave_type)->withTrashed()->first();
                       if(empty($checkData)){
                         // dd('dsklkd');
                         $saveData = new EssentialsUserLeaveAndDeduction();
                         $saveData->user_id = $id;
                         $saveData->leave_id = $leave_type;
                         $saveData->business_id = $business_id;
                         $saveData->save();
                          // EssentialsUserLeaveAndDeduction::insert(['user_id' => $id, 'leave_id' => $leave_type,'business_id'=>$business_id]);
                       }
                 }
              }


            }else {
              $checkData = EssentialsUserLeaveAndDeduction::where('user_id',$id)->delete();
              $deleteLeaveTransactionData = EssentialsUserLeaveAndDeductionsTransaction::where('user_id',$id)->delete();
              if(!empty($deleteLeaveTransactionData)){
                $deleteLeaveTransactionData->each(function ($transaction) {
                      $transaction->delete();
                    });
              }
            }
            DB::beginTransaction();

            if ($user_data['allow_login'] && $request->has('username')) {
                $user_data['username'] = $request->input('username');
                $ref_count = $this->moduleUtil->setAndGetReferenceCount('username');
                if (blank($user_data['username'])) {
                    $user_data['username'] = $this->moduleUtil->generateReferenceNumber('username', $ref_count);
                }

                $username_ext = $this->moduleUtil->getUsernameExtension();
                if (!empty($username_ext)) {
                    $user_data['username'] .= $username_ext;
                }
            }
            if (!empty($request->first_name)) {
              $user_data['first_name']  = $request->first_name;
            }
            $user = User::where('business_id', $business_id)
                ->findOrFail($id);
                // dd($user);

            $user->update($user_data);
            $role_id = $request->input('role');
            $user_role = $user->roles->first();
            $previous_role = !empty($user_role->id) ? $user_role->id : 0;
            if ($previous_role != $role_id) {
                $is_admin = $this->moduleUtil->is_admin($user);
                $all_admins = $this->getAdmins();
                //If only one admin then can not change role
                if ($is_admin && count($all_admins) <= 1) {
                    throw new \Exception(__('lang_v1.cannot_change_role'));
                }
                if (!empty($previous_role)) {
                    $user->removeRole($user_role->name);
                }

                $role = Role::findOrFail($role_id);
                $user->assignRole($role->name);
            }
            // dd('sdkdk');
            if (!empty($request->input('access_all_locations')) || !empty($request->input('location_permissions'))) {
              $this->moduleUtil->giveLocationPermissions($user, $request);
            }
            //Grant Location permissions


            //Assign selected contacts
            if ($user_data['selected_contacts'] == 1) {
                $contact_ids = $request->get('selected_contact_ids');
            } else {
                $contact_ids = [];
            }
            $user->contactAccess()->sync($contact_ids);

            //Update module fields for user
            $this->moduleUtil->getModuleData('afterModelSaved', ['event' => 'user_saved', 'model_instance' => $user]);

            $this->moduleUtil->activityLog($user, 'edited', null, ['name' => $user->user_full_name]);
            //update manager users
            $managerUsers = $this->commonUtil->getManagerUSers($business_id, $user->id);
            session(['managerUsers' => $managerUsers]);

            $output = ['success' => 1,
                'msg' => __('user.user_update_success'),
            ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = ['success' => 0,
                'msg' => $e->getMessage(),
            ];
        }

        return redirect('users')->with('status', $output);
    }

    private function getAdmins()
    {
        $business_id = request()->session()->get('user.business_id');
        $admins = User::role('Admin#' . $business_id)->get();

        return $admins;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('user.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $user = User::where('business_id', $business_id)
                    ->findOrFail($id);

                $this->moduleUtil->activityLog($user, 'deleted', null, ['name' => $user->user_full_name, 'id' => $user->id]);

                $user->delete();
                $output = ['success' => true,
                    'msg' => __('user.user_delete_success'),
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

    public function docDestroy($id)
    {
        if (!auth()->user()->can('user.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $user = UserDocument::findOrFail($id);
                $user->delete();
                $output = ['success' => true,
                    'msg' => __('user.document_delete_success'),
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


    public function addDoc(Request $request)
    {
      if (!auth()->user()->can('user.create')) {
          abort(403, 'Unauthorized action.');
      }

      try {
          $user_details = $request->only([
              'user_id', 'doc_name', 'document', 'doc_note',
          ]);
          // dd($user_details);
          $business_id = request()->session()->get('user.business_id');
          $saveData = new UserDocument();
          $saveData->user_id = $request->user_id;
          $saveData->business_id = $business_id;
          $saveData->doc_name = $request->doc_name;
          $saveData->doc_note = $request->doc_note;
          //upload logo
          $upload_doc = $this->commonUtil->uploadFile($request, 'document', 'user_documents', 'image');
          if (!empty($upload_doc)) {
              $saveData->document = $upload_doc;
          }
          $saveData->save();
          $output = ['success' => 1,
              'msg' => __('user.Doucment added successfully'),
          ];
      } catch (\Exception $e) {
          \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
          $output = ['success' => 0,
              'msg' => __('messages.something_went_wrong'),
          ];
      }

      return redirect('user/docList/'.$request->user_id)->with('status', $output);
    }
    /**
     * Retrives roles array (Hides admin role from non admin users)
     *
     * @param  int  $business_id
     * @return array $roles
     */
    private function getRolesArray($business_id)
    {
        $roles_array = Role::where('business_id', $business_id)->get()->pluck('name', 'id');
        $roles = [];

        $is_admin = $this->moduleUtil->is_admin(auth()->user(), $business_id);

        foreach ($roles_array as $key => $value) {
            if (!$is_admin && $value == 'Admin#' . $business_id) {
                continue;
            }
            $roles[$key] = str_replace('#' . $business_id, '', $value);
        }

        return $roles;
    }

    /**
     * Retrives users array (Hides existing user )
     *
     * @param  int  $business_id
     * @return array $roles
     */
    private function getUsersArray($business_id, $user_id = false)
    {
        if (!empty($user_id)) {
            //$users = User::where('business_id', $business_id)->where('id', "!=", $user_id)->select([DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as full_name"), 'id'])->get();
            $users = User::where('business_id', $business_id)->select([DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as full_name"), 'id'])->get();
        } else {
            $users = User::where('business_id', $business_id)->select([DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as full_name"), 'id'])->get();
        }
        $arrUsers = ['' => 'Select'];
        foreach ($users as $key => $value) {
            $arrUsers[$value['id']] = $value['full_name'];
        }
        return $arrUsers;
    }

    /**
     * Signes in from user id
     *
     * @param  int  $id
     */
    public function signInAsUser($id)
    {
        if (!auth()->user()->can('superadmin') && empty(session('previous_user_id'))) {
            abort(403, 'Unauthorized action.');
        }

        $user_id = auth()->user()->id;
        $username = auth()->user()->username;
        session()->flush();

        if (request()->has('save_current')) {
            session(['previous_user_id' => $user_id, 'previous_username' => $username]);
        }

        Auth::loginUsingId($id);

        return redirect()->route('home');
    }
}
