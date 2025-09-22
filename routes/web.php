<?php


use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\DailyReportController;
use App\Http\Controllers\Dashboard\EquipmentDirectoryController;
use App\Http\Controllers\Dashboard\GeneralPaymentsController;
use App\Http\Controllers\Dashboard\MeetingController;
use App\Http\Controllers\Dashboard\MeetingLocationController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\OrderController as rateOrderController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\OrderStatusController;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\Dashboard\SurveyController;
use App\Http\Controllers\Dashboard\SurveySubmissionController;
use App\Http\Controllers\Dashboard\WhatsappMessageTemplateController;
use App\Http\Controllers\Dashboard\StockController;
use App\Http\Controllers\Dashboard\StockAdjustmentController ;
use App\Http\Controllers\Dashboard\ServiceSiteAndCustomerServiceController;
use App\Http\Controllers\Dashboard\InternalNoteController ;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{Dashboard\CampReportController, OrderSignatureController, RegistrationformController};
use App\Http\Controllers\Dashboard\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */


Route::resource('registrationforms', RegistrationformController::class)->except(['index', 'edit', 'update', 'destroy']);

Route::get('order-rate/{order}', "VisitorsController@rate")->name('rate');
//  Route::post('order-rate', "VisitorsController@rateStore")->name('rate.save');
Route::post('orders/rate_orders', "VisitorsController@rateStore")->name('rate.save');
# orders rate

Route::get('orders/rate_orders', [rateOrderController::class, 'rate_orders'])->name('orders.rate');

Route::get('/sign/{order}', [OrderSignatureController::class, 'show'])
    ->name('signature.show'); // public page to draw

Route::post('/sign/{order}', [OrderSignatureController::class, 'store'])
    ->name('signature.store');

Route::delete('/sign/{order}', [OrderSignatureController::class, 'destroy'])
    ->name('signature.destroy');

// Auth::routes();
// Stock Report Route
Route::get('dashboard/stocks/{stock}/report', [StockController::class, 'stockReport'])->name('dashboard.stock.report');
Route::group(['middleware' => ['web']], function () {
    Route::get('/', [LoginController::class, 'showloginform'])->name('show.login');
    Route::post('admin-login', [LoginController::class, 'login'])->name('admin-login');
});

// Temporary route to check user permissions (outside check-role middleware)
Route::get('check-permissions', function () {
    if (!auth()->check()) {
        return 'Please login first';
    }

    $user = auth()->user();
    $permissions = $user->getAllPermissions()->pluck('name')->toArray();

    echo "<h3>User: " . $user->name . " (ID: " . $user->id . ")</h3>";
    echo "<h3>All Permissions (" . count($permissions) . "):</h3>";
    foreach ($permissions as $permission) {
        echo "- " . $permission . "<br>";
    }

    echo "<br><h3>Specific Checks:</h3>";
    echo "Has notices.index: " . ($user->hasPermissionTo('notices.index') ? 'YES' : 'NO') . "<br>";
    echo "Can access notices.index: " . (Gate::allows('notices.index') ? 'YES' : 'NO') . "<br>";

    return '';
})->middleware('auth');

