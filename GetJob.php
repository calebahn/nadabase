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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Job Description</title>
    <script>
      function checkStatus(){
        var loginStatus = sessionStorage.getItem("login_status");
        if (loginStatus!="true"){
          window.location.replace("login.html");
        }
      }
    </script>
    <?php
        require "dbutil.php";
        session_start();
        if(!$_SESSION['login_status']){
          ?>
              <script type = "text/javascript">
                  window.location.replace("login.html");
              </script>
        <?php
        } 

        if($_SESSION['role']=="student"){
          $db = DbUtil::logInUserB();
        }
        elseif($_SESSION['role']=="admin"){
          $db = DbUtil::logInAdmin();
        }
        else{
          $db = DbUtil::notLoggedIn();
        }
        $user = $_SESSION['user'];
        $stmt = $db->stmt_init();
        $job_id = $_GET['jid'];

        echo "<script>console.log('Role: " . $_SESSION['user'] . "' );</script>";
        
        $backButton=$_COOKIE['backButton'];
        $review_count=0;
        setcookie("jid", $job_id);

        /* merge stuff */
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

          $avg_diff_pc = ($avg_diff/5)*100;
          $avg_boss_pc = ($avg_boss/5)*100;
          $avg_satisf_pc = ($avg_satisf/5)*100;
          $avg_flex_pc = ($avg_flexib/5)*100;

          $avg_diff = round($avg_diff, 2); 
          $avg_boss = round($avg_boss, 2);
          $avg_satisf = round($avg_satisf, 2); 
          $avg_flexib = round($avg_flexib, 2);    

          $avg_diff_calc = 5 - $avg_diff;

          $review_count=$out[4];
          $overallRating =  ($avg_diff_calc + $avg_boss +$avg_satisf + $avg_flexib)/4;
          $overallRating = round($overallRating, 2);      
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
            $skillsNeeded=substr($skillsNeeded, 0, -2);
          }

          $empCategory='';
          $phoneNum='';

          if ($result2 = $db->query("SELECT num from proj_phonenum where `name`='$name'")) {
            while($out2 = $result2->fetch_row()) {
              $phoneNum=$phoneNum . $out2[0] . ', ';
            }
            $phoneNum=substr($phoneNum, 0, -2);
          }else {
            echo mysqli_error($db);
          }

          if ($result3 = $db->query("SELECT cat_word from proj_employer where `name`='$name' limit 1")) {

            $out3=$result3->fetch_array(MYSQLI_NUM);
            $empCategory=$out3[0];   
          }

          if($review_count==0){
            $avg_boss = '--';
            $avg_diff = '--';
            $avg_satisf = '--';
            $avg_flexib = '--';
            $overallRating = '--';
          }

        ?>
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
      .job-name {
        font-size: 50px;
        font-family: 'Ubuntu', sans-serif;
        margin-top: 10px;
        margin-left: 30px;
      }
      .job-loc{
        font-size: 25px;
        font-family: 'Ubuntu', sans-serif;
        margin-top: -10px;
        margin-left: 30px;
      }
      .review-title{
        font-size: 35px;
        font-family: 'Ubuntu', sans-serif;
        margin-left: 30px;
      }
      .leave-review-title{
        font-size: 35px;
        font-family: 'Ubuntu', sans-serif;
        margin-left: 30px;
        margin-bottom: -10px;
      }
      .btn-back{
        margin-left: 30px;
        background: #f96e5b;
        border: none;
      }
      .btn-back:hover{
        background: #d85443;
        outline: none;
      }
      .btn-orange{
        background: #f96e5b;
        border: none;
        outline: none;
        color: #FFFFFF;
        width: 100%;
      }
      .btn-orange:hover{
        background: #d85443;
        color: #FFFFFF;
        outline: none;
      }

      .btn-job{
        margin-right: 30px;
        margin-top: 10px;
        background: #f96e5b;
        border: none;
        float: right;
        overflow: auto;
      }
      .btn-job:hover{
        background: #d85443;
        outline: none;
      }

      .about{
        background-color: #FFFFFF;
        border-radius: 5px;
        box-shadow: 0px 0px 2px .75px rgba(0, 0, 0, 0.16);
        margin-right: -10px;
        margin-left: -15px;
        padding: 10px;
      }
      .rating{
        background-color: #FFFFFF;
        border-radius: 5px;
        box-shadow: 0px 0px 2px .75px rgba(0, 0, 0, 0.16);
        margin-left: -10px;
        margin-right: -15px;
        padding:10px;
      }
      .left-margin{
        margin-left: 30px;
      }
      .right-margin{
        margin-right: 30px;
      }
      .grey-line{
        border-left: 3px solid rgb(219, 219, 219);
        height: 80%;
      }
      .text-center{
        text-align: center;
      }
      .text-left{
        text-align: left;
      }
      .text-right{
        text-align: right;
      }
      .text-justified{
        text-align: justify;
      }
      .big-text{
        font-size: 50px;
        color: #f96e5b;
      }
      .medbig-text{
        font-size: 30px;
      }
      .med-text{
        font-size: 15px;
      }
      .orange-text{
        color: #f96e5b;
      }
      .small-text{
        margin-top: -10px;
      }
      .div-center{
        margin: auto;
      }
      .v-small{
        font-size: 12px;
      }
      .vert-centered{
        margin: auto;
      }
      .first-review{
        background-color: #FFFFFF;
        border-radius: 10px 10px 0px 0px;
        padding:10px;
        margin-left: 30px;
        margin-right: 30px;
        background: rgb(245, 245, 245);
        border: 1px solid rgb(219, 219, 219);
      }
      .end-review{
        background-color: #FFFFFF;
        border-radius: 0px 0px 10px 10px;
        padding:10px;
        margin-left: 30px;
        margin-right: 30px;
        margin-top: -1px;
        background: rgb(245, 245, 245);
        border: 1px solid rgb(219, 219, 219);
        margin-bottom: 30px;
      }
      .middle-review{
        background-color: #FFFFFF;
        border-radius: 0px 0px 0px 0px;
        padding:10px;
        margin-left: 30px;
        margin-right: 30px;
        margin-top: -1px;
        background: rgb(245, 245, 245);
        border: 1px solid rgb(219, 219, 219);
      }
      .review-user{
        font-size: 20px;
        color: #f96e5b;
      }
      .first-review:hover{
        background: #FFFFFF;
      }
      .middle-review:hover{
        background: #FFFFFF;
      }
      .end-review:hover{
        background: #FFFFFF;
      }
      .user-edit{
        background: rgba(0, 0, 0, 0.00);
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
        <h1 class="display-4">Welcome to Hoo's Employed!</h1>
        <p class="lead">
          This is a platform for people to search for on-grounds jobs and leave
          reviews!
        </p>
      </div>
    </div>
    
        <?php
          echo "
          <a class='btn btn-primary btn-sm btn-back' href=$backButton role='button'> <i class='fas fa-reply'></i> Back </a>
          
          </br>
          </br>

          <div class='row'>
            <div class='col-10'>
              <div class='job-name'>$location $title</div>
              <div class='job-loc'>$location ($empCategory)";
              if ($isFavorite){
                echo "<a class='btn btn-primary btn-sm btn-back' href='unfavoriteJob.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Unfavorite this job!' >Unfavorite <i class='fas fa-star'></i></a>";
              } else{
                echo "<a class='btn btn-primary btn-sm btn-back' href='favoriteJob.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Mark this job as Favorited!' >Favorite <i class='far fa-star'></i></a>";
              }
          
          echo "
              </div>
            </div>

            <div class='col-2'>";
            if ($isCurrent){
              echo "<a class='btn btn-primary btn-job' href='RemoveCurrJobConfirmation.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Remove this job as one you are currently working!'>Remove Current</a>";
            } else{
              echo "<a class='btn btn-primary btn-job' href='currJobForm.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Mark this job as one you are currently working!'>Current Job</a>";
            }
            echo "
              <a class='btn btn-primary btn-job' href='prevJobForm.php' role='button' data-toggle='tooltip' data-placement='bottom' title='Mark this job as one you have previously worked!'>Previous Job</a>
            </div>
          </div>
          </br>
          </br>

          <div class='row left-margin right-margin'>

            <div class='col-6'>
              <div class='about'>
              <div class='row'>
              <div class='col-3 orange-text medbig-text text-center vert-centered'>
              About
              </div>
              <div class='col-9 grey-line'>
              

                <div class='row'>
                  <div class='col-4 med-text orange-text text-right'>
                  Contact Info:
                  </div>
                  <div class='col-8 med-text'>
                  $phoneNum
                  </div>
                </div>

                <div class='row'>
                  <div class='col-4 med-text orange-text text-right'>
                  Description:
                  </div>
                  <div class='col-8 med-text text-left'>
                  $description
                  </div>
                </div>

                <div class='row'>
                  <div class='col-4 med-text orange-text text-right'>
                  Skills Needed:
                  </div>
                  <div class='col-8 med-text'>
                  $skillsNeeded
                  </div>
                </div>

                <div class='row'>
                  <div class='col-4 med-text orange-text text-right'>
                  Hourly Pay:
                  </div>
                  <div class='col-8 med-text'>
                  $$wages
                  </div>
                </div>
                </div>
                </div>

              </div>
            </div>

            <div class='col-6'>
              <div class='rating'>
                <div class='row'>

                  <div class='col-3 text-center vert-centered'>
                    <div class='big-text'>
                      $overallRating
                    </div>
                    <div class='small-text'>
                      Rating
                    </div>
                  </div>

                  <div class='col-9 grey-line'>

                    <div class='row'>

                      <div class='col-3 v-small text-right div-center'>
                      Difficulty <i class='fas fa-chalkboard'></i>
                      </div>

                      <div class='col-7 div-center'>
                        <div class='w3-light-grey'>
                          <div class='w3-blue' style='height:12px;width:$avg_diff_pc%'></div>
                        </div>
                      </div>

                      <div class='col-2 div-center text-left'>
                        $avg_diff
                      </div>

                    </div>
                    </br>

                    <div class='row'>

                      <div class='col-3 v-small text-right div-center'>
                      Boss <i class='far fa-user'></i>
                      </div>

                      <div class='col-7 div-center'>
                        <div class='w3-light-grey'>
                          <div class='w3-blue' style='height:12px;width:$avg_boss_pc%'></div>
                        </div>
                      </div>

                      <div class='col-2 div-center text-left'>
                        $avg_boss
                      </div>

                    </div>
                    </br>

                    <div class='row'>

                      <div class='col-3 v-small text-right div-center'>
                      Satisfaction <i class='far fa-smile'></i>
                      </div>

                      <div class='col-7 div-center'>
                        <div class='w3-light-grey'>
                          <div class='w3-blue' style='height:12px;width:$avg_satisf_pc%'></div>
                        </div>
                      </div>

                      <div class='col-2 div-center text-left'>
                        $avg_satisf
                      </div>

                    </div>
                    </br>

                    <div class='row'>

                      <div class='col-3 v-small text-right div-center'>
                      Flexibility <i class='far fa-thumbs-up'></i>
                      </div>

                      <div class='col-7 div-center'>
                        <div class='w3-light-grey'>
                          <div class='w3-blue' style='height:12px;width:$avg_flex_pc%'></div>
                        </div>
                      </div>

                      <div class='col-2 div-center text-left'>
                        $avg_flexib
                      </div>

                    </div>

                  </div>

                </div>
              </div>
            </div>

          </div>

          </br>
          </br>


          ";

          $result->close();
          $result1->close();
          $result2->close();
          $result3->close();
        }

        echo "
        <br/> 
        <div class='leave-review-title'>Leave a Review</div>";

        $word_count=0;
        if ($result = $db->query("SELECT cult_word from proj_culture_words")) {
          while($out = $result->fetch_row()) {
            $words[]=$out[0];
          }
          $result->close();
        }
        $word_count=count($words);
        echo "<form action='ReviewInsert.php?job_id=$job_id' method='post'>
        <div class='input-group row'>
          <div class='col-2 text-right'>
            Difficulty Rating:
          </div>
          <div class='col-10'>
            <input class='form-control' type='number' name='diff' required placeholder='Enter A Number 0-5'/>
          </div>
        </div>
        <div class='input-group row'>
          <div class='col-2 text-right'>
            Boss Rating:
          </div>
          <div class='col-10'>
            <input class='form-control' type='number' name='boss' required placeholder='Enter A Number 0-5'/>
          </div>
        </div>
        <div class='input-group row'>
          <div class='col-2 text-right'>
            Satisfaction Rating:
          </div>
          <div class='col-10'>
            <input class='form-control' type='number' name='satisf' required placeholder='Enter A Number 0-5'/>
          </div>
        </div>
        <div class='input-group row'>
          <div class='col-2 text-right'>
            Flexibility Rating:
          </div>
          <div class='col-10'>
          <input class='form-control' type='number' name='flex' required placeholder='Enter A Number 0-5'/>
          </div>
        </div>
        <div class='input-group row'>
          <div class='col-2 text-right'>
            Review: 
          </div>
          <div class='col-10'>
            <textarea class='form-control' rows='5' type='text' required name='review' ></textarea>
          </div>
        </div>
        <br/>
        <div class='dropdown'>
          <button class='btn btn-outline-secondary btn-block dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            Culture
          </button>
        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
          <div class='input-group'>
        ";
        for ($j=0; $j<$word_count;$j++){
          echo "
          <div class='dropdown-item'>
            <input type='checkbox' name='$words[$j]' value='$words[$j]'> $words[$j]<br>
          </div>";
        }
        echo "</div>
        </div>
        </div>
        </br>
        <input class='btn btn-orange' type='Submit' />
      </form>";
        
        $review_index=0;
        echo "<br/> <div class='review-title'>$review_count Reviews</div></br>";
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
            $cultureWords='';

            echo "<script>console.log('Role:: " . $cid . "' );</script>";

            if ($result1 = $db->query("SELECT cult_word from proj_culture where rid=$rid")) {
              $i=0;
              while($out1 = $result1->fetch_row()) {
                $cultureWords=$cultureWords . $out1[0] . ', ';
                $i=+1;
              }
              $cultureWords=substr($cultureWords, 0, -2);
            }

            if($cultureWords==''){
              $cultureWords= 'n/a';
            }

            $diff_calc = 5 - $diff_rate;
            $overall_rate = ($diff_calc + $boss_rate + $flexib_rate + $satisf_rate)/4;

            if($user==$cid){
              if($review_index==$review_count-1){
                echo "
                <div class='end-review'>
                <div class='row'>

                  <div class='col-4 text-center vert-centered'>
                    <div class='review-user'>
                      $cid
                    </div>
                    <div class='med-text'>
                      $post_date
                    </div>
                    <div>
                    <a class='btn btn-light' href='ReviewUpdateForm.php?rid=$rid' role='button'>
                    <i class='fas fa-pencil-alt'></i>
                  </a>
                      <a class='btn btn-light'  href='ReviewDeleteConfirmation.php?rid=$rid' role='button'>
                        <i class='fas fa-trash-alt'></i>
                      </a>
                    </div>
                  </div>

                  <div class='col-8 grey-line'>
                    <div class='row'>
                      <div class='col-2 orange-text text-right'>
                        Rating:
                      </div>

                      <div class='col-10'>
                        <div class='row'>
                          <div class='col-1'>
                            <i class='fas fa-chalkboard'></i> $diff_rate
                          </div>
                          <div class='col-1'>
                            <i class='far fa-user'></i> $boss_rate
                          </div>
                          <div class='col-1'>
                            <i class='far fa-smile'></i> $satisf_rate
                          </div>
                          <div class='col-1'>
                            <i class='far fa-thumbs-up'></i> $flexib_rate
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Culture Words: 
                      </div>

                      <div class='col-10 text-left'>
                        $cultureWords
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Review: 
                      </div>

                      <div class='col-10 text-left'>
                        $message
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Overall Rating: 
                      </div>

                      <div class='col-10 text-left'>
                        $overall_rate
                      </div>
                    </div>

                  </div>

                </div>

              </div>
                ";
              }
              elseif($review_index==0){
                echo "
                <div class='first-review'>
                <div class='row'>

                  <div class='col-4 text-center vert-centered'>
                    <div class='review-user'>
                      $cid
                    </div>
                    <div class='med-text'>
                      $post_date
                    </div>
                    <div>
                    <a class='btn btn-light' href='ReviewUpdateForm.php?rid=$rid' role='button'>
                    <i class='fas fa-pencil-alt'></i>
                  </a>
                      <a class='btn btn-light'  href='ReviewDeleteConfirmation.php?rid=$rid' role='button'>
                        <i class='fas fa-trash-alt'></i>
                      </a>
                    </div>
                  </div>

                  <div class='col-8 grey-line'>
                    <div class='row'>
                      <div class='col-2 orange-text text-right'>
                        Rating:
                      </div>

                      <div class='col-10'>
                        <div class='row'>
                          <div class='col-1'>
                            <i class='fas fa-chalkboard'></i> $diff_rate
                          </div>
                          <div class='col-1'>
                            <i class='far fa-user'></i> $boss_rate
                          </div>
                          <div class='col-1'>
                            <i class='far fa-smile'></i> $satisf_rate
                          </div>
                          <div class='col-1'>
                            <i class='far fa-thumbs-up'></i> $flexib_rate
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Culture Words: 
                      </div>

                      <div class='col-10 text-left'>
                        $cultureWords
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Review: 
                      </div>

                      <div class='col-10 text-left'>
                        $message
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Overall Rating: 
                      </div>

                      <div class='col-10 text-left'>
                        $overall_rate
                      </div>
                    </div>

                  </div>

                </div>
                </div>
                ";
              }
              else{
                echo "
                <div class='middle-review'>
                <div class='row'>

                  <div class='col-4 text-center vert-centered'>
                    <div class='review-user'>
                      $cid
                    </div>
                    <div class='med-text'>
                      $post_date
                    </div>
                    <div>
                    <a class='btn btn-light user-edit' href='ReviewUpdateForm.php?rid=$rid' role='button'>
                        <i class='fas fa-pencil-alt'></i>
                      </a>
                      <a class='btn btn-light user-edit'  href='ReviewDeleteConfirmation.php?rid=$rid' role='button'>
                        <i class='fas fa-trash-alt'></i>
                      </a>
                    </div>
                  </div>

                  <div class='col-8 grey-line'>
                    <div class='row'>
                      <div class='col-2 orange-text text-right'>
                        Rating:
                      </div>

                      <div class='col-10'>
                        <div class='row'>
                          <div class='col-1'>
                            <i class='fas fa-chalkboard'></i> $diff_rate
                          </div>
                          <div class='col-1'>
                            <i class='far fa-user'></i> $boss_rate
                          </div>
                          <div class='col-1'>
                            <i class='far fa-smile'></i> $satisf_rate
                          </div>
                          <div class='col-1'>
                            <i class='far fa-thumbs-up'></i> $flexib_rate
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Culture Words: 
                      </div>

                      <div class='col-10 text-left'>
                        $cultureWords
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Review: 
                      </div>

                      <div class='col-10 text-left'>
                        $message
                      </div>
                    </div>

                    <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Overall Rating: 
                      </div>

                      <div class='col-10 text-left'>
                        $overall_rate
                      </div>
                    </div>

                  </div>

                </div>
                </div>
                ";
              }
            }
            else{
              if($review_index==$review_count-1){
                echo "
                <div class='end-review'>
                  <div class='row'>

                    <div class='col-4 text-center vert-centered'>
                      <div class='review-user'>
                        $cid
                      </div>
                      <div class='med-text'>
                        $post_date
                      </div>
                    </div>

                    <div class='col-8 grey-line'>
                      <div class='row'>
                        <div class='col-2 orange-text text-right'>
                          Rating:
                        </div>

                        <div class='col-10'>
                          <div class='row'>
                            <div class='col-1'>
                              <i class='fas fa-chalkboard'></i> $diff_rate
                            </div>
                            <div class='col-1'>
                              <i class='far fa-user'></i> $boss_rate
                            </div>
                            <div class='col-1'>
                              <i class='far fa-smile'></i> $satisf_rate
                            </div>
                            <div class='col-1'>
                              <i class='far fa-thumbs-up'></i> $flexib_rate
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class='row'>
                        <div class='col-2 text-right orange-text'>
                          Culture Words: 
                        </div>

                        <div class='col-10 text-left'>
                          $cultureWords
                        </div>
                      </div>

                      <div class='row'>
                        <div class='col-2 text-right orange-text'>
                          Review: 
                        </div>

                        <div class='col-10 text-left'>
                          $message
                        </div>
                      </div>

                      <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Overall Rating: 
                      </div>

                      <div class='col-10 text-left'>
                        $overall_rate
                      </div>
                    </div>

                    </div>

                  </div>

                </div>
                ";
              }
              elseif($review_index==0){
                echo "
                <div class='first-review'>
                <div class='row'>

                <div class='col-4 text-center vert-centered'>
                  <div class='review-user'>
                    $cid
                  </div>
                  <div class='med-text'>
                    $post_date
                  </div>
                </div>

                <div class='col-8 grey-line'>
                  <div class='row'>
                    <div class='col-2 orange-text text-right'>
                      Rating:
                    </div>

                    <div class='col-10'>
                      <div class='row'>
                        <div class='col-1'>
                          <i class='fas fa-chalkboard'></i> $diff_rate
                        </div>
                        <div class='col-1'>
                          <i class='far fa-user'></i> $boss_rate
                        </div>
                        <div class='col-1'>
                          <i class='far fa-smile'></i> $satisf_rate
                        </div>
                        <div class='col-1'>
                          <i class='far fa-thumbs-up'></i> $flexib_rate
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class='row'>
                    <div class='col-2 text-right orange-text'>
                      Culture Words: 
                    </div>

                    <div class='col-10 text-left'>
                      $cultureWords
                    </div>
                  </div>

                  <div class='row'>
                    <div class='col-2 text-right orange-text'>
                      Review: 
                    </div>

                    <div class='col-10 text-left'>
                      $message
                    </div>
                  </div>

                  <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Overall Rating: 
                      </div>

                      <div class='col-10 text-left'>
                        $overall_rate
                      </div>
                    </div>

                </div>

              </div>
                </div>
                ";
              }
              else{
                echo "
                <div class='middle-review'>
                <div class='row'>

                    <div class='col-4 text-center vert-centered'>
                      <div class='review-user'>
                        $cid
                      </div>
                      <div class='med-text'>
                        $post_date
                      </div>
                    </div>

                    <div class='col-8 grey-line'>
                      <div class='row'>
                        <div class='col-2 orange-text text-right'>
                          Rating:
                        </div>

                        <div class='col-10'>
                          <div class='row'>
                            <div class='col-1'>
                              <i class='fas fa-chalkboard'></i> $diff_rate
                            </div>
                            <div class='col-1'>
                              <i class='far fa-user'></i> $boss_rate
                            </div>
                            <div class='col-1'>
                              <i class='far fa-smile'></i> $satisf_rate
                            </div>
                            <div class='col-1'>
                              <i class='far fa-thumbs-up'></i> $flexib_rate
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class='row'>
                        <div class='col-2 text-right orange-text'>
                          Culture Words: 
                        </div>

                        <div class='col-10 text-left'>
                          $cultureWords
                        </div>
                      </div>

                      <div class='row'>
                        <div class='col-2 text-right orange-text'>
                          Review: 
                        </div>

                        <div class='col-10 text-left'>
                          $message
                        </div>
                      </div>

                      <div class='row'>
                      <div class='col-2 text-right orange-text'>
                        Overall Rating: 
                      </div>

                      <div class='col-10 text-left'>
                        $overall_rate
                      </div>
                    </div>

                    </div>

                  </div>
                </div>
                ";
              }
            }
            $review_index=$review_index+1;

            /*
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
            */

          }
          $result->close();
          $result1->close();
      }}
        $db->close();
    }}
?>
  </body>
</html>

