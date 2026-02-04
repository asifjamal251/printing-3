<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\BreadController;
use App\Http\Controllers\Admin\CartonRateController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CoatingController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\CylinderInwardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DyeController;
use App\Http\Controllers\Admin\DyeCuttingController;
use App\Http\Controllers\Admin\DyeLockTypeController;
use App\Http\Controllers\Admin\EmbossingController;
use App\Http\Controllers\Admin\FirmController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ItemForBillingController;
use App\Http\Controllers\Admin\JobCardController;
use App\Http\Controllers\Admin\LaminationController;
use App\Http\Controllers\Admin\LeafingController;
use App\Http\Controllers\Admin\MaterialInwardController;
use App\Http\Controllers\Admin\MaterialIssueController;
use App\Http\Controllers\Admin\MaterialOrderController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\OrderSheetController;
use App\Http\Controllers\Admin\PaperCuttingController;
use App\Http\Controllers\Admin\PastingController;
use App\Http\Controllers\Admin\PrintingController;
use App\Http\Controllers\Admin\ProcessingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\ReelInwardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SpotUVController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\WarehouseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/run-migration', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration completed!';
});

Route::get('storage/link', function () {
    try {
        Artisan::call('storage:link');
        return redirect()->back()->with('success', '✔ Storage linked Successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', '❌ Error: ' . $e->getMessage());
    }
})->name('storage.link');

Route::get('clear/all-cache', function () {
    try {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        //Artisan::call('optimize');
        return redirect()->back()->with('success', '✔ All cache cleared successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', '❌ Error: ' . $e->getMessage());
    }
})->name('clear.cache');

Route::get('/', function() {
    //return "ok";
    return redirect()->route('admin.login.form');
    return view('admin.home');
});