Route::group(['middleware' => ['auth', 'admin-lang', 'web', 'check-role'], 'namespace' => 'Dashboard'], function () {



    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('edit-profile', [AdminController::class, 'editProfile'])->name('edit-profile');
    Route::put('update-profile', [AdminController::class, 'updateProfile'])->name('update-profile');
    Route::get('home', [
        'uses' => 'HomeController@index',
        'as' => 'home',
        'title' => 'dashboard.home',
        'type' => 'parent',
        'child' => ['reprots']
    ]);

    # roles store
    Route::get('reprots', [
        'uses' => 'HomeController@reprots',
        'as' => 'reprots',
        'type' => 'child',
        'title' => ['', 'dashboard.reprots']
    ]);
    /*------------ start Of roles ----------*/
    Route::get('roles', [
        'uses' => 'RoleController@index',
        'as' => 'roles.index',
        'title' => 'dashboard.roles',
        'type' => 'parent',
        'child' => ['roles.store', 'roles.edit', 'roles.update', 'roles.destroy', 'roles.deleteAll']
    ]);

    # roles store
    Route::get('roles/create', [
        'uses' => 'RoleController@create',
        'as' => 'roles.create',
        'type' => 'child',
        'title' => ['actions.add', 'dashboard.role']
    ]);

    # roles store
    Route::post('roles/store', [
        'uses' => 'RoleController@store',
        'as' => 'roles.store',
        'type' => 'child',
        'title' => ['actions.add', 'dashboard.role']
    ]);

    # roles update
    Route::get('roles/{id}/edit', [
        'uses' => 'RoleController@edit',
        'as' => 'roles.edit',
        'type' => 'child',
        'title' => ['actions.edit', 'dashboard.role']
    ]);

    # roles update
    Route::put('roles/{id}', [
        'uses' => 'RoleController@update',
        'as' => 'roles.update',
        'type' => 'child',
        'title' => ['actions.edit', 'dashboard.role']
    ]);

    # roles delete
    Route::delete('roles/{id}', [
        'uses' => 'RoleController@destroy',
        'as' => 'roles.destroy',
        'type' => 'child',
        'title' => ['actions.delete', 'dashboard.role']
    ]);
    #delete all roles
    Route::post('delete-all-roles', [
        'uses' => 'RoleController@deleteAll',
        'as' => 'roles.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.roles']
    ]);
    /*------------ end Of roles ----------*/
    /*------------ start Of admins ----------*/
    Route::get('admins', [
        'uses' => 'AdminController@index',
        'as' => 'admins.index',
        'title' => 'dashboard.admins',
        'type' => 'parent',
        'child' => ['admins.store', 'admins.edit', 'admins.update', 'admins.destroy', 'admins.deleteAll']
    ]);

    # admins store
    Route::get('admins/create', [
        'uses' => 'AdminController@create',
        'as' => 'admins.create',
        'title' => ['actions.add', 'dashboard.admin']
    ]);

    # admins store
    Route::post('admins/store', [
        'uses' => 'AdminController@store',
        'as' => 'admins.store',
        'title' => ['actions.add', 'dashboard.admin']
    ]);

    # admins update
    Route::get('admins/{id}/edit', [
        'uses' => 'AdminController@edit',
        'as' => 'admins.edit',
        'title' => ['actions.edit', 'dashboard.admin']
    ]);

    # admins update
    Route::put('admins/{id}', [
        'uses' => 'AdminController@update',
        'as' => 'admins.update',
        'title' => ['actions.edit', 'dashboard.admin']
    ]);

    # admins delete
    Route::delete('admins/{id}', [
        'uses' => 'AdminController@destroy',
        'as' => 'admins.destroy',
        'title' => ['actions.delete', 'dashboard.admin']
    ]);
    #delete all admins
    Route::post('delete-all-admins', [
        'uses' => 'AdminController@deleteAll',
        'as' => 'admins.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.admins']
    ]);
    /*------------ end Of admins ----------*/
    /*------------ start Of customers ----------*/
    Route::get('customers', [
        'uses' => 'CustomersController@index',
        'as' => 'customers.index',
        'title' => 'dashboard.customers',
        'type' => 'parent',
        'child' => ['customers.store', 'customers.edit', 'customers.update', 'customers.destroy', 'customers.deleteAll']
    ]);

    # customers store
    Route::get('customers/create', [
        'uses' => 'CustomersController@create',
        'as' => 'customers.create',
        'title' => ['actions.add', 'dashboard.customers'],
        'middleware' => ['auth', 'permission:customers.create']
    ]);

    # customers store
    Route::post('customers/store', [
        'uses' => 'CustomersController@store',
        'as' => 'customers.store',
        'title' => ['actions.add', 'dashboard.customers'],
        'middleware' => ['auth', 'permission:customers.create']
    ]);

    # customers update
    Route::get('customers/{id}/edit', [
        'uses' => 'CustomersController@edit',
        'as' => 'customers.edit',
        'title' => ['actions.edit', 'dashboard.customers'],
        'middleware' => ['auth', 'permission:customers.edit']
    ]);

    # customers update
    Route::put('customers/{id}', [
        'uses' => 'CustomersController@update',
        'as' => 'customers.update',
        'title' => ['actions.edit', 'dashboard.customers'],
        'middleware' => ['auth', 'permission:customers.edit']
    ]);

    # customers delete
    Route::delete('customers/{id}', [
        'uses' => 'CustomersController@destroy',
        'as' => 'customers.destroy',
        'title' => ['actions.delete', 'dashboard.customers'],
        'middleware' => ['auth', 'permission:customers.destroy']
    ]);
    #delete all customers
    Route::post('delete-all-customers', [
        'uses' => 'CustomersController@deleteAll',
        'as' => 'customers.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.customers'],
        'middleware' => ['auth', 'permission:customers.deleteAll']
    ]);
    /*------------ end Of customers ----------*/
    /*------------ start Of stocks ----------*/
    Route::get('stocks', [
        'uses' => 'StockController@index',
        'as' => 'stocks.index',
        'title' => 'dashboard.stocks',
        'type' => 'parent',
        'child' => ['stocks.store', 'stocks.edit', 'stocks.show', 'stocks.update', 'stocks.destroy', 'stocks.deleteAll', 'stocks.destroyServiceStock', 'stocks.destroyServiceReport', 'stock.decrement']
    ]);

    # stocks storexa
    Route::get('stocks/create', [
        'uses' => 'StockController@create',
        'as' => 'stocks.create',
        'title' => ['actions.add', 'dashboard.stocks']
    ]);
    # stocks show
    Route::get('stocks/{id}/show', [
        'uses' => 'StockController@show',
        'as' => 'stocks.show',
        'title' => ['actions.show', 'dashboard.stocks']
    ]);

    # stocks store
    Route::post('stocks/store', [
        'uses' => 'StockController@store',
        'as' => 'stocks.store',
        'title' => ['actions.add', 'dashboard.stocks']
    ]);

    # stocks update
    // Manual item withdrawal and return routes
    Route::prefix('manual')->group(function () {
        // Create page
        Route::get('item-issue-and-return/create', [StockAdjustmentController::class, 'create'])->name('item-issue-and-return-create');
        // Manual item withdrawal page
        Route::get('item-issue', [StockAdjustmentController::class, 'issuedItemsIndex'])->name('item-issue');
        // Manual item return page
        Route::get('item-return', [StockAdjustmentController::class, 'returnedItemsIndex'])->name('item-return');

        Route::get('stock-adjustments/{adjustment}/json', [StockAdjustmentController::class, 'json'])->name('stock.adjustments.json');
        Route::get('stockTaking/create', [StockAdjustmentController::class, 'stockTakingCreate'])->name('stockTaking.create');

    Route::get('stockTaking/index', [StockAdjustmentController::class, 'stockTakingIndex'])->name('stockTaking.index');

        // Store adjustment
    Route::post('stock-adjustments', [StockAdjustmentController::class, 'store'])->name('stock.adjustments.store');
    // Update adjustment
    Route::put('stock-adjustments/{adjustment}', [StockAdjustmentController::class, 'update'])->name('stock.adjustments.update');
    // Delete adjustment
    Route::delete('stock-adjustments/{adjustment}', [StockAdjustmentController::class, 'destroy'])->name('stock.adjustments.destroy');
    // Download image
    Route::get('stock-adjustments/{id}/download', [StockAdjustmentController::class, 'downloadImage'])->name('stock.adjustments.download');
    });
    Route::get('stocks/{id}/edit', [
        'uses' => 'StockController@edit',
        'as' => 'stocks.edit',
        'title' => ['actions.edit', 'dashboard.stocks']
    ]);

    # stocks update
    Route::put('stocks/{id}', [
        'uses' => 'StockController@update',
        'as' => 'stocks.update',
        'title' => ['actions.edit', 'dashboard.stocks']
    ]);

    # stocks delete
    Route::delete('stocks/{id}', [
        'uses' => 'StockController@destroy',
        'as' => 'stocks.destroy',
        'title' => ['actions.delete', 'dashboard.stocks']
    ]);
    #delete all stocks
    Route::post('delete-all-stocks', [
        'uses' => 'StockController@deleteAll',
        'as' => 'stocks.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.stocks']
    ]);

    # Service Stock delete
    Route::delete('service/{service}/stocks/{stock}', [
        'uses' => 'StockController@destroyServiceStock',
        'as' => 'stocks.destroyServiceStock',
        'title' => ['actions.delete', 'dashboard.stocks']
    ]);

    Route::delete('service-reports/{report}', [
        'uses' => 'StockController@destroyServiceReport',
        'as' => 'stocks.destroyServiceReport',
        'title' => ['actions.delete', 'dashboard.stocks']
    ]);

    Route::post('stock/decrement', [
        'uses' => 'OrderController@decrmentOrIncrementStock',
        'as' => 'stock.decrement',
        'title' => ['actions.decrmentStock', 'dashboard.stocks']
    ]);

    // // Manual stock adjustment (increment/decrement) - stores audit record
    // Route::post('stock/adjust', [
    //     'uses' => 'StockAdjustmentController@store',
    //     'as' => 'stock.adjust',
    //     'title' => ['actions.adjust', 'dashboard.stocks']
    // ]);

    // // RESTful store endpoint for stock adjustments (preferred for forms)
    // Route::post('stock-adjustments', [
    //     'uses' => 'StockAdjustmentController@store',
    //     'as' => 'stock.adjustments.store',
    //     'title' => ['actions.store', 'dashboard.stocks']
    // ]);

    // // Update an existing stock adjustment
    // Route::put('stock-adjustments/{adjustment}', [
    //     'uses' => 'StockAdjustmentController@update',
    //     'as' => 'stock.adjustments.update',
    //     'title' => ['actions.update', 'dashboard.stocks']
    // ]);

    // // Delete a stock adjustment
    // Route::delete('stock-adjustments/{adjustment}', [
    //     'uses' => 'StockAdjustmentController@destroy',
    //     'as' => 'stock.adjustments.destroy',
    //     'title' => ['actions.delete', 'dashboard.stocks']
    // ]);
    
    // // List all stock adjustments (index)
    // Route::get('stock-adjustments', [
    //     'uses' => 'StockAdjustmentController@index',
    //     'as' => 'stock.adjustments.index',
    //     'title' => ['actions.index', 'dashboard.stocks']
    // ]);

    //     // Show create form for stock adjustment
    //     Route::get('stock-adjustments/create', [
    //         'uses' => 'StockAdjustmentController@create',
    //         'as' => 'stock.adjustments.create',
    //         'title' => ['actions.create', 'dashboard.stocks']
    //     ]);
    
    /*------------ end Of stocks ----------*/
    /*------------ start Of addons ----------*/
    Route::get('addons', [
        'uses' => 'AddonsController@index',
        'as' => 'addons.index',
        'title' => 'dashboard.addons',
        'type' => 'parent',
        'middleware' => ['auth', 'permission:addons.index'],
        'child' => ['addons.store', 'addons.edit', 'addons.show', 'addons.update', 'addons.destroy', 'addons.deleteAll', 'addons.print']
    ]);

    # addons store
    Route::get('addons/create', [
        'uses' => 'AddonsController@create',
        'as' => 'addons.create',
        'title' => ['actions.add', 'dashboard.addons'],
        'middleware' => ['auth', 'permission:addons.create']
    ]);
    # addons show
    Route::get('addons/{id}/show', [
        'uses' => 'AddonsController@show',
        'as' => 'addons.show',
        'title' => ['actions.show', 'dashboard.addons'],
        'middleware' => ['auth', 'permission:addons.show']
    ]);

    # addons store
    Route::post('addons/store', [
        'uses' => 'AddonsController@store',
        'as' => 'addons.store',
        'title' => ['actions.add', 'dashboard.addons'],
        'middleware' => ['auth', 'permission:addons.create']
    ]);

    # addons update
    Route::get('addons/{id}/edit', [
        'uses' => 'AddonsController@edit',
        'as' => 'addons.edit',
        'title' => ['actions.edit', 'dashboard.addons'],
        'middleware' => ['auth', 'permission:addons.edit']
    ]);

    Route::get('addons/{id}/print', [
        'uses' => 'AddonsController@print',
        'as' => 'addons.print',
        'title' => ['addons.print', 'dashboard.addons']
    ]);

    # addons update
    Route::put('addons/{id}', [
        'uses' => 'AddonsController@update',
        'as' => 'addons.update',
        'title' => ['actions.edit', 'dashboard.addons'],
        'middleware' => ['auth', 'permission:addons.edit']
    ]);

    # addons delete
    Route::delete('addons/{id}', [
        'uses' => 'AddonsController@destroy',
        'as' => 'addons.destroy',
        'title' => ['actions.delete', 'dashboard.addons'],
        'middleware' => ['auth', 'permission:addons.destroy']
    ]);
    #delete all addons
    Route::post('delete-all-addons', [
        'uses' => 'AddonsController@deleteAll',
        'as' => 'addons.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.addons'],
        'middleware' => ['auth', 'permission:addons.destroy']
    ]);
    /*------------ end Of addons ----------*/

    /*------------ start Of stock-quantities ----------*/
    Route::get('stock-quantities', [
        'uses' => 'StockQuantitiesController@index',
        'as' => 'stock-quantities.index',
        'title' => 'dashboard.stock-quantities',
        'type' => 'parent',
        'child' => ['stock-quantities.store', 'stock-quantities.show', 'stock-quantities.edit', 'stock-quantities.update', 'stock-quantities.destroy']
    ]);

    # stock-quantities store
    Route::get('stock-quantities/create', [
        'uses' => 'StockQuantitiesController@create',
        'as' => 'stock-quantities.create',
        'title' => ['actions.add', 'dashboard.stock-quantities']
    ]);

    # stock-quantities store
    Route::post('stock-quantities/store/{id}', [
        'uses' => 'StockQuantitiesController@store',
        'as' => 'stock-quantities.store',
        'title' => ['actions.add', 'dashboard.stock-quantities']
    ]);

    # stock-quantities update
    Route::get('stock-quantities/{id}/edit', [
        'uses' => 'StockQuantitiesController@edit',
        'as' => 'stock-quantities.edit',
        'title' => ['actions.edit', 'dashboard.stock-quantities']
    ]);
    # stock-quantities show
    Route::get('stock-quantities/{id}/show', [
        'uses' => 'StockQuantitiesController@show',
        'as' => 'stock-quantities.show',
        'title' => ['actions.show', 'dashboard.stock-quantities']
    ]);

    # stock-quantities update
    Route::put('stock-quantities/{id}', [
        'uses' => 'StockQuantitiesController@update',
        'as' => 'stock-quantities.update',
        'title' => ['actions.edit', 'dashboard.stock-quantities']
    ]);

    # stock-quantities delete
    Route::delete('stock-quantities/{id}', [
        'uses' => 'StockQuantitiesController@destroy',
        'as' => 'stock-quantities.destroy',
        'title' => ['actions.delete', 'dashboard.stock-quantities']
    ]);
    #delete all stock-quantities
    Route::post('delete-all-stock-quantities', [
        'uses' => 'StockQuantitiesController@deleteAll',
        'as' => 'stock-quantities.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.stock-quantities']
    ]);
    /*------------ end Of stock-quantities ----------*/
    /*------------ start Of services ----------*/
    Route::get('services', [
        'uses' => 'ServicesController@index',
        'as' => 'services.index',
        'title' => 'dashboard.services',
        'type' => 'parent',
        'child' => ['services.store', 'services.show', 'services.edit', 'services.update', 'services.destroy', 'services.reports.move']
    ]);

    # services store
    Route::get('services/create', [
        'uses' => 'ServicesController@create',
        'as' => 'services.create',
        'title' => ['actions.add', 'dashboard.services']
    ]);

    # services store
    Route::post('services/store', [
        'uses' => 'ServicesController@store',
        'as' => 'services.store',
        'title' => ['actions.add', 'dashboard.services']
    ]);

    # services update
    Route::get('services/{id}/edit', [
        'uses' => 'ServicesController@edit',
        'as' => 'services.edit',
        'title' => ['actions.edit', 'dashboard.services']
    ]);
    # services show
    Route::get('services/{id}/show', [
        'uses' => 'ServicesController@show',
        'as' => 'services.show',
        'title' => ['actions.show', 'dashboard.services']
    ]);

    # services update
    Route::put('services/{id}', [
        'uses' => 'ServicesController@update',
        'as' => 'services.update',
        'title' => ['actions.edit', 'dashboard.services']
    ]);

    # services image upload
    Route::post('services/upload-image', [
        'uses' => 'ServicesController@update',
        'as' => 'services.uploadImage',
        'title' => ['actions.upload_image', 'dashboard.services']
    ]);

    # services delete
    Route::delete('services/{id}', [
        'uses' => 'ServicesController@destroy',
        'as' => 'services.destroy',
        'title' => ['actions.delete', 'dashboard.services']
    ]);
    #delete all services
    Route::post('delete-all-services', [
        'uses' => 'ServicesController@deleteAll',
        'as' => 'services.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.services']
    ]);

    Route::post('/services/{service}/reports/{report}/move', [
        'uses' => 'ServicesController@move',
        'as' => 'services.reports.move',
        'title' => ['actions.move', 'dashboard.services']
    ]);

    /*------------ end Of services ----------*/
    /*------------ start Of orders ----------*/
    Route::get('orders', [
        'uses' => 'OrderController@index',
        'as' => 'orders.index',
        'title' => 'dashboard.orders',
        'type' => 'parent',
        'middleware' => ['auth'],
        'child' => ['orders.store', 'orders.signin', 'orders/{id}/terms_form', 'orders.logout', 'orders.receipt', 'orders.show', 'orders.reports', 'orders.edit', 'orders.removeAddon', 'orders.update', 'orders.addons', 'user-orders', 'orders.destroy', 'orders.deleteAll', 'order.verified', 'orders.accept_terms', 'orders.updateNotes', 'orders.registeration-forms', 'orders.registeration-forms.edit', 'orders.registeration-forms.destroy', 'orders.registeration-forms.fetch', 'orders.registeration-forms.fetch', 'orders.customers.check']
    ]);

    // Reservations board (today default, upcoming as separate route)
    Route::get('reservations/board', [
        'uses' => 'OrderController@boardToday',
        'as' => 'reservations.board',
        'title' => ['actions.view', 'dashboard.reservations_board']
    ]);
    Route::get('reservations/board/today', [
        'uses' => 'OrderController@boardToday',
        'as' => 'reservations.board.today',
        'title' => ['actions.view', 'dashboard.reservations_board']
    ]);
    Route::get('reservations/board/upcoming', [
        'uses' => 'OrderController@boardUpcoming',
        'as' => 'reservations.board.upcoming',
        'title' => ['actions.view', 'dashboard.reservations_board']
    ]);

    # orders store
    Route::get('orders/{id}/signin', [
        'uses' => 'OrderController@signin',
        'as' => 'orders.signin',
        'title' => ['actions.add', 'dashboard.signin']
    ]);

    # orders registeration-forms
    Route::get('orders/registeration-forms/search', [
        'uses' => 'RegistrationformController@search',
        'as'   => 'orders.registeration-forms.search',
        'title' => ['actions.add', 'dashboard.orders']
    ]);

    # orders registeration-forms
    Route::get('orders/registeration-forms/{id}/fetch', [
        'uses' => 'RegistrationformController@fetch',
        'as'   => 'orders.registeration-forms.fetch',
        'title' => ['actions.add', 'dashboard.orders']
    ]);

    # orders Customer fetch
    Route::get('orders/customer-forms', [
        'uses' => 'RegistrationformController@customer',
        'as'   => 'orders.customers.check',
        'title' => ['actions.add', 'dashboard.orders']
    ]);

    # orders registeration-forms
    Route::get('orders/registeration-forms', [
        'uses' => 'RegistrationformController@index',
        'as' => 'orders.registeration-forms',
        'title' => ['actions.index', 'dashboard.registeration-forms']
    ]);

    # orders registeration-forms
    Route::put('orders/registeration-forms/update/{id}', [
        'uses' => 'RegistrationformController@update',
        'as' => 'orders.registeration-forms.update',
        'title' => ['actions.index', 'dashboard.registeration-forms']
    ]);


    # orders edit registeration-forms
    Route::get('orders/registeration-forms/{id}', [
        'uses' => 'RegistrationformController@edit',
        'as' => 'orders.registeration-forms.edit',
        'title' => ['actions.index', 'dashboard.registeration-forms']
    ]);

    # orders edit registeration-forms
    Route::delete('orders/registeration-forms/{id}/destroy', [
        'uses' => 'RegistrationformController@destroy',
        'as' => 'orders.registeration-forms.destroy',
        'title' => ['actions.delete', 'dashboard.registeration-forms']
    ]);

    # order items Verified
    Route::get('order/verified/{Id}/{type?}', [
        'uses' => 'OrderController@updateVerified',
        'as' => 'order.verified',
        'title' => ['actions.verified', 'dashboard.signin']
    ]);

    # orders store
    Route::put('orders/{id}/updatesignin', [
        'uses' => 'OrderController@updatesignin',
        'as' => 'orders.updatesignin',
        'title' => ['actions.add', 'dashboard.signin']
    ]);

    # orders store
    Route::post('orders/upload-temporary-image', [
        'uses' => 'OrderController@uploadTemporaryImage',
        'as' => 'orders.uploadTemporaryImage',
        'title' => ['actions.add', 'dashboard.signin']
    ]);

    # orders remove-image
    Route::delete('orders/remove-image/{id}', [
        'uses' => 'OrderController@removeImage',
        'as' => 'orders.removeImage',
        'title' => ['actions.add', 'dashboard.signin']
    ]);


    # orders logout
    Route::get('orders/{id}/logout', [
        'uses' => 'OrderController@logout',
        'as' => 'orders.logout',
        'title' => ['actions.add', 'dashboard.logout']
    ]);

    # orders store
    Route::put('orders/{id}/updatelogout', [
        'uses' => 'OrderController@updatelogout',
        'as' => 'orders.updatelogout',
        'title' => ['actions.add', 'dashboard.logout']
    ]);


    # orders store
    Route::get('orders/{id}/insurance', [
        'uses' => 'OrderController@insurance',
        'as' => 'orders.insurance',
        'title' => ['actions.add', 'dashboard.insurance']
    ]);

    # orders store
    Route::put('orders/{id}/updateInsurance', [
        'uses' => 'OrderController@updateInsurance',
        'as' => 'orders.updateInsurance',
        'title' => ['actions.add', 'dashboard.insurance']
    ]);

    # orders store
    Route::get('orders/{id}/reports', [
        'uses' => 'OrderController@reports',
        'as' => 'orders.reports',
        'title' => ['actions.add', 'dashboard.reports']
    ]);

    # addons store
    Route::get('orders/{id}/addons', [
        'uses' => 'OrderController@addons',
        'as' => 'orders.addons',
        'title' => ['actions.add', 'dashboard.addons']
    ]);

    # addons store
    Route::post('orders/{id}/addonsStore', [
        'uses' => 'OrderController@storeAddons',
        'as' => 'ordersStore.addons',
        'title' => ['actions.add', 'dashboard.addons']
    ]);
    # addons store
    Route::put('orders/{addon}/addonsUpdate', [
        'uses' => 'OrderController@UpdateAddons',
        'as' => 'ordersUpdate.addons',
        'title' => ['actions.add', 'dashboard.addons']
    ]);
    # addons store
    Route::delete('orders/{id}/removeAddon', [
        'uses' => 'OrderController@removeAddon',
        'as' => 'orders.removeAddon',
        'title' => ['actions.add', 'dashboard.addons']
    ]);

    Route::post('update/reports/{id}', [
        'uses' => 'OrderController@updateReports',
        'as' => 'update.reports',
        'title' => ['actions.add', 'dashboard.reports']
    ]);

    # orders store
    Route::get('orders/create', [
        'uses' => 'OrderController@create',
        'as' => 'orders.create',
        'title' => ['actions.add', 'dashboard.orders']
    ]);

    # orders show
    Route::get('orders/{id}/show', [
        'uses' => 'OrderController@show',
        'as' => 'orders.show',
        'title' => ['actions.add', 'dashboard.orders']
    ]);

    # orders store
    Route::post('orders/store', [
        'uses' => 'OrderController@store',
        'as' => 'orders.store',
        'title' => ['actions.add', 'dashboard.orders']
    ]);

    # orders invoice
    Route::get('orders/{id}/invoice', [
        'uses' => 'OrderController@invoice',
        'as' => 'orders.invoice',
        'title' => ['actions.invoice', 'dashboard.orders']
    ]);

    # orders update
    Route::get('orders/{id}/edit', [
        'uses' => 'OrderController@edit',
        'as' => 'orders.edit',
        'title' => ['actions.edit', 'dashboard.orders']
    ]);
    # orders quote
    Route::get('orders/{id}/quote', [
        'uses' => 'OrderController@quote',
        'as' => 'orders.quote',
        'title' => ['actions.quote', 'dashboard.orders']
    ]);

    # orders update
    Route::put('orders/{id}', [
        'uses' => 'OrderController@update',
        'as' => 'orders.update',
        'title' => ['actions.edit', 'dashboard.orders']
    ]);

    # orders delete
    Route::delete('orders/{id}', [
        'uses' => 'OrderController@destroy',
        'as' => 'orders.destroy',
        'title' => ['actions.delete', 'dashboard.orders']
    ]);
    #delete all orders
    Route::post('delete-all-orders', [
        'uses' => 'OrderController@deleteAll',
        'as' => 'orders.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.orders']
    ]);

    Route::get('user-orders/{user_id}', [
        'uses' => 'OrderController@userOrders',
        'as' => 'user-orders',
        'title' => ['actions.show', 'dashboard.user_orders']
    ]);

    Route::get('order/accept-terms/{order_id}', [
        'uses' => 'OrderController@acceptTerms',
        'as' => 'orders.accept_terms',
        'title' => ['actions.accept_terms', 'dashboard.orders']
    ]);

    Route::patch('/orders/{order}/notes', [
        'uses' => 'OrderController@updateNotes',
        'as' => 'orders.updateNotes',
        'title' => ['actions.updateNotes', 'dashboard.orders']
    ]);

    /*------------ end Of orders ----------*/
    /*------------ start Of bank_accounts ----------*/
    Route::get('bank-accounts', [
        'uses' => 'BankAccountsController@index',
        'as' => 'bank-accounts.index',
        'title' => 'dashboard.bank-accounts',
        'type' => 'parent',
        'child' => ['bank-accounts.create', 'bank-accounts.edit', 'bank-accounts.destroy', 'bank-accounts.show', 'bank-accounts.deleteAll']
    ]);

    # bank-accounts store
    Route::get('bank-accounts/create', [
        'uses' => 'BankAccountsController@create',
        'as' => 'bank-accounts.create',
        'title' => ['actions.add', 'dashboard.bank-accounts']
    ]);
    # bank-accounts.show
    Route::get('bank-accounts/{id}/show', [
        'uses' => 'BankAccountsController@show',
        'as' => 'bank-accounts.show',
        'title' => ['actions.show', 'dashboard.bank-accounts']
    ]);
    # bank-accounts.export
    Route::get('bank-accounts/{id}/export', [
        'uses' => 'BankAccountsController@export',
        'as' => 'bank-accounts.export',
        'title' => ['', 'dashboard.export']
    ]);

    # bank-accounts store
    Route::post('bank-accounts/store', [
        'uses' => 'BankAccountsController@store',
        'as' => 'bank-accounts.store',
        'title' => ['actions.add', 'dashboard.bank-accounts']
    ]);

    # bank-accounts update
    Route::get('bank-accounts/{id}/edit', [
        'uses' => 'BankAccountsController@edit',
        'as' => 'bank-accounts.edit',
        'title' => ['actions.edit', 'dashboard.bank-accounts']
    ]);

    # bank-accounts update
    Route::put('bank-accounts/{id}', [
        'uses' => 'BankAccountsController@update',
        'as' => 'bank-accounts.update',
        'title' => ['actions.edit', 'dashboard.bank-accounts']
    ]);

    # bank-accounts delete
    Route::delete('bank-accounts/{id}', [
        'uses' => 'BankAccountsController@destroy',
        'as' => 'bank-accounts.destroy',
        'title' => ['actions.delete', 'dashboard.bank-accounts']
    ]);
    #delete all bank-accounts
    Route::post('delete-all-bank-accounts', [
        'uses' => 'BankAccountsController@deleteAll',
        'as' => 'bank-accounts.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.bank-accounts']
    ]);

    /*------------ end Of bank-accounts ----------*/
    /*------------ start Of payments ----------*/
    Route::get('payments', [
        'uses' => 'PaymentsController@index',
        'as' => 'payments.index',
        'title' => 'dashboard.payments',
        'type' => 'parent',
        'child' => ['payments.create', 'payments.transfer', 'money-transfer', 'transactions.index', 'transactions.destroy', 'accounts.store', 'accounts.update', 'payments.show', 'payments.edit', 'payments.update', 'payments.destroy', 'payments.deleteAll']
    ]);
    # payments store
    Route::get('transactions', [
        'uses' => 'PaymentsController@transactions',
        'as' => 'transactions.index',
        'act-as' => 'payments.index',
        'title' => ['actions.add', 'dashboard.accounts']
    ]);

    # payments store
    Route::post('accounts/store', [
        'uses' => 'PaymentsController@accountsStore',
        'as' => 'accounts.store',
        'act-as' => 'payments.create',
        'title' => ['actions.add', 'dashboard.accounts']
    ]);

    // New routes to handle account charges via GeneralPaymentsController
    Route::post('general-accounts/store', [
        'uses' => 'GeneralPaymentsController@storeAccountCharge',
        'as' => 'general-accounts.store',
        'act-as' => 'payments.create',
        'title' => ['actions.add', 'dashboard.accounts']
    ]);

    # accounts update
    Route::get('accounts/{id}/edit', [
        'uses' => 'PaymentsController@accountsEdit',
        'as' => 'accounts.edit',
        'act-as' => 'payments.edit',
        'title' => ['actions.edit', 'dashboard.accounts']
    ]);

    # payments update
    Route::put('accounts/{id}', [
        'uses' => 'PaymentsController@accountsUpdate',
        'as' => 'accounts.update',
        'act-as' => 'payments.edit',
        'title' => ['actions.edit', 'dashboard.payments']
    ]);

    // New route to update account charges via GeneralPaymentsController
    Route::put('general-accounts/{id}', [
        'uses' => 'GeneralPaymentsController@updateAccountCharge',
        'as' => 'general-accounts.update',
        'act-as' => 'payments.edit',
        'title' => ['actions.edit', 'dashboard.accounts']
    ]);

    # payments store
    Route::get('payments/create/{bankAccount?}', [
        'uses' => 'PaymentsController@create',
        'as' => 'payments.create',
        'title' => ['actions.add', 'dashboard.payments']
    ]);
    
        Route::get('payments/transfer', [
        'uses' => 'PaymentsController@transfer',
        'as' => 'payments.transfer',
        'title' => ['actions.add', 'dashboard.payments']
    ]);
    
    
    Route::post('payments/money-transfer', [
        'uses' => 'PaymentsController@moneyTransfer',
        'as' => 'money-transfer',
        // 'title' => ['actions.add', 'dashboard.payments']
    ]);


    # payments show
    Route::get('payments/{id}/show', [
        'uses' => 'PaymentsController@show',
        'as' => 'payments.show',
        'title' => ['actions.show', 'dashboard.payments']
    ]);

    # payments verified
    Route::get('payments/{id}/verified', [
        'uses' => 'PaymentsController@verified',
        'as' => 'payments.verified',
        'title' => ['actions.verified', 'dashboard.payments']
    ]);

    # payments print
    Route::get('payments/{id}/print', [
        'uses' => 'PaymentsController@print',
        'as' => 'payments.print',
        'title' => ['actions.print', 'dashboard.payments']
    ]);

    # payments store
    Route::post('payments/store', [
        'uses' => 'PaymentsController@store',
        'as' => 'payments.store',
        'title' => ['actions.add', 'dashboard.payments']
    ]);

    # payments update
    Route::get('payments/{id}/edit', [
        'uses' => 'PaymentsController@edit',
        'as' => 'payments.edit',
        'title' => ['actions.edit', 'dashboard.payments']
    ]);

    # payments update
    Route::put('payments/{id}', [
        'uses' => 'PaymentsController@update',
        'as' => 'payments.update',
        'title' => ['actions.edit', 'dashboard.payments']
    ]);

    # payments delete
    Route::delete('payments/{id}', [
        'uses' => 'PaymentsController@destroy',
        'as' => 'payments.destroy',
        'title' => ['actions.delete', 'dashboard.payments']
    ]);

    Route::delete('transactions/{id}', [
        'uses' => 'PaymentsController@transactionsDestroy',
        'as' => 'transactions.destroy',
        'act-as' => 'payments.destroy',
        'title' => ['actions.delete', 'dashboard.payments']
    ]);
    #delete all payments
    Route::post('delete-all-payments', [
        'uses' => 'PaymentsController@deleteAll',
        'as' => 'payments.deleteAll',
        'title' => ['actions.delete_all', 'dashboard.payments']
    ]);

    /*------------ end Of payments ----------*/
    /*------------ start Of payment-links ----------*/
    Route::get('payment-links', [
        'uses' => 'PaymentLinkController@index',
        'as' => 'payment-links.index',
        'title' => 'dashboard.payment-links',
        'type' => 'parent',
    ]);

    # payment-links create
    Route::get('payment-links/create', [
        'uses' => 'PaymentLinkController@create',
        'as' => 'payment-links.create',
        'title' => ['actions.add', 'dashboard.payment-links']
    ]);

    # payment-links store
    Route::post('payment-links', [
        'uses' => 'PaymentLinkController@store',
        'as' => 'payment-links.store',
        'title' => ['actions.add', 'dashboard.payment-links']
    ]);

    # payment-links show created
    Route::get('payment-links/created', [
        'uses' => 'PaymentLinkController@showCreated',
        'as' => 'payment-links.show-created',
        'title' => ['actions.show', 'dashboard.payment_link_created']
    ]);

    # payment-links test-connection
    Route::post('payment-links/test-connection', [
        'uses' => 'PaymentLinkController@testConnection',
        'as' => 'payment-links.test-connection',
        'title' => ['actions.test', 'dashboard.payment-links']
    ]);

    # payment-links resend whatsapp
    Route::post('payment-links/{id}/resend-whatsapp', [
        'uses' => 'PaymentLinkController@resendWhatsApp',
        'as' => 'payment-links.resend-whatsapp',
        'title' => ['actions.resend', 'dashboard.payment_links']
    ]);

    # payment-links test-connection debug
    Route::get('payment-links/test-connection-debug', [
        'uses' => 'PaymentLinkController@testConnectionDebug',
        'as' => 'payment-links.test-connection-debug',
        'title' => ['actions.test', 'dashboard.payment-links']
    ]);

    # payment-links test-connection simple
    Route::get('payment-links/test-simple', function () {
        return response()->json([
            'success' => true,
            'message' => 'Route working correctly',
            'timestamp' => now()
        ]);
    });

    # payment-links test-simple controller
    Route::get('payment-links/test-simple-controller', [
        'uses' => 'PaymentLinkController@testSimple',
        'as' => 'payment-links.test-simple-controller'
    ]);

    # payment-links show
    Route::get('payment-links/{paymentLink}', [
        'uses' => 'PaymentLinkController@show',
        'as' => 'payment-links.show',
        'title' => ['actions.show', 'dashboard.payment-links']
    ]);

    # payment-links resend
    Route::post('payment-links/{paymentLink}/resend', [
        'uses' => 'PaymentLinkController@resend',
        'as' => 'payment-links.resend',
        'title' => ['actions.resend', 'dashboard.payment-links']
    ]);

    # payment-links resend email
    Route::post('payment-links/{paymentLink}/resend-email', [
        'uses' => 'PaymentLinkController@resendEmail',
        'as' => 'payment-links.resend-email',
        'title' => ['actions.resend_email', 'dashboard.payment-links']
    ]);

    # payment-links cancel
    Route::post('payment-links/{paymentLink}/cancel', [
        'uses' => 'PaymentLinkController@cancel',
        'as' => 'payment-links.cancel',
        'title' => ['actions.cancel', 'dashboard.payment-links']
    ]);

    # payment-links destroy
    Route::delete('payment-links/{paymentLink}', [
        'uses' => 'PaymentLinkController@destroy',
        'as' => 'payment-links.destroy',
        'title' => ['actions.delete', 'dashboard.payment-links']
    ]);

    # payment-links qr-code
    Route::get('payment-links/{paymentLink}/qr-code', [
        'uses' => 'PaymentLinkController@qrCode',
        'as' => 'payment-links.qr-code',
        'title' => ['actions.qr-code', 'dashboard.payment-links']
    ]);

    # payment-links copy
    Route::get('payment-links/{paymentLink}/copy', [
        'uses' => 'PaymentLinkController@copy',
        'as' => 'payment-links.copy',
        'title' => ['actions.copy', 'dashboard.payment-links']
    ]);

    # payment-links update-status
    Route::get('payment-links/{paymentLink}/update-status', [
        'uses' => 'PaymentLinkController@updateStatus',
        'as' => 'payment-links.update-status',
        'title' => ['actions.update-status', 'dashboard.payment-links']
    ]);

    # payment-links test email
    Route::get('payment-links/test-email', [
        'uses' => 'PaymentLinkController@testEmail',
        'as' => 'payment-links.test-email',
        'title' => ['actions.test_email', 'dashboard.payment-links']
    ]);

    # payment-links check-all-status
    Route::post('payment-links/check-all-status', [
        'uses' => 'PaymentLinkController@checkAllStatus',
        'as' => 'payment-links.check-all-status',
        'title' => ['actions.check_all_status', 'dashboard.payment-links']
    ]);
    /*------------ end Of payment-links ----------*/

    /*------------ start Of webhooks ----------*/
    # webhook for Paymennt
    Route::post('webhooks/paymennt', [
        'uses' => 'WebhookController@handle',
        'as' => 'webhook.paymennt'
    ])->withoutMiddleware(['auth', 'admin-lang', 'web', 'check-role']);

    # test webhook route
    Route::get('webhook/test', function () {
        return response()->json(['message' => 'Webhook route is working!']);
    })->withoutMiddleware(['auth', 'admin-lang', 'web', 'check-role']);
    /*------------ end Of webhooks ----------*/

    Route::get('warehouse-sales', [
        'uses' => 'WarehousesalesController@index',
        'as' => 'warehouse_sales.index',
        'title' => 'dashboard.warehouse_sales',
        'type' => 'parent',
        'child' => ['warehouse_sales.edit', 'warehouse_sales.destroy', 'warehouse_sales.store']
    ]);

    Route::get('warehouse-sales/{id}/show', [
        'uses' => 'WarehousesalesController@show',
        'as' => 'warehouse_sales.show',
        'title' => ['actions.show', 'dashboard.warehouse_sales']
    ]);

    Route::post('warehouse-sales/store', [
        'uses' => 'WarehousesalesController@store',
        'as' => 'warehouse_sales.store',
        'title' => ['actions.store', 'dashboard.warehouse_sales']
    ]);

    Route::put('warehouse_sales/{id}/update', [
        'uses' => 'WarehousesalesController@update',
        'as' => 'warehouse_sales.update',
        'title' => ['actions.update', 'dashboard.warehouse_sales']
    ]);

    Route::delete('warehouse_sales/{id}', [
        'uses' => 'WarehousesalesController@destroy',
        'as' => 'warehouse_sales.destroy',
        'title' => ['actions.delete', 'dashboard.warehouse_sales']
    ]);

    /*------------ end Of warehouse_sales ----------*/

    /*------------ start Of expense_items ----------*/
    Route::get('expense-items', [
        'uses' => 'ExpenseItemsController@index',
        'as' => 'expense-items.index',
        'title' => 'dashboard.expense-items',
        'type' => 'parent',
        'child' => ['expense-items.create', 'expense-items.edit', 'expense-items.destroy']
    ]);

    # expense-items store
    Route::get('expense-items/create', [
        'uses' => 'ExpenseItemsController@create',
        'as' => 'expense-items.create',
        'title' => ['actions.add', 'dashboard.expense-items']
    ]);

    # expense-items show
    Route::get('expense-items/{id}/show', [
        'uses' => 'ExpenseItemsController@show',
        'as' => 'expense-items.show',
        'title' => ['actions.show', 'dashboard.expense-items']
    ]);

    # expense-items store
    Route::post('expense-items/store', [
        'uses' => 'ExpenseItemsController@store',
        'as' => 'expense-items.store',
        'title' => ['actions.add', 'dashboard.expense-items']
    ]);

    # expense-items update
    Route::put('expense-items/{id}', [
        'uses' => 'ExpenseItemsController@update',
        'as' => 'expense-items.update',
        'title' => ['actions.edit', 'dashboard.expense-items']
    ]);

    # expense-items edit
    Route::get('expense-items/{id}/edit', [
        'uses' => 'ExpenseItemsController@edit',
        'as' => 'expense-items.edit',
        'title' => ['actions.edit', 'dashboard.expense-items']
    ]);

    # expense-items delete
    Route::delete('expense-items/{id}', [
        'uses' => 'ExpenseItemsController@destroy',
        'as' => 'expense-items.destroy',
        'title' => ['actions.delete', 'dashboard.expense-items']
    ]);

    /*------------ end Of expense-items ----------*/
    /*------------ start Of expenses ----------*/
    Route::get('expenses', [
        'uses' => 'ExpensesController@index',
        'as' => 'expenses.index',
        'title' => 'dashboard.expenses',
        'type' => 'parent',
        'child' => ['expenses.create', 'expenses.edit', 'expenses.destroy']
    ]);

    # expenses export
    Route::get('expenses/export', [
        'uses' => 'ExpensesController@export',
        'as' => 'expenses.export',
        'title' => ['actions.export', 'dashboard.expenses']
    ]);

    # expenses store
    Route::get('expenses/create', [
        'uses' => 'ExpensesController@create',
        'as' => 'expenses.create',
        'title' => ['actions.add', 'dashboard.expenses']
    ]);

    # expenses show
    Route::get('expenses/{id}/show', [
        'uses' => 'ExpensesController@show',
        'as' => 'expenses.show',
        'title' => ['actions.show', 'dashboard.expenses']
    ]);

    # expenses store
    Route::post('expenses/store', [
        'uses' => 'ExpensesController@store',
        'as' => 'expenses.store',
        'title' => ['actions.add', 'dashboard.expenses']
    ]);

    # expenses update
    Route::put('expenses/{id}', [
        'uses' => 'ExpensesController@update',
        'as' => 'expenses.update',
        'title' => ['actions.edit', 'dashboard.expenses']
    ]);

    # expenses edit
    Route::get('expenses/{id}/edit', [
        'uses' => 'ExpensesController@edit',
        'as' => 'expenses.edit',
        'title' => ['actions.edit', 'dashboard.expenses']
    ]);


    # expenses delete
    Route::delete('expenses/{id}', [
        'uses' => 'ExpensesController@destroy',
        'as' => 'expenses.destroy',
        'title' => ['actions.delete', 'dashboard.expenses']
    ]);

    # expenses image download
    Route::get('expenses/{id}/download-image', [
        'uses' => 'ExpensesController@downloadImage',
        'as' => 'expenses.download_image',
        'title' => ['actions.download', 'dashboard.expenses']
    ]);

    /*------------ end Of expenses ----------*/
    /*------------ start Of Settings----------*/
    Route::get('set-lang/{lang}', [
        'uses' => 'SettingController@SetLanguage',
        'as' => 'set-lang',
        'title' => 'dashboard.set_lang'
    ]);

    Route::get('calender', [
        'uses' => 'HomeController@calender',
        'as' => 'calender',
        'title' => 'dashboard.calender'
    ]);
    /*------------ end Of Settings ----------*/

    /*------------ start Of general payments ----------*/
    Route::get('general_payments', [
        'uses' => 'GeneralPaymentsController@index',
        'as' => 'general_payments.index',
        'title' => 'dashboard.general_payments',
        'type' => 'parent',
        'child' => ['general_payments.create', 'general_payments.store', 'general_payments.edit', 'general_payments.update', 'general_payments.destroy', 'general_payments.verified', 'general_payments.download']
    ]);

    Route::get('general_payments/create', [
        'uses' => 'GeneralPaymentsController@create',
        'as' => 'general_payments.create',
        'title' => ['actions.add', 'dashboard.general_payments']
    ]);

    Route::post('general_payments', [
        'uses' => 'GeneralPaymentsController@store',
        'as' => 'general_payments.store',
        'title' => ['actions.add', 'dashboard.general_payments']
    ]);

    Route::get('general_payments/{id}/edit', [
        'uses' => 'GeneralPaymentsController@edit',
        'as' => 'general_payments.edit',
        'title' => ['actions.edit', 'dashboard.general_payments']
    ]);

    Route::get('general_payments/{id}/show', [
        'uses' => 'GeneralPaymentsController@show',
        'as' => 'general_payments.show',
        'title' => ['actions.show', 'dashboard.general_payments']
    ]);

    Route::put('general_payments/{id}', [
        'uses' => 'GeneralPaymentsController@update',
        'as' => 'general_payments.update',
        'title' => ['actions.edit', 'dashboard.general_paaddon.bladeyments']
    ]);

    Route::delete('general_payments/{id}', [
        'uses' => 'GeneralPaymentsController@destroy',
        'as' => 'general_payments.destroy',
        'title' => ['actions.delete', 'dashboard.general_payments']
    ]);

    Route::get('general_payments/{id}/download', [
        'uses' => 'GeneralPaymentsController@downloadImage',
        'as' => 'general_payments.download',
        'title' => ['actions.download', 'dashboard.general_payments']
    ]);

    Route::get('general_payments/{id}/verified', [
        'uses' => 'GeneralPaymentsController@verified',
        'as' => 'general_payments.verified',
        'title' => ['actions.verified', 'dashboard.general_payments']
    ]);

    Route::get('general_payments/add-funds/create', [
        'uses' => 'GeneralPaymentsController@createAddFunds',
        'as' => 'general_payments.create_add_funds',
        'title' => ['actions.add_funds', 'dashboard.general_payments']
    ]);

    Route::post('general_payments/add-funds/store', [
        'uses' => 'GeneralPaymentsController@storeAddFunds',
        'as' => 'general_payments.store_add_funds',
        'title' => ['actions.add_funds', 'dashboard.general_payments']
    ]);

    Route::put('general_payments/add-funds/update/{id}', [
        'uses' => 'GeneralPaymentsController@updateAddFunds',
        'as' => 'general_payments.update_add_funds',
        'title' => ['actions.update_funds', 'dashboard.general_payments']
    ]);
    /*------------ end Of general payments ----------*/
});

