<html>
  <head>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />
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
    <style>
      h2, p {
      margin: 0 0 20px;
      font-weight: 400;
      color: #666;
      }
      span{
      color: #666;
      display:block;
      padding:0 0 5px;
      }
      form {
      padding: 25px;
      margin: 25px;
      box-shadow: 0 2px 5px #f5f5f5; 
      background: #eee; 
      }
      input, textarea {
      padding: 8px;
      margin-bottom: 20px;
      border: 1px solid #1c87c9;
      outline: none;
      }
      input.btn-outline-secondary{
      width: calc(100%);
      padding: 8px;
      margin-bottom: 20px;
      border: 1px solid #1c87c9;
      }
      .dropdown-menu{
      width: calc(100%);
      }
    </style>
  </head>
  <body onload="checkStatus();">
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
    <div id='navbar'>
      <script>
          var el = document.getElementById('navbar');
          var loginStatus = sessionStorage.getItem("login_status");
          console.log(loginStatus);
          var content;

          if  (loginStatus=="true") {
            content = "<div id='cssmenu'><ul><li><a href='index.html'>Home</span></a></li><li class='active'><a href='jobsList.html'><span>Browse Jobs</span></a></li><li><a href='searchJobs.html'><span>Search Jobs</span></a></li><li><a href='profile.php'><span>Profile</span></a><li class='last'><a href='logout.php'><span>Logout</span></a></li></ul></div>";
          }
          else {
              content = "<div id='cssmenu'><ul><li><a href='index.html'>Home</span></a></li><li class='active'><a href='login.html'><span>Login</span></a></li></ul></div>";
          }

          el.insertAdjacentHTML('afterbegin', content);

      </script>
    </div>
    </div>
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
        session_start();
        $db = DbUtil::logInUserB();

        $user = $_SESSION['user'];
        //$user='cha4yw';
        $stmt = $db->stmt_init();
        $job_id = $_GET['jid'];

        if($stmt->prepare("SELECT job_id FROM proj_job where job_id=?") or die(mysqli_error($db))) {
        $stmt->bind_param("i", $job_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 0) {
          $backButton=$_COOKIE['backButton'];
          echo "<center><h3>Something went wrong</h3><a class='btn btn-primary btn-sm' href='$backButton' role='button'>Go back</a></center>";
        } else {
        $backButton=$_COOKIE['backButton'];
        $review_count=0;
        setcookie("jid", $job_id);

        $isFavorite=false;
        $isCurrent=false;

        if ($result = $db->query("SELECT * from proj_favorite where cid='$user' and job_id=$job_id limit 1")) {
          $out=$result->fetch_array(MYSQLI_NUM);
          if (count($out)>0){
            $isFavorite=true;
          } 
          $result->close();
        }
        if ($result = $db->query("SELECT * from proj_curr_work where cid='$user' and job_id=$job_id limit 1")) {
          $out=$result->fetch_array(MYSQLI_NUM);
          if (count($out)>0){
            $isCurrent=true;
          } 
          $result->close();
        }

        echo "<div class='row'>
        <div class='col'>
          <a class='btn btn-primary btn-sm' href=$backButton role='button'> Back </a>
        </div>
        <div class='col-7'>
        </div>
        <div class='col' align='right'>";
        if ($isFavorite){
          echo "<a class='btn btn-primary btn-sm' href='unfavoriteJob.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Unfavorite this job!' ><i class='fas fa-star'></i></a>";
        } else{
          echo "<a class='btn btn-primary btn-sm' href='favoriteJob.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Mark this job as Favorited!' ><i class='far fa-star'></i></a>";
        }

        if ($isCurrent){
          echo "<a class='btn btn-primary btn-sm' href='RemoveCurrJobConfirmation.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Remove this job as one you are currently working!'>Remove Current</a>";
        } else{
          echo "<a class='btn btn-primary btn-sm' href='currJobForm.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Mark this job as one you are currently working!'>Current</a>";
        }

        echo "<a class='btn btn-primary btn-sm' href='prevJobForm.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Mark this job as one you have previously worked!'>Previous</a>
        </div>
        </div>";

        $avg_diff=0;
        $avg_boss=0; 
        $avg_satisf=0;
        $avg_flexib=0;
        $review_count=0;
        $overallRating =0;

        if ($result = $db->query("SELECT AVG(diff_rate), AVG(boss_rate), AVG(satisf_rate), AVG(flexib_rate), COUNT(rid) from proj_review where job_id=$job_id limit 1")) {

          $out=$result->fetch_array(MYSQLI_NUM);
          $avg_diff=$out[0];
          $avg_boss=$out[1]; 
          $avg_satisf=$out[2];
          $avg_flexib=$out[3];
          $review_count=$out[4];
          $overallRating =  ($avg_diff + $avg_boss +$avg_satisf + $avg_flexib)/5;      
          $result->close();
        }

        if ($result = $db->query("SELECT * from proj_job where job_id=$job_id limit 1")) {

          $out=$result->fetch_array(MYSQLI_NUM);
          $job_id=$out[0];
          $title=$out[1]; 
          $description=$out[2];
          $hrs=$out[3];
          $wages=$out[4];
          $location=$out[5];
          $work_study=$out[6];
          $name=$out[7];
      
          $skillsNeeded='';

          if ($result1 = $db->query("SELECT skill_word from proj_skills_required where job_id=$job_id")) {
            $i=0;
            while($out1 = $result1->fetch_row()) {
              $skillsNeeded=$skillsNeeded . $out1[0] . ', ';
              $i=+1;
            }
            $skillsNeeded=substr($skillsNeeded, 0, -1);
          }

          $empCategory='';
          $phoneNum='';

          if ($result2 = $db->query("SELECT num from proj_phonenum where `name`='$name'")) {
            while($out2 = $result2->fetch_row()) {
              $phoneNum=$phoneNum . $out2[0] . ', ';
            }
            $phoneNum=substr($phoneNum, 0, -1);
          }else {
            echo mysqli_error($db);
          }

          if ($result3 = $db->query("SELECT cat_word from proj_employer where `name`='$name' limit 1")) {

            $out3=$result3->fetch_array(MYSQLI_NUM);
            $empCategory=$out3[0];   
          }

          echo "<div class='card w-90'>
          <div class='card-header'>$title</div>
          <div class='card-body'>
            <h5 class='card-title'>$name ($empCategory) - Contact Info: $phoneNum</h5>
            <p class='card-text'>$description</br>Hourly Pay: $wages</p>
            <div class='card-header'>Skills Needed</div>
            <div class='card-body'>
              <p class='card-text'>$skillsNeeded</p>
            </div>
            <div class='card-header'>Overall Rating</div>
            <div class='card-body'>
              <p class='card-text'>$overallRating</p>
            </div>
          </div></div></br></br>" ;

          $result->close();
          $result1->close();
          $result2->close();
          $result3->close();
        }

        echo "<br/> <h5>Leave a Review</h5><br/>";

        $word_count=0;
        if ($result = $db->query("SELECT cult_word from proj_culture_words")) {
          while($out = $result->fetch_row()) {
            $words[]=$out[0];
          }
          $result->close();
        }
        $word_count=count($words);
        echo "<form action='ReviewInsert.php?job_id=$job_id' method='post'>
        <div class='input-group'>
          Review: <textarea class='form-control' rows='5' type='text' name='review' required></textarea>
        </div><br/>
        <div class='input-group'>
          Difficulty Rating:<input class='form-control' type='number' name='diff' required/>
          <br/><br/>
        </div>
        <div class='input-group'>
          Boss Rating:<input class='form-control' type='number' name='boss' required/>
          <br/><br/>
        </div>
        <div class='input-group'>
          Satisfaction Rating:<input class='form-control' type='number' name='satisf' required/>
          <br/><br/>
        </div>
        <div class='input-group'>
          Flexibility Rating:<input class='form-control' type='number' name='flex' required/>
          <br/><br/>
        </div>
        <div class='dropdown'>
          <button class='btn btn-outline-secondary btn-block dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            Culture
          </button>
        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
          <div class='input-group'>
        ";
        for ($j=0; $j<$word_count;$j++){
          echo "<div class='dropdown-item'>
            <input type='checkbox' name='$words[$j]' value='$words[$j]'> $words[$j]<br>
          </div>";
        }
        echo "</div>
        </div>
        </div>
        <input class='btn btn-outline-secondary' type='Submit' />
      </form>";

        echo "<br/> <h5>Average Ratings</h5>";

        if($review_count==0){
          echo "<b>Difficulty: </b> ---<br/><b>Boss: </b>---<br/><b>Satisfaction: </b>---<br/><b>Flexibility: </b>---<br/>" ;

        }else {
          echo "<b>Difficulty: </b> $avg_diff<br/><b>Boss: </b>$avg_boss<br/><b>Satisfaction: </b>$avg_satisf<br/><b>Flexibility: </b>$avg_flexib<br/>" ;
        }

        echo "<br/> <h5>Reviews</h5>";
        if ($review_count==0){
          echo "No reviews have been left on this job!";
        }else {
        if ($result = $db->query("SELECT * from proj_review where job_id=$job_id")) {
          while($out = $result->fetch_row()) {
            $rid=$out[0];
            $post_date=$out[1];
            $post_time=$out[2];
            $diff_rate=$out[3];
            $boss_rate=$out[4];
            $satisf_rate=$out[5];
            $flexib_rate=$out[6];
            $message=$out[7];
            $cid=$out[8];
            $job_id=$out[9];
            $cultureWords='Culture: ';

            if ($result1 = $db->query("SELECT cult_word from proj_culture where rid=$rid")) {
              $i=0;
              while($out1 = $result1->fetch_row()) {
                $cultureWords=$cultureWords . $out1[0] . ', ';
                $i=+1;
              }
              $cultureWords=substr($cultureWords, 0, -1);
            }

            if($user==$cid){
              if ($i>0){
                echo "<div class='card w-90'>
                  <div class='card-header'>Difficulty: $diff_rate/5, Boss Rating: $boss_rate/5, Satisfaction: $satisf_rate/5, Flexibility: $flexib_rate/5</div>
                  <div class='card-body'>
                  <p class='card-text'>$message <hr>$cultureWords</p>
                <div class='card-footer text-muted'>
                <a class='btn btn-light'  href='ReviewDeleteConfirmation.php?rid=$rid' role='button' data-toggle='tooltip' data-placement='bottom' title='Delete this review!'>
                  <i class='fas fa-trash-alt'></i>
                </a>
                <a class='btn btn-light' href='ReviewUpdateForm.php?rid=$rid' role='button' data-toggle='tooltip' data-placement='bottom' title='Update this review!'>
                  <i class='fas fa-pencil-alt'></i></a>
                $cid, $post_date</div>

                </div></div></div></br></br>" ;
              } else{
                echo "<div class='card w-90'><div class='card-header'>Difficulty: $diff_rate/5, Boss Rating: $boss_rate/5, Satisfaction: $satisf_rate/5, Flexibility: $flexib_rate/5</div><div class='card-body'><p class='card-text'>$message</p><div class='card-footer text-muted'>
                <a class='btn btn-light'  href='ReviewDeleteConfirmation.php?rid=$rid' role='button' data-toggle='tooltip' data-placement='bottom' title='Delete this review!'><i class='fas fa-trash-alt'></i></a>

                <a class='btn btn-light' href='ReviewUpdateForm.php?rid=$rid' role='button' data-toggle='tooltip' data-placement='bottom' title='Update this review!'><i class='fas fa-pencil-alt'></i></a>
                $cid, $post_date</div>

                </div></div></div></br></br>" ;
              }
            }
            else {
              if ($i>0){
                echo "<div class='card w-90'><div class='card-header'>Difficulty: $diff_rate/5, Boss Rating: $boss_rate/5, Satisfaction: $satisf_rate/5, Flexibility: $flexib_rate/5</div><div class='card-body'><p class='card-text'>$message <hr> $cultureWords</p><div class='card-footer text-muted'>$cid, $post_date</div></div></div></br></br>" ;
              }
              else {
                echo "<div class='card w-90'><div class='card-header'>Difficulty: $diff_rate/5, Boss Rating: $boss_rate/5, Satisfaction: $satisf_rate/5, Flexibility: $flexib_rate/5</div><div class='card-body'><p class='card-text'>$message</p><div class='card-footer text-muted'>$cid, $post_date</div></div></div></br></br>" ;
              }
            }

          }
          $result->close();
          $result1->close();
      }}
        $db->close();
    }}
?>
  </body>
</html>

