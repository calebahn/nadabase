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
        session_start();
        $db = DbUtil::logInUserB();

        $user = $_SESSION['user'];
        //$user='cha4yw';
        $stmt = $db->stmt_init();
        $job_id = $_GET['jid'];
        $backButton=$_GET['backButton'];
        $review_count=0;

        echo "<a class='btn btn-primary btn-sm' href=$backButton role='button'> Back </a>";

        if($stmt->prepare("select *
        from proj_job Natural join proj_culture NATURAL JOIN proj_skills_required
        where proj_job.job_id = proj_skills_required.job_id AND proj_job.job_id = proj_culture.rid AND proj_job.job_id=$job_id") or die(mysqli_error($db))) {
                $searchString = '%';
                $stmt->bind_param(s, $searchString);
                $stmt->execute();
                $stmt->bind_result($job_id, $title, $description, $hrs, $wages, $location, $work_study, $name, $rid, $cult_word, $skill_word);
                $skilleWords='Culture: ';
                $overallRating =  ($diff_rate + $boss_rate +$satisf_rate + $flexib_rate)/5;

                // if ($result1 = $db->query("SELECT cult_word from proj_culture where rid=$rid")) {
                //   $i=0;
                //   while($out1 = $result1->fetch_row()) {
                //     $skillWords=$skillWords . $skill_word[0] . ', ';
                //     $i=+1;
                //   }
                //   $skillWords=substr($skillWords, 0, -1);
                // }

                while($stmt->fetch()) {
                    $skillWords=$skillWords . $skill_word . ', ';
                    $i=+1;
                                         
                }
                $skillWords=substr($skillWords, 0, -1);
                echo "<div class='card w-90'><div class='card-header'>$title</div><div class='card-body'><h5 class='card-title'>$name</h5><p class='card-text'>$description</br>Hourly Pay: $wages</p><div class='card-header'>Skills Needed</div><div class='card-body'><p class='card-text'>$skillWords</p></div><div class='card w-90'>$overallRating</div><</div></div></br></br>" ;
                $stmt->close();
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
          Review: <textarea class='form-control' rows='5' type='text' name='review'></textarea>
        </div><br/>
        <div class='input-group'>
          Difficulty Rating:<input class='form-control' type='number' name='diff' />
          <br/><br/>
        </div>
        <div class='input-group'>
          Boss Rating:<input class='form-control' type='number' name='boss' />
          <br/><br/>
        </div>
        <div class='input-group'>
          Satisfaction Rating:<input class='form-control' type='number' name='satisf' />
          <br/><br/>
        </div>
        <div class='input-group'>
          Flexibility Rating:<input class='form-control' type='number' name='flex' />
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
        $stmt = $db->stmt_init();

        if($stmt->prepare("select avg(diff_rate), avg(boss_rate), avg(satisf_rate), avg(flexib_rate), count(rid) from proj_review where job_id=$job_id") or die(mysqli_error($db))) {
          $searchString = '%';
          $stmt->bind_param(s, $searchString);
          $stmt->execute();
          $stmt->bind_result($avg_diff, $avg_boss, $avg_satisf, $avg_flexib, $count);
          
          while($stmt->fetch()) {
                  if($count==0){
                    echo "<b>Difficulty: </b> ---<br/><b>Boss: </b>---<br/><b>Satisfaction: </b>---<br/><b>Flexibility: </b>---<br/>" ;
                    break;
                  }
                  echo "<b>Difficulty: </b> $avg_diff<br/><b>Boss: </b>$avg_boss<br/><b>Satisfaction: </b>$avg_satisf<br/><b>Flexibility: </b>$avg_flexib<br/>" ;
          }
          $review_count=$count;
          $stmt->close();
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
                echo "<div class='card w-90'><div class='card-header'>Difficulty: $diff_rate/5, Boss Rating: $boss_rate/5, Satisfaction: $satisf_rate/5, Flexibility: $flexib_rate/5</div><div class='card-body'><p class='card-text'>$message <hr>$cultureWords</p><div class='card-footer text-muted'>
                <a class='btn btn-light'  href='ReviewDeleteConfirmation.php?rid=$rid' role='button'><i class='fas fa-trash-alt'></i></a>
                
                <a class='btn btn-light' href='ReviewUpdateForm.php?rid=$rid' role='button'><i class='fas fa-pencil-alt'></i></a>
                $cid, $post_date</div>
                
                </div></div></div></br></br>" ;
              } else{
                echo "<div class='card w-90'><div class='card-header'>Difficulty: $diff_rate/5, Boss Rating: $boss_rate/5, Satisfaction: $satisf_rate/5, Flexibility: $flexib_rate/5</div><div class='card-body'><p class='card-text'>$message</p><div class='card-footer text-muted'>
                <a class='btn btn-light'  href='ReviewDeleteConfirmation.php?rid=$rid' role='button'><i class='fas fa-trash-alt'></i></a>
                
                <a class='btn btn-light' href='ReviewUpdateForm.php?rid=$rid' role='button'><i class='fas fa-pencil-alt'></i></a>
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
?>
  </body>
</html>