/*** update route if i added new routes  */

use App\Http\Controllers\Dashboard\TermsSittngController;
use App\Http\Controllers\Dashboard\ViolationController;
use App\Http\Controllers\Dashboard\ViolationTypeController;
use App\Http\Controllers\statisticsController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;



Route::get('update-routes', function () {
    $routes_data = [];

    foreach (Route::getRoutes() as $route) {
        if ($route->getName()) {

            $check_permission = Permission::where('name', $route->getName())->count();

            if (!$check_permission) {
                $routes_data[] = [
                    'name' => $route->getName(),
                    'nickname_en' => $route->getName(),
                    'nickname_ar' => $route->getName(),
                    'guard_name' => 'web'
                ];
            }
        }
    }
    Permission::insert($routes_data);

    $admin = App\Models\User::find(1);
    $role = Role::find(1);

    $role->givePermissionTo(Permission::all());
    $admin->assignRole('super-admin');
});

Route::get('files/storage/{filePath}', [AdminController::class, 'fileStorageServe'])->where(['filePath' => '.*'])->name('serve.file');

/*** USE AUTH AREA  */
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
// REHIESTER
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
// routes/web.php
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
/*** USE AUTH AREA  */

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('key:generate');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    Artisan::call('storage:link');
    return response()->json(['status' => 'success', 'code' => 1000000000]);
});
// General payments routes are now defined inside the dashboard middleware group above

