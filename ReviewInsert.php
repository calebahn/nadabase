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
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="styles.css">
    <title>Job Description</title>
    <script>
      function checkStatus(){
        var loginStatus = sessionStorage.getItem("login_status");
        if (loginStatus!="true"){
          window.location.replace("login.html");
        }
      }
    </script>
  </head>
  <body onload="checkStatus();">
<div id='navbar'>
<script>
    var el = document.getElementById('navbar');
    var loginStatus = sessionStorage.getItem("login_status");
    console.log(loginStatus);
    var content;

    if  (loginStatus=="true") {
      content = "<div id='cssmenu'><ul><li class='active'><a href='index.html'>Home</span></a></li><li><a href='jobsList.html'><span>Browse Jobs</span></a></li><li><a href='searchJobs.html'><span>Search Jobs</span></a></li><li><a href='profile.php'><span>Profile</span></a><li class='last'><a href='logout.php'><span>Logout</span></a></li></ul></div>";
    }
    else {
        content = "<div id='cssmenu'><ul><li><a href='index.html'>Home</span></a></li><li class='active'><a href='login.html'><span>Login</span></a></li></ul></div>";
    }

    el.insertAdjacentHTML('afterbegin', content);

</script>
</div>
    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <h1 class="display-4">Welcome to Hoo's Employed!</h1>
        <p class="lead">
          This is a platform for people to search for on-grounds jobs and leave
          reviews!
        </p>
      </div>
    </div>
<?php
        session_start();
        if(!$_SESSION['login_status']){
                ?>
                    <script type = "text/javascript">
                        window.location.replace("login.html");
                    </script>
                  <?php
        }

        require "dbutil.php";
        
        echo "<script>console.log('Role:: " . $_SESSION['role'] . "' );</script>";

        if($_SESSION['role']=="student"){
                $db = DbUtil::logInUserB();
        }
        elseif($_SESSION['role']=="admin"){
                $db = DbUtil::logInAdmin();
        }
        else{
                $db = DbUtil::notLoggedIn();
        }

        // $stmt = $db->stmt_init();
        $rid=0;
        $post_date=date('Y-m-d');
        $post_time=date('H:i:s');
        $job_id=$_COOKIE['jid'];

        $cid=$_SESSION['user'];
        // echo $cid;
        //$cid='cha4yw';

        if ($result = $db->query("SELECT cult_word from proj_culture_words")) {
          while($out = $result->fetch_row()) {
            $words[]=$out[0];
          }
          $result->close();
        }$word_count=count($words);

        if ($result = $db->query("SELECT MAX(rid) from proj_review limit 1")) {
            $out=$result->fetch_array(MYSQLI_NUM);
            $rid=$out[0]+1;
            $result->close();
        }
        $stmt = $db->stmt_init();
        if($stmt->prepare("INSERT INTO proj_review VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)") or die(mysqli_error($db))) {
          $stmt->bind_param("issssssssi", $rid,$post_date, $post_time, $_POST["diff"], $_POST['boss'],$_POST['satisf'], $_POST['flex'], $_POST['review'], $cid, $job_id );
          $stmt->execute();
          $stmt->close();
          echo "<center><h3>Your review has been added!</h3><a class='btn btn-secondary btn-sm' href='GetJob.php?jid=$job_id' role='button'>Return to Job Page</a></center>";
        }
        else {
          echo "<center>
          <h3>Something went wrong!</h3>
          <a class='btn btn-secondary btn-sm' href='GetJob.php?jid=$job_id' role='button'>Return to Job Page</a>
        </center>";
            //echo mysqli_error($db);
        }
        for ($j=0; $j<$word_count;$j++){
          if(isset($_POST[$words[$j]]))
          {
            if ($db->query("INSERT INTO proj_culture VALUES ($rid, '".$_POST[$words[$j]]."')")){
             console.log("good");
          } else {
            echo "<center>
              <h3>Something went wrong!</h3>
              <a class='btn btn-secondary btn-sm' href='GetJob.php?jid=$job_id' role='button'>Return to Job Page</a>
            </center>";
            //echo mysqli_error($db);
          }
          }
        }
        $db->close();


?>
  </body>
</html>
