<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;


Route::group(['prefix' => 'person'], function () {
    
    Route::get('/', [PersonController::class, 'index'])->name('person');
    Route::get('/view/{id}', [PersonController::class, 'view'])->name('person.view');
    Route::post('/update', [PersonController::class, 'update'])->name('person.update');
    Route::post('/delete', [PersonController::class, 'delete'])->name('person.delete');
    Route::post('/delete-organization', [PersonController::class, 'delete_organization'])->name('person.delete-organization');
    Route::post('/person-add-organization', [PersonController::class, 'add_organization'])->name('person.add-organization');
    Route::get('/create-person', [PersonController::class, 'create'])->name('person.create');
    Route::post('/create-person', [PersonController::class, 'store'])->name('person.store');

});