Route::middleware('admin.guest')->group(function() {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');


    Route::get('password/reset', [LoginController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [LoginController::class, 'sendResetLinkEmail']);

    Route::get('password/reset/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [LoginController::class, 'reset'])->name('password.request.sore');

    Route::get('new-password/{id}', [LoginController::class, 'newPasswordForm'])->name('password.newPassword');
    Route::post('password/set-password/{id}', [LoginController::class, 'sepPassword'])->name('password.setPassword');

    Route::get('2fa/verify', [LoginController::class, 'show2FAVerificationForm'])->name('2fa.verify');
    Route::post('2fa/verify', [LoginController::class, 'verify2FA'])->name('2fa.verify.post');


});


Route::middleware(['admin.auth', '2fa', 'check.admin.ip', 'login.time'])->group(function() {


    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('can:browse_dashboard');
    Route::post('dashboard', [DashboardController::class, 'filter'])->name('dashboard.filter')->middleware('can:browse_dashboard');



    //Common
     Route::controller(CommonController::class)->name('common.')->group(function(){
        Route::get('common/quality/list/{vendor_id?}', 'qualityList')->name('quality.list');
        Route::get('common/vendor/list', 'vendorList')->name('vendor.list');
        Route::get('common/firm/list', 'firmList')->name('firm.list');
        Route::get('common/client/list', 'clientList')->name('client.list');
        Route::get('common/client/list/carton/rate', 'clientListCartonRate')->name('client.list.carton-rate');
        Route::get('common/country/list', 'countryList')->name('country.list');
        Route::get('common/state/list', 'StateList')->name('state.list');
        Route::get('common/district/list/{state_id}', 'districtList')->name('district.list');
        Route::get('common/city/list', 'cityList')->name('city.list');
        Route::get('common/city/list/all', 'cityListAll')->name('city.list.all');

        Route::get('common/dye/list', 'dyeList')->name('dye.list');
        Route::get('common/dye/single/{id}', 'dyeSingle')->name('dye.single');
        Route::get('common/item/list', 'itemList')->name('item.list');
        Route::get('common/item/details/{id}', 'itemDetails')->name('item.details');

        
        Route::get('common/product/list', 'productList')->name('product.list');
        Route::get('common/product/stock/all', 'productStock')->name('product.stock.all');
        Route::get('common/product/single', 'productSingle')->name('product.single');
        Route::get('common/product/attribute/list', 'productAttributeList')->name('product.attribute.list');
        Route::get('common/product/attribute/single', 'attriSingle')->name('product.attribute.single');
        Route::get('common/product/mo/rate', 'productMORate')->name('product.mo.rate');
    });

     //Excell Download
     Route::controller(ExcelController::class)->prefix('download-excell')->name('excell-download.')->group(function(){
        Route::post('product-stock', 'productStock')->name('product-stock');
    });

    //PDF Download
    Route::controller(PDFController::class)->prefix('download-pdf')->name('pdf.')->group(function(){
        Route::get('billing/{billings}', 'billing')->name('billing');
    });




    Route::controller(BreadController::class)->group(function(){
        Route::get('bread', 'index')->name('bread.index')->middleware('can:browse_bread');
        Route::get('bread/create', 'create')->name('bread.create')->middleware('can:add_bread');
        Route::get('bread/{slug}/edit', 'edit')->name('bread.edit')->middleware('can:edit_bread');
        Route::put('bread/{bread}/update', 'update')->name('bread.update')->middleware('can:edit_bread');
        Route::delete('bread/{slug}/delete', 'destroy')->name('bread.destroy')->middleware('can:delete_bread');
        Route::post('bread', 'store')->name('bread.store')->middleware('can:add_bread');
    });


    Route::controller(RoleController::class)->group(function(){
        Route::get('role', 'index')->name('role.index')->middleware('can:browse_role');
        Route::get('role/create', 'create')->name('role.create')->middleware('can:add_role');
        Route::get('role/{role}/edit', 'edit')->name('role.edit')->middleware('can:edit_role');
        Route::post('role', 'store')->name('role.store')->middleware('can:add_role');
        Route::put('role/{role}', 'update')->name('role.update')->middleware('can:edit_role');
        Route::delete('role/{slug}/delete', 'destroy')->name('role.destroy')->middleware('can:delete_role');
    });


    Route::controller(MenuController::class)->group(function(){
        Route::get('menu', 'index')->name('menu.index')->middleware('can:browse_menu');
        Route::get('menu/create', 'create')->name('menu.create')->middleware('can:add_menu');
        Route::get('menu/{menu}/edit', 'edit')->name('menu.edit')->middleware('can:edit_menu');
        Route::post('menu', 'store')->name('menu.store')->middleware('can:add_menu');
        Route::put('menu/{menu}', 'update')->name('menu.update')->middleware('can:edit_menu');
        Route::delete('menu/{menu}', 'destroy')->name('menu.destroy')->middleware('can:delete_menu');
    });


     //Admin
    Route::controller(AdminController::class)->group(function(){
        Route::match(['get','patch'],'admin', 'index')->name('admin.index')->middleware('can:browse_admin');
        Route::get('admin/create', 'create')->name('admin.create')->middleware('can:add_admin');
        Route::get('admin/{admin}', 'show')->name('admin.show')->middleware('can:read_admin');
        Route::get('admin/{admin}/edit', 'edit')->name('admin.edit')->middleware('can:edit_admin');
        Route::post('admin', 'store')->name('admin.store')->middleware('can:add_admin');
        Route::put('admin/{admin}', 'update')->name('admin.update')->middleware('can:edit_admin');
        Route::delete('admin/{admin}/delete', 'destroy')->name('admin.destroy')->middleware('can:delete_admin');

        Route::get('profile', 'profile')->name('profile');
        Route::put('profile/update', 'profileUpdate')->name('profile.update');
        Route::put('profile/photo/update/{admin}', 'profilePhotoUpdate')->name('profile.photo.update');
        Route::put('profile/cover/photo/update/{admin}', 'profileCoverPhotoUpdate')->name('profile.cover.photo.update');

        Route::get('change-password/{admin}', 'changePassword')->name('change-password');
        Route::put('update-password/{admin}', 'updatePassword')->name('update-password');

        Route::get('admin/2fa/setup/{id}', 'setup2FA')->name('admin.2fa.setup');
        Route::post('admin/2fa/setup/{id}', 'enable2FA')->name('admin.2fa.enable');

    });

     //Site Setting
    Route::controller(AppSettingController::class)->group(function(){
        Route::get('get-all-country', 'getAllCountry')->name('app-setting.country')->middleware('can:browse_app_setting');
        Route::get('app-setting', 'index')->name('app-setting.index')->middleware('can:browse_app_setting');

        Route::post('app/basic-info', 'basicInfo')->name('app-setting.basic-info')->middleware('can:logo_app_setting');
        Route::post('app/contact-details', 'contactDetails')->name('app-setting.contact-details')->middleware('can:logo_app_setting');

        Route::post('app/logo', 'logo')->name('app-setting.logo')->middleware('can:logo_app_setting');
        Route::get('access-control', 'index')->name('access-control.index')->middleware('can:browse_access_control');
    });


    //media
    Route::controller(MediaController::class)->group(function(){
        Route::match(['get','patch'],'media', 'index')->name('media.index')->middleware('can:browse_media');
        Route::get('media/create', 'create')->name('media.create')->middleware('can:add_media');
        Route::get('media/{media}', 'show')->name('media.show')->middleware('can:read_media');
        Route::get('media/{media}/edit', 'edit')->name('media.edit')->middleware('can:edit_media');

        Route::post('media', 'store')->name('media.store')->middleware(['can:add_media', 'optimizeImages']);
        Route::put('media/update/{media}', 'update')->name('media.update')->middleware(['can:edit_media', 'optimizeImages']);

        Route::delete('media/{media}/delete', 'destroy')->name('media.destroy')->middleware('can:delete_media');
        Route::get('media/get/multiple', 'getAllMediaMultiple')->name('media.get.multiple');
        Route::get('media/get/single', 'getAllMediaSingle')->name('media.get.single');
    });


    //country
    Route::controller(CountryController::class)->group(function(){
        Route::match(['get','patch'],'country', 'index')->name('country.index')->middleware('can:browse_country');
        Route::get('country/create', 'create')->name('country.create')->middleware('can:add_country');
        Route::get('country/{id}', 'show')->name('country.show')->middleware('can:read_country');
        Route::get('country/{id}/edit', 'edit')->name('country.edit')->middleware('can:edit_country');
        Route::post('country', 'store')->name('country.store')->middleware('can:add_country');
        Route::put('country/{id}', 'update')->name('country.update')->middleware('can:edit_country');
        Route::delete('country/{id}/delete', 'destroy')->name('country.destroy')->middleware('can:delete_country');
    });

    //country
    Route::controller(CountryController::class)->group(function(){
        Route::match(['get','patch'],'country', 'index')->name('country.index')->middleware('can:browse_country');
        Route::get('country/create', 'create')->name('country.create')->middleware('can:add_country');
        Route::get('country/{id}', 'show')->name('country.show')->middleware('can:read_country');
        Route::get('country/{id}/edit', 'edit')->name('country.edit')->middleware('can:edit_country');
        Route::post('country', 'store')->name('country.store')->middleware('can:add_country');
        Route::put('country/{id}', 'update')->name('country.update')->middleware('can:edit_country');
        Route::delete('country/{id}/delete', 'destroy')->name('country.destroy')->middleware('can:delete_country');
    });

    //State
    Route::controller(StateController::class)->group(function(){
        Route::match(['get','patch'],'state', 'index')->name('state.index')->middleware('can:browse_state');
        Route::get('state/create', 'create')->name('state.create')->middleware('can:add_state');
        Route::get('state/{id}', 'show')->name('state.show')->middleware('can:read_state');
        Route::get('state/{id}/edit', 'edit')->name('state.edit')->middleware('can:edit_state');
        Route::post('state', 'store')->name('state.store')->middleware('can:add_state');
        Route::put('state/{id}', 'update')->name('state.update')->middleware('can:edit_state');
        Route::delete('state/{id}/delete', 'destroy')->name('state.destroy')->middleware('can:delete_state');
    });

    //District
    Route::controller(DistrictController::class)->group(function(){
        Route::match(['get','patch'],'district', 'index')->name('district.index')->middleware('can:browse_district');
        Route::get('district/create', 'create')->name('district.create')->middleware('can:add_district');
        Route::get('district/{id}', 'show')->name('district.show')->middleware('can:read_district');
        Route::get('district/{id}/edit', 'edit')->name('district.edit')->middleware('can:edit_district');
        Route::post('district', 'store')->name('district.store')->middleware('can:add_district');
        Route::put('district/{id}', 'update')->name('district.update')->middleware('can:edit_district');
        Route::delete('district/{id}/delete', 'destroy')->name('district.destroy')->middleware('can:delete_district');
    });


    //City
    Route::controller(CityController::class)->group(function(){
        Route::match(['get','patch'],'city', 'index')->name('city.index')->middleware('can:browse_city');
        Route::get('city/create', 'create')->name('city.create')->middleware('can:add_city');
        Route::get('city/{id}', 'show')->name('city.show')->middleware('can:read_city');
        Route::get('city/{id}/edit', 'edit')->name('city.edit')->middleware('can:edit_city');
        Route::post('city', 'store')->name('city.store')->middleware('can:add_city');
        Route::put('city/{id}', 'update')->name('city.update')->middleware('can:edit_city');
        Route::delete('city/{id}/delete', 'destroy')->name('city.destroy')->middleware('can:delete_city');
    });

    //Client
    Route::controller(ClientController::class)->group(function(){
        Route::match(['get','patch'],'client', 'index')->name('client.index')->middleware('can:browse_client');
        Route::get('client/create', 'create')->name('client.create')->middleware('can:add_client');
        Route::get('client/{id}', 'show')->name('client.show')->middleware('can:read_client');
        Route::get('client/{id}/edit', 'edit')->name('client.edit')->middleware('can:edit_client');
        Route::post('client', 'store')->name('client.store')->middleware('can:add_client');
        Route::put('client/{id}', 'update')->name('client.update')->middleware('can:edit_client');
        Route::delete('client/{id}/delete', 'destroy')->name('client.destroy')->middleware('can:delete_client');

        Route::get('client/import/create', 'importCreate')->name('client.import.create')->middleware('can:add_client');
        Route::post('client/import/store', 'importStore')->name('client.import.store')->middleware('can:add_client');
    });


    //Vendor
    Route::controller(VendorController::class)->group(function(){
        Route::match(['get','patch'],'vendor', 'index')->name('vendor.index')->middleware('can:browse_vendor');
        Route::get('vendor/create', 'create')->name('vendor.create')->middleware('can:add_vendor');
        Route::get('vendor/{id}', 'show')->name('vendor.show')->middleware('can:read_vendor');
        Route::get('vendor/{id}/edit', 'edit')->name('vendor.edit')->middleware('can:edit_vendor');
        Route::post('vendor', 'store')->name('vendor.store')->middleware('can:add_vendor');
        Route::put('vendor/{id}', 'update')->name('vendor.update')->middleware('can:edit_vendor');
        Route::delete('vendor/{id}/delete', 'destroy')->name('vendor.destroy')->middleware('can:delete_vendor');

        Route::get('vendor/import/create', 'importCreate')->name('vendor.import.create')->middleware('can:add_vendor');
        Route::post('vendor/import/store', 'importStore')->name('vendor.import.store')->middleware('can:add_vendor');
    });


    //Status
    Route::controller(StatusController::class)->group(function(){
        Route::match(['get','patch'],'status', 'index')->name('status.index')->middleware('can:browse_status');
        Route::get('status/create', 'create')->name('status.create')->middleware('can:add_status');
        Route::get('status/{id}', 'show')->name('status.show')->middleware('can:read_status');
        Route::get('status/{id}/edit', 'edit')->name('status.edit')->middleware('can:edit_status');
        Route::post('status', 'store')->name('status.store')->middleware('can:add_status');
        Route::put('status/{id}', 'update')->name('status.update')->middleware('can:edit_status');
        Route::delete('status/{id}/delete', 'destroy')->name('status.destroy')->middleware('can:delete_status');
    });


    //Category
    Route::controller(CategoryController::class)->group(function(){
        Route::get('category/create', 'create')->name('category.create')->middleware('can:add_category');
        Route::get('category/{category}/edit', 'edit')->name('category.edit')->middleware('can:edit_category');
        Route::post('category', 'store')->name('category.store')->middleware('can:add_category');
        Route::put('category/{category}', 'update')->name('category.update')->middleware('can:edit_category');
        Route::put('category/parent/{category}', 'updateParent')->name('category.updateParent')->middleware('can:edit_category');
        Route::delete('category/{category}/delete', 'destroy')->name('category.destroy')->middleware('can:delete_category');

        Route::get('category/remove/parent/{category}', 'removeParent')->name('category.remove.parent')->middleware('can:edit_category');

        Route::get('category/parent/list', 'parentList')->name('category.parent')->middleware('can:add_category');
        Route::post('category/update/all', 'updateOrder')->name('category.change')->middleware('can:add_category');
    });


    //Store
    Route::controller(StoreController::class)->group(function(){
        Route::get('store/create', 'create')->name('store.create')->middleware('can:add_store');
        Route::get('store/{store}/edit', 'edit')->name('store.edit')->middleware('can:edit_store');
        Route::post('store', 'store')->name('store.store')->middleware('can:add_store');
        Route::put('store/{store}', 'update')->name('store.update')->middleware('can:edit_store');
        Route::put('store/parent/{store}', 'updateParent')->name('store.updateParent')->middleware('can:edit_store');
    });


     //Product
    Route::controller(ProductController::class)->group(function(){
        Route::match(['get','patch'],'product', 'index')->name('product.index')->middleware('can:browse_product');
        Route::get('product/create', 'create')->name('product.create')->middleware('can:add_product');
        Route::match(['get','patch'],'product/{id}', 'show')->name('product.show')->middleware('can:read_product');
        Route::get('product/{id}/edit', 'edit')->name('product.edit')->middleware('can:edit_product');
        Route::post('product', 'store')->name('product.store')->middleware('can:add_product');
        Route::put('product/{id}', 'update')->name('product.update')->middleware('can:edit_product');
        Route::delete('product/{id}/delete', 'destroy')->name('product.destroy')->middleware('can:delete_product');
        Route::get('product/rate/view', 'rate')->name('product.rate')->middleware('can:rate_product');

        Route::get('product/import/create', 'importCreate')->name('product.import.create');
        Route::get('product/import/show', 'importShow')->name('product.import.show');
        Route::post('product/import/store', 'importStore')->name('product.import.store');
        Route::post('product/import/update', 'importUpdate')->name('product.import.update');

    });


    //ProductType
    Route::controller(ProductTypeController::class)->group(function(){
        //Route::match(['get','patch'],'paper-type', 'index')->name('paper-type.index')->middleware('can:browse_paper_type');
        Route::get('product-type/create', 'create')->name('product-type.create')->middleware('can:add_product_type');
        Route::get('product-type/{id}', 'show')->name('product-type.show')->middleware('can:read_product_type');
        Route::get('product-type/{id}/edit', 'edit')->name('product-type.edit')->middleware('can:edit_product_type');
        Route::post('product-type', 'store')->name('product-type.store')->middleware('can:add_product_type');
        Route::put('product-type/{id}', 'update')->name('product-type.update')->middleware('can:edit_product_type');
        Route::delete('product-type/{id}/delete', 'destroy')->name('product-type.destroy')->middleware('can:delete_product_type');

        Route::post('product-type/update/order', 'updateOrder')->name('product-type.ordering')->middleware('can:edit_product_type');
    });


     //MaterialOrder
    Route::controller(MaterialOrderController::class)->group(function(){
        Route::match(['get','patch'],'material-order', 'index')->name('material-order.index')->middleware('can:browse_material_order');
        Route::get('material-order/create', 'create')->name('material-order.create')->middleware('can:add_material_order');
        Route::get('material-order/show/{id}', 'show')->name('material-order.show')->middleware('can:read_material_order');
        Route::get('material-order/{id}/edit', 'edit')->name('material-order.edit')->middleware('can:edit_material_order');
        Route::post('material-order', 'store')->name('material-order.store')->middleware('can:add_material_order');
        Route::put('material-order/{id}', 'update')->name('material-order.update')->middleware('can:edit_material_order');
        Route::delete('material-order/{id}/delete', 'destroy')->name('material-order.destroy')->middleware('can:delete_material_order');
    });



    //MaterialInward
    Route::controller(MaterialInwardController::class)->group(function(){
        Route::match(['get','patch'],'material-inward', 'index')->name('material-inward.index')->middleware('can:browse_material_inward');
        Route::get('material-inward/create', 'create')->name('material-inward.create')->middleware('can:add_material_inward');
        Route::get('material-inward/{id}', 'show')->name('material-inward.show')->middleware('can:read_material_inward');
        Route::get('material-inward/{id}/edit', 'edit')->name('material-inward.edit')->middleware('can:edit_material_inward');
        Route::post('material-inward', 'store')->name('material-inward.store')->middleware('can:add_material_inward');
        Route::put('material-inward/{id}', 'update')->name('material-inward.update')->middleware('can:edit_material_inward');
        Route::delete('material-inward/{id}/delete', 'destroy')->name('material-inward.destroy')->middleware('can:delete_material_inward');

        Route::delete('material-inward/item/delete/{id}', 'destroyItem')->name('material-inward.destroy.item')->middleware('can:delete_material_inward');
    });




    //DyeLockType
    Route::controller(DyeLockTypeController::class)->group(function(){
        Route::match(['get','patch'],'dye-lock-type', 'index')->name('dye-lock-type.index')->middleware('can:browse_dye_lock_type');
        Route::get('dye-lock-type/create', 'create')->name('dye-lock-type.create')->middleware('can:add_dye_lock_type');
        Route::get('dye-lock-type/{id}', 'show')->name('dye-lock-type.show')->middleware('can:read_dye_lock_type');
        Route::get('dye-lock-type/{id}/edit', 'edit')->name('dye-lock-type.edit')->middleware('can:edit_dye_lock_type');
        Route::post('dye-lock-type', 'store')->name('dye-lock-type.store')->middleware('can:add_dye_lock_type');
        Route::put('dye-lock-type/{id}', 'update')->name('dye-lock-type.update')->middleware('can:edit_dye_lock_type');
        Route::delete('dye-lock-type/{id}/delete', 'destroy')->name('dye-lock-type.destroy')->middleware('can:delete_dye_lock_type');
    });


    //Dye
    Route::controller(DyeController::class)->group(function(){
        Route::match(['get','patch'],'dye', 'index')->name('dye.index')->middleware('can:browse_dye');
        Route::get('dye/create', 'create')->name('dye.create')->middleware('can:add_dye');
        Route::get('dye/{id}', 'show')->name('dye.show')->middleware('can:read_dye');
        Route::get('dye/{id}/edit', 'edit')->name('dye.edit')->middleware('can:edit_dye');
        Route::post('dye', 'store')->name('dye.store')->middleware('can:add_dye');
        Route::put('dye/{id}', 'update')->name('dye.update')->middleware('can:edit_dye');
        Route::delete('dye/{id}/delete', 'destroy')->name('dye.destroy')->middleware('can:delete_dye');

        Route::get('dye/import/create', 'importCreate')->name('dye.import.create')->middleware('can:add_dye');
        Route::post('dye/import/store', 'importStore')->name('dye.import.store')->middleware('can:add_dye');
    });


     //Item
    Route::controller(ItemController::class)->group(function(){
        Route::match(['get','patch'],'item', 'index')->name('item.index')->middleware('can:browse_item');
        Route::get('item/create', 'create')->name('item.create')->middleware('can:add_item');
        Route::get('item/show/{id}', 'show')->name('item.show')->middleware('can:read_item');
        Route::get('item/{id}/edit', 'edit')->name('item.edit')->middleware('can:edit_item');
        Route::post('item/store', 'store')->name('item.store')->middleware('can:add_item');
        Route::put('item/update/{id}', 'update')->name('item.update')->middleware('can:edit_item');
        Route::delete('item/{id}/delete', 'destroy')->name('item.destroy')->middleware('can:delete_item');

        Route::get('item/add-to-po/{id}', 'addToPO')->name('item.add.to.po')->middleware('can:add_purchase_order');
        Route::post('item/add-to-po/store/{id}', 'addToPOStore')->name('item.add.to.po.store')->middleware('can:add_purchase_order');


        Route::get('item/generate/po', 'generatePO')->name('item.generate.po')->middleware('can:add_purchase_order');
        Route::post('item/generate/po/store', 'storePO')->name('item.po.store')->middleware('can:add_purchase_order');


        Route::get('item/import/create', 'importCreate')->name('item.import.create')->middleware('can:add_item');
        Route::post('item/import/store', 'importStore')->name('item.import.store')->middleware('can:add_item');
    });


    //PurchaseOrder
    Route::controller(PurchaseOrderController::class)->group(function(){
        Route::match(['get','patch'],'purchase-order', 'index')->name('purchase-order.index')->middleware('can:browse_purchase_order');
        Route::get('purchase-order/create', 'create')->name('purchase-order.create')->middleware('can:add_purchase_order');
        Route::get('purchase-order/show/{id}', 'show')->name('purchase-order.show')->middleware('can:read_purchase_order');
        Route::get('purchase-order/show/coa/{id}', 'showCoa')->name('purchase-order.show.coa')->middleware('can:read_purchase_order');
        Route::get('purchase-order/{id}/edit', 'edit')->name('purchase-order.edit')->middleware('can:edit_purchase_order');
        Route::get('purchase-order/add/more/item/{id}', 'addMoreItem')->name('purchase-order.add.more.item')->middleware('can:edit_purchase_order');
        Route::post('purchase-order', 'store')->name('purchase-order.store')->middleware('can:add_purchase_order');
        Route::put('purchase-order/{id}', 'update')->name('purchase-order.update')->middleware('can:edit_purchase_order');
        Route::delete('purchase-order/{id}/delete', 'destroy')->name('purchase-order.destroy')->middleware('can:delete_purchase_order');
        Route::match(['get','patch'],'purchase-order/{id}/approvals', 'approval')->name('purchase-order.approval')->middleware('can:read_purchase_order');

        Route::get('purchase-order/item/{id}/edit', 'editItem')->name('purchase-order.edit.item')->middleware('can:edit_purchase_order');
        Route::put('purchase-order/item/{id}', 'updateItem')->name('purchase-order.update.item')->middleware('can:edit_purchase_order');

        Route::put('purchase-order/items/quantity/status', 'quantityStatus')->name('purchase-order.update.item.quantity')->middleware('can:edit_purchase_order');
        Route::put('purchase-order/items/rate/status', 'rateStatus')->name('purchase-order.update.item.rate')->middleware('can:edit_purchase_order');
        Route::put('purchase-order/items/assign/order-sheet', 'assignOrderSheet')->name('purchase-order.update.item.assign.order-sheet')->middleware('can:edit_purchase_order');

        Route::put('purchase-order/items/cancel', 'cancelItem')->name('purchase-order.cancel.item')->middleware('can:delete_purchase_order');
        Route::delete('purchase-order/item/{id}/delete', 'destroyItem')->name('purchase-order.destroy.item')->middleware('can:delete_purchase_order');


        Route::get('purchase-order/sxport/form', 'exportForm')->name('purchase-order.export.form');
        Route::post('purchase-order/export', 'export')->name('purchase-order.export');

    });


    //OrderSheet
    Route::controller(OrderSheetController::class)->group(function(){
        Route::match(['get','patch'],'order-sheet', 'index')->name('order-sheet.index')->middleware('can:browse_order_sheet');
        Route::get('order-sheet/create', 'create')->name('order-sheet.create')->middleware('can:add_order_sheet');
        Route::get('order-sheet/{id}', 'show')->name('order-sheet.show')->middleware('can:read_order_sheet');
        Route::get('order-sheet/{id}/edit', 'edit')->name('order-sheet.edit')->middleware('can:edit_order_sheet');
        Route::post('order-sheet', 'store')->name('order-sheet.store')->middleware('can:add_order_sheet');
        Route::delete('order-sheet/{id}/delete', 'destroy')->name('order-sheet.destroy')->middleware('can:delete_order_sheet');
        Route::post('order-sheet/update/ups', 'updateUPS')->name('order-sheet.update.ups')->middleware('can:edit_order_sheet');

        Route::put('order-sheet/update/final-quantity', 'updateFinalQuantity')->name('order-sheet.update.final-quantity')->middleware('can:edit_order_sheet');
        Route::put('order-sheet/update/job-type', 'updateJobType')->name('order-sheet.update.job-type')->middleware('can:edit_order_sheet');
        Route::put('order-sheet/update/urgent', 'updateUrgent')->name('order-sheet.update.urgent')->middleware('can:edit_order_sheet');
        Route::put('order-sheet/update/ups', 'updateUps')->name('order-sheet.update.ups')->middleware('can:edit_order_sheet');
        Route::put('order-sheet/update/gsm', 'updateGSM')->name('order-sheet.update.gsm')->middleware('can:edit_order_sheet');

        Route::post('order-sheet/create/processing', 'createProcessing')->name('order-sheet.create.processing')->middleware('can:add_order_sheet');

        Route::get('order-sheet/sxport/form', 'exportForm')->name('order-sheet.export.form');
        Route::post('order-sheet/export', 'export')->name('order-sheet.export');

        Route::put('order-sheet/back', 'back')->name('order-sheet.back');
    });



    //Processing
    Route::controller(ProcessingController::class)->group(function(){
        Route::match(['get','patch'],'processing', 'index')->name('processing.index')->middleware('can:browse_processing');
        Route::get('processing/create', 'create')->name('processing.create')->middleware('can:add_processing');
        Route::get('processing/{id}', 'show')->name('processing.show')->middleware('can:read_processing');
        Route::get('processing/{id}/edit', 'edit')->name('processing.edit')->middleware('can:edit_processing');
        Route::post('processing', 'store')->name('processing.store')->middleware('can:add_processing');
        Route::put('processing/{id}', 'update')->name('processing.update')->middleware('can:edit_processing');
        Route::delete('processing/{id}/delete', 'destroy')->name('processing.destroy')->middleware('can:delete_processing');

        Route::put('processing/update/ups', 'updateUps')->name('processing.update.ups')->middleware('can:add_processing');
        Route::put('processing/update/designer', 'updateDesigner')->name('processing.update.designer')->middleware('can:add_processing');
        Route::put('processing/back/to/order-sheet', 'backToOrderSheet')->name('processing.back.to.order-sheet');

        Route::post('processing/store/job-card', 'storeJobCard')->name('processing.store.job-card')->middleware('can:add_processing');
    });



    //JobCard
    Route::controller(JobCardController::class)->group(function(){
        Route::match(['get','patch'],'job-card', 'index')->name('job-card.index')->middleware('can:browse_job_card');
        Route::get('job-card/create', 'create')->name('job-card.create')->middleware('can:add_job_card');
        Route::get('job-card/view/{id}', 'show')->name('job-card.show')->middleware('can:read_job_card');
        Route::get('job-card/{id}/edit', 'edit')->name('job-card.edit')->middleware('can:edit_job_card');
        Route::post('job-card', 'store')->name('job-card.store')->middleware('can:add_job_card');
        Route::put('job-card/update/{id}', 'update')->name('job-card.update')->middleware('can:edit_job_card');
        Route::delete('job-card/{id}/delete', 'destroy')->name('job-card.destroy')->middleware('can:delete_job_card');

        Route::get('job-card/add/details/{id}', 'addDetails')->name('job-card.add.details')->middleware('can:add_details_job_card');
        Route::post('job-card/update/details/{id}', 'updateDetails')->name('job-card.update.details')->middleware('can:add_details_job_card');

        Route::get('job-card/add/operator/{id}', 'addOperator')->name('job-card.operator.create');
        Route::put('job-card/add/operator/update/{id}', 'updateOperator')->name('job-card.operator.update');

        Route::put('job-card/assign', 'assign')->name('job-card.assign');
        Route::put('job-card/cancel', 'cancel')->name('job-card.assign.cancel');

        Route::get('job-card/pdf/download/{id}', 'downloadPdf')->name('job-card.pdf');
        Route::get('job-card/selected/download', 'selectedDownload')->name('job-card.selected.download');
    });


    
    //Firm
    Route::controller(FirmController::class)->group(function(){
        Route::match(['get','patch'],'firm', 'index')->name('firm.index')->middleware('can:browse_firm');
        Route::get('firm/create', 'create')->name('firm.create')->middleware('can:add_firm');
        Route::get('firm/{id}', 'show')->name('firm.show')->middleware('can:read_firm');
        Route::get('firm/{id}/edit', 'edit')->name('firm.edit')->middleware('can:edit_firm');
        Route::post('firm', 'store')->name('firm.store')->middleware('can:add_firm');
        Route::put('firm/{id}', 'update')->name('firm.update')->middleware('can:edit_firm');
        Route::delete('firm/{id}/delete', 'destroy')->name('firm.destroy')->middleware('can:delete_firm');
    });


    //Department
    Route::controller(DepartmentController::class)->group(function(){
        Route::get('department/create', 'create')->name('department.create')->middleware('can:add_department');
        Route::get('department/{id}', 'show')->name('department.show')->middleware('can:read_department');
        Route::get('department/{id}/edit', 'edit')->name('department.edit')->middleware('can:edit_department');
        Route::post('department', 'store')->name('department.store')->middleware('can:add_department');
        Route::put('department/{id}', 'update')->name('department.update')->middleware('can:edit_department');
        Route::delete('department/{id}/delete', 'destroy')->name('department.destroy')->middleware('can:delete_department');
    });


    //MaterialIssue
    Route::controller(MaterialIssueController::class)->group(function(){
        Route::match(['get','patch'],'material-issue', 'index')->name('material-issue.index')->middleware('can:browse_material_issue');
        Route::get('material-issue/create', 'create')->name('material-issue.create')->middleware('can:add_material_issue');
        Route::get('material-issue/show/{id}', 'show')->name('material-issue.show')->middleware('can:read_material_issue');
        Route::get('material-issue/{id}/edit', 'edit')->name('material-issue.edit')->middleware('can:edit_material_issue');
        Route::post('material-issue', 'store')->name('material-issue.store')->middleware('can:add_material_issue');
        Route::put('material-issue/{id}', 'update')->name('material-issue.update')->middleware('can:edit_material_issue');
        Route::delete('material-issue/{id}/delete', 'destroy')->name('material-issue.destroy')->middleware('can:delete_material_issue');
    });


    //CartonRate
    Route::controller(CartonRateController::class)->group(function(){
        Route::match(['get','patch'],'carton-rate', 'index')->name('carton-rate.index')->middleware('can:browse_carton_rate');
        Route::get('carton-rate/{id}/edit', 'edit')->name('carton-rate.edit')->middleware('can:edit_carton_rate');
        Route::put('carton-rate/update/rate', 'update')->name('carton-rate.update.rate')->middleware('can:edit_carton_rate');
        Route::put('carton-rate/update/rate/completed', 'rateCompleted')->name('carton-rate.update.rate.completed')->middleware('can:edit_carton_rate');

        Route::get('carton-rate/sxport/form', 'exportForm')->name('carton-rate.export.form');
        Route::post('carton-rate/export', 'export')->name('carton-rate.export');

        Route::post('carton-rate/update/approved', 'updateApproved')->name('carton-rate.update.approved');
    });



    //Operator
    Route::controller(OperatorController::class)->group(function(){
        Route::match(['get','patch'],'operator', 'index')->name('operator.index')->middleware('can:browse_operator');
        Route::get('operator/create', 'create')->name('operator.create')->middleware('can:add_operator');
        Route::get('operator/{id}', 'show')->name('operator.show')->middleware('can:read_operator');
        Route::get('operator/{id}/edit', 'edit')->name('operator.edit')->middleware('can:edit_operator');
        Route::post('operator', 'store')->name('operator.store')->middleware('can:add_operator');
        Route::put('operator/{id}', 'update')->name('operator.update')->middleware('can:edit_operator');
        Route::delete('operator/{id}/delete', 'destroy')->name('operator.destroy')->middleware('can:delete_operator');
    });


    //PaperCutting
    Route::controller(PaperCuttingController::class)->group(function(){
        Route::match(['get','patch'],'paper-cutting', 'index')->name('paper-cutting.index')->middleware('can:browse_paper_cutting');
        Route::put('paper-cutting/update/operator', 'updateOperator')->name('paper-cutting.update.operator')->middleware('can:edit_paper_cutting');
        Route::put('paper-cutting/update/counter', 'updateCounter')->name('paper-cutting.update.counter')->middleware('can:edit_paper_cutting');
        Route::put('paper-cutting/cancel', 'cancel')->name('paper-cutting.cancel')->middleware('can:edit_paper_cutting');
    });


    //Printing
    Route::controller(PrintingController::class)->group(function(){
        Route::match(['get','patch'],'printing', 'index')->name('printing.index')->middleware('can:browse_printing');
        Route::put('printing/update/operator', 'updateOperator')->name('printing.update.operator')->middleware('can:edit_printing');
        Route::put('printing/update/counter', 'updateCounter')->name('printing.update.counter')->middleware('can:edit_printing');
        Route::put('printing/cancel', 'cancel')->name('printing.cancel')->middleware('can:edit_printing');
    });


    //Coating
    Route::controller(CoatingController::class)->group(function(){
        Route::match(['get','patch'],'coating', 'index')->name('coating.index')->middleware('can:browse_coating');
        Route::put('coating/update/operator', 'updateOperator')->name('coating.update.operator')->middleware('can:edit_coating');
        Route::put('coating/update/counter', 'updateCounter')->name('coating.update.counter')->middleware('can:edit_coating');
        Route::put('coating/cancel', 'cancel')->name('coating.cancel')->middleware('can:edit_coating');
    });


    //Leafing
    Route::controller(LeafingController::class)->group(function(){
        Route::match(['get','patch'],'leafing', 'index')->name('leafing.index')->middleware('can:browse_leafing');
        Route::put('leafing/update/operator', 'updateOperator')->name('leafing.update.operator')->middleware('can:edit_leafing');
        Route::put('leafing/update/counter', 'updateCounter')->name('leafing.update.counter')->middleware('can:edit_leafing');
        Route::put('leafing/cancel', 'cancel')->name('leafing.cancel')->middleware('can:edit_leafing');
    });


    //Embossing
    Route::controller(EmbossingController::class)->group(function(){
        Route::match(['get','patch'],'embossing', 'index')->name('embossing.index')->middleware('can:browse_embossing');
        Route::put('embossing/update/operator', 'updateOperator')->name('embossing.update.operator')->middleware('can:edit_embossing');
        Route::put('embossing/update/counter', 'updateCounter')->name('embossing.update.counter')->middleware('can:edit_embossing');
        Route::put('embossing/cancel', 'cancel')->name('embossing.cancel')->middleware('can:edit_embossing');
    });


    //Lamination
    Route::controller(LaminationController::class)->group(function(){
        Route::match(['get','patch'],'lamination', 'index')->name('lamination.index')->middleware('can:browse_lamination');
        Route::put('lamination/update/operator', 'updateOperator')->name('lamination.update.operator')->middleware('can:edit_lamination');
        Route::put('lamination/update/counter', 'updateCounter')->name('lamination.update.counter')->middleware('can:edit_lamination');
        Route::put('lamination/cancel', 'cancel')->name('lamination.cancel')->middleware('can:edit_lamination');
    });


    //SpotUV
    Route::controller(SpotUVController::class)->group(function(){
        Route::match(['get','patch'],'spot-uv', 'index')->name('spot-uv.index')->middleware('can:browse_spot_uv');
        Route::put('spot-uv/update/operator', 'updateOperator')->name('spot-uv.update.operator')->middleware('can:edit_spot_uv');
        Route::put('spot-uv/update/counter', 'updateCounter')->name('spot-uv.update.counter')->middleware('can:edit_spot_uv');
        Route::put('spot-uv/cancel', 'cancel')->name('spot-uv.cancel')->middleware('can:edit_spot_uv');
    });


    //DyeCutting
    Route::controller(DyeCuttingController::class)->group(function(){
        Route::match(['get','patch'],'dye-cutting', 'index')->name('dye-cutting.index')->middleware('can:browse_dye_cutting');
        Route::put('dye-cutting/update/operator', 'updateOperator')->name('dye-cutting.update.operator')->middleware('can:edit_dye_cutting');
        Route::put('dye-cutting/update/counter', 'updateCounter')->name('dye-cutting.update.counter')->middleware('can:edit_dye_cutting');
        Route::put('dye-cutting/cancel', 'cancel')->name('dye-cutting.cancel')->middleware('can:edit_dye_cutting');
    });


    //Pasting
    Route::controller(PastingController::class)->group(function(){
        Route::match(['get','patch'],'pasting', 'index')->name('pasting.index')->middleware('can:browse_pasting');
        Route::put('pasting/update/operator', 'updateOperator')->name('pasting.update.operator')->middleware('can:edit_pasting');
        Route::put('pasting/cancel', 'cancel')->name('pasting.cancel')->middleware('can:edit_pasting');

        Route::put('pasting/send/warehouse/{id}', 'sendWarehouse')->name('pasting.send.warehouse')->middleware('can:edit_pasting');
        Route::put('pasting/make/complete/{id}', 'completed')->name('pasting.make.complete')->middleware('can:edit_pasting');

        Route::get('pasting/add/details/{id}', 'addDetails')->name('pasting.add.details')->middleware('can:edit_pasting');
        Route::put('pasting/update/details/{id}', 'updateDetails')->name('pasting.update.details')->middleware('can:edit_pasting');
    });

    //Warehouse
    Route::controller(WarehouseController::class)->group(function(){
        Route::match(['get','patch'],'warehouse', 'index')->name('warehouse.index')->middleware('can:browse_warehouse');
        Route::put('warehouse/update/operator', 'updateOperator')->name('warehouse.update.operator')->middleware('can:edit_warehouse');
        Route::put('warehouse/cancel', 'cancel')->name('warehouse.cancel')->middleware('can:edit_warehouse');

        Route::get('warehouse/add/details/{id}', 'addDetails')->name('warehouse.add.details')->middleware('can:edit_warehouse');
        Route::put('warehouse/update/details/{id}', 'updateDetails')->name('warehouse.update.details')->middleware('can:edit_warehouse');
    });


    //ItemForBilling
    Route::controller(ItemForBillingController::class)->group(function(){
        Route::match(['get','patch'],'item-for-billing', 'index')->name('item-for-billing.index')->middleware('can:browse_item_for_billing');
        Route::put('item-for-billing/update/operator', 'updateOperator')->name('item-for-billing.update.operator')->middleware('can:edit_item_for_billing');
        Route::put('item-for-billing/cancel', 'cancel')->name('item-for-billing.cancel')->middleware('can:edit_item_for_billing');

        Route::get('item-for-billing/create', 'create')->name('item-for-billing.create');
        Route::post('item-for-billing/store', 'store')->name('item-for-billing.store');

        Route::post('item-for-billing/add/billing', 'addedForBilling')->name('item-for-billing.add-billing');

        Route::get('item-for-billing/add/details/{id}', 'addDetails')->name('item-for-billing.add.details')->middleware('can:edit_item_for_billing');
        Route::put('item-for-billing/update/details/{id}', 'updateDetails')->name('item-for-billing.update.details')->middleware('can:edit_item_for_billing');


    });


    //Billing
    Route::controller(BillingController::class)->group(function(){
        Route::match(['get','patch'],'billing', 'index')->name('billing.index')->middleware('can:browse_billing');
        Route::get('billing/create', 'create')->name('billing.create')->middleware('can:add_billing');
        Route::get('billing/{id}', 'show')->name('billing.show')->middleware('can:read_billing');
        Route::get('billing/{id}/edit', 'edit')->name('billing.edit')->middleware('can:edit_billing');
        Route::post('billing', 'store')->name('billing.store')->middleware('can:add_billing');
        Route::put('billing/{id}', 'update')->name('billing.update')->middleware('can:edit_billing');
        Route::delete('billing/{id}/delete', 'destroy')->name('billing.destroy')->middleware('can:delete_billing');
    });


    //ReelInward
    Route::controller(ReelInwardController::class)->group(function(){
        Route::match(['get','patch'],'reel-inward', 'index')->name('reel-inward.index')->middleware('can:browse_reel_inward');
        Route::get('reel-inward/create', 'create')->name('reel-inward.create')->middleware('can:add_reel_inward');
        Route::get('reel-inward/{id}', 'show')->name('reel-inward.show')->middleware('can:read_reel_inward');
        Route::get('reel-inward/{id}/edit', 'edit')->name('reel-inward.edit')->middleware('can:edit_reel_inward');
        Route::post('reel-inward', 'store')->name('reel-inward.store')->middleware('can:add_reel_inward');
        Route::put('reel-inward/{id}', 'update')->name('reel-inward.update')->middleware('can:edit_reel_inward');
        Route::delete('reel-inward/{id}/delete', 'destroy')->name('reel-inward.destroy')->middleware('can:delete_reel_inward');
    });


    Route::fallback(function () {
        return response()->view('admin.errors.404', [], 404);
    });


});
