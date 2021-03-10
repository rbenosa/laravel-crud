<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
use App\Models\Organization;

Route::group(['prefix' => 'organization'], function () {

    Route::get('/', [OrganizationController::class, 'index'])->name('organization');
    Route::get('/create-organization', [OrganizationController::class, 'create'])->name('organization.create');
    Route::post('/create-organization', [OrganizationController::class, 'store'])->name('organization.store');
    Route::get('/view/{id}', [OrganizationController::class, 'view'])->name('organization.view');
    Route::post('/update', [OrganizationController::class, 'update'])->name('organization.update');
    Route::post('/delete', [OrganizationController::class, 'delete'])->name('organization.delete');

    Route::post('/add-member', [OrganizationController::class, 'add_member'])->name('organization.add-member');
    Route::post('/delete-member', [OrganizationController::class, 'delete_member'])->name('organization.delete-member');
    
});