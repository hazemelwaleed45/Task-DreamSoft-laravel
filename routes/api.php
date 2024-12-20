<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;

// tested shows 403 Forbidden  and return "error": "Access denied."  if the ip not allowed
Route::middleware(['ip.whitelist'])->group(function () {
    Route::get('/employees/highest-salary', [EmployeeController::class, 'highestSalary']); 
    Route::get('/employees', [EmployeeController::class, 'index']);  
    
    Route::get('/employees/{id}', [EmployeeController::class, 'show']); 
    Route::post('/employees', [EmployeeController::class, 'store']); 
    Route::put('/employees/{id}', [EmployeeController::class, 'update']); 
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']); 
    


    Route::get('/departments/employees/count', [DepartmentController::class, 'countEmployees']); 
    Route::get('/departments/average-salary', [DepartmentController::class, 'averageSalary']); 
    Route::get('/departments', [DepartmentController::class, 'index']); 


    Route::get('/department-employees/{id}', [EmployeeController::class, 'employeesByDepartment']); 
});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
