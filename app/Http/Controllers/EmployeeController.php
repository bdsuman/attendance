<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
class EmployeeController extends Controller
{
    /**
        * @OA\Get(
        *     path="/api/employees",
        *     summary="List employees",
        *     tags={"Employees"},
        *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
        *     @OA\Response(response=200, description="Employee list retrieved")
        * )
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Optional: filter by search keyword
        $search = $request->input('search');
       $employees = Employee::query()
        ->when($search, function ($q) use ($search) {
            $q->where('employee_no', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        })
        ->paginate(10);
        // Return JSON response using your base Controller method
        return $this->success($employees, 'Employee list retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
            /**
             * @OA\Post(
             *     path="/api/employees",
             *     summary="Create employee",
             *     tags={"Employees"},
             *     @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
             *     @OA\Response(response=201, description="Employee created")
             * )
             */
            public function store(StoreEmployeeRequest $request)
    {
        try {
            // Create a new employee with validated data
            $employee = Employee::create($request->validated());

            // Return success response
            return $this->success($employee, 'Employee created successfully', 201);
        } catch (\Exception $e) {
            // Return error response if something goes wrong
            return $this->error(null, 'Failed to create employee: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/employees/{id}",
     *     summary="Get employee",
     *     tags={"Employees"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Employee retrieved")
     * )
     */
    public function show(Employee $employee)
    {
        return $this->success($employee, 'Employee details retrieved successfully');

    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/employees/{id}",
     *     summary="Update employee",
     *     tags={"Employees"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=200, description="Employee updated")
     * )
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
         try {

            // Update the employee with validated data
            $employee->update($request->validated());

            // Return success response
            return $this->success($employee, 'Employee updated successfully');
        } catch (\Exception $e) {
            // Handle unexpected errors
            return $this->error(null, 'Failed to update employee: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/employees/{id}",
     *     summary="Delete employee",
     *     tags={"Employees"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Employee deleted")
     * )
     */
    public function destroy(Employee $employee)
    {
         try {
            // Delete the employee
            $employee->delete();

            // Return success response
            return $this->success(null, 'Employee deleted successfully');
        } catch (\Exception $e) {
            // Handle unexpected errors
            return $this->error(null, 'Failed to delete employee: ' . $e->getMessage(), 500);
        }
    }
}
