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
  $table = "tenants";
  $attList=[];
  $conn = createConnection();

  function makeQuery($table,&$attList){
    $attList = ["First Name","MI","Last Name","DOB","Street","Rent","Pets","Room No"];
    if(isset($_GET["age"])&&$_GET["age"]!=""){
      $age = $_GET["age"];
      return "SELECT first_name,middle_initial,last_name,dob,street,rent,pets,room_no FROM tenants WHERE EXTRACT(YEAR FROM SYSDATE()) - EXTRACT(YEAR FROM DOB) >=$age";
    }
    if(isset($_GET["lower"])&&$_GET["lower"]!=""){
      $lower = $_GET["lower"];
      $upper = floatval($_GET["upper"]);
      $lower = floatval($_GET["lower"]);
      return "SELECT first_name,middle_initial,last_name,dob,street,rent,pets,room_no FROM tenants WHERE (rent BETWEEN $lower AND $upper)";
    }
    if(isset($_GET["bdaymonth"])&&$_GET["bdaymonth"]!=""){
      $bmonth = $_GET["bdaymonth"];
      $bmonth = intval($bmonth);
      return "SELECT first_name,middle_initial,last_name,dob,street,rent,pets,room_no FROM tenants WHERE EXTRACT(MONTH FROM DOB)= $bmonth";
    }
    if(isset($_GET["daysremaining"])&&$_GET["daysremaining"]!=""){
      $attList = ["First Name","MI","Last Name","DOB","Street","Rent","Pets","Room No","Days Remaining"];
      $daysRemaining = $_GET["daysremaining"];
      $daysRemaining = intval($daysRemaining);
      return "SELECT first_name,middle_initial,last_name,dob,street,rent,pets,room_no,datediff(r.lease_end,SYSDATE()) FROM tenants t
      INNER JOIN rentsfrom r
      ON r.tenant_ssn = t.ssn
      WHERE datediff(r.lease_end,SYSDATE())<=$daysRemaining";
    }
    return "SELECT first_name,middle_initial,last_name,dob,street,rent,pets,room_no FROM $table";
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
  Search by Rent Range:<br>
  <input type="text" name="lower" value=""> to
  <input type="text" name="upper" value="">
  <input type="submit" value="Submit"><br><br>
  Search by Age:<br>
  <input type="text" name="age" value="">
  <input type="submit" value="Submit"><br><br>
  Find Birthdays in Month:
  <select onchange="this.form.submit()" name="bdaymonth">
    <option selected="selected"value=>Select a month</option>
    <option value=1>January</option>
    <option value=2>February</option>
    <option value=3>March</option>
    <option value=4>April</option>
    <option value=5>May</option>
    <option value=6>June</option>
    <option value=7>July</option>
    <option value=8>August</option>
    <option value=9>September</option>
    <option value=10>October</option>
    <option value=11>November</option>
    <option value=12>December</option>
  </select><br/>
  Find tenant with less than
  <input type="text" name="daysremaining" value="">days left in lease
  <input type="submit" value="Submit"><br><br>

</body>
</html>
