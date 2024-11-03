<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class EmployeeController extends Controller
{
   
    public function index()
    {
        return Cache::remember('employees', 600, function () {
            return Employee::all();
        });
    }

    public function employeesByDepartment($departmentId)
    {
        $employees = Employee::where('department_id', $departmentId)->get();
        return EmployeeResource::collection($employees);
    }

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone_number' => 'required|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //dd($request);
        $employee = Employee::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'hire_date'=>$request->hire_date,
            'salary'=>$request->salary,
            'department_id'=>$request->department_id,
        ]);
        //dd($request);
      
        Cache::forget('employees');
        Cache::forget('employee_count_by_department');
        return new EmployeeResource($employee);
    }

    
    public function show($id)
    {
        $employee = Employee::with('department')->findOrFail($id);
        return new EmployeeResource($employee);
    }

    
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,'.$id,
            'phone_number' => 'required|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $employee->update($request->all());
      
        Cache::forget('employees');
        Cache::forget('employee_count_by_department');
        return new EmployeeResource($employee);
    }

    public function highestSalary()
    {
        
        $highestSalaryEmployee = DB::table('employees')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->select('employees.first_name', 'employees.last_name', 'departments.name as department', 'employees.hire_date', 'employees.salary')
            ->orderBy('employees.salary', 'desc')
            ->first();

        return response()->json($highestSalaryEmployee);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        Cache::forget('employees');
        Cache::forget('employee_count_by_department');
        return response()->json(['message' => 'Employee deleted successfully.']);
    }
}
