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
        $job_id=$_COOKIE['jid'];

        $rid=$_GET['rid'];
        if ($db->query("UPDATE proj_review SET diff_rate='".$_POST["diff"]."', boss_rate='".$_POST["boss"]."', satisf_rate='".$_POST["satisf"]."', flexib_rate='".$_POST["flex"]."', `message`='".$_POST["review"]."' WHERE rid=$rid")){
            echo "<center><h3>Your review has been updated!</h3>";
            echo "<a class='btn btn-outline-secondary' href='GetJob.php?jid=$job_id' role='button'>Return</a></center>";

        } else {
            echo "error";
            echo mysqli_error($db);
        }

        if ($result = $db->query("SELECT cult_word from proj_culture where rid=$rid")) {
          while($out2 = $result->fetch_row()) {
            $orig_checked_words[]=$out2[0];
          }
          $result->close();
        }
        if ($result = $db->query("SELECT cult_word from proj_culture_words")) {
          while($out1 = $result->fetch_row()) {
            $words[]=$out1[0];
          }
          $result->close();
        }$word_count=count($words);

        for ($j=0; $j<$word_count;$j++){
          if(isset($_POST[$words[$j]])) {
            if(!in_array($words[$j], $orig_checked_words)){
              if ($db->query("INSERT INTO proj_culture VALUES ($rid, '".$_POST[$words[$j]]."')")){
                console.log('good');
              } else {
                echo mysqli_error($db);
              } 
            }
          }else {
            if(in_array($words[$j], $orig_checked_words)){
              if ($db->query("DELETE FROM proj_culture WHERE rid=$rid AND cult_word='$words[$j]'")){
                console.log('good');
            } else {
                echo mysqli_error($db);
            }
            }
          }
        }

        $db->close();
?>
  </body>
</html>