// Custom create route with optional id for terms settings
Route::get('terms_sittngs/create/{id?}', [TermsSittngController::class, 'create'])
    ->name('terms_sittngs.create')
    ->middleware(['auth']);
// Other resource routes (excluding create) remain
Route::resource('terms_sittngs', TermsSittngController::class)
    ->except(['create'])
    ->middleware(['auth']);
Route::get('/Terms_and_Conditions/{link}', [OrderController::class, 'getInvoiceByLink']);
Route::resource('statistics', statisticsController::class);
// Stock report page
Route::get('stocks/{stock}/report', [\App\Http\Controllers\Dashboard\StockController::class, 'stockReport'])->name('stocks.report');

Route::resource('questions', QuestionController::class);
Route::post('/questions/{question}/answer', [QuestionController::class, 'storeAnswer'])->name('questions.storeAnswer');
Route::get('/questions/{id}/answers', [QuestionController::class, 'showAnswers'])->name('questions.answers');
Route::get('answers/user/{userId}', [QuestionController::class, 'showUserAnswers'])->name('answers.user');

// Admin reports
Route::group(['middleware' => ['auth', 'admin']], function () {
    // Task Types
    Route::resource('task-types', 'Dashboard\TaskTypeController');

    Route::resource('tasks', 'Dashboard\TaskController')
        ->except('show')
        ->middleware(['auth', 'permission:tasks.index|tasks.create|tasks.edit|tasks.destroy']);

    // Orders -> Tasks listing with filters
    Route::get('orders/tasks', 'Dashboard\TaskController@ordersTasksIndex')
        ->name('orders.tasks.index')
        ->middleware(['auth', 'permission:tasks.index']);

    Route::get('tasks/reports', 'Dashboard\TaskController@reports')
        ->name('tasks.reports');

    Route::get('tasks/export-reports', 'Dashboard\TaskController@exportReports')
        ->name('tasks.exportReports');

    // Contact Guides
    Route::resource('contact-guides', App\Http\Controllers\Dashboard\ContactGuideController::class)
        ->middleware(['auth', 'admin'])
        ->except(['show']);

    // Export Contact Guides as PDF
    Route::get('contact-guides/export/pdf', [App\Http\Controllers\Dashboard\ContactGuideController::class, 'exportPdf'])
        ->name('contact-guides.export-pdf')
        ->middleware(['auth', 'admin']);
});

