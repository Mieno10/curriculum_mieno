<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyInfoController;
use App\Http\Controllers\Api\CompanyBillInfoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/companyInfo/create', [CompanyInfoController::class, 'store'])->name('api.companyInfo.store');
Route::get('/companyInfo/{id}', [CompanyInfoController::class, 'show'])->name('api.companyInfo.show');
Route::put('/companyInfo/{id}', [CompanyInfoController::class, 'update'])->name('api.companyInfo.update');
Route::delete('/companyInfo/{id}', [CompanyInfoController::class, 'destroy'])->name('api.companyInfo.destroy');

Route::get('/companyInfoAndBill/{id}',[CompanyInfoController::class, 'showRelated'])->name('api.companyInfo.showRelated');

Route::post('/companyBillInfo/create', [CompanyBillInfoController::class, 'store'])->name('api.companyBillInfo.store');
Route::get('/companyBillInfo/{id}', [CompanyBillInfoController::class, 'show'])->name('api.companyBillInfo.show');
Route::put('/companyBillInfo/{id}', [CompanyBillInfoController::class, 'update'])->name('api.companyBillInfo.update');
Route::delete('/companyBillInfo/{id}', [CompanyBillInfoController::class, 'destroy'])->name('api.companyBillInfo.destroy');
