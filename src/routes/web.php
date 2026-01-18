<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AdminController;
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

Route::get('/', [InquiryController::class, 'index'])->name('contact.index');
Route::post('/confirm', [InquiryController::class, 'confirm']);
Route::post('/contacts', [InquiryController::class, 'store']);
Route::get('/thanks', [InquiryController::class, 'thanks']);

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/search', [AdminController::class, 'index'])->name('admin.search');
    Route::get('/reset',  [AdminController::class, 'reset'])->name('admin.reset');
    Route::delete('/admin/contacts/{contact}', [AdminController::class, 'destroy'])
    ->name('admin.contacts.destroy');
});