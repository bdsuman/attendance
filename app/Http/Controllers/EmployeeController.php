<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\Request;
class EmployeeController extends Controller
{
    /**
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
            public function store(StoreEmployeeRequest $request)
    {
        try {
            // Create a new employee with validated data
            $employee = Employee::create($request->validated());

            // If the client expects JSON (API / Swagger UI), return JSON response
            if ($request->wantsJson() || $request->expectsJson()) {
                return $this->success($employee, 'Employee created successfully', 201);
            }

            // Otherwise (typical browser form), redirect to a detail page or index with flash
            return redirect()->route('employees.show', ['employee' => $employee->id])
                ->with('success', 'Employee created successfully');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return $this->error(null, 'Failed to create employee: ' . $e->getMessage(), 500);
            }

            return back()->withErrors(['error' => 'Failed to create employee: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return $this->success($employee, 'Employee details retrieved successfully');

    }

    /**
     * Update the specified resource in storage.
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
