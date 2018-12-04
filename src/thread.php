<?php
session_start();
include 'include/db_creds.php';
include 'include/random_str.php';

if (!isset($_SESSION['sessionID'])) {
    $_SESSION['sessionID']=random_str(8);
    echo $_SESSION['sessionID'];
    $_SESSION['IP_addr']=$_SERVER['REMOTE_ADDR'];
}
$pid = $_GET['thread_id'];
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
  <title>thread</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
  <?php include 'include/header.php'; ?>
  <main>
    <div>
      <form id="newReply" name="newReply" method="post" action="action/newReply.php">
        <input type="text" name="nickname" id="nickname" placeholder="name">
        <input type="text" name="body" id="body" placeholder="body">
        <input type="text" name="parent" id="parent" value=<?php echo '"'.$pid.'"'; ?> disabled>
        <input type="submit" id="submitButton" value="submit">

      <script type="text/javascript">
        $(document).ready(function() {

          $('form[name="newReply"]').on('submit', function(e){
            e.preventDefault();
            $.ajax({
              url: 'action/newReply.php',
              data: $("#newReply").serialize(),
              type: 'POST',
              dataType: 'JSON',
              success: function(data){
                alert("uploaded successfully.")
                $(".childContainer").append("<div class='childPost'>"+ data["postID"]+ " "+data["postTime"]+" "+data["postContent"]+" "+data["sessionID"]+"</div>");
                console.log();
              }
            });
          });
        /*  $("#submitButton").click(function() {

            if (!$("#body").val().length < 1) {

              $.when($.post("action/newReply.php")).then(function(data){
                alert("Reply uploaded.")
                const arr = [];
                json_data = JSON.parse(data, (key, value) => {
                  arr[key] = value;
                })
                $("#childContainer").appendChild("<div class='childPost'>"+arr["postID"]+" "+arr["postTime"]+" "+arr["postContent"]+" "+arr["sessionID"]+"</div>")
              });
              //document.getElementById("newReply").submit();
            }

          });*/

        });
      </script>
      </form>
    </div>

    <div>
      <?php
      $sql_parent = "SELECT * FROM Post WHERE postID=$pid";
      if ($result = mysqli_query($connection, $sql_parent)) {
        if($row = mysqli_fetch_assoc($result)){
        echo "<div class='parentPost'>".$row["postID"]." ".$row["postTime"]." ".$row["postContent"]." ".$row["sessionID"]."</div>";
      }        mysqli_free_result($result);
      }else{
        echo "<div>This post has been deleted.</div>";

      }

      $sql_replies = "SELECT * FROM Post WHERE parent=$pid";
      if ($result = mysqli_query($connection, $sql_replies)) {
        echo "<div class='childContainer'>";
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<div class='childPost'>".$row["postID"]." ".$row["postTime"]." ".$row["postContent"]." ".$row["sessionID"]."</div>";
        }
        mysqli_free_result($result);
        echo "</div>";
      } else{echo "Error: " . mysqli_error($connection);}


    ?>
    </div>
  </main>
</body>
