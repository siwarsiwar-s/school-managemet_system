<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolSubject;
use App\Models\StudentClass;
use App\Models\AssignSubject;


class AssignSubjectController extends Controller
{
    public function ViewAssignSubject(){
        
       // $data['allData'] = AssignSubject::all();
           $data['allData'] = AssignSubject::select('class_id') ->groupBy('class_id')->get();
                               
        
            return view('backend.setup.assign_subject.view_assign_subject', $data);
        
    }
    public function AddAssignSubject()
    {
        $data['subjects'] = SchoolSubject::all();
        $data['classes'] = StudentClass::all();
        return view('backend.setup.assign_subject.add_assign_subject', $data);
    }

    public function AssignSubjectStore(Request $request)
    {
        $subjectCount = count($request->subject_id);
        if ($subjectCount != null) {
            for ($i = 0; $i < $subjectCount; $i++) {
                $assign_subject = new AssignSubject();
                $assign_subject->class_id = $request->class_id;
                $assign_subject->subject_id = $request->subject_id[$i];
                $assign_subject->full_mark= $request->full_mark[$i];
                $assign_subject->pass_mark= $request->pass_mark[$i];
                $assign_subject->subjective_mark= $request->subjective_mark[$i];
                $assign_subject->save();
            }
        }

        $notification = [
            'message' => 'Subject Assign Inserted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('assign.subject.view')->with($notification);
    }


    public function EditAssignSubject($class_id)
    {
        $data['editData'] = AssignSubject::where('class_id', $class_id)->orderBy('subject_id','asc')->get();
        $data['subjects'] = SchoolSubject::all();
        $data['classes'] = StudentClass::all();
        return view('backend.setup.assign_subject.edit_assign_subject', $data);
   }

   public function UpdateAssignSubject(Request $request, $class_id)
   {
       // Vérifier si aucune classe n'est sélectionnée
       if ($request->subject_id == null) {
           $notification = [
               'message' => 'Sorry, you did not select any Subject',
               'alert-type' => 'error'
           ];
           return redirect()->route('assign.subject.edit', $class_id)->with($notification);
       } else {
           // Compter le nombre de classes
           $countClass = count($request->subject_id);
   
           // Supprimer les anciens enregistrements de cette catégorie de frais
           AssignSubject::where('class_id', $class_id)->delete();
   
           // Ajouter les nouveaux enregistrements
           for ($i = 0; $i < $countClass; $i++) {
               // Créer un nouvel objet FeeCategoryAmount et assigner manuellement
              
   
               $assign_subject = new AssignSubject();
               $assign_subject->class_id = $request->class_id;
               $assign_subject->subject_id = $request->subject_id[$i];
               $assign_subject->full_mark= $request->full_mark[$i];
               $assign_subject->pass_mark= $request->pass_mark[$i];
               $assign_subject->subjective_mark= $request->subjective_mark[$i];
             
               // Sauvegarder l'enregistrement dans la base de données
               $assign_subject->save();
           }
       }
          // Notification de succès
          $notification = [
            'message' => 'Data Updated Successfully',
            'alert-type' => 'success'
        ];
    
        // Retourner la vue avec la notification
        return redirect()->route('assign.subject.view')->with($notification);
    }

    public function DetailsAssignSubject($class_id)
    {
        // Récupère les détails de la catégorie de frais
        $data['detailsData'] = AssignSubject::where('class_id', $class_id)
                                    ->orderBy('subject_id', 'asc')->get();
                                    
        
        // Assure-toi de passer la variable $allData à la vue
        //$data['allData'] = FeeCategoryAmount::where('fee_category_id', $fee_category_id)
                                             //->get();
    
        return view('backend.setup.assign_subject.details_assign_subject', $data);
    }
}
