<?php include "functions.php" ?>
<html>
<head>
  <link rel="stylesheet" href="css/stylesheet.css">
  <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-orange.min.css">
</head>
<body>
  <?php
  $table = "employees";
  $attList=[];
  $conn = createConnection();

  function makeQuery($table,&$attList){
    $attList = ["Employee No","First Name","Last Name","MI","DOB","Salary","Sex","SSN","Department No"];
    if(isset($_GET["departmentall"])&&$_GET["departmentall"]!="Choose a department"){
      $departmentAll = $_GET["departmentall"];
      return "SELECT employee_no,first_name,middle_initial,last_name,dob,salary,sex,ssn,department_no FROM $table where department_no = $departmentAll ";
    }

    if(isset($_GET["lowerrange"])&&isset($_GET["upperrange"])&&$_GET["lowerrange"]!="" && $_GET["upperrange"]!=""){
      $attList = ["Employee No","First Name","Last Name","Number of Jobs"];
      $lowerRange = $_GET["lowerrange"];
      $upperRange = $_GET["upperrange"];
      return "SELECT e.employee_no, e.first_name, e.last_name, COUNT(m.request_id) From maintenancerequests m
      INNER JOIN employees e
      ON e.employee_no = m.assignedstaff_id
      WHERE m.date_requested BETWEEN '$lowerRange' AND '$upperRange'
      GROUP BY e.employee_no";
    }
    if(isset($_GET["departmentforsalary"])&&$_GET["departmentforsalary"]!="Choose a department"){
      $departmentForSalary = $_GET["departmentforsalary"];
      $attList = ["Department No","Sum of Monthly Pay" ];
      return "SELECT department_no, SUM(salary/12) FROM $table
      where department_no = $departmentForSalary
      GROUP BY department_no";
    }
    return "SELECT * FROM $table";

  }


  $result = mysqli_query($conn, makeQuery($table,$attList));

  // Check connection
  if(!$result){
    echo "no result found!<br>";
  }
  else{
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='mdl-data-table  mdl-shadow--2dp'>";
        for($i = 0; $i<sizeof($attList);$i++){
          echo "<th>".$attList[$i]."</th>";
        }
        while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          foreach($row as $rowData){
            echo "<td>".$rowData."</td>";
          }
          echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
  }
  echo "<a href='home.php'>Back</a><br>";
  ?>
  <form method="get">
    Find Employees by department
    <select onchange="this.form.submit()" name="departmentall">
      <option selected="selected">Choose a department</option>
      <option value=0001>Repair</option>
      <option value=0002>Leasing</option>
      <option value=0003>Management</option>
    </select><br><br>
    Find Amount of Maintenance Requests by Month:
    <input type="date" name="lowerrange"> to
    <input type="date" name="upperrange">
    <input type="submit" value="Submit"><br><br>
    Sum monthly by department:
    <select onchange="this.form.submit()" name="departmentforsalary">
      <option selected="selected">Choose a department</option>
      <option value=0001>Repair</option>
      <option value=0002>Leasing</option>
      <option value=0003>Management</option>
    </select><br><br>

</body>
</html>
