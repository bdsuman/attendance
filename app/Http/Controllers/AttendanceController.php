<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use OpenApi\Annotations as OA;
// DB-level calculations moved to model/observer; no direct DB raw usage here

class AttendanceController extends Controller
{
    /**
        * @OA\Get(
        *     path="/api/attendances",
        *     summary="List attendances",
        *     tags={"Attendances"},
        *     @OA\Parameter(name="employee_id", in="query", @OA\Schema(type="integer")),
        *     @OA\Parameter(name="entry_date", in="query", @OA\Schema(type="string", format="date")),
        *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
        *     @OA\Response(response=200, description="Attendance list retrieved")
        * )
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $query = Attendance::query()
                    ->filterEmployee($request->input('employee_id'))
                    ->filterDate($request->input('entry_date'))
                    ->filterStatus($request->input('status'));

        $perPage = (int) $request->input('per_page', 10);
        $attendances = $query->orderBy('entry_date', 'desc')->paginate($perPage);

        return $this->success($attendances, 'Attendance list retrieved successfully');
    }

   

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/attendances",
     *     summary="Record attendance (check_in, check_out, break_in, break_out)",
     *     tags={"Attendances"},
     *     @OA\RequestBody(@OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=201, description="Attendance recorded")
     * )
     */
    public function store(StoreAttendanceRequest $request)
    {
        // Delegate to the shared processor so other entry points can use same logic
        return $this->processAttendance($request->validated());
    }
    /**
     * Core processor for creating or updating attendance based on `status`.
     * Returns a JSON response via helper methods.
     */
    private function processAttendance(array $data)
    {
        $employeeId = $data['employee_id'];

        // Entry date defaults to today if not provided
        $entryDate = isset($data['entry_date']) ? Carbon::parse($data['entry_date'])->toDateString() : Carbon::now()->toDateString();

        $status = $data['status']; // one of check_in, check_out, break_in, break_out
        $shiftId = $data['shift_id'];

        // Find existing attendance for the employee and date
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('entry_date', $entryDate)
            ->first();

        $now = Carbon::now();

        // If no attendance exists yet
        if (! $attendance) {
            // Only allow creating with status = check_in. Others require an existing check-in first.
            if ($status !== 'check_in') {
                return $this->error(null, 'Check-in must be recorded before ' . $status, 400);
            }

            $attendance = Attendance::create([
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'entry_date' => $entryDate,
                'check_in' => $now,
            ]);
            return $this->success($attendance, 'Check-in recorded', 201);
        }

        // Attendance exists — enforce rules
        switch ($status) {
            case 'check_in':
                if (!empty($attendance->check_in)) {
                    return $this->error(null, 'Check-in already recorded for this date', 409);
                }
                $attendance->check_in = $now;
                break;

            case 'break_in':
                if (empty($attendance->check_in)) {
                    return $this->error(null, 'Cannot start break before check-in', 400);
                }
                if (!empty($attendance->break_in) && empty($attendance->break_out)) {
                    return $this->error(null, 'A break is already in progress', 409);
                }
                $attendance->break_in = $now;
                // Reset break_out to null to pair with new break_in
                $attendance->break_out = null;
                break;

            case 'break_out':
                if (empty($attendance->break_in)) {
                    return $this->error(null, 'Cannot end break without a break start', 400);
                }
                if (!empty($attendance->break_out)) {
                    return $this->error(null, 'Break already ended', 409);
                }
                $attendance->break_out = $now;
                break;

            case 'check_out':
                if (empty($attendance->check_in)) {
                    return $this->error(null, 'Cannot check out before check-in', 400);
                }
                if (!empty($attendance->check_out)) {
                    return $this->error(null, 'Check-out already recorded', 409);
                }
                $attendance->check_out = $now;
                break;

            default:
                return $this->error(null, 'Invalid status', 400);
        }

        // Persist timestamp changes first
        $attendance->save();
        
        return $this->success($attendance, ucfirst(str_replace('_', ' ', $status)) . ' recorded', 200);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/attendances/{id}",
     *     summary="Get attendance",
     *     tags={"Attendances"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Attendance details retrieved")
     * )
     */
    public function show(Attendance $attendance)
    {
        return $this->success($attendance, 'Attendance details retrieved successfully');
    }

  

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/attendances/{id}",
     *     summary="Delete attendance",
     *     tags={"Attendances"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Attendance deleted")
     * )
     */
    public function destroy(Attendance $attendance)
    {
        try {
            $attendance->delete();
            return $this->success(null, 'Attendance deleted successfully');
        } catch (\Exception $e) {
            return $this->error(null, 'Failed to delete attendance: ' . $e->getMessage(), 500);
        }
    }
}
