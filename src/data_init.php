<?php
session_start();
include "/db_creds.php";
$connection = mysqli_connect($host, $user, $password);
$sql = "SHOW DATABASES";
$result = mysqli_query($connection, $sql);
if ($result == false) {
  echo "Error: " . mysqli_error($connection);
} else {
  if (mysqli_num_rows($result) < 1) {
      echo "Databases not found.";
  } else {
      echo "<ul>";
      while ($row = mysqli_fetch_row($result)) {
          echo "<li>$row[0]</li>";
      }
      echo "</ul>";
  }
}
