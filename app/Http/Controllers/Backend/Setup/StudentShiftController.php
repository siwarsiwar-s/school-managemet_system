<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use App\Models\StudentShift;
use Illuminate\Http\Request;

class StudentShiftController extends Controller
{
    public function ViewShift(){
        $data['allData'] = StudentShift::all();
        return view('backend.setup.shift.view_shift', $data);
    
    }
    public function StudentshiftAdd(){
        $data['allData'] = StudentShift::all();
        return view('backend.setup.shift.add_shift', $data);
    }

    public function StudentShiftStore(Request $request){
        $validateData = $request->validate([
            'name' => 'required|unique:student_shifts,name',
        ]);

        $data = new StudentShift();
        $data->name = $request->name;
        $data->save();

        $notification = [
            'message' => 'Student Shift Inserted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('student.shift.view')->with($notification);
    }

    public function StudentShiftEdit($id){
        $editData = StudentShift::find($id);
        if (!$editData) {
            return redirect()->route('student.shift.view')->with('error', 'Student Shift not found.');
        }

        return view('backend.setup.shift.edit_shift', compact('editData'));
    }

    public function StudentShiftUpdate(Request $request,$id){
      
        $data = StudentShift::find($id);
        $validateData = $request->validate([
            'name' => 'required|unique:student_shifts,name,'.$data->id
        ]);

      
        $data->name = $request->name;
        $data->save();

        $notification = [
            'message' => 'Student Shift  Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('student.shift.view')->with($notification);
   
    }
    public function StudentShiftDelete($id){
        $user = StudentShift::find($id);
        $user->delete();
        $notification = [
            'message' => 'Student Shift Deleted Successfully',
            'alert-type' => 'info'
        ];

        return redirect()->route('student.shift.view')->with($notification);
   

    }

}
