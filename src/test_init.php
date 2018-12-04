<?php
  session_start();
  include 'include/db_creds.php';
  include 'include/random_str.php';

  if(!isset($_SESSION['sessionID'])){
    $_SESSION['sessionID']=random_str(8);
    echo $_SESSION['sessionID'];
    $_SESSION['IP_addr']=$_SERVER['REMOTE_ADDR'];
  }

  $connection = mysqli_connect($host, $user, $password, $database);
  $error      = mysqli_connect_error();
  if ($connection -> connect_error) {
    die("Connection failed: " . $connection -> connect_error);
  }
  echo "Connected to Server.";
  if ($error != null) {
    $output = "<p>Unable to connect to database!</p>";
    exit($output);
  }
  $sqlSource = file_get_contents('database/imageboard_data.ddl');
  mysqli_multi_query($connection,$sqlSource);
  //die();
  header("Location: index.php");

  $sql = "DESCRIBE Post";
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
?>
