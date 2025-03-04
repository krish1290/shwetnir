<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WastageType extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Return list of tax rate dropdown for a business
     *
     * @param $business_id int
     * @param $prepend_none = true (boolean)
     * @param $include_attributes = false (boolean)
     * @return array['wastage_types', 'attributes']
     */
    public static function forBusinessDropdown(
        $business_id,
        $prepend_none = true,
        $include_attributes = false,
        $exclude_for_tax_group = true
    ) {
        $all_wastages = WastageType::where('business_id', $business_id);

        $result = $all_wastages->get();
        $wastage_types = $result->pluck('name', 'id');

        //Prepend none
        if ($prepend_none) {
            $wastage_types = $wastage_types->prepend(__('lang_v1.none'), '');
        }
        $output = ['wastage_types' => $wastage_types];

        return $output;
    }

    /**
     * Return list of tax rate for a business
     *
     * @return array
     */
    public static function forBusiness($business_id)
    {
        $wastage_types = WastageType::where('business_id', $business_id)
                        ->select(['id', 'name', 'amount'])
                        ->get()
                        ->toArray();

        return $wastage_types;
    }
}
