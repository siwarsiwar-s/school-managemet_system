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

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

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



<table id="customers">
  <tr>
    <td><h2> Easy Learning</h2></td>
    <td><h2> Easy School ERP</h2></td>
    <p>School Address </p>
    <p>Phone : 3265456588 </p>
    <p>School Address </p>
    <p>Email : sou@gmail.com </p>
  </tr>
  
 
 
  <table id="customers">
    <tr>
      <th  width="10%">Sl</th>
      <th width="45%">Student Details</th>
      <th width="45%">Student Data </th>
    </tr>
    <tr>
      <td>1</td>
      <td><b>Student Name</b></td>
      <td>{{$details['student']['name']}}</td>
    </tr>
    <td>2</td>
    <td><b>Student ID No</b></td>
    <td>{{$details['student']['id_no']}}</td>
  </tr>
  <td>3</td>
  <td><b>Student Role</b></td>
  <td>{{$details->roll}}</td>
</tr>
    <td>4</td>
    <td><b>Father's Name</b></td>
    <td>{{$details['student']['fname']}}</td>
  </tr>
  <td>5</td>
  <td><b>Mather's Name</b></td>
  <td>{{$details['student']['mname']}}</td>
</tr>
<td>6</td>
<td><b>Mobile Number</b></td>
<td>{{$details['student']['mobile']}}</td>
</tr>
<td>7</td>
<td><b>Address</b></td>
<td>{{$details['student']['address']}}</td>
</tr>

<td>8</td>
<td><b>Gender</b></td>
<td>{{$details['student']['gender']}}</td>
</tr>
<td>9</td>
<td><b>Religion</b></td>
<td>{{$details['student']['religion']}}</td>
</tr>
<td>10</td>
<td><b>Date of Birth</b></td>
<td>{{$details['student']['dob']}}</td>
</tr>
  
</table>
<br> <br>
<i style="font-size: 10px; float : right;" >Print Data : {{date("d M Y"
)}}</i>
</body>
</html>


