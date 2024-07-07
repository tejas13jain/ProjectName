<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Session;

class AppoinmentController extends Controller
{
    //

    public function getAppointmentAll(){
        $data = Appointment::all();

        foreach($data as $m){
            if($m->status == 0){
                $m->status="Pending";
            }
            if($m->status == 1){
                $m->status="Approve";
            }
            if($m->status == 2){
                $m->status="cancel";
            }
        }
        return $data;
    }

    public function appointment(Request $request){
        try {
            $request->validate([
                'name' => 'required|string',
                'number' => 'required|string',
                'desc' => 'required|string',
                'daytime' => 'required|date_format:Y-m-d H:i:s',
            ]);

            $existingAppointment = Appointment::where('daytime', $request->daytime)
            ->first();

            if ($existingAppointment) {
                return response()->json(['error' => 'Another appointment already exists at the same date and time.'], 409);
            }

            $data = Appointment::updateOrCreate(['id' => $request->id], [
                'user_id' => Auth::id(), 
                'name' => $request->name,
                'number' => $request->number,
                'desc' => $request->desc,
                'daytime' => $request->daytime,
                'status' => 'RSVP', 
            ]);

            return response()->json(['message' => 'Appointment saved successfully', 'data' => $data], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create/update appointment: ' . $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id){
        try {
   
               $request->validate([
                   'approved' => 'required',
                   'patient_id' =>'required',
               ]);
   
               $userCheck = User::findOrFail($request->patient_id);

               if($userCheck->role != "patient" || $userCheck->id == $request->patient){
                 return response()->json(['error' => 'Unauthorized'], 401);
               }

               $appointment = Appointment::findOrFail($id);
   
               $appointment->approved = $request->approved;
               if($appointment->approved == 4){
                   $appointment->approved="reject";
               }
               if($appointment->approved == 3){
                   $appointment->approved="postpone";
               }
               if($appointment->approved == 2){
                   $appointment->approved="Cancel";
               }
               $appointment->save();
   
               return response()->json(['message' => 'Appointment status updated successfully', 'appointment' => $appointment], 200);
   
           } catch (\Exception $e) {
               return response()->json(['error' => 'Failed to update appointment status: ' . $e->getMessage()], 500);
           }
       }
   

    public function filterDate(Request $request ,$date = null){
     try {

            $query = Appointment::where('user_id', $request->patient_id);

            if (isset($request->daytime)) {
                $query->whereDate('daytime', $request->daytime);
            }

            $appointments = $query->orderBy('daytime', 'asc')->get();

            return response()->json(['appointments' => $appointments], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch appointments: ' . $e->getMessage()], 500);
        }
    }
}
