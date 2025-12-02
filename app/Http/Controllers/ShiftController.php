<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Http\Resources\ShiftResource;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ShiftController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/shifts",
     *     summary="List shifts",
     *     tags={"Shifts"},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Shift list retrieved")
     * )
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Shift::query();

        // Optional search by name or description
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $shifts = $query->paginate($perPage);

        // Return a resource collection which includes seconds derived from stored/raw values
        $resourceCollection = ShiftResource::collection($shifts);

        // Use the controller's `success()` helper with the resource array
        return $this->success($resourceCollection->response()->getData(true), 'Shift list retrieved successfully');
    }
    /**
     * @OA\Post(
     *     path="/api/shifts",
     *     summary="Create shift",
     *     tags={"Shifts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ShiftCreate")
     *     ),
     *     @OA\Response(response=201, description="Shift created")
     * )
     * 
     * Store a newly created resource in storage.
     */
    public function store(StoreShiftRequest $request)


    {
        try {
            $shift = Shift::create($request->validated());
            return $this->success($shift, 'Shift created successfully', 201);
        } catch (\Exception $e) {
            return $this->error(null, 'Failed to create shift: ' . $e->getMessage(), 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/shifts/{id}",
     *     summary="Show shift",
     *     tags={"Shifts"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Shift retrieved")
     * )
     *
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        return $this->success($shift, 'Shift details retrieved successfully');
    }
    /**
     * @OA\Put(
     *     path="/api/shifts/{id}",
     *     summary="Update shift",
     *     tags={"Shifts"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/ShiftUpdate")
     *     ),
     *     @OA\Response(response=200, description="Shift updated")
     * )
     *
     * Update the specified resource in storage.
     */
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        try {
            $shift->update($request->validated());
            return $this->success($shift, 'Shift updated successfully');
        } catch (\Exception $e) {
            return $this->error(null, 'Failed to update shift: ' . $e->getMessage(), 500);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/shifts/{id}",
     *     summary="Delete shift",
     *     tags={"Shifts"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Shift deleted")
     * )
     *
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        try {
            $shift->delete();
            return $this->success(null, 'Shift deleted successfully');
        } catch (\Exception $e) {
            return $this->error(null, 'Failed to delete shift: ' . $e->getMessage(), 500);
        }
    }
}
