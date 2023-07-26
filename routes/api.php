<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EnquiryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\CompatibleController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ModelController;
use App\Http\Controllers\Api\StatusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/register', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('updateToken', [AuthController::class, 'login'])->middleware('auth:api');
Route::post('/logout', [AuthController::class, 'login'])->middleware('auth:api');

//Jobs Apis
Route::post('add-jobs', [JobController::class, 'addJobs'])->middleware('auth:api');

Route::post('update-jobDocument', [JobController::class, 'updateDocument'])->middleware('auth:api');
Route::post('update-idCardImage', [JobController::class, 'update_CardImages'])->middleware('auth:api');

Route::post('edit-job', [JobController::class, 'editJob'])->middleware('auth:api');

Route::post('delete-job', [JobController::class, 'deleteJob'])->middleware('auth:api');
Route::post('export-job', [JobController::class, 'export'])->middleware('auth:api');
Route::post('import-job', [JobController::class, 'importSave'])->middleware('auth:api');
Route::post('delete-idCard', [JobController::class, 'deleteidCard'])->middleware('auth:api');


Route::post('get-jobs', [JobController::class, 'getJobs'])->middleware('auth:api');
Route::post('get-generic-data', [JobController::class, 'getGenericData'])->middleware('auth:api');
Route::post('search-jobs', [JobController::class, 'searchJobs'])->middleware('auth:api');

Route::post('job-detail', [JobController::class, 'jobDetail'])->middleware('auth:api');

Route::post('search-shops', [UserController::class, 'searchShops'])->middleware('auth:api');
Route::post('search-users', [UserController::class, 'searchUsers'])->middleware('auth:api');
Route::post('search-brands', [UserController::class, 'searchBrands'])->middleware('auth:api');
Route::post('get-models', [UserController::class, 'getModels'])->middleware('auth:api');

Route::post('change-status', [JobController::class, 'changeStatus'])->middleware('auth:api');

//User Apis

Route::post('add-users', [UserController::class, 'addUser'])->middleware('auth:api');
Route::post('add-full-user', [UserController::class, 'addFullUser'])->middleware('auth:api');
Route::post('user-detail', [UserController::class, 'userDetail'])->middleware('auth:api');
Route::post('get-users', [UserController::class, 'getUsers'])->middleware('auth:api');
Route::post('edit-user', [UserController::class, 'editUsers'])->middleware('auth:api');
Route::post('delete-user', [UserController::class, 'deleteUser'])->middleware('auth:api');
Route::post('import-user', [UserController::class, 'importSave'])->middleware('auth:api');
Route::post('export-user', [UserController::class, 'export'])->middleware('auth:api');
Route::post('delete-device', [UserController::class, 'deleteDevice'])->middleware('auth:api');
Route::post('user-generic-data', [UserController::class, 'getGenericData'])->middleware('auth:api');
Route::post('get-country-provinces', [UserController::class, 'getCountryProvinces'])->middleware('auth:api');


Route::post('create-enquiry', [EnquiryController::class, 'addEnquiry'])->middleware('auth:api');

Route::post('get-enquires', [EnquiryController::class, 'getEnquiry'])->middleware('auth:api');
Route::post('add-enquiry', [EnquiryController::class, 'addEnquiry'])->middleware('auth:api');
Route::post('update-enquiry', [EnquiryController::class, 'updateEnquiry'])->middleware('auth:api');
Route::post('update-enquiry-status', [EnquiryController::class, 'updateStatus'])->middleware('auth:api');
Route::post('delete-enquiry', [EnquiryController::class, 'deleteEnquiry'])->middleware('auth:api');
Route::post('import-enquiry', [EnquiryController::class, 'importSave'])->middleware('auth:api');
Route::post('export-enquiry', [EnquiryController::class, 'export'])->middleware('auth:api');
Route::post('search-enquires', [EnquiryController::class, 'searchEnquiry'])->middleware('auth:api');
Route::post('get-enquiry-detail', [EnquiryController::class, 'getEnquiryDetailInfo'])->middleware('auth:api');

