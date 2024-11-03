<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    // public function index()
    // {
    //     $departments = Department::all();

        // return response()->json([
        //     'data' => DepartmentResource::collection($departments),
        //     'message' => 'Departments retrieved successfully',
        // ], 200);
    // }

    public function index()
    {
        return Cache::remember('departments', 600, function () {
            return Department::all();
        });
    }

  

    public function countEmployees()
    {
        return Cache::remember('employees_count', 300, function () {
            $employeeCount = DB::table('employees')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->select('departments.name as department_name', DB::raw('COUNT(employees.id) as total'))
                ->groupBy('departments.name')
                ->orderBy('departments.name')
                ->get();
    
            return response()->json($employeeCount, 200);
        });
    }

    public function averageSalary()
    {
        $averageSalary = DB::table('employees')
            ->select('department_id', DB::raw('AVG(salary) as average_salary'))
            ->groupBy('department_id')
            ->get();

        return response()->json($averageSalary, 200);
    }

    function show($id)
     {
           
        $department = Department::findOrFail($id);
        return response()->json(new DepartmentResource($department), 200);
     }

     // public function employeesByDepartment($id)
    // {
    //     $employees = Employee::where('department_id', $id)->get();
    //     return response()->json($employees, 200);
    // }

    //  public function store(Request $request)
    //  {
    //     // dd($request);
    //     $validator = Validator::make($request->all(), [
    //     'name' => 'required|min:5',
    //     ], [
    //         'name.required' => "Enter The Name",
    //         'name.min' => "Name must be at least 5 characters",
    //     ]);

 
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors(),
    //         ], 400);
    //     }

    
    //     $department = Department::create([
    //         'name' => $request->name,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'data' => new DepartmentResource($department),
    //         'message' => 'Department created successfully',
    //     ], 201);
    //  }

    //  public function update(Request $request, $id)
    //  {
    //     //dd($request);
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|min:5',
    //     ], [
    //         'name.required' => "Enter The Name",
    //         'name.min' => "Name must be at least 5 characters",
    //     ]);
    
       
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors(),
    //         ], 400);
    //     }
    
    //     $department = Department::findOrFail($id);
    
    //     $department->update($request->all());
    
 
    //     return response()->json([
    //         'success' => true,
    //         'data' => new DepartmentResource($department),
    //         'message' => 'Department updated successfully',
    //     ], 200);

    //  }

    //  public function destroy($id)
    //  {
        
    //     $department = Department::findOrFail($id);

        
    //     $department->delete();
    
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Department deleted successfully',
    //     ], 200);
         
    //  }

}