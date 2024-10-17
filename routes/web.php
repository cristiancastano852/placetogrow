<?php

use App\Http\Controllers\MicrositePaymentController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MicrositesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [MicrositesController::class, 'showAll'])->name('micrositesall');

Route::post('payments', [PaymentController::class, 'store'])
    ->name('payments.store');

Route::get('payments/{payment}', [PaymentController::class, 'show'])
    ->name('payments.show');
Route::middleware('auth')->group(function () {
    Route::get('payments', [PaymentController::class, 'transactions'])
        ->name('payments.transactions');

    //transactions by microsite
    Route::get('payments/microsite/{microsite}', [MicrositePaymentController::class, 'transactionsByMicrosite'])
        ->name('payments.transactionsByMicrosite');

    Route::get('invoices', [InvoiceController::class, 'invoicesByUser'])->name('invoice.invoicesByUser');

});

Route::get('/micrositesall', [MicrositesController::class, 'showAll'])->name('micrositesall');
Route::middleware('auth')->group(function () {
    Route::get('/microsite/pay/{slug}_{id}', [MicrositesController::class, 'showMicrosite'])->name('microsite.showMicrosite');
});
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')
    ->resource('microsites', MicrositesController::class);

Route::middleware('auth')->group(function () {
    Route::get('/role-user', [RolePermissionController::class, 'index'])->name('rolePermission.index');
    Route::put('/role-user/{user}/update', [RolePermissionController::class, 'update'])->name('admin.users.update');
    Route::get('/role-permission', [RolePermissionController::class, 'managePermissions'])->name('rolePermission.permissions');
    Route::put('/roles/{role}/update-permissions', [RolePermissionController::class, 'editPermissions'])->name('admin.rolePermission.edit-permissions');
});

Route::middleware('auth')->group(function () {

    Route::prefix('microsites/{microsite}')->group(function () {

        Route::get('plans', [PlanController::class, 'index'])->name('plans.index');

        Route::get('plans/create', [PlanController::class, 'create'])->name('plans.create');

        Route::get('plans/show', [PlanController::class, 'show'])->name('plans.show');

        Route::post('plans', [PlanController::class, 'store'])->name('plans.store');

        Route::get('plans/{plan}/edit', [PlanController::class, 'edit'])->name('plans.edit');

        Route::put('plans/{plan}', [PlanController::class, 'update'])->name('plans.update');

        Route::delete('plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');
    });
});

Route::middleware('auth')->group(function () {

    Route::prefix('microsites/{microsite}')->group(function () {

        Route::post('subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');

        Route::get('subscriptions/{subscription}/return', [SubscriptionController::class, 'return'])->name('subscriptions.return');

        Route::get('invoices', [InvoiceController::class, 'invoicesByMicrosite'])->name('invoice.invoicesByMicrosite');
        Route::get('invoice', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::post('invoice', [InvoiceController::class, 'invoicesByDocument'])->name('invoice.invoicesByDocument');
        Route::post('invoice/pay', [InvoiceController::class, 'invoicesPayment'])->name('invoice.invoicesPayment');
        Route::get('invoices/import', [ImportController::class, 'create'])->name('import.create');
        Route::post('invoices/import', [ImportController::class, 'store'])->name('import.store');
        Route::get('invoices/import/asd', [ImportController::class, 'show'])->name('imports.show');
    });

    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{subscription}', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
});

Route::middleware('auth')
    ->resource('users', UserController::class);

require __DIR__.'/auth.php';
