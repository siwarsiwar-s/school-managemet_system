<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeCategory;
use App\Models\StudentClass;
use App\Models\FeeCategoryAmount;

class FeeAmountController extends Controller
{
    public function ViewFeeAmount()
    {
        // Utilisation de MAX pour la colonne amount afin de respecter le GROUP BY
        $data['allData'] = FeeCategoryAmount::select('fee_category_id')
                                ->groupBy('fee_category_id')
                                ->get();
    
        return view('backend.setup.fee_amount.view_fee_amount', $data);
    }
    

    public function FeeAmountAdd()
    {
        $data['fee_categories'] = FeeCategory::all();
        $data['classes'] = StudentClass::all();
        return view('backend.setup.fee_amount.add_fee_amount', $data);
    }

    public function FeeAmountStore(Request $request)
    {
        $countClass = count($request->class_id);
        if ($countClass != null) {
            for ($i = 0; $i < $countClass; $i++) {
                $fee_amount = new FeeCategoryAmount();
                $fee_amount->fee_category_id = $request->fee_category_id;
                $fee_amount->class_id = $request->class_id[$i];
                $fee_amount->amount = $request->amount[$i];
                $fee_amount->save();
            }
        }

        $notification = [
            'message' => 'Fee Amount Inserted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('fee.amount.view')->with($notification);
    }

    public function FeeAmountEdit($fee_category_id)
    {
        $data['editData'] = FeeCategoryAmount::where('fee_category_id', $fee_category_id)->get();
        $data['fee_categories'] = FeeCategory::all();
        $data['classes'] = StudentClass::all();
        return view('backend.setup.fee_amount.edit_fee_amount', $data);
    }
    public function FeeAmountUpdate(Request $request, $fee_category_id)
    {
        // Vérifier si aucune classe n'est sélectionnée
        if ($request->class_id == null) {
            $notification = [
                'message' => 'Sorry, you did not select any class amount',
                'alert-type' => 'error'
            ];
            return redirect()->route('fee.amount.edit', $fee_category_id)->with($notification);
        } else {
            // Compter le nombre de classes
            $countClass = count($request->class_id);
    
            // Supprimer les anciens enregistrements de cette catégorie de frais
            FeeCategoryAmount::where('fee_category_id', $fee_category_id)->delete();
    
            // Ajouter les nouveaux enregistrements
            for ($i = 0; $i < $countClass; $i++) {
                // Créer un nouvel objet FeeCategoryAmount et assigner manuellement
                $fee_amount = new FeeCategoryAmount();
    
                // Affecter les valeurs pour chaque classe et montant
                $fee_amount->fee_category_id = $request->fee_category_id;
                $fee_amount->class_id = $request->class_id[$i];
                $fee_amount->amount = $request->amount[$i];
    
                // Sauvegarder l'enregistrement dans la base de données
                $fee_amount->save();
            }
        }
    
        // Notification de succès
        $notification = [
            'message' => 'Data Updated Successfully',
            'alert-type' => 'success'
        ];
    
        // Retourner la vue avec la notification
        return redirect()->route('fee.amount.view')->with($notification);
    }
    


    public function FeeAmountDetails($fee_category_id)
    {
        // Récupère les détails de la catégorie de frais
        $data['detailsData'] = FeeCategoryAmount::where('fee_category_id', $fee_category_id)
                                    ->orderBy('class_id', 'asc')
                                    ->get();
        
        // Assure-toi de passer la variable $allData à la vue
        $data['allData'] = FeeCategoryAmount::where('fee_category_id', $fee_category_id)
                                             ->get();
    
        return view('backend.setup.fee_amount.details_fee_amount', $data);
    }
    
    
}
