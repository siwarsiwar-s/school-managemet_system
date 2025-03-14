<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\User;
use App\Models\DiscountStudent;
use App\Models\StudentClass;
use App\Models\StudentGroup;
use App\Models\StudentShift;
use App\Models\StudentYear;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class StudentRegController extends Controller
{
    public function StudentRegView()
    {
        $data['years'] = StudentYear::all();
        $data['classes'] = StudentClass::all();
        $data['year_id'] = StudentYear::orderBy('id', 'desc')->first()->id;
        $data['class_id'] = StudentClass::orderBy('id', 'desc')->first()->id;

        $data['allData'] = AssignStudent::where('year_id', $data['year_id'])
                                        ->where('class_id', $data['class_id'])
                                        ->with('user')
                                        ->get();

        return view('backend.student.student_reg.student_view', $data);
    }

    public function StudentClassYearWise(Request $request)
    {
        $data['years'] = StudentYear::all();
        $data['classes'] = StudentClass::all();
        $data['year_id'] = $request->year_id;
        $data['class_id'] = $request->class_id;

        $data['allData'] = AssignStudent::where('year_id', $request->year_id)
                                        ->where('class_id', $request->class_id)
                                        ->get();

        return view('backend.student.student_reg.student_view', $data);
    }

    public function StudentRegAdd()
    {
        $data['years'] = StudentYear::all();
        $data['classes'] = StudentClass::all();
        $data['groups'] = StudentGroup::all();
        $data['shifts'] = StudentShift::all();

        return view('backend.student.student_reg.student_add', $data);
    }

    public function StudentRegStore(Request $request)
    {
        DB::transaction(function () use ($request) {
            $checkYear = StudentYear::find($request->year_id)->name;
            $student = User::where('usertype', 'student')->orderBy('id', 'DESC')->first();

            $studentId = $student ? $student->id + 1 : 1;
            $id_no = str_pad($studentId, 4, '0', STR_PAD_LEFT);
            $final_id_no = $checkYear . $id_no;

            $user = new User();
            $code = rand(0, 9999);
            $user->id_no = $final_id_no;
            $user->password = bcrypt($code);
            $user->usertype = 'Student';
            $user->code = $code;
            $user->name = $request->name;
            $user->fname = $request->fname;
            $user->mname = $request->mname;
            $user->mobile = $request->mobile;
            $user->address = $request->address;
            $user->gender = $request->gender;
            $user->religion = $request->religion;
            $user->dob = date('Y-m-d', strtotime($request->dob));
            $user->email = $final_id_no . '@example.com';

            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/student_images'), $filename);
                $user->image = $filename;
            }

            $user->save();

            $assign_student = new AssignStudent();
            $assign_student->student_id = $user->id;
            $assign_student->year_id = $request->year_id;
            $assign_student->class_id = $request->class_id;
            $assign_student->group_id = $request->group_id;
            $assign_student->shift_id = $request->shift_id;
            $assign_student->save();

            $discount_student = new DiscountStudent();
            $discount_student->assign_student_id = $assign_student->id;
            $discount_student->fee_category_id = '1';
            $discount_student->discount = $request->discount;
            $discount_student->save();
        });

        return redirect()->route('student.registration.view')->with([
            'message' => 'Student Registration Inserted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function StudentRegEdit($student_id)
    {
        $data['years'] = StudentYear::all();
        $data['classes'] = StudentClass::all();
        $data['groups'] = StudentGroup::all();
        $data['shifts'] = StudentShift::all();
        $data['editData'] = AssignStudent::with(['student', 'discount'])
                                         ->where('student_id', $student_id)
                                         ->first();

        return view('backend.student.student_reg.student_edit', $data);
    }

    public function StudentRegUpdate(Request $request, $student_id)
    {
        DB::transaction(function () use ($request, $student_id) {
            $user = User::find($student_id);

            $user->update([
                'name' => $request->name,
                'fname' => $request->fname,
                'mname' => $request->mname,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'gender' => $request->gender,
                'religion' => $request->religion,
                'dob' => date('Y-m-d', strtotime($request->dob))
            ]);

            if ($request->file('image')) {
                $file = $request->file('image');
                @unlink(public_path('upload/student_images/' . $user->image));
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/student_images'), $filename);
                $user->image = $filename;
            }

            $assign_student = AssignStudent::where('student_id', $student_id)->first();
            $assign_student->update([
                'year_id' => $request->year_id,
                'class_id' => $request->class_id,
                'group_id' => $request->group_id,
                'shift_id' => $request->shift_id
            ]);

            $discount_student = DiscountStudent::where('assign_student_id', $assign_student->id)->first();
            $discount_student->update(['discount' => $request->discount]);
        });

        return redirect()->route('student.registration.view')->with([
            'message' => 'Student Registration Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function StudentRegPromotion($student_id)
    {
        $data['years'] = StudentYear::all();
        $data['classes'] = StudentClass::all();
        $data['groups'] = StudentGroup::all();
        $data['shifts'] = StudentShift::all();
        $data['editData'] = AssignStudent::with(['student', 'discount'])
                                         ->where('student_id', $student_id)
                                         ->first();

        return view('backend.student.student_reg.student_promotion', $data);
    }

    public function StudentUpdatePromotion(Request $request, $student_id)
    {
        DB::transaction(function () use ($request, $student_id) {
            $assign_student = new AssignStudent();
            $assign_student->student_id = $student_id;
            $assign_student->year_id = $request->year_id;
            $assign_student->class_id = $request->class_id;
            $assign_student->group_id = $request->group_id;
            $assign_student->shift_id = $request->shift_id;
            $assign_student->save();

            $discount_student = new DiscountStudent();
            $discount_student->assign_student_id = $assign_student->id;
            $discount_student->fee_category_id = '1';
            $discount_student->discount = $request->discount;
            $discount_student->save();
        });

        return redirect()->route('student.registration.view')->with([
            'message' => 'Student Promotion Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function StudentRegDetails($student_id)
    {
        $data['details'] = AssignStudent::with(['student', 'discount'])->where('student_id', $student_id)->first();
        $pdf = PDF::loadView('backend.student.student_reg.student_details_pdf', $data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');

        return $pdf->stream('document.pdf');
    }
}
