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
  $table = "maintenancerequests";
  $conn = createConnection();
  $attList=[];

  function makeQuery($table,&$attList){
    $attList = ["Request ID","Date Requested","Action Requested","Staff Assigned"];
    if(isset($_GET["lastname"])&&$_GET["lastname"]!=""){
      $lastname=$_GET["lastname"];
      $attList = ["Request ID","Action Requested","First Name","Last Name"];
      return "SELECT m2.request_id, m2.action_requested, t.first_name, t.last_name From tenants t
      INNER JOIN maintenancerequests m2 ON m2.requestingtenant_ssn = t.ssn AND t.last_name = '$lastname'";
    }
    if(isset($_GET["request"])&&$_GET["request"]!=""){
      $request=$_GET["request"];
      $attList = ["Request ID","Date Requested","Action Requested","Staff Assigned"];
      return "SELECT request_id,date_requested,action_requested,assignedstaff_id FROM $table WHERE action_requested LIKE '%$request%'";
    }
    if(isset($_GET["lowerrange"])&&isset($_GET["upperrange"])&&$_GET["lowerrange"]!="" && $_GET["upperrange"]!=""){
      $attList = ["Request ID","Date Requested","Action Requested","Staff Assigned"];
      $lowerRange = $_GET["lowerrange"];
      $upperRange = $_GET["upperrange"];
      return "SELECT request_id,date_requested,action_requested,assignedstaff_id From maintenancerequests
      WHERE date_requested BETWEEN '$lowerRange' AND '$upperRange'";
    }
    return "SELECT request_id,date_requested,action_requested,assignedstaff_id FROM $table";
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
      }
  }
  echo "<a href='home.php'>Back</a><br>";
  ?>
  <form method="get">
  Search by Tenant Last Name:<br>
  <input type="text" name="lastname" value=""><br>
  <input type="submit" value="Submit"><br>
  Search by Action:<br>
  <input type="text" name="request" value=""><br>
  <input type="submit" value="Submit"><br>
  Search by Date Requested:<br>
  <input type="date" name="lowerrange"> to
  <input type="date" name="upperrange">
  <input type="submit" value="Submit"><br><br>
</body>
</html>
