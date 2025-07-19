<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\PaymentReceiptController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;
use App\Models\Unit;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('auth/Register');
})->name('home');

Route::get('/dashboard', [DashBoardController::class, 'analytics'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::resource('payment-receipts', PaymentReceiptController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::get('payment-receipts-download-pdf/{id}', [PaymentReceiptController::class, 'downloadPaymentRecipt'])->name('payment-receipts-download-pdf');
Route::get('/receipt/download/{project}/{transaction}', [PaymentReceiptController::class, 'newDownloadPaymentReceipts'])->name('new-payment-receipts-download-pdf');
Route::resource('organisation', OrganisationController::class)->only(['show', 'edit', 'update']);

Route::get('projects/{project}/units/{unit}/booking', [UnitController::class, 'booking'])->name('units.booking');
Route::post('projects/{project}/units/x{unit}/booking', [UnitController::class, 'saveBooking'])->name('units.booking.store');
// GET route to render the edit form
Route::get('/projects/{project}/units/{unit}/edit-booking', [UnitController::class, 'editBooking'])->name('units.booking.edit');
Route::post('/projects/{project}/units/{unit}/edit-booking', [UnitController::class, 'updateBooking'])->name('units.booking.update');

// Route::get('/projects/{project}/units/{unit}/booking', [UnitController::class, 'showBooking'])->name('units.booking.show');

Route::resource('projects', ProjectController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
Route::resource('units', UnitController::class)->only(['show']);
Route::get('units/create/{project}', [UnitController::class, 'create'])->name('units.create');
Route::delete('units/{unit}', [UnitController::class, 'delete'])->name('units.delete');

Route::post('units/store/{project}', [UnitController::class, 'store'])->name('units.store');
Route::get('units/{project}/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
Route::get('units/{organisation}/{project}', [UnitController::class, 'index'])->name('units.index');
// Route::get('/organisations/{organisation}/projects/{project}/units', [UnitController::class, 'index'])->name('units.index');

Route::post('units/{project}/{unit}/update', [UnitController::class, 'update'])->name('units.update');

// customer 
Route::get('customers/create/{project}', [CustomerController::class, 'create'])->name('customers.create');
Route::post('customers/{project}', [CustomerController::class, 'store'])->name('customers.store');
Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
Route::get('customers/{project}', [CustomerController::class, 'index'])->name('customers.index');
Route::get('org-customers/{organisation}', [CustomerController::class, 'getOrgCustomers'])->name('org-customers');
Route::resource('customers', CustomerController::class)->only(['show', 'edit', 'update']);

Route::get('unit-transactions/{unit}', [TransactionController::class, 'create'])->name('unit-transactions.create');
// Route::delete('unit-transactions/{unit}', [TransactionController::class, 'unBook'])->name('unit-transactions.unBook');
Route::get('unit-unbook/{unit}', [TransactionController::class, 'unBook'])->name('unit-unbook');
Route::get('index-transactions/{organisation}/{project?}', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('delete-transaction/{transaction}', [TransactionController::class, 'deleteTransaction'])->name('delete-transaction');
Route::get('transactions/{project}', [TransactionController::class, 'create'])->name('transactions.create');
Route::get('transactions/{transaction}/{unit}/{project}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
Route::post('transactions/{unit}', [TransactionController::class, 'store'])->name('transactions.store');
Route::post('transactions/{transaction}/update', [TransactionController::class, 'update'])->name('transactions.update');





require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
