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

class DoctorController extends Controller
{

    public function updateStatusByDoctor(Request $request, $id){
        try {
   
               $request->validate([
                   'approved' => 'required',
                   'doctor_id' => 'required',
               ]);
   
               $userCheck = User::findOrFail($request->doctor_id);

               if($userCheck->role != "doctor"){
                return response()->json(['error' => 'Unauthorized'], 401);
               }
               $appointment = Appointment::findOrFail($id);
   
               $appointment->approved = $request->approved;
               if($appointment->approved == 0){
                   $appointment->approved="Pending";
               }
               if($appointment->approved == 1){
                   $appointment->approved="Approve";
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
}