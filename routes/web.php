<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'subjects', 'as' => 'subjects.', 'middleware' => ['auth']], function () {
    Route::get('/', [App\Http\Controllers\UserSubjectController::class, 'index'])->name('index');
    Route::post('/one', [App\Http\Controllers\UserSubjectController::class, 'storeFormStepOne'])->name('store_one');
    Route::post('/two', [App\Http\Controllers\UserSubjectController::class, 'storeFormStepTwo'])
        ->name('store_two')
        ->middleware(['step:1']);
    Route::post('/three', [App\Http\Controllers\UserSubjectController::class, 'storeFormStepThree'])
        ->name('store_three')
        ->middleware(['step:2']);
    Route::post('/finalization', [App\Http\Controllers\UserSubjectController::class, 'finalization'])
        ->name('finalization')
        ->middleware(['step:3']);

    Route::get('/create/{step?}', [App\Http\Controllers\UserSubjectController::class, 'create'])->name('create');
    Route::get('/{subjectUser}/edit', [App\Http\Controllers\UserSubjectController::class, 'edit'])->name('edit');
    Route::delete('/{subjectUser}', [App\Http\Controllers\UserSubjectController::class, 'destroy'])->name('destroy');
//    Route::delete('/{user_subject}', [App\Http\Controllers\UserSubjectController::class, 'destroy'])->name('destroy');
//    Route::delete('/{user_subject}', [App\Http\Controllers\UserSubjectController::class, 'destroy'])->name('destroy');
});
