<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\Api\StudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AdminController::class, 'login']);
Route::middleware('admin.auth')->get('/protected-route', [AdminController::class, 'protectedRoute']);



//crud api 

Route::get('students',[StudentController::class,'index']);
Route::post('students',[StudentController::class,'store']);
Route::get('students/{id}',[StudentController::class,'show']);
Route::put('students/{id}',[StudentController::class,'update']);

Route::delete('students/{id}',[StudentController::class,'destroy']);


//file uploading crud..

Route::post('upload', [FileUploadController::class, 'FileUpload']);
Route::get('files', [FileUploadController::class, 'index']);
Route::get('files/{id}', [FileUploadController::class, 'show']);
Route::put('files/{id}', [FileUploadController::class, 'update']);
Route::delete('files/{id}', [FileUploadController::class, 'destroy']);
