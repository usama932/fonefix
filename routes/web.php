<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/main-logout', 'FrontendController@logout')->name('main-logout');
Route::get('/', 'FrontendController@search')->name('home');
Route::get('/contact', 'FrontendController@contact')->name('contact');
Route::post('contact-form', 'FrontendController@contactUsForm')->name('contact-form');
//Route::get('/send-message', 'FrontendController@sendMessage')->name('send-message');
//Route::get('/test-smtp', 'FrontendController@testSmtp')->name('send-smtp');
//Route::get('/test-mailchimp', 'FrontendController@testMailchimp')->name('send-mailchimp');
//Route::get('/test-vonage', 'FrontendController@testVonage')->name('send-vonage');
//Route::get('/assign-customer', 'FrontendController@assignCustomer')->name('assign-customer');
Route::get('/assign-slug', 'FrontendController@assignSlug')->name('assign-slug');
Route::get('/countries', 'FrontendController@updateCountries')->name('countries');
Route::get('/{id}/{shop_id}/pdf', 'FrontendController@pdf')->name('job-pdf-public');
Route::get('/shop/{slug}', 'FrontendController@shop')->name('shop');
Route::get('/contact/{slug}', 'FrontendController@shopContact')->name('shop-contact');
Route::get('/search', 'FrontendController@search')->name('search-job');
Route::get('/clear',function(){
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
});

Auth::routes();

Route::group([
    'middleware'    => ['auth'],
    'prefix'        => 'client',
    'namespace'     => 'Client'
], function ()
{
    Route::get('/dashboard', 'ClientController@index')->name('client.dashboard');
	Route::get('/profile', 'ClientController@edit')->name('client-profile');
	Route::post('/admin-update', 'ClientController@update')->name('client-update');


});

