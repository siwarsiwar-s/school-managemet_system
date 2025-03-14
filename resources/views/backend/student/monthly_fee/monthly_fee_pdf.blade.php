<!DOCTYPE html>
<html>
<head>
  <style>
  #customers {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }
  
  #customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
  }
  
  #customers tr:nth-child(even) { background-color: #f2f2f2; }
  #customers tr:hover { background-color: #ddd; }
  
  #customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
  }
  </style>
</head>
<body>

<!-- Header Table -->
<table id="customers">
  <tr>
    <td><h2>
        <?php $image_path = '/upload/logo.jpg'; ?>
        <img src="{{public_path() . $image_path}}" width="200" height="100">
    </h2></td>
    <td><h2>Easy School ERP</h2>
      <p>School Address</p>
      <p>Phone: 3265456588</p>
      
      <p>Email: sou@gmail.com</p>
      <p><b>Student Monthly Fee</b></p>
      
    </td>
  </tr>
</table>


@php
// Récupère le premier enregistrement qui correspond aux conditions
$registrationfee = App\Models\FeeCategoryAmount::where('fee_category_id', '2')
                                                ->where('class_id', $details->class_id)
                                                ->first();  // Utilise first() pour obtenir un seul enregistrement

// Vérifie si un enregistrement a été trouvé avant d'accéder à la propriété 'amount'
if ($registrationfee) {
    $originalfee = $registrationfee->amount;
} else {
    $originalfee = 0;  // Valeur par défaut si aucun enregistrement n'est trouvé
}

// Vérifie si la clé 'discount' existe et définit la valeur
$discount = isset($details['discount']['discount']) ? $details['discount']['discount'] : 0;

// Calcule les frais
$discounttablefee = ($discount / 100) * $originalfee;
$finalfee = (float)$originalfee - (float)$discounttablefee;
$month = date("F"); // Nom du mois courant
@endphp


<table id="customers">
  <tr>
    <th width="10%">Sl</th>
    <th width="45%">Student Details</th>
    <th width="45%">Student Data</th>
  </tr>

  <tr>
    <td>1</td>
    <td><b>Student ID No</b></td>
    <td>{{ $details['student']['id_no'] }}</td>
  </tr>
  <tr>
    <td>2</td>
    <td><b>Roll No</b></td>
    <td>{{ $details->roll  }}</td>
  </tr>
  <tr>
    <td>3</td>
    <td><b>Student Name</b></td>
    <td>{{ $details['student']['name']  }}</td>
  </tr>
  <tr>
    <td>4</td>
    <td><b>Father's Name</b></td>
    <td>{{ $details['student']['fname'] }}</td>
  </tr>
  <tr>
    <td>5</td>
    <td><b>Session</b></td>
    <td>{{ $details['student_year']['name']  }}</td>
  </tr>
  <tr>
    <td>6</td>
    <td><b>Class</b></td>
    <td>{{ $details['student_class']['name']  }}</td>
  </tr>
  <tr>
    <td>7</td>
    <td><b>Monthly Fee</b></td>
    <td>{{ $originalfee }} $</td>
  </tr>
  <tr>
    <td>8</td>
    <td><b>Discount Fee</b></td>
    <td>{{ $discount }} %</td>
  </tr>
  <tr>
    <td>9</td>
    <td><b>Fee For This Student of {{ $month }}</b></td>
    <td>{{ $finalfee }} $</td>
  </tr>
</table>

<br><br>
<i style="font-size: 10px; float: left;">Print Data: {{ date("d M Y") }}</i>
<br><br>
<hr style="border: solid 2px; width: 95%; color: #000000; margin-bottom: 50px;">



<table id="customers">
  <tr>
    <th width="10%">Sl</th>
    <th width="45%">Student Details</th>
    <th width="45%">Student Data</th>
  </tr>

  <tr>
    <td>1</td>
    <td><b>Student ID No</b></td>
    <td>{{ $details['student']['id_no'] }}</td>
  </tr>
  <tr>
    <td>2</td>
    <td><b>Roll No</b></td>
    <td>{{ $details->roll  }}</td>
  </tr>
  <tr>
    <td>3</td>
    <td><b>Student Name</b></td>
    <td>{{ $details['student']['name']  }}</td>
  </tr>
  <tr>
    <td>4</td>
    <td><b>Father's Name</b></td>
    <td>{{ $details['student']['fname'] }}</td>
  </tr>
  <tr>
    <td>5</td>
    <td><b>Session</b></td>
    <td>{{ $details['student_year']['name']  }}</td>
  </tr>
  <tr>
    <td>6</td>
    <td><b>Class</b></td>
    <td>{{ $details['student_class']['name']  }}</td>
  </tr>
  <tr>
    <td>7</td>
    <td><b>Monthly Fee</b></td>
    <td>{{ $originalfee }} $</td>
  </tr>
  <tr>
    <td>8</td>
    <td><b>Discount Fee</b></td>
    <td>{{ $discount }} %</td>
  </tr>
  <tr>
    <td>9</td>
    <td><b>Fee For This Student of {{ $month }}</b></td>
    <td>{{ $finalfee }} $</td>
  </tr>
</table>

<br><br>
<i style="font-size: 10px; float: left;">Print Data: {{ date("d M Y") }}</i>
<br><br>
<hr style="border: solid 2px; width: 95%; color: #000000; margin-bottom: 50px;">

</body>
</html>
