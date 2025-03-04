<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Utils\BusinessUtil;
use App\Utils\ModuleUtil;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Essentials\Entities\EssentialsLeave;
use Modules\Essentials\Entities\EssentialsTodoComment;
use Modules\Essentials\Entities\EssentialsUserAllowancesAndDeduction;
use Modules\Essentials\Entities\EssentialsUserLeaveAndDeductionsTransaction;
use Modules\Essentials\Entities\EssentialsUserLeaveAndDeduction;
use Modules\Essentials\Entities\EssentialsLeaveType;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * All Utils instance.
     */
    protected $businessUtil;

    protected $moduleUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BusinessUtil $businessUtil, ModuleUtil $moduleUtil)
    {
        $this->middleware('guest')->except('logout');
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Change authentication from email to username
     *
     * @return void
     */
    public function username()
    {
        return 'username';
    }

    public function logout()
    {
        $this->businessUtil->activityLog(auth()->user(), 'logout');

        request()->session()->flush();
        \Auth::logout();

        return redirect('/login');
    }

    /**
     * The user has been authenticated.
     * Check if the business is active or not.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $this->businessUtil->activityLog($user, 'login', null, [], false, $user->business_id);

        if (!$user->business->is_active) {
            \Auth::logout();

            return redirect('/login')
                ->with(
                    'status',
                    ['success' => 0, 'msg' => __('lang_v1.business_inactive')]
                );
        } elseif ($user->status != 'active') {
            \Auth::logout();

            return redirect('/login')
                ->with(
                    'status',
                    ['success' => 0, 'msg' => __('lang_v1.user_inactive')]
                );
        } elseif (!$user->allow_login) {
            \Auth::logout();

            return redirect('/login')
                ->with(
                    'status',
                    ['success' => 0, 'msg' => __('lang_v1.login_not_allowed')]
                );
        } elseif (($user->user_type == 'user_customer') && !$this->moduleUtil->hasThePermissionInSubscription($user->business_id, 'crm_module')) {
            \Auth::logout();

            return redirect('/login')
                ->with(
                    'status',
                    ['success' => 0, 'msg' => __('lang_v1.business_dont_have_crm_subscription')]
                );
        }
    }

    protected function redirectTo()
    {   


        $user = \Auth::user();
        $leave_types = EssentialsUserLeaveAndDeduction::where('essentials_user_leave_and_deductions.user_id',$user->id)
         ->join('essentials_leave_types as let', 'essentials_user_leave_and_deductions.leave_id', '=', 'let.id')
         ->where('let.type',0)
        ->get();



        //$leave_types = $leave_types->get();
        

        foreach ($leave_types as $key => $leave_type) {

          
            //echo $leave_type;
            $user_leave = EssentialsLeaveType::where('id',$leave_type->leave_id)->first();
            if($user_leave->leave_count_interval == 'month'){

             $check_variable = date('M-Y');
             }elseif ($user_leave->leave_count_interval == 'year') {
                $check_variable = date('Y');
             }
             //echo $check_variable;
             //echo '<pre>';
             //print_r($user_leave);
            $user_leave_check  = EssentialsUserLeaveAndDeductionsTransaction::where('added_date',$check_variable)->where('user_id',$user->id)->where('leave_type_deduction_id',$leave_type->id)->where('leave_type_id',$leave_type->leave_id)->first();

            //echo $user_leave_check;exit;

            if(empty($user_leave_check)){
               $euldt = new EssentialsUserLeaveAndDeductionsTransaction();
               $euldt->user_id = $user->id;
               $euldt->leave_type_id = $leave_type->leave_id;
               $euldt->leave_type_deduction_id = $leave_type->id;
               //$euldt->type = $user->id;
               $euldt->leave_count = $user_leave->max_leave_count;
               $euldt->added_date = $check_variable;
               if($euldt->save()){
                
                 $leave_update = EssentialsUserLeaveAndDeduction::where('leave_id',$leave_type->id)->where('user_id',$user->id)->first();
                 $leave_update->balance = $leave_update->balance+$user_leave->max_leave_count;
                 $leave_update->save(); 

               }
             }
        }
        //exit;

        if (!$user->can('dashboard.data') && $user->can('sell.create')) {
            return '/pos/create';
        }

        if ($user->user_type == 'user_customer') {
            return 'contact/contact-dashboard';
        }

        return '/home';
    }
}
