<?php
  session_start();
  include 'include/db_creds.php';
  include 'include/random_str.php';

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

?>

<head>
  <meta charset="utf-8">
  <title>index</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
  <?php include 'include/header.php'; ?>
  <main>
    <div>
      <form id="newThread" name="newThread" method="post" action="action/newThread.php">
        <input type="text" name="nickname" id="nickname" placeholder="name">
        <input type="text" name="body" id="body" placeholder="body">
        <input type="button" id="submitButton" value="submit">
      </form>
      <script type="text/javascript">
        $(document).ready(function() {

          document.getElementById("submitButton").addEventListener("click", function() {

            if (!$("#body").val().length < 1) {
              document.getElementById("newThread").submit();
            }

          });

        });
      </script>
    </div>
    <div>
      <?php
      $sql = "SELECT * FROM Post WHERE parent=0";
      if ($result = mysqli_query($connection, $sql)) {
        echo "<div>";
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<div class='thread'>";
          echo "<div class='parentPost'>".$row["postID"]." ".$row["postTime"]." ".$row["postContent"]." ".$row["sessionID"]."</div>";
          $sql_child = "SELECT * FROM Post WHERE parent=".$row['postID'];
          if ($result_child = mysqli_query($connection, $sql_child)) {
          for($counter=0;$counter<5;$counter++){
            if( $row_child = mysqli_fetch_assoc($result_child)){
              echo "<div class='childPost'>".$row_child["postID"]." ".$row_child["postTime"]." ".$row_child["postContent"]." ".$row_child["sessionID"]."</div>";
            }
            else break;
          } mysqli_free_result($result_child);

        }
          echo "</div>";

        }
        mysqli_free_result($result);
        echo "</div>";
      } else{echo "Error: " . mysqli_error($connection);}


    ?>
    </div>
  </main>
</body>
