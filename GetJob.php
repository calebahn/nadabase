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
        $db = DbUtil::logInUserB();

        // $user = $_SESSION['user'];
        $user='cha4yw';
        $stmt = $db->stmt_init();
        $job_id = $_GET['jid'];
        $backButton=$_GET['backButton'];
        $review_count=0;

        echo "<a class='btn btn-primary btn-sm' href=$backButton role='button'> Back </a>";

        if($stmt->prepare("select * from proj_job where job_id=$job_id") or die(mysqli_error($db))) {
                $searchString = '%';
                $stmt->bind_param(s, $searchString);
                $stmt->execute();
                $stmt->bind_result($job_id, $title, $description, $hrs, $wages, $location, $work_study, $name);
                
                while($stmt->fetch()) {
                        echo "<div class='card w-90'><div class='card-header'>$title</div><div class='card-body'><h5 class='card-title'>$name</h5><p class='card-text'>$description</br>Hourly Pay: $wages</p></div></div></br>" ;
                }
                $stmt->close();
        }

        echo "<br/> <h5>Leave a Review</h5><br/>";
        echo "<form action='ReviewInsert.php?job_id=$job_id' method='post'>
        <div class='input-group'>
        Review: <textarea class='form-control' rows='5' type='text' name='review'></textarea></div><br/>
        <div class='input-group'>
        Difficulty Rating:<input class='form-control' type='number' name='diff' /><br/><br/></div>
        <div class='input-group'>
        Boss Rating:<input class='form-control' type='number' name='boss' /><br/><br/></div>
        <div class='input-group'>
        Satisfaction Rating:<input class='form-control' type='number' name='satisf' /><br/><br/></div>
        <div class='input-group'>
        Flexibility Rating:<input class='form-control' type='number' name='flex' /><br/><br/></div>
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
        $stmt = $db->stmt_init();

        if($stmt->prepare("select * from proj_review where job_id=$job_id") or die(mysqli_error($db))) {
          $searchString = '%';
          $stmt->bind_param(s, $searchString);
          $stmt->execute();
          $stmt->bind_result($rid, $post_date, $post_time, $diff_rate, $boss_rate, $satisf_rate, $flexib_rate, $message, $cid, $job_id);
          
          if ($review_count==0){
            echo "No reviews have been left on this job!";
          } else{
          while($stmt->fetch()) {
                  if($user==$cid){
                    echo "<div class='card w-90'><div class='card-header'>Difficulty: $diff_rate/5, Boss Rating: $boss_rate/5, Satisfaction: $satisf_rate/5, Flexibility: $flexib_rate/5</div><div class='card-body'><p class='card-text'>$message</p><div class='card-footer text-muted'>
                    <a class='btn btn-light'  href='ReviewDeleteConfirmation.php?rid=$rid' role='button'><i class='fas fa-trash-alt'></i></a>
                    
                    <a class='btn btn-light' href='ReviewUpdateForm.php?rid=$rid' role='button'><i class='fas fa-pencil-alt'></i></a>
                    $cid, $post_date</div>
                    
                    </div></div></div></br></br>" ;
                  }
                  else {
                    echo "<div class='card w-90'><div class='card-header'>Difficulty: $diff_rate/5, Boss Rating: $boss_rate/5, Satisfaction: $satisf_rate/5, Flexibility: $flexib_rate/5</div><div class='card-body'><p class='card-text'>$message</p><div class='card-footer text-muted'>$cid, $post_date</div></div></div></br></br>" ;
                  }
          }
        }
          $stmt->close();
  }
        $db->close();
?>
  </body>
</html>
