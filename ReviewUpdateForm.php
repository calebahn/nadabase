<html>
  <head>
  <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
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
    <script src="https://kit.fontawesome.com/86d36be8d3.js" crossorigin="anonymous"></script>
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

        session_start();
        $user = $_SESSION['user'];
        $rid=$_GET['rid'];
        $job_id=$_COOKIE['jid'];

        $message='';
        $d='';
        $b='';
        $s='';
        $f='';
        $stmt = $db->stmt_init();
        if($stmt->prepare("SELECT rid FROM proj_review where rid=? and cid=?") or die(mysqli_error($db))) {
          $stmt->bind_param("is", $rid, $user);
          $stmt->execute();
          $result = $stmt->get_result();
          if($result->num_rows == 0) {
            $backButton=$_COOKIE['backButton'];
            echo "<center><h3>Something went wrong</h3><a class='btn btn-secondary btn-sm' href='$backButton' role='button'>Go back</a></center>";
          } else {
        if ($result = $db->query("SELECT * from proj_review where rid=$rid limit 1")) {
            $out=$result->fetch_array(MYSQLI_ASSOC);
            $message=$out["message"];
            $d=$out["diff_rate"];
            $b=$out["boss_rate"];
            $s=$out["satisf_rate"];
            $f=$out["flexib_rate"];
            $result->close();
        }
        if ($result = $db->query("SELECT cult_word from proj_culture where rid=$rid")) {
          while($out2 = $result->fetch_row()) {
            $checked_words[]=$out2[0];
          }
          $result->close();
        }
        if ($result = $db->query("SELECT cult_word from proj_culture_words")) {
          while($out1 = $result->fetch_row()) {
            $words[]=$out1[0];
          }
          $result->close();
        }$word_count=count($words);


        echo "<form action='ReviewUpdate.php?rid=$rid' method='post'>
        <div class='input-group'>
          Review: <textarea class='form-control' rows='5' type='text' name='review' required>$message</textarea>
        </div><br/>
        <div class='input-group'>
          Difficulty Rating:<input class='form-control' type='number' name='diff' value='$d' required/>
          <br/><br/>
        </div>
        <div class='input-group'>
          Boss Rating:<input class='form-control' type='number' name='boss' value='$b' required/>
          <br/><br/>
        </div>
        <div class='input-group'>
          Satisfaction Rating:<input class='form-control' type='number' name='satisf' value='$s' required/>
          <br/><br/>
        </div>
        <div class='input-group'>
          Flexibility Rating:<input class='form-control' type='number' name='flex' value='$f' required/>
          <br/><br/>
        </div>
        <div class='dropdown'>
          <button class='btn btn-outline-secondary btn-block dropdown-toggle' type='button' id='dropdownMenuButton1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            Culture
          </button>
        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton1'>
          <div class='input-group'>";
        for ($j=0; $j<$word_count;$j++){
          echo "<div class='dropdown-item'>
            <input type='checkbox' name='$words[$j]' value='$words[$j]'".(in_array($words[$j], $checked_words)? "checked='checked'":"")."> $words[$j]<br>
          </div>";
        }
        echo "</div>
        </div>
        </div>
        <input class='btn btn-outline-secondary' type='Submit' />
        </form>";
      
        $job_id=$_COOKIE['jid'];
        echo "<center><a class='btn btn-outline-secondary' href='GetJob.php?jid=$job_id' role='button'>Cancel</a></center>"; 
      }}
      $db->close();

?>
  </body>
</html>
