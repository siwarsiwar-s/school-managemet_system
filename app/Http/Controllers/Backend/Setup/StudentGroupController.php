<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentGroup;

class StudentGroupController extends Controller
{
    public function ViewGroup(){
        $data['allData'] = StudentGroup::all();
        return view('backend.setup.group.view_group', $data);
    
    }
    public function StudentGroupAdd(){
        $data['allData'] = StudentGroup::all();
        return view('backend.setup.group.add_group', $data);
    }

    public function StudentGroupStore(Request $request){
        $validateData = $request->validate([
            'name' => 'required|unique:student_groups,name',
        ]);

        $data = new StudentGroup();
        $data->name = $request->name;
        $data->save();

        $notification = [
            'message' => 'Student Group Inserted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('student.group.view')->with($notification);
    }

    public function StudentGroupEdit($id){
        $editData = StudentGroup::find($id);
        if (!$editData) {
            return redirect()->route('student.group.view')->with('error', 'Student Group not found.');
        }

        return view('backend.setup.group.edit_group', compact('editData'));
    }

    public function StudentGroupUpdate(Request $request,$id){
      
        $data = StudentGroup::find($id);
        $validateData = $request->validate([
            'name' => 'required|unique:student_groups,name,'.$data->id
        ]);

      
        $data->name = $request->name;
        $data->save();

        $notification = [
            'message' => 'Student Group Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('student.group.view')->with($notification);
   
    }
    public function StudentGroupDelete($id){
        $user = StudentGroup::find($id);
        $user->delete();
        $notification = [
            'message' => 'Student Group Deleted Successfully',
            'alert-type' => 'info'
        ];

        return redirect()->route('student.group.view')->with($notification);
   

    }

}
