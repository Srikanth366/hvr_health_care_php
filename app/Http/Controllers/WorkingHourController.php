<?php 
// app/Http/Controllers/WorkingHourController.php
namespace App\Http\Controllers;

use App\Models\WorkingHour;
use App\Models\Appointments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class WorkingHourController extends Controller
{
    public function store(Request $request)
    {
        // Custom validation logic for overlapping time ranges
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'start_time.required' => 'Start time is required',
            'end_time.required' => 'End time is required',
            'end_time.after' => 'End time must be after start time',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (!$this->noOverlapTimeRange(
                $request->user_id,
                $request->day_of_week,
                $request->start_time,
                $request->end_time
            )) {
                $validator->errors()->add('time_overlap', 'The specified time range overlaps with an existing working hour.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 400);
        }

        $workingHour = WorkingHour::create([
            'user_id' => $request->user_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json(['status' => true, 'message' => 'Working hour created successfully', 'data' => $workingHour], 201);
    }

    private function noOverlapTimeRange($user_id, $day_of_week, $start_time, $end_time)
    {
        $overlap = WorkingHour::where('user_id', $user_id)
            ->where('day_of_week', $day_of_week)
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                    ->orWhereBetween('end_time', [$start_time, $end_time])
                    ->orWhere(function ($query) use ($start_time, $end_time) {
                        $query->where('start_time', '<=', $start_time)
                            ->where('end_time', '>=', $end_time);
                    });
            })
            ->exists();

        return !$overlap;
    }

    public function destroy($id)
    {
        $workingHour = WorkingHour::find($id);

        // Check if the entry exists
        if (!$workingHour) {
            return response()->json([
                'status' => false,
                'message' => 'Working hour not found',
            ], 404);
        }

        $workingHour->delete();

        return response()->json([
            'status' => true,
            'message' => 'Working hour deleted successfully',
        ], 200);
    }

    public function GetWorkingHours($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Success',
                'errors' => implode(', ', $validator->errors()->all())
            ], 400);
        }
        $workingHours = WorkingHour::where('user_id', $id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $workingHours
        ], 200);

    }

    public function GetAppointmentslots(Request $request)
    {

        $dayOfWeek = $request->input('day_of_week');

        $validateUser = Validator::make([
        'user_id' => $request->user_id,
        'day_of_week' => $dayOfWeek,
        'appointment_date' => $request->appointment_date
        ], [
        'user_id' => 'required|integer|exists:users,id',
        'day_of_week' => 'required|string|in:monday,Monday,tuesday,Tuesday,wednesday,Wednesday,thursday,Thursday,friday,Friday,saturday,Saturday,sunday,Sunday',
        'appointment_date' => 'required|date'
        ]);


         if ($validateUser->fails()) {
             return response()->json([
                 'status' => false,
                 'message' => 'Validation error',
                 'errors' => implode(', ', $validateUser->errors()->all())
             ], 400);
         }

         $workingHours = WorkingHour::where('user_id', $request->user_id)
         ->where('day_of_week', $dayOfWeek)
         ->get();
 
         if ($workingHours->count() > 0) {

            $slots = $this->generateSlots($workingHours,$request->appointment_date, $request->user_id);

            /* $appointmentTime = Appointments::where('user_id', $request->user_id)
            ->where('appointment_date', $request->appointment_date)
            ->whereIn('status', ['confirmed', 'requested'])
            ->pluck('appointment_time'); */

                return response()->json([
                    'status' => true,
                    'message' => 'Appointment slots have been generated.',
                    'data' => $slots,
                ], 200);
         } else {
            return response()->json([
                'status' => false,
                'message' => 'No data found.',
            ], 400);
         }
        
    }

    private function generateSlots($workingHours,$appointment_date, $user_id)
    {
    $slots = [];

    foreach ($workingHours as $workingHour) {
        $day = strtolower($workingHour->day_of_week);
        $startTime = Carbon::createFromFormat('H:i:s', $workingHour->start_time);
        $endTime = Carbon::createFromFormat('H:i:s', $workingHour->end_time);

        while ($startTime->lessThan($endTime)) {
            $slotStart = $startTime->format('H:i:s');
            $startTime->addMinutes(10);
            $slotEnd = $startTime->format('H:i:s');

            $appointmentTimeQuery = Appointments::where('DoctorID', $user_id)
            ->where('AppointmentDate', $appointment_date)
            ->where('AppointmentTime', $slotStart)
            ->whereIn('status', ['confirmed', 'requested','Completed']);

            $today = date('Y-m-d');
            $curret_time = date('H:i:s');

            if($today == $appointment_date && $curret_time < $slotStart){
                $color = 1; // Available
            } else {
                $color = 0; // Time closed 
            }

        if ($appointmentTimeQuery->exists()) {
            $slots[] = ["slot"=>$slotStart, "is_available"=>1, "appointmentstatus"=>$color];
        } else {
            $slots[] = ["slot"=>$slotStart, "is_available"=>0,  "appointmentstatus"=>$color];
        }
        

            // Add the slot to the slots array
           // $slots[$day][] = "$slotStart - $slotEnd";
           
        }
    }

    return $slots;
}

}