// Employee routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('employee/tasks', 'Dashboard\TaskController@myTasks')
        ->name('employee.tasks');

    Route::post('tasks/{task}/status', 'Dashboard\TaskController@updateTaskStatus')
        ->name('tasks.updateStatus');
});

Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.read')->middleware(['auth']);

Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])
    ->name('notifications.destroy')->middleware(['auth']);

// Notice Routes
Route::get('notices', [
    'uses' => 'Dashboard\NoticeController@index',
    'as' => 'notices.index',
    'title' => 'dashboard.notices',
    'type' => 'parent',
    'child' => ['notices.create', 'notices.store', 'notices.show', 'notices.edit', 'notices.update', 'notices.destroy']
])->middleware(['auth']);

Route::post('notices/store', [
    'uses' => 'Dashboard\NoticeController@store',
    'as' => 'notices.store',
    'title' => ['actions.add', 'dashboard.notice']
])->middleware(['auth']);

Route::get('notices/{notice}', [
    'uses' => 'Dashboard\NoticeController@show',
    'as' => 'notices.show',
    'title' => ['actions.show', 'dashboard.notice']
])->middleware(['auth']);

Route::get('notices/{notice}/edit', [
    'uses' => 'Dashboard\NoticeController@edit',
    'as' => 'notices.edit',
    'title' => ['actions.edit', 'dashboard.notice']
])->middleware(['auth']);

