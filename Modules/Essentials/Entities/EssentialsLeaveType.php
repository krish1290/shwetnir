<?php

namespace Modules\Essentials\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class EssentialsLeaveType extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function forDropdown($business_id)
    {
        $leave_types = EssentialsLeaveType::where('business_id', $business_id)
                                    ->pluck('leave_type', 'id');

        return $leave_types;
    }

    // public static function forDropdownnew($business_id)
    // {
    //     $user_id = request()->session()->get('user.id');
    //     $leave_types = EssentialsLeaveType::where('essentials_leave_types.business_id', $business_id)
    //
    //                    ->join('essentials_user_leave_and_deductions as ldeu', 'essentials_leave_types.id', '=', 'ldeu.leave_id')
    //                    ->where('ldeu.user_id',$user_id)
    //
    //                   ->select(
    //                    DB::raw("CONCAT(essentials_leave_types.leave_type,' ',(ldeu.balance)) AS leave_type"), 'essentials_leave_types.id')
    //                     ->pluck('leave_type', 'essentials_leave_types.id');
    //
    //     return $leave_types;
    // }

    public static function forDropdownnew($business_id)
    {
        $user_id = request()->session()->get('user.id');
        $leave_types = EssentialsUserLeaveAndDeduction::where(['essentials_user_leave_and_deductions.user_id' => $user_id, 'essentials_user_leave_and_deductions.business_id' => $business_id])
                        ->join('essentials_leave_types', 'essentials_user_leave_and_deductions.leave_id', '=', 'essentials_leave_types.id')
                        ->select('essentials_leave_types.leave_type', 'essentials_user_leave_and_deductions.balance','essentials_user_leave_and_deductions.leave_id')
                        ->get()
                        ->mapWithKeys(function ($item) {
                          // dd($item->leave_id);
                            return [
                                $item->leave_id => $item->leave_type . ' ' . number_format(($item->balance),2)
                            ];
                        })
                        ->toArray();
        return $leave_types;
    }
}
