<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AssignStudent;
use App\Models\User;
use App\Models\DiscountStudent;
use App\Models\FeeCategoryAmount;
use App\Models\StudentClass;
use App\Models\StudentGroup;
use App\Models\StudentShift;
use App\Models\StudentYear;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrationFeeController extends Controller
{
    public function RegFeeView(){
        $data['years'] = StudentYear::all();
        $data['classes'] = StudentClass::all();
        return view('backend.student.registration_fee.registration_fee_view', $data);
    }
    public function GetStudentsFee(Request $request){
        $year_id = $request->year_id;
        $class_id = $request->class_id;
        $where = [];
    
        if ($year_id != '') {
            $where[] = ['year_id', 'like', $year_id . '%'];
        }
        if ($class_id != '') {
            $where[] = ['class_id', 'like', $class_id . '%'];
        }
        
        $allStudent = AssignStudent::with(['student', 'discount'])->where($where)->get();
        dd($allStudent);
        // 🔥 Ajout des guillemets pour éviter l'erreur
        $html['thsource']  = '<th>SL</th>';
        $html['thsource'] .= '<th>ID No</th>';
        $html['thsource'] .= '<th>Student Name</th>';
        $html['thsource'] .= '<th>Roll No</th>';
        $html['thsource'] .= '<th>Reg Fee</th>';
        $html['thsource'] .= '<th>Discount</th>';
        $html['thsource'] .= '<th>Student Fee</th>';
        $html['thsource'] .= '<th>Action</th>';
    
        $html['tbody'] = ''; 
        foreach ($allStudent as $key => $student) {
            $registrationfee = FeeCategoryAmount::where('fee_category_id', 1)
                                                 ->where('class_id', $student->class_id)
                                                 ->first();
            $amount = optional($registrationfee)->amount ?? 0;
            $discount = $student->discount->discount ?? 0;
            $finalFee = $amount - ($discount / 100 * $amount);
    
            $html['tbody'] .= '<tr>';
            $html['tbody'] .= '<td>' . ($key + 1) . '</td>';
            $html['tbody'] .= '<td>' . $student->student->id_no . '</td>';
            $html['tbody'] .= '<td>' . $student->student->name . '</td>';
            $html['tbody'] .= '<td>' . $student->roll . '</td>';
            $html['tbody'] .= '<td>' . $amount . '</td>';
            $html['tbody'] .= '<td>' . $discount . '%</td>';
            $html['tbody'] .= '<td>' . $finalFee . '$</td>';
            $html['tbody'] .= '<td><a href="' . route('student.registration.fee.payslip') . '?class_id=' . $student->class_id . '&student_id=' . $student->student_id . '" target="_blank" class="btn btn-sm btn-success">Fee Slip</a></td>';
            $html['tbody'] .= '</tr>';
        }
       

        return response()->json($html);
    }
        public function RegFeeClassData(Request $request)
{
    $year_id = $request->year_id;
    $class_id = $request->class_id;

    $where = [];
    if ($year_id != '') {
        $where[] = ['year_id', 'like', $year_id . '%'];
    }
    if ($class_id != '') {
        $where[] = ['class_id', 'like', $class_id . '%'];
    }

    // Récupérer les étudiants et leurs réductions
    $allStudent = AssignStudent::with(['student', 'discount'])->where($where)->get();

    // Créer l'en-tête du tableau
    $html['thsource']  = '<th>SL</th>';
    $html['thsource'] .= '<th>ID No</th>';
    
    $html['thsource'] .= '<th>Student Name</th>';
    $html['thsource'] .= '<th>Roll No</th>';
    $html['thsource'] .= '<th>Reg Fee</th>';
    $html['thsource'] .= '<th>Discount</th>';
    $html['thsource'] .= '<th>Student Fee</th>';
    $html['thsource'] .= '<th>Action</th>';

    // Initialisation du corps du tableau
    $html['tbody'] = ''; 

    // Remplir le corps du tableau avec les étudiants et leurs informations
    foreach ($allStudent as $key => $v) {
        $registrationfee = FeeCategoryAmount::where('fee_category_id', '1')
                                             ->where('class_id', $v->class_id)
                                             ->first();

        $color = 'success';
        $amount = optional($registrationfee)->amount ?? 0;
        $discount = $v['discount']['discount'];

        // Calcul des frais après réduction
        $discounttablefee = $discount / 100 * $amount;
        $finalfee = $amount - $discounttablefee;

        // Ajouter une ligne dans le tableau
        $html['tbody'] .= '<tr>';
        $html['tbody'] .= '<td>' . ($key + 1) . '</td>';
        $html['tbody'] .= '<td>' . $v['student']['id_no'] . '</td>';
        $html['tbody'] .= '<td>' . $v['student']['name'] . '</td>';
        $html['tbody'] .= '<td>' . $v->roll . '</td>';
        $html['tbody'] .= '<td>' . $amount . '</td>';
        $html['tbody'] .= '<td>' . $discount . '%</td>';
        $html['tbody'] .= '<td>' . $finalfee . '$</td>';
        $html['tbody'] .= '<td>';
        $html['tbody'] .= '<a class="btn btn-sm btn-' . $color . '" title="PaySlip" target="_blanks" href="' . route('student.registration.fee.payslip') . '?class_id=' . $v->class_id . '&student_id=' . $v->student_id . '">Fee Slip</a>';
        $html['tbody'] .= '</td>';
        $html['tbody'] .= '</tr>';
    }

    // Retourner la réponse avec le tableau complet
    return response()->json([
        'thsource' => $html['thsource'],
        'tbody' => $html['tbody']
    ]);
    

}


public function RegFeePayslip(Request $request) {
    $student_id = $request->student_id;
    $class_id = $request->class_id;
 
    $allStudent['details'] = AssignStudent::with(['student', 'discount'])
                                           ->where('student_id', $student_id)
                                           ->where('class_id', $class_id)
                                           ->first();
 
    // Charger la vue sans protection (dompdf ne supporte pas SetProtection)
    $pdf = PDF::loadView('backend.student.registration_fee.registration_fee_pdf', $allStudent);
 
    // Retourner le PDF sans protection
    return $pdf->stream('document.pdf');
 }
 
}
