<?php

namespace Modules\Essentials\Http\Controllers;

use App\User;
use App\Utils\ModuleUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Essentials\Entities\EssentialsClaimReimbursement;
use Modules\Essentials\Entities\EssentialsUserClaimReimbursement;
use Modules\Essentials\Utils\EssentialsUtil;
use Yajra\DataTables\Facades\DataTables;

class ClaimReimbursementController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $moduleUtil;
    protected $claim_statuses;
    protected $essentialsUtil;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(ModuleUtil $moduleUtil, EssentialsUtil $essentialsUtil)
    {
        $this->moduleUtil = $moduleUtil;
        $this->essentialsUtil = $essentialsUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');
        $this->claim_statuses = [null => ['class' => 'badge-warning', 'label' => 'Pending', 'style' => ''],
            0 => ['class' => 'badge-green', 'label' => 'Reimbursed', 'style' => 'background:#008000'],
            2 => ['class' => 'badge-danger', 'label' => 'UnApproved', 'style' => ''],
            1 => ['class' => 'badge-success', 'label' => 'Approved', 'style' => ''],
        ];
        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
            abort(403, 'Unauthorized action.');
        }

        if (!auth()->user()->can('essentials.add_claim_reimbursement') && !auth()->user()->can('essentials.view_claim_reimbursement')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $managerUsers = session('managerUsers');
            $managerUsers[] = auth()->user()->id;
            $essential_ids = [];
            $essential_ids = EssentialsUserClaimReimbursement::whereIn('user_id', $managerUsers)->pluck('claim_reimbursement_id')->toArray();
            $allowances = EssentialsClaimReimbursement::where('business_id', $business_id)->whereIn('id', $essential_ids)
                ->with('employees');
            if (auth()->user()->can('essentials.approve_claim_reimbursement') || auth()->user()->can('superadmin')) {
                $allowances = EssentialsClaimReimbursement::where('business_id', $business_id)
                    ->with('employees');
            }

            return Datatables::of($allowances)
                ->addColumn(
                    'action',
                    function ($row) {
                        //dd(row);
                        $html = '';
                        if (auth()->user()->can('essentials.add_claim_reimbursement')) {
                            if (empty($row->is_approved)) {
                                $html .= '<button data-href="' . action([\Modules\Essentials\Http\Controllers\ClaimReimbursementController::class, 'edit'], [$row->id]) . '" data-container="#add_allowance_deduction_modal" class="btn-modal btn btn-primary btn-xs"><i class="fa fa-edit" aria-hidden="true"></i> </button>';
                            }

                            $html .= '&nbsp; <button data-href="' . action([\Modules\Essentials\Http\Controllers\ClaimReimbursementController::class, 'destroy'], [$row->id]) . '" class="delete-allowance btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> </button>';
                            if (!empty($row->document)) {
                                $html .= '&nbsp; <a target="_blank" href="' . url('uploads/documents/' . $row->document) . '" class="btn btn-success btn-xs"><i class="fa fa-download" aria-hidden="true"></i> </button>';
                            }
                        }

                        return $html;
                    }
                )
                ->editColumn('applicable_date', function ($row) {
                    return $this->essentialsUtil->format_date($row->applicable_date);
                })
                ->editColumn('type', '{{__("essentials::lang." . $type)}}')
                ->editColumn('amount', '<span class="display_currency" data-currency_symbol="false">{{$amount}}</span> @if($amount_type =="percent") % @endif')
                ->editColumn('employees', function ($row) {
                    $employees = [];
                    foreach ($row->employees as $employee) {
                        $employees[] = $employee->user_full_name;
                    }

                    return implode(', ', $employees);
                })
                ->editColumn('is_approved', function ($row) {
                    //print_r($row->is_approved);\

                    $html = '';
                    $html = '<span class="badge ' . $this->claim_statuses[$row->is_approved]['class'] . '" style="' . $this->claim_statuses[$row->is_approved]['style'] . '" >' . $this->claim_statuses[$row->is_approved]['label'] . '</span>';

                    $claimData = EssentialsUserClaimReimbursement::where('claim_reimbursement_id',$row->id)->first();
                    $managerUserData= User::where('id',$claimData->user_id)->first();
                    $managerUser_id = $managerUserData->parent_id;

                    $user_can_manage_leave = auth()->user()->can('essentials.approve_claim_reimbursement') || auth()->user()->can('superadmin');

                    // Check if the logged-in user is the manager of the user associated with the listing
                    $logged_in_user_is_manager = auth()->user()->id == $managerUser_id;
                    if ($user_can_manage_leave && $logged_in_user_is_manager) {
                        $html = '<a href="#" class="change_status" data-status_note="' . $row->status_note . '" data-leave-id="' . $row->id . '" data-orig-value="' . $row->is_approved . '" data-status-name="' . $this->claim_statuses[$row->is_approved]['label'] . '"> ' . $html . '</a>';
                    }
                    return $html;

                })
                ->rawColumns(['action', 'amount', 'is_approved'])
                ->make(true);

        }
        $claim_statuses = $this->claim_statuses;
        return view('essentials::claim_reimbursement.index')->with(compact('claim_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module')) || !auth()->user()->can('essentials.add_claim_reimbursement')) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::forDropdown($business_id, false);
        $employes = auth()->user()->id;

        return view('essentials::claim_reimbursement.create')->with(compact('users', 'employes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module')) || !auth()->user()->can('essentials.add_claim_reimbursement')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            //print_r($request->file('document'));
            $input = $request->only(['description', 'type', 'document', 'amount', 'is_approved', 'amount_type', 'applicable_date']);
            $input['business_id'] = $business_id;
            $input['amount'] = $this->moduleUtil->num_uf($input['amount']);
            $input['applicable_date'] = !empty($input['applicable_date']) ? $this->essentialsUtil->uf_date($input['applicable_date']) : null;
            $input['document'] = $this->moduleUtil->uploadFile($request, 'document', 'documents');
            $allowance = EssentialsClaimReimbursement::create($input);
            $allowance->employees()->sync($request->input('employees'));

            $output = ['success' => true,
                'msg' => __('lang_v1.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = ['success' => false,
                'msg' => 'File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage(),
            ];
        }

        return $output;
    }

    /**
     * Show the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module')) || !auth()->user()->can('essentials.add_claim_reimbursement')) {
            abort(403, 'Unauthorized action.');
        }

        return view('essentials::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module')) || !auth()->user()->can('essentials.add_claim_reimbursement')) {
            abort(403, 'Unauthorized action.');
        }

        $allowance = EssentialsClaimReimbursement::where('business_id', $business_id)
            ->with('employees')
            ->findOrFail($id);
        $users = User::forDropdown($business_id, false);

        $selected_users = [];
        foreach ($allowance->employees as $employee) {
            $selected_users[] = $employee->id;
        }

        $applicable_date = !empty($allowance->applicable_date) ? $this->essentialsUtil->format_date($allowance->applicable_date) : null;
        $employes = auth()->user()->id;
        $claimData = EssentialsUserClaimReimbursement::where('claim_reimbursement_id',$allowance->id)->first();
        $managerUserData= User::where('id',$claimData->user_id)->first();
        $managerUser_id = $managerUserData->parent_id;

        $logged_in_user_is_manager = auth()->user()->id == $managerUser_id;
        return view('essentials::claim_reimbursement.edit')
            ->with(compact('allowance', 'users', 'selected_users', 'applicable_date', 'employes','logged_in_user_is_manager'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $business_id = request()->session()->get('user.business_id');

        try {
            if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module')) || !auth()->user()->can('essentials.add_claim_reimbursement')) {
                abort(403, 'Unauthorized action.');
            }
            $input = $request->only(['description', 'type', 'amount', 'amount_type', 'is_approved', 'applicable_date']);
            $input['amount'] = $this->moduleUtil->num_uf($input['amount']);
            $input['applicable_date'] = !empty($input['applicable_date']) ? $this->essentialsUtil->uf_date($input['applicable_date']) : null;
            $input['document'] = $this->moduleUtil->uploadFile($request, 'document', 'documents');

            $allowance = EssentialsClaimReimbursement::findOrFail($id);

            $allowance->update($input);

            $allowance->employees()->sync($request->input('employees'));

            $output = ['success' => true,
                'msg' => __('lang_v1.updated_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
            dd($e);
            $output = ['success' => false,
                'msg' => 'File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage(),
            ];
        }

        return $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module')) || !auth()->user()->can('essentials.add_claim_reimbursement')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                EssentialsClaimReimbursement::where('business_id', $business_id)
                    ->where('id', $id)
                    ->delete();

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

    public function changeStatus(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        $managerUsers = session('managerUsers');

        try {

            if ((!(auth()->user()->can('superadmin') && !auth()->user()->can('essentials.approve_claim_reimbursement')) || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
                //abort(403, 'Unauthorized action.');
                $output = ['success' => true,
                    'msg' => __('You have not right permission to perform this action!'),
                ];
            }

            $input = $request->only(['status', 'reimbursement_id', 'status_note']);

            $reimbursement = EssentialsClaimReimbursement::where('business_id', $business_id)
                ->find($input['reimbursement_id']);

            // if (!in_array($reimbursement->user_id, $managerUsers) && !(auth()->user()->can('superadmin'))) {
            //     abort(403, 'Unauthorized action.');
            // }
            $reimbursement->is_approved = $input['status'];
            $reimbursement->status_note = $input['status_note'];
            $reimbursement->save();

            $output = ['success' => true,
                'msg' => __('lang_v1.updated_success'),
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
