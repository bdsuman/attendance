<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ShiftController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('employees',EmployeeController::class);
Route::apiResource('shifts',ShiftController::class);
Route::apiResource('attendances',AttendanceController::class)->only(['index','show','store']);
