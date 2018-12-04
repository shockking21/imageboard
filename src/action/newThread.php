<?php
  session_start();
  include '../include/db_creds.php';
  include '../include/random_str.php';

  if (!isset($_SESSION['sessionID'])) {
      $_SESSION['sessionID']=random_str(8);
      echo $_SESSION['sessionID'];
      $_SESSION['IP_addr']=$_SERVER['REMOTE_ADDR'];
  }

  $connection = mysqli_connect($host, $user, $password, $database);
  $error      = mysqli_connect_error();
  if ($connection -> connect_error) {
      die("Connection failed: " . $connection -> connect_error);
  }
  //echo "Connected to Server.";
  if ($error != null) {
      $output = "<p>Unable to connect to database!</p>";
      exit($output);
  }
  $sql_insert = "INSERT INTO Post (sessionID, postContent, nickname, postTime, parent) VALUES ('".$_SESSION['sessionID']."', '".$_POST['body']."', '".$_POST['nickname']."', NOW(), 0)";
  $sql_getPostID = "SELECT LAST_INSERT_ID()";
  if (mysqli_query($connection, $sql_insert)) {
  	echo "Post successful, redirecting.";
  } else {
  	echo "Error: " . $sql_insert . "" . mysqli_error($connection);
  }
  if ($result = mysqli_query($connection, $sql_getPostID)) {
      $pid = mysqli_fetch_row($result);
  }
  //mysqli_free_result($result);
  mysqli_close($connection);
  header("Location: ../thread.php?thread_id=".$pid[0]);
?>
