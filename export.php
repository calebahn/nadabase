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
  </head>
  <body>
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

        //citation: https://stackoverflow.com/questions/16391528/query-mysql-and-export-data-as-csv-in-php
        //citation: https://stackoverflow.com/questions/16251625/how-to-create-and-download-a-csv-file-from-php-script
        function export_csv( $arr=[],$arr1=[]){
            header( 'Content-Type: application/csv' );
            header( 'Content-Disposition: attachment; filename="job_info.csv";' );

            // clean output buffer
            ob_end_clean();

            $f = fopen( 'php://output', 'w' );

            if (count($arr)>0){
                while ($row = $arr->fetch_array(MYSQLI_NUM)) {
                    fputcsv($f, array_values($row), ',');
                }
                if(count($arr1)>0){
                    while ($row1 = $arr1->fetch_array(MYSQLI_NUM)) {
                        fputcsv($f, array_values($row1), ',');
                    }
                }
            }else {
                $headers=['Job Title', 'Job Description', 'Hourly Pay', 'Employer Name', 'Start Date'];
                fputcsv($f, $headers, ',');
                while ($row1 = $arr1->fetch_array(MYSQLI_NUM)) {
                    fputcsv($f, array_values($row1), ',');
                }
            }

            fclose( $f );

            // flush buffer
            ob_flush();

            // use exit to get rid of unexpected output afterward
            exit();
        }
        
        if ($result = mysqli_query($db, "(SELECT 'Job Title', 'Job Description', 'Hourly Pay', 'Employer Name', 'Start Date', 'End Date') UNION (SELECT title, description, wages, name, start_date, end_date FROM proj_prev_worked natural join proj_job where cid='$user')")){
            if ($result1 = mysqli_query($db, "SELECT title, `description`, wages, `name`, `start_date` FROM proj_curr_work natural join proj_job where cid='$user'")){
                if ($result1->num_rows==0 ){
                    if ($result->num_rows==1){
                        echo "<center><h3>You have no job information to export!</h3>";
                        echo "<a class='btn btn-outline-secondary' href='profile.php?' role='button'>Return to profile!</a></center>";
                    } else {
                      export_csv($result,[]);
                    }
                }
                else{
                    if ($result->num_rows==1){
                      export_csv([],$result1);
                    } else {
                      export_csv($result, $result1);
                    }
                }
            }else {
              echo "<center>
                <h3>Something went wrong!</h3>
                <a class='btn btn-secondary btn-sm' href='profile.php' role='button'>Return to Profile</a>
              </center>";
                //echo mysqli_error($db);
            }
        } else {
          echo "<center>
              <h3>Something went wrong!</h3>
              <a class='btn btn-secondary btn-sm' href='profile.php' role='button'>Return to Profile</a>
              </center>";
            //echo mysqli_error($db);
        }
        $db->close();

    ?>
  </body>
</html>