<?php
function createConnection(){
  $servername = "localhost";
  $username = "root";
  $password = "";
  $db="apartmentcomplex";
  $conn = new mysqli($servername, $username, $password,$db);
  return $conn;
}
function requestTenantsNoQuery($table,&$attList,$query,&$sql){
  $attList = ["First Name","MI","Last Name","DOB","Street","Rent","Pets","Room No"];
  $sql = "SELECT first_name,middle_initial,last_name,dob,street,rent,pets,room_no FROM tenants";
}
function requestTenantsByAge($table,&$attList,$query,&$sql){
  $age = $_GET["age"];
  $attList = ["First Name","MI","Last Name","DOB","Street","Rent","Pets","Room No"];
  $sql = "SELECT first_name,middle_initial,last_name,dob,street,rent,pets,room_no FROM tenants WHERE EXTRACT(YEAR FROM SYSDATE()) - EXTRACT(YEAR FROM DOB) >=$age";
}
function requestTenantsByRentRange($table,&$attList,$query,&$sql){
  echo "does this happen";
  $attList = ["First Name","MI","Last Name","DOB","Street","Rent","Pets","Room No"];
  $upper = floatval($query[1]);
  $lower = floatval($query[0]);
  $sql = "SELECT first_name,middle_initial,last_name,dob,street,rent,pets,room_no FROM tenants WHERE (rent BETWEEN $lower AND $upper)";
}
function requestTenantsByBirthMonth($table,&$attList,$query,&$sql){
  $bmonth = $_GET["bdaymonth"];
  $bmonth = intval($bmonth);
  $sql = "SELECT first_name,middle_initial,last_name,dob,street,rent,pets,room_no FROM tenants WHERE EXTRACT(MONTH FROM DOB)= $bmonth";
}
?>
