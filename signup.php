<html>
  <head>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />
    <script src="js/jquery-1.6.2.min.js" type="text/javascript"></script>
    <script
      src="js/jquery-ui-1.8.16.custom.min.js"
      type="text/javascript"
    ></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
      integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
      integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
      crossorigin="anonymous"
    ></script>
    <title>Job Description</title>
  </head>
  <body>
    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <h1 class="display-4">Welcome to [insert app name here]</h1>
        <p class="lead">
          This is a platform for people to search for on-grounds jobs and leave
          reviews!
        </p>
      </div>
    </div>
<?php
        session_start();
        require "dbutil.php";
        $db = DbUtil::newUser();

        
        $first = $_POST["fname"];
        $last = $_POST["lname"];
        $cid = $_POST["cid"];
        $pass = $_POST["password"];

        echo "<script>console.log('first:: " . $first . "' );</script>";
        echo "<script>console.log('last:: " . $last . "' );</script>";
        echo "<script>console.log('cid:: " . $cid . "' );</script>";
        echo "<script>console.log('Pass:: " . $pass . "' );</script>";

        $search = $db->query("SELECT cid from proj_usertable WHERE cid='$cid'");
        if (mysqli_num_rows($result)!=0){
            echo "<center><h3>You seem to already have an account!</h3><a class='btn btn-primary btn-sm' href='login.html' role='button'>Login Here</a></center>";
        }
        else {
            if($db->query("INSERT INTO proj_usertable VALUES ('$cid', '$pass', 'student')")){
                if($db->query("INSERT INTO proj_student VALUES ('$cid', '$first', '$last')")){
                    $_SESSION['login_status'] = true;
                    $_SESSION['user'] = $cid;
                    $_SESSION['role'] = "student";
                    ?>
                  <script type = "text/javascript">
				              window.location.replace("jobsList.html");
			            </script>
                  <?php
                }
                else {
                    echo "<script>console.log('Error 1' );</script>";
                    echo mysqli_error($db);
                }
            }
            else {
                echo "<script>console.log('Error 2' );</script>";
                echo mysqli_error($db);
            }
        }
        
        $db->close();
?>
    </body>
</html>