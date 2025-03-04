<?php

namespace Modules\DocumentSign\Http\Controllers;

use App\User;
use App\Utils\ModuleUtil;
use Illuminate\Routing\Controller;
use Menu;

class DataController extends Controller
{

    /**
     * Defines user permissions for the module.
     *
     * @return array
     */
    public function user_permissions()
    {
        return [
            [
                'value' => 'documentsign.crud_documents',
                'label' => __('documentsign::lang.crud_document'),
                'default' => false,
            ],
            [
                'value' => 'documentsign.view_documents',
                'label' => __('documentsign::lang.view_documents'),
                'default' => false,
            ],

        ];
    }

    /**
     * Superadmin package permissions
     *
     * @return array
     */
    public function superadmin_package()
    {
        return [
            [
                'name' => 'essentials_module',
                'label' => __('essentials::lang.essentials_module'),
                'default' => false,
            ],
        ];
    }

    /**
     * Adds Essentials menus
     *
     * @return null
     */
    public function modifyAdminMenu()
    {
        $module_util = new ModuleUtil();

        $business_id = session()->get('user.business_id');
        $is_essentials_enabled = (bool) $module_util->hasThePermissionInSubscription($business_id, 'essentials_module');

        if ($is_essentials_enabled) {
            Menu::modify('admin-sidebar-menu', function ($menu) {
                $menu->url(
                    action([\Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'index']),
                    __('documentsign::lang.documents'),
                    ['icon' => 'las la-file-upload', 'has_sub_item' => 'no', 'active' => request()->segment(1) == 'hrm', 'style' => config('app.env') == 'demo' ? 'background-color: #605ca8 !important;' : '']
                )
                    ->order(1090);

            });
        }
    }

}
