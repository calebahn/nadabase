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
        require "dbutil.php";
        $db = DbUtil::logInUserB();

        $rid=$_GET['rid'];
        $job_id=$_GET['job_id'];

        $message='';
        $d='';
        $b='';
        $s='';
        $f='';

        if ($result = $db->query("SELECT * from proj_review where rid=$rid limit 1")) {
            $out=$result->fetch_array(MYSQLI_ASSOC);
            $message=$out["message"];
            $d=$out["diff_rate"];
            $b=$out["boss_rate"];
            $s=$out["satisf_rate"];
            $f=$out["flexib_rate"];
            $result->close();
        }

        echo "<form action='ReviewUpdate.php?rid=$rid' method='post'>
        <div class='input-group'>
        Review: <textarea class='form-control' rows='5' type='text' name='review'>$message</textarea></div><br/>
        <div class='input-group'>
        Difficulty Rating:<input class='form-control' type='number' name='diff' value='$d'/><br/><br/></div>
        <div class='input-group'>
        Boss Rating:<input class='form-control' type='number' name='boss' value='$b'/><br/><br/></div>
        <div class='input-group'>
        Satisfaction Rating:<input class='form-control' type='number' name='satisf' value='$s'/><br/><br/></div>
        <div class='input-group'>
        Flexibility Rating:<input class='form-control' type='number' name='flex' value='$f'/><br/><br/></div>
        <input class='btn btn-outline-secondary' type='Submit' />
      </form>
      "
      ;

      $db->close();
        
?>
  </body>
</html>