Route::put('notices/{notice}', [
    'uses' => 'Dashboard\NoticeController@update',
    'as' => 'notices.update',
    'title' => ['actions.update', 'dashboard.notice']
])->middleware(['auth']);

Route::delete('notices/{notice}', [
    'uses' => 'Dashboard\NoticeController@destroy',
    'as' => 'notices.destroy',
    'title' => ['actions.delete', 'dashboard.notice']
])->middleware(['auth']);

Route::resource('notice-types', 'Dashboard\NoticeTypeController')->middleware(['auth']);

Route::get('notices/get-customer-orders/{customer_id}', [
    'uses' => 'Dashboard\NoticeController@getCustomerOrders',
    'as' => 'notices.get-customer-orders'
])->middleware(['auth']);

Route::get('orders/check-customer-notices/{customerId}', [
    'uses' => 'OrderController@checkCustomerNotices',
    'as' => 'orders.check-customer-notices'
])->middleware(['auth']);



// Payment Callback Route
Route::get('payment/callback', [App\Http\Controllers\PaymentWebhookController::class, 'callback'])
    ->name('payment.callback');

// Test email route (public access)
Route::get('test-email', [
    'uses' => 'App\Http\Controllers\Dashboard\PaymentLinkController@testEmail',
    'as' => 'test.email'
]);