Route::group([
    'middleware'    => ['auth','is_admin'],
    'prefix'        => 'admin',
    'namespace'     => 'Admin'
], function ()
{
    Route::get('/dashboard', 'AdminController@index')->name('admin.dashboard');
    Route::get('/profile', 'AdminController@edit')->name('admin-profile');
    Route::post('/admin-update', 'AdminController@update')->name('admin-update');
    // Setting Routes 1
    Route::resource('setting','SettingController');

    //basic Setting Routes 1
    Route::resource('basic-setting','BasicSettingController');

    //SMS Setting Routes 2
    Route::resource('sms-setting','SmsSettingController');
    Route::post('get-settings', 'SmsSettingController@getsms')->name('admin.getSettings');
    Route::post('get-getSetting', 'SmsSettingController@smsDetail')->name('admin.getSetting');
    Route::get('sms/delete/{id}', 'SmsSettingController@destroy');
    Route::post('delete-selected-sms', 'SmsSettingController@deleteSelectedsms')->name('admin.delete-selected-sms');

    //SMS Setting Routes 2
    Route::resource('whatsapp','WhatsappTemplateController');
    Route::post('get-wsettings', 'WhatsappTemplateController@getTemplates')->name('admin.getWhatsapps');
    Route::post('get-getWSetting', 'WhatsappTemplateController@templatesDetail')->name('admin.getWhatsapp');
    Route::get('whatsapp/delete/{id}', 'WhatsappTemplateController@destroy');
    Route::post('delete-selected-whatsapp', 'WhatsappTemplateController@deleteSelectedsms')->name('admin.delete-selected-whatsapp');
    Route::post('get-wused', 'WhatsappTemplateController@getused')->name('admin.wgetUsed');
    Route::post('assign_wused', 'WhatsappTemplateController@assign_used')->name('admin.wassign_used');

    // Mailing Template
    Route::resource('mailings','EmailTemplateController');
    Route::post('get-mailings', 'EmailTemplateController@getMailings')->name('admin.getMailings');
    Route::post('get-mailing', 'EmailTemplateController@MailingDetail')->name('admin.getMailing');
    Route::get('mailing/delete/{id}', 'EmailTemplateController@destroy');
    Route::post('delete-selected-mailings', 'EmailTemplateController@deleteSelectedsms')->name('admin.delete-selected-mailings');

    //Jobs Setting Routes 3
    Route::resource('jobs-setting','JobSettingController');

    //Email Setting Routes 4
    Route::resource('email-setting','MailSettingController');

    //Other Setting Routes 5
    Route::resource('other-setting','OtherSettingController');

    //CMS Setting Routes 6
    Route::resource('cms-setting','CmsSettingController');

    //whatsapp Setting Routes 6
    Route::resource('whatsapp-setting','WhatsappSettingController');

	//User Routes
	Route::resource('clients','ClientController');
	Route::post('get-clients', 'ClientController@getClients')->name('admin.getClients');
	Route::post('get-client', 'ClientController@clientDetail')->name('admin.getClient');
    Route::post('get-amount', 'ClientController@clientamount')->name('admin.getamount');
    Route::post('pay_amount', 'ClientController@payamount')->name('admin.payamount');
	Route::get('client/delete/{id}', 'ClientController@destroy');
	Route::get('brand-delete/{id}', 'ClientController@deleteBrand')->name("delete-user-brand");
	Route::get('clients/jobs/{id}', 'ClientController@clientJobs')->name("client.jobs");
	Route::post('delete-selected-clients', 'ClientController@deleteSelectedClients')->name('admin.delete-selected-clients');
	Route::post('popup-add', 'ClientController@popupAdd')->name('admin.popup-add');
	Route::post('phone-check', 'ClientController@phoneCheck')->name('admin.phone-check');
    Route::get('client-import', 'ClientController@import')->name("client-import");
    Route::get('client-export', 'ClientController@export')->name("client-export");
    Route::post('client-import-save', 'ClientController@importSave')->name("client-import-save");
    Route::get('client-download-sample', 'ClientController@download')->name('client-download-sample');

    //Staff Routes
    Route::resource('staffs','StaffController');
    Route::post('get-staffs', 'StaffController@getClients')->name('admin.getStaffs');
    Route::post('get-staff', 'StaffController@clientDetail')->name('admin.getStaff');
    Route::get('staff/delete/{id}', 'StaffController@destroy');
    Route::post('delete-selected-staffs', 'StaffController@deleteSelectedClients')->name('admin.delete-selected-staffs');

    //Role Routes
    Route::resource('roles','RoleController');
    Route::post('get-roles', 'RoleController@getClients')->name('admin.getRoles');
    Route::post('get-role', 'RoleController@clientDetail')->name('admin.getRole');
    Route::get('role/delete/{id}', 'RoleController@destroy');
    Route::post('delete-selected-roles', 'RoleController@deleteSelectedClients')->name('admin.delete-selected-roles');



    //Report Routes
    Route::resource('reports','ReportController');
    Route::get('reports-search', 'ReportController@search')->name('reports.search');
    Route::get('reports-job', 'ReportController@searchJobs')->name('reports.jobs');

    //Shops Routes
    Route::resource('shops','ShopController');
    Route::post('get-shops', 'ShopController@getClients')->name('admin.getShops');
    Route::post('get-shop', 'ShopController@clientDetail')->name('admin.getShop');
    Route::get('shop/delete/{id}', 'ShopController@destroy');
    Route::post('delete-selected-shops', 'ShopController@deleteSelectedClients')->name('admin.delete-selected-shops');
    Route::post('country-provinces', 'ShopController@countryProvinces')->name('country-provinces');
    Route::get('shop-import', 'ShopController@import')->name("shop-import");
    Route::get('shop-export', 'ShopController@export')->name("shop-export");
    Route::post('shop-import-save', 'ShopController@importSave')->name("shop-import-save");
    Route::get('shop-download-sample', 'ShopController@download')->name('shop-download-sample');

    //IdCards  Routes
    Route::resource('id-cards','IdCardController');
    Route::post('get-id-cards', 'IdCardController@getClients')->name('admin.getIdCards');
    Route::post('get-id-card', 'IdCardController@clientDetail')->name('admin.getIdCard');
    Route::get('id-card/delete/{id}', 'IdCardController@destroy');
    Route::post('delete-selected-id-cards', 'IdCardController@deleteSelectedClients')->name('admin.delete-selected-id-cards');

    //Couriers Routes
    Route::resource('couriers','CourierController');
    Route::post('get-couriers', 'CourierController@getClients')->name('admin.getCouriers');
    Route::post('get-courier', 'CourierController@clientDetail')->name('admin.getCourier');
    Route::get('courier/delete/{id}', 'CourierController@destroy');
    Route::post('delete-selected-couriers', 'CourierController@deleteSelectedClients')->name('admin.delete-selected-couriers');

    //Country Routes
    Route::resource('countries','CountryController');
    Route::post('get-countries', 'CountryController@getClients')->name('admin.getCountries');
    Route::post('get-country', 'CountryController@clientDetail')->name('admin.getCountry');
    Route::get('country/delete/{id}', 'CountryController@destroy');
    Route::post('delete-selected-countries', 'CountryController@deleteSelectedClients')->name('admin.delete-selected-countries');

    //Province Routes
    Route::resource('provinces','ProvinceController');
    Route::post('get-provinces', 'ProvinceController@getClients')->name('admin.getProvinces');
    Route::post('get-province', 'ProvinceController@clientDetail')->name('admin.getProvince');
    Route::get('province/delete/{id}', 'ProvinceController@destroy');
    Route::post('delete-selected-provinces', 'ProvinceController@deleteSelectedClients')->name('admin.delete-selected-provinces');
    Route::post('get-pro', 'ProvinceController@getPro')->name('admin.getPro');

    //Category Routes
    Route::resource('categories','CategoriesController');
    Route::post('get-categories', 'CategoriesController@getClients')->name('admin.getCategories');
    Route::post('get-category', 'CategoriesController@clientDetail')->name('admin.getCategory');
    Route::get('categories/delete/{id}', 'CategoriesController@destroy');
    Route::post('delete-selected-categories', 'CategoriesController@deleteSelectedClients')->name('admin.delete-selected-categories');

    //Brands Routes
    Route::resource('brands','BrandController');
    Route::post('get-brands', 'BrandController@getClients')->name('admin.getBrands');
    Route::post('get-brand', 'BrandController@clientDetail')->name('admin.getBrand');
    Route::get('brand/delete/{id}', 'BrandController@destroy');
    Route::post('delete-selected-brands', 'BrandController@deleteSelectedClients')->name('admin.delete-selected-brands');
    Route::get('brand-import', 'BrandController@import')->name("brand-import");
    Route::get('brand-export', 'BrandController@export')->name("brand-export");
    Route::post('brand-import-save', 'BrandController@importSave')->name("brand-import-save");
    Route::get('brand-download-sample', 'BrandController@download')->name('brand-download-sample');

    //Statuses Routes
    Route::resource('statuses','StatusController');
    Route::post('get-statuses', 'StatusController@getClients')->name('admin.getStatuses');
    Route::post('get-status', 'StatusController@clientDetail')->name('admin.getStatus');
    Route::post('get-usedstatus', 'StatusController@getused')->name('admin.getUsedStatus');
    Route::get('status/delete/{id}', 'StatusController@destroy');
    Route::post('delete-selected-statuses', 'StatusController@deleteSelectedClients')->name('admin.delete-selected-statuses');
    Route::get('status-import', 'StatusController@import')->name("status-import");
    Route::get('status-export', 'StatusController@export')->name("status-export");
    Route::post('status-import-save', 'StatusController@importSave')->name("status-import-save");
    Route::get('status-download-sample', 'StatusController@download')->name('status-download-sample');
    Route::post('status_assign_used', 'StatusController@assign_used')->name('admin.status_used');
    //Devices  Routes
    Route::resource('devices','DeviceController');
    Route::post('get-devices', 'DeviceController@getClients')->name('admin.getDevices');
    Route::post('get-device-models', 'DeviceController@getDevices')->name('admin.getDeviceModels');
    Route::post('product-device-models', 'DeviceController@productDevices')->name('product.getDeviceModels');
    Route::post('get-pre-repair', 'DeviceController@getPreRepair')->name('admin.getPreRepair');
    Route::post('get-device', 'DeviceController@clientDetail')->name('admin.getDevice');
    Route::get('device/delete/{id}', 'DeviceController@destroy');
    Route::post('delete-selected-devices', 'DeviceController@deleteSelectedClients')->name('admin.delete-selected-devices');
    Route::get('pre-repair-delete/{id}', 'DeviceController@preRepairDelete')->name("pre-repair-delete");
    Route::get('device-import', 'DeviceController@import')->name("device-import");
    Route::get('device-export', 'DeviceController@export')->name("device-export");
    Route::post('device-import-save', 'DeviceController@importSave')->name("device-import-save");
    Route::get('device-download-sample', 'DeviceController@download')->name('device-download-sample');
    Route::post('api/fetch-brands', 'DeviceController@fetchBrands');
    //Products Routes
    Route::resource('products','ProductController');
    Route::post('get-products', 'ProductController@getClients')->name('admin.getProducts');
    Route::post('get-product', 'ProductController@clientDetail')->name('admin.getProduct');
    Route::get('product/delete/{id}', 'ProductController@destroy');
    Route::get('image/delete/{id}', 'ProductController@deleteImage')->name('product-image-delete');
    Route::get('image/default/{id}', 'ProductController@defaultImage')->name('product-image-default');
    Route::post('delete-selected-products', 'ProductController@deleteSelectedClients')->name('admin.delete-selected-products');
    Route::get('product-import', 'ProductController@import')->name("product-import");
    Route::get('product-export', 'ProductController@export')->name("product-export");
    Route::post('product-import-save', 'ProductController@importSave')->name("product-import-save");
    Route::get('product-download-sample', 'ProductController@download')->name('product-download-sample');

    //Jobs Routes
    Route::resource('jobs','JobController');
    Route::post('get-jobs', 'JobController@getClients')->name('admin.getJobs');
    Route::post('get-job', 'JobController@clientDetail')->name('admin.getJob');
    Route::post('check-qty', 'JobController@checkQty')->name('admin.checkQty');
    Route::get('job/delete/{id}', 'JobController@destroy');
    Route::get('jobs/parts/{id}', 'JobController@parts')->name("job-parts");
    Route::get('jobs/pdf/{id}', 'JobController@pdf')->name("job-pdf");
    Route::get('jobs/invoice/{id}', 'JobController@invoice')->name("job-invoice");
    Route::get('jobs/part-delete/{id}', 'JobController@partDelete')->name("job-parts-delete");
    Route::post('delete-selected-jobs', 'JobController@deleteSelectedClients')->name('admin.delete-selected-jobs');
    Route::post('add-job-parts', 'JobController@addParts')->name('add-job-parts');
    Route::post('job-invoice-store', 'JobController@invoiceStore')->name('job-invoice-store');
    Route::get('remove-id-card/{id}', 'JobController@removeIdCard')->name('remove-id-card');
    Route::get('invoice-pdf/{id}', 'JobController@invoicePdf')->name('invoice-pdf');
    Route::post('update-job-status', 'JobController@updateStatus')->name('update-job-status');
    Route::get('job-import', 'JobController@import')->name("job-import");
    Route::get('job-export', 'JobController@export')->name("job-export");
    Route::post('job-import-save', 'JobController@importSave')->name("job-import-save");
    Route::get('job-download-sample', 'JobController@download')->name('job-download-sample');

    Route::post('get-user-cards', 'ClientController@getUserCards')->name('get-user-cards');

    //Invoice Routes
    Route::resource('invoices','InvoiceController');
    Route::post('get-invoices', 'InvoiceController@getClients')->name('admin.getInvoices');
    Route::post('get-invoice', 'InvoiceController@clientDetail')->name('admin.getInvoice');
    Route::get('invoice/delete/{id}', 'InvoiceController@destroy');
    Route::post('delete-selected-invoices', 'InvoiceController@deleteSelectedClients')->name('admin.delete-selected-invoices');

    //Types Routes
    Route::resource('types','TypeController');
    Route::post('get-types', 'TypeController@getClients')->name('admin.getTypes');
    Route::post('get-type', 'TypeController@clientDetail')->name('admin.getType');
    Route::get('type/delete/{id}', 'TypeController@destroy');
    Route::post('delete-selected-types', 'TypeController@deleteSelectedClients')->name('admin.delete-selected-types');

    //Compatible Routes
    Route::resource('compatibles','CompatibleController');
    Route::post('get-compatibles', 'CompatibleController@getClients')->name('admin.getCompatibles');
    Route::post('get-compatible', 'CompatibleController@clientDetail')->name('admin.getCompatible');
    Route::get('compatible/delete/{id}', 'CompatibleController@destroy');
    Route::post('delete-selected-compatibles', 'CompatibleController@deleteSelectedClients')->name('admin.delete-selected-compatibles');
    Route::get('compatible-export/{id}', 'CompatibleController@export')->name("compatible-export");

    //Enquiries Routes
    Route::resource('enquiries','EnquiryController');
    Route::post('get-enquiries', 'EnquiryController@getClients')->name('admin.getEnquiries');
    Route::post('get-enquiry', 'EnquiryController@clientDetail')->name('admin.getEnquiry');
    Route::get('enquiry/delete/{id}', 'EnquiryController@destroy');
    Route::post('delete-selected-enquiries', 'EnquiryController@deleteSelectedClients')->name('admin.delete-selected-enquiries');
    Route::get('enquiry-brand-delete/{id}', 'EnquiryController@deleteBrand')->name("delete-enquiry-brand");
    Route::post('update-enquiry-status', 'EnquiryController@updateStatus')->name('update-enquiry-status');
    Route::get('enquiry-import', 'EnquiryController@import')->name("enquiry-import");
    Route::get('enquiry-export', 'EnquiryController@export')->name("enquiry-export");
    Route::post('enquiry-import-save', 'EnquiryController@importSave')->name("enquiry-import-save");
    Route::get('enquiry-download-sample', 'EnquiryController@download')->name('enquiry-download-sample');

      //Informations
      Route::resource('informations','InformationController');
      Route::post('get-informations', 'InformationController@getInformations')->name('admin.getInformations');
      Route::post('get-information', 'InformationController@informationDetail')->name('admin.getInformation');
      Route::get('information/delete/{id}', 'InformationController@destroy');
      Route::post('delete-selected-informations', 'InformationController@deleteSelectedInformations')->name('admin.delete-selected-informations');

       //SMS Template
    Route::resource('templates','SmsTemaplateController');
    Route::post('get-templates', 'SmsTemaplateController@getTemplates')->name('admin.getTemplates');
    Route::post('get-template', 'SmsTemaplateController@templatesDetail')->name('admin.getTemplate');
    Route::get('template/delete/{id}', 'SmsTemaplateController@destroy');
    Route::post('delete-selected-templates', 'SmsTemaplateController@deleteSelectedTemplates')->name('admin.delete-selected-templates');
    Route::post('get-used', 'SmsTemaplateController@getused')->name('admin.getUsed');
    Route::post('assign_used', 'SmsTemaplateController@assign_used')->name('admin.assign_used');

    Route::resource('slides','SlideController');
    Route::get('slide/delete/{id}','SlideController@delete_Slide')->name('delete.slide');
    });

