<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

trait Roles
{
    function addRole()
    {
        $routes = Route::getRoutes();
        $routes_data = [];
        $id = 0;
        $html = '';

        foreach ($routes as $route) {
            if ($route->getName()) {
                // Save the route's title, or null if it doesn't exist
                $routes_data['"' . $route->getName() . '"'] = [
                    'title' => $route->getAction()['title'] ?? null
                ];
            }
        }

        foreach ($routes as $value) {
            if (isset($value->getAction()['title']) && isset($value->getAction()['type']) && $value->getAction()['type'] == 'parent') {
                $parent_class = 'gtx_' . $id++;
                $html .= '
                    <div class="col-md-4">
                        <div class="card permissionCard package bg-white shadow">
                            <div class="role-title text-white" style="display: flex; justify-content: space-between;">
                                <div style="display: flex; flex-direction: row; align-items: center">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" name="permissions[]" value="' . $value->getName() . '" id="' . $parent_class . '" class="roles-parent">
                                        <label for="' . $parent_class . '" dir="ltr"></label>
                                    </div>
                                    <p class="text-white selectP" for="' . $parent_class . '">' . __($value->getAction()["title"]) . '</p>
                                </div>
                                <div style="display: flex; flex-direction: row-reverse; align-items: center">
                                    <p class="text-white selectP">' . __("dashboard.select_all") . '</p>
                                    <input type="checkbox" class="checkChilds checkChilds_' . $parent_class . '" data-parent="' . $parent_class . '">
                                </div>
                            </div>';
                
                if (isset($value->getAction()['child']) && count($value->getAction()['child'])) {
                    $html .= '<div class="card permissionCard bg-white shadow"><ul class="list-unstyled">';

                    foreach ($value->getAction()['child'] as $key => $child) {
                        // Ensure the child key exists before accessing the title
                        $title1 = isset($routes_data['"' . $child . '"']['title'][0]) ? __($routes_data['"' . $child . '"']['title'][0]) : 'Default Title 1';
                        $title2 = isset($routes_data['"' . $child . '"']['title'][1]) ? __($routes_data['"' . $child . '"']['title'][1]) : 'Default Title 2';

                        $html .= '
                            <li>
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" name="permissions[]" data-parent="' . $parent_class . '" value="' . $child . '" id="' . $value->getName() . $key . '" class="childs ' . $parent_class . '">
                                        <label for="' . $value->getName() . $key . '" dir="ltr"></label>
                                    </div>
                                    <label class="title_lable" for="' . $value->getName() . $key . '">' . $title1 . ' ' . $title2 . '</label>
                                </div>
                            </li>';
                    }

                    $html .= '</ul></div>';
                }

                $html .= '</div></div>';
            }
        }

        return $html;
    }

    // editRole
    function editRole($id)
    {
        $routes = Route::getRoutes();
        $routes_data = [];
        $my_routes = Role::with('permissions')->find($id)->permissions()->pluck('name')->toArray();
        $id = 0;
        $html = '';

        foreach ($routes as $route) {
            if ($route->getName()) {
                $routes_data['"' . $route->getName() . '"'] = [
                    'title' => $route->getAction()['title'] ?? null
                ];
            }
        }
        foreach ($routes as $value) {
            $action = $value->getAction();
            
            if (isset($action['title'], $action['type']) && $action['type'] === 'parent') {
                $select = in_array($value->getName(), $my_routes) ? 'checked' : '';
                $parent_class = 'gtx_' . $id++;
                
                $html .= '
                <div class="col-md-4">
                    <div class="card permissionCard package bg-white shadow">
                        <div class="role-title text-white" style="display: flex; justify-content: space-between;">
                            <div style="display: flex; flex-direction: row; align-items: center">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" name="permissions[]" value="' . $value->getName() . '" id="' . $parent_class . '" class="roles-parent" ' . $select . '>
                                    <label for="' . $parent_class . '" dir="ltr"></label>
                                </div>
                                <p class="text-white selectP">' . __($action["title"]) . '</p>
                            </div>
                            <div style="display: flex; flex-direction: row-reverse; align-items: center">
                                <p class="text-white selectP">' . __("dashboard.select_all") . '</p>
                                <input type="checkbox" class="checkChilds checkChilds_' . $parent_class . '" data-parent="' . $parent_class . '">
                            </div>
                        </div>';
        
                if (isset($action['child']) && count($action['child'])) {
                    $html .= '<div class="card permissionCard bg-white shadow"><ul class="list-unstyled">';
                    
                    foreach ($action['child'] as $key => $child) {
                        $select = in_array($child, $my_routes) ? 'checked' : '';
        
                        // استخدام null coalescing operator لتعيين قيمة افتراضية
                        $title1 = __($routes_data['"' . $child . '"']['title'][0] ?? 'Default Title 1');
                        $title2 = __($routes_data['"' . $child . '"']['title'][1] ?? 'Default Title 2');
        
                        $html .= '
                            <li>
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" name="permissions[]" data-parent="' . $parent_class . '" value="' . $child . '" id="' . $value->getName() . $key . '" class="childs ' . $parent_class . '" ' . $select . '>
                                        <label for="' . $value->getName() . $key . '" dir="ltr"></label>
                                    </div>
                                    <label for="' . $value->getName() . $key . '">' . $title1 . ' ' . $title2 . '</label>
                                </div>
                            </li>';
                    }
                    
                    $html .= '</ul></div>';
                }
        
                $html .= '</div></div>';
            }
        }
        
        return $html;        
    }
}