Route::group(['middleware' => ['auth']], function () {
    Route::resource('daily-reports', 'Dashboard\DailyReportController');
    Route::get('daily-reports/export/pdf', [DailyReportController::class, 'exportToPdf'])
        ->name('daily-reports.export');
});

// Equipment Directories
Route::resource('equipment-directories', EquipmentDirectoryController::class)
    ->except(['show'])
    ->middleware(['auth']);

// Directory Items
Route::prefix('equipment-directories/{equipmentDirectory}/items')->middleware('auth')->group(function () {
    Route::get('/', [EquipmentDirectoryController::class, 'itemsIndex'])
        ->name('equipment-directories.items.index');
    Route::get('/create', [EquipmentDirectoryController::class, 'createItem'])
        ->name('equipment-directories.items.create');
    Route::post('/', [EquipmentDirectoryController::class, 'storeItem'])
        ->name('equipment-directories.items.store');
    Route::get('/{item}/edit', [EquipmentDirectoryController::class, 'editItem'])
        ->name('equipment-directories.items.edit');
    Route::put('/{item}', [EquipmentDirectoryController::class, 'updateItem'])
        ->name('equipment-directories.items.update');
    Route::delete('/{item}', [EquipmentDirectoryController::class, 'destroyItem'])
        ->name('equipment-directories.items.destroy');
});

// Media
Route::delete('/equipment-directories/media/{media}', [EquipmentDirectoryController::class, 'destroyMedia'])
    ->name('equipment-directories.media.destroy')->middleware(['auth']);

