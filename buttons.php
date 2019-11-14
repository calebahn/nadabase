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
        session_start();
        $cid=$_SESSION ['user'];
        $job_id=$_COOKIE['jid'];

		$cstart = isset($_POST['cstart']) ? $_POST['cstart'] : '';
		$pstart= isset($_POST['pstart']) ? $_POST['pstart'] : '';
		$pend= isset($_POST['pend']) ? $_POST['pend'] : '';

		if($cstart){
			$sql = "INSERT INTO proj_curr_work (cid, job_id, `start_date`)
			VALUES ('$cid', '$job_id', '$cstart')";
			if ($db->query($sql)){
				echo "<center><h3>The job has been marked as 'Currently Worked'!</h3><a class='btn btn-primary btn-sm' href='GetJob.php?jid=$job_id' role='button'>Return to Job Page</a></center>";
			} else {
				echo "error";
				echo mysqli_error($db);
			}
		} else if($pstart && $pend){
			$sql = "INSERT INTO proj_prev_worked(cid, job_id, `start_date`, end_date)
			VALUES ('$cid', '$job_id', '$pstart', '$pend')";
			if ($db->query($sql)){
				echo "<center><h3>The job has been marked as 'Previously Worked'!</h3><a class='btn btn-primary btn-sm' href='GetJob.php?jid=$job_id' role='button'>Return to Job Page</a></center>";
			} else {
				echo "error";
				echo mysqli_error($db);
			}
		}
		else{
			$sql = "INSERT INTO proj_favorite(cid, job_id)
			VALUES ('$cid', '$job_id')";
			if ($db->query($sql)){
				echo "<center><h3>Your job has been favorited!</h3><a class='btn btn-primary btn-sm' href='GetJob.php?jid=$job_id' role='button'>Return to Job Page</a></center>";
			} else {
				echo "error";
				echo mysqli_error($db);
			}
		}

    $db->close();
    ?>

</body>
</html>



