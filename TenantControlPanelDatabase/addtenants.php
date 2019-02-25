<?php include "functions.php" ?>
<html>
<body>
  <form method="get">
  First name:<br>
  <input type="text" name="firstname" value="(First Name)"><br>
  Middle Initial:<br>
  <input type="text" name="lastname" value="(Last Name)"><br><br>
  Last name:<br>
  <input type="text" name="lastname" value="(Last Name)"><br><br>
  <input type="submit" value="Submit">
  <?php
  $conn = createConnection();
  if($_GET){
    $firstname = $_GET["firstname"];
    $middleinitial = $_GET["firstname"];
    $lastname = $_GET["firstname"];
    $sql = "INSERT INTO `tenants` (first_name, middle_initial, last_name, ssn, dob, street, rent, pets, room_no)
    VALUES ($firstname, $middleinitial, $lastname,$ssn,$dob,$street,$rent,$pets,$roomno)";

    if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
    }
    else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
  }

  ?>

</form>
</body>
</html>