// PDF Export
Route::get('/equipment-directories/{equipmentDirectory}/export', [EquipmentDirectoryController::class, 'exportPdf'])
    ->name('equipment-directories.export')->middleware(['auth']);
Route::get('equipment-directories/export', [EquipmentDirectoryController::class, 'exportDirectoriesPdf'])
    ->name('equipment-directories.export')->middleware(['auth']);
Route::get('equipment-directories/{equipmentDirectory}/items/export', [EquipmentDirectoryController::class, 'exportItemsPdf'])
    ->name('equipment-directories.items.export')->middleware(['auth']);

Route::resource('camp-reports', 'Dashboard\CampReportController')
    ->except(['show'])
    ->middleware(['auth', 'permission:camp-reports.index|camp-reports.create|camp-reports.edit|camp-reports.destroy']);
Route::get('camp-reports/{campReport}', 'Dashboard\CampReportController@show')
    ->name('camp-reports.show')->middleware(['auth']);
Route::get('camp-reports/export/pdf', [CampReportController::class, 'exportPdf'])->name('camp-reports.export');

// Order Status Updates
Route::post('orders/status', [OrderStatusController::class, 'getOrderStatuses'])
    ->name('orders.status');

// Order Internal Notes Routes
Route::post('orders/{order}/internal-notes', [OrderController::class, 'updateInternalNote'])
    ->name('orders.internal-notes.store')
    ->middleware(['auth']);

Route::get('orders/{order}/internal-notes', [OrderController::class, 'getInternalNotes'])
    ->name('orders.internal-notes.index')
    ->middleware(['auth']);
    
Route::delete('orders/{order}/internal-notes/{note}', [OrderController::class, 'deleteInternalNote'])
    ->name('orders.internal-notes.destroy')
    ->middleware(['auth']);

Route::resource('meetings', MeetingController::class)
    ->middleware(['auth']);
Route::resource('meeting-locations', MeetingLocationController::class)
    ->middleware(['auth']);
Route::get('/meetings/export/pdf', [MeetingController::class, 'exportPdf'])->name('meetings.export');
Route::get('/meetings/{meeting}/export/pdf', [MeetingController::class, 'exportSinglePdf'])->name('meetings.export.single');

Route::resource('violation-types', ViolationTypeController::class)
    ->middleware(['auth']);

Route::resource('violations', ViolationController::class)
    ->middleware(['auth']);

Route::get('orders/{id}/client-pdf', [
    'uses' => 'Dashboard\OrderController@generateClientPDF',
    'as' => 'orders.client-pdf',
    'title' => ['client-pdf', 'dashboard.orders']
]);

// Addon receipt route
Route::get('orders/{order}/addons/{addon}/receipt', [OrderController::class, 'addonReceipt'])->name('addons.receipt');

// Payment receipt route
Route::get('orders/{order}/payments/{payment}/receipt', [OrderController::class, 'paymentReceipt'])->name('payments.receipt');

// Warehouse receipt route
Route::get('orders/{order}/warehouse/{warehouse}/receipt', [OrderController::class, 'warehouseReceipt'])->name('warehouse.receipt');

// Temporary route to generate order numbers for existing orders
Route::get('/generate-order-numbers', function () {
    // Check if we're in local environment for safety
    if (!app()->environment('local')) {
        return response()->json(['error' => 'This route is only available in local environment'], 403);
    }

    $orders = Order::whereNull('order_number')->get();
    $updatedCount = 0;
    $errors = [];

    foreach ($orders as $order) {
        try {
            $order->order_number = App\Models\Order::generateOrderNumber();
            $order->save();
            $updatedCount++;
        } catch (\Exception $e) {
            $errors[] = "Failed to update order ID {$order->id}: " . $e->getMessage();
        }
    }

    return response()->json([
        'message' => "Successfully generated order numbers for {$updatedCount} orders",
        'errors' => $errors,
        'total_updated' => $updatedCount,
        'total_errors' => count($errors)
    ]);
});


Route::post('orders/{id}/send-email', [OrderController::class, 'sendEmail'])->name('orders.sendEmail');
Route::post('orders/{id}/send-whatsapp', [OrderController::class, 'sendWhatsApp'])->name('orders.sendWhatsApp');

// WhatsApp Message Templates Routes
Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth', 'admin']], function () {
    Route::resource('whatsapp-templates', WhatsappMessageTemplateController::class);
    Route::post('whatsapp-templates/{id}/toggle-status', [WhatsappMessageTemplateController::class, 'toggleStatus'])
        ->name('whatsapp-templates.toggle-status');
});

// Manual WhatsApp Sends Routes
Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth', 'admin']], function () {
    Route::resource('manual-whatsapp-sends', \App\Http\Controllers\Dashboard\ManualWhatsappSendController::class);
    Route::get('manual-whatsapp-sends/search-customers', [\App\Http\Controllers\Dashboard\ManualWhatsappSendController::class, 'searchCustomers'])
        ->name('manual-whatsapp-sends.search-customers');
});
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('surveys/create', [SurveyController::class, 'create'])->name('surveys.create')->middleware(['auth']);
    Route::get('survey/{response_id}/answer', [SurveyController::class, 'answer'])->name('surveys.answer')->middleware(['auth']);
    Route::put('surveys/{survey}', [SurveyController::class, 'update'])->name('surveys.update')->middleware(['auth']);
    Route::get('surveys/{survey}/results', [SurveyController::class, 'results'])->name('surveys.results')->middleware(['auth']);
    Route::get('surveys/{survey}/statistics', [SurveyController::class, 'statistics'])->name('surveys.statistics')->middleware(['auth']);
    Route::get('surveys/settings', [SurveyController::class, 'settings'])->name('surveys.settings')->middleware(['auth']);
    Route::put('surveys/{survey}/settings', [SurveyController::class, 'updateSettings'])->name('surveys.settings.update')->middleware(['auth']);
    Route::get('surveys/demo', [SurveyController::class, 'demo'])->name('surveys.demo');

    // Export routes
    Route::get('surveys/{survey}/results/export-excel', [SurveyController::class, 'exportResultsExcel'])->name('surveys.results.export.excel')->middleware(['auth']);
    Route::get('surveys/{survey}/results/export-pdf', [SurveyController::class, 'exportResultsPdf'])->name('surveys.results.export.pdf')->middleware(['auth']);
    Route::get('surveys/{survey}/statistics/export-excel', [SurveyController::class, 'exportStatisticsExcel'])->name('surveys.statistics.export.excel')->middleware(['auth']);
    Route::get('surveys/{survey}/statistics/export-pdf', [SurveyController::class, 'exportStatisticsPdf'])->name('surveys.statistics.export.pdf')->middleware(['auth']);
    Route::get('surveys/{survey}/answers/export-excel', [SurveyController::class, 'exportAnswersExcel'])->name('surveys.answers.export.excel')->middleware(['auth']);
    Route::get('surveys/{survey}/answers/export-pdf', [SurveyController::class, 'exportAnswersPdf'])->name('surveys.answers.export.pdf')->middleware(['auth']);
});

// Public survey route
Route::get('survey/{survey}/thankyou', [SurveySubmissionController::class, 'thankyou'])->name('surveys.thankyou');
Route::post('survey/{survey}/submit', [SurveySubmissionController::class, 'submit'])->name('surveys.submit');

Route::get('survey/{order}', [SurveyController::class, 'showPublic'])->name('surveys.public');

Route::get('stocks/available', [StockController::class, 'getAvailableStocks'])->name('stocks.available');

// Named routes for ServiceSiteAndCustomerServiceController CRUD actions
Route::post('service_site_customer_service', [ServiceSiteAndCustomerServiceController::class, 'store'])->name('service_site_customer_service.store');
Route::get('service_site_customer_service/{id}/edit', [ServiceSiteAndCustomerServiceController::class, 'edit'])->name('service_site_customer_service.edit');
Route::get('service_site_customer_service/create', [ServiceSiteAndCustomerServiceController::class, 'create'])->name('service_site_customer_service.create');
Route::delete('service_site_customer_service/{id}', [ServiceSiteAndCustomerServiceController::class, 'destroy'])->name('service_site_customer_service.destroy');
Route::put('service_site_customer_service/{id}', [ServiceSiteAndCustomerServiceController::class, 'update'])->name('service_site_customer_service.update');

// Internal notes CRUD (dashboard)
Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function() {
    Route::resource('internal-notes', InternalNoteController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
});

Route::middleware(['web', 'auth'])->group(function(){
    Route::resource('pages', PageController::class)->except(['show']);
});