Route::post('get-products', [ProductController::class, 'getProducts'])->middleware('auth:api');
Route::post('get-all-products', [ProductController::class, 'getAllProducts'])->middleware('auth:api');
Route::post('search-products', [ProductController::class, 'searchProducts'])->middleware('auth:api');
Route::post('add-product', [ProductController::class, 'add'])->middleware('auth:api');
Route::post('update-product', [ProductController::class, 'update'])->middleware('auth:api');
Route::post('delete-product', [ProductController::class, 'delete'])->middleware('auth:api');
Route::post('import-product', [ProductController::class, 'importSave'])->middleware('auth:api');
Route::post('export-product', [ProductController::class, 'export'])->middleware('auth:api');
Route::post('delete-product-image', [ProductController::class, 'deleteImage'])->middleware('auth:api');
Route::post('default-product-image', [ProductController::class, 'makeDefault'])->middleware('auth:api');


Route::post('get-invoices', [InvoiceController::class, 'getInvoices'])->middleware('auth:api');
Route::post('search-invoices', [InvoiceController::class, 'searchInvoices'])->middleware('auth:api');
Route::post('add-invoice', [InvoiceController::class, 'add'])->middleware('auth:api');
Route::post('get-invoice', [InvoiceController::class, 'getSingle'])->middleware('auth:api');
Route::post('delete-invoice', [InvoiceController::class, 'delete'])->middleware('auth:api');
Route::post('update-invoice', [InvoiceController::class, 'update'])->middleware('auth:api');

Route::post('get-compatibles', [CompatibleController::class, 'getCompatibles'])->middleware('auth:api');
Route::post('add-compatible', [CompatibleController::class, 'add'])->middleware('auth:api');
Route::post('update-compatible', [CompatibleController::class, 'update'])->middleware('auth:api');
Route::post('delete-compatible', [CompatibleController::class, 'delete'])->middleware('auth:api');
Route::post('search-compatibles', [CompatibleController::class, 'searchCompatibles'])->middleware('auth:api');
Route::post('export-compatible', [CompatibleController::class, 'export'])->middleware('auth:api');

Route::post('get-brands', [BrandController::class, 'getBrand'])->middleware('auth:api');
Route::post('add-brand', [BrandController::class, 'addBrand'])->middleware('auth:api');
Route::post('update-brand', [BrandController::class, 'updateBrand'])->middleware('auth:api');
Route::post('delete-brand', [BrandController::class, 'deleteBrand'])->middleware('auth:api');
Route::post('import-brand', [BrandController::class, 'importSave'])->middleware('auth:api');
Route::post('export-brand', [BrandController::class, 'export'])->middleware('auth:api');
Route::post('search-brands', [BrandController::class, 'searchBrand'])->middleware('auth:api');

Route::post('get-models', [ModelController::class, 'getDevice'])->middleware('auth:api');
Route::post('add-model', [ModelController::class, 'addDevice'])->middleware('auth:api');
Route::post('update-model', [ModelController::class, 'updateDevice'])->middleware('auth:api');
Route::post('delete-model', [ModelController::class, 'deleteDevice'])->middleware('auth:api');
Route::post('import-model', [ModelController::class, 'importSave'])->middleware('auth:api');
Route::post('export-model', [ModelController::class, 'export'])->middleware('auth:api');
Route::post('search-models', [ModelController::class, 'searchDevice'])->middleware('auth:api');

Route::post('get-statuses', [StatusController::class, 'getStatus'])->middleware('auth:api');
Route::post('add-status', [StatusController::class, 'addStatus'])->middleware('auth:api');
Route::post('update-status', [StatusController::class, 'updateStatus'])->middleware('auth:api');
Route::post('delete-status', [StatusController::class, 'deleteStatus'])->middleware('auth:api');
Route::post('import-status', [StatusController::class, 'importSave'])->middleware('auth:api');
Route::post('export-status', [StatusController::class, 'export'])->middleware('auth:api');
Route::post('search-statuses', [StatusController::class, 'searchStatus'])->middleware('auth:api');
