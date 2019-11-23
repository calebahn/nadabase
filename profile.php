<?php
session_start();
//echo($_SESSION["login_status"]);
if ($_SESSION["login_status"]!="1"){
	?>
	<script type = "text/javascript">
		window.location.replace("login.html");
	</script>
<?php
}
else{
//	echo("YES");
?>
<!DOCTYPE html>
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

    <title>Profile</title>
  </head>

  <body>
<div id='navbar'>
<script>
    var el = document.getElementById('navbar');
    var loginStatus = sessionStorage.getItem("login_status");
    console.log(loginStatus);
    var content;

    if  (loginStatus=="true") {
			content = "<div id='cssmenu'><ul><li><a href='index.html'>Home</span></a></li><li><a href='jobsList.html'><span>Browse Jobs</span></a></li><li><a href='searchJobs.html'><span>Search Jobs</span></a></li><li class='active'><a href='profile.php'><span>Profile</span></a><li class='last'><a href='logout.php'><span>Logout</span></a></li></ul></div>";
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

    <link rel="stylesheet" href="https://bootswatch.com/4/simplex/bootstrap.min.css"/>


    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <div class="card-title mb-4">
                            <div class="d-flex justify-content-start">
                                <div class="image-container">
                                    <img src="rotunda.png" id="imgProfile" style="width: 150px; height: 150px" class="img-thumbnail" />
                                </div>
                                <div class="userData ml-3">
                                    <h2 class="d-block" style="font-size: 1.5rem; font-weight: bold"><?php echo($_SESSION['user']);?></h2>
                                </div>
<?php
	require "dbutil.php";
        $db = DbUtil::logInUserB();
        $user = $_SESSION['user'];
       // $user='cha4yw';
        $stmt = $db->stmt_init();
?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="tab-content ml-1" id="myTabContent">
                                    <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">

                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">First Name</label>
                                            </div>
                                            <div class="col-md-8 col-6">
                                                <?php
if($stmt->prepare("select * from proj_student where cid=?") or die(mysqli_error($db))) {
                $stmt->bind_param(s, $user);
                $stmt->execute();
                $stmt->bind_result($cid, $first_name, $last_name);
                if($stmt->fetch()) {
			echo($first_name);
//		}
//                $stmt->close();
//        }
?>
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Last Name</label>
                                            </div>
                                            <div class="col-md-8 col-6">
<?php
echo($last_name);
}
$stmt->close();
}
?>

                                            </div>
                                        </div>
                                        <hr />


                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Favorited Jobs</label>
<a class='btn btn-light'  href='downloadFav.php' role='button'><i class='fas fa-trash-alt'></i></a><br>
                                            </div>
                                            <div class="col-md-8 col-6">
<?php
if ($result = $db->query("SELECT job_id, title, location from proj_job NATURAL JOIN proj_favorite WHERE cid='$user'")) {
        if($result->num_rows==0){
                echo("no favorited job");
        }
        else{
                while ($row = $result->fetch_assoc()) {
                        $jid = $row["job_id"];
                        echo("title: " . $row["title"]. " - location: " . $row["location"]. "<a class='btn btn-light'  href='delFav.php?jid=$jid' role='button'><i class='fas fa-trash-alt'></i></a><br>");
                }
        }
}
else{
	echo("no favorited jobs");
	echo($result);
}
?>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Previously Worked</label>
                                            </div>
                                            <div class="col-md-8 col-6">
<?php
if ($result = $db->query("SELECT job_id, title, location, start_date, end_date from proj_job NATURAL JOIN proj_prev_worked WHERE cid='$user'")) {
	if($result->num_rows==0){
		echo("no previously worked jobs");
	}
	else{
        	while ($row = $result->fetch_assoc()) {
                	$jid = $row["job_id"];
			$start = $row["start_date"];	
                        echo("title: " . $row["title"]. " - location: " . $row["location"]. " -- Start:  ". $row["start_date"]. " -- End: ". $row["end_date"].  "<a class='btn btn-light'  href='delPrev.php?jid=$jid&start=$start' role='button'><i class='fas fa-trash-alt'></i></a><br>");
        	}
	}
}
else{
echo("no previously worked jobs");
}
?>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Currently Working</label>
                                            </div>
                                            <div class="col-md-8 col-6">
<?php
if ($result = $db->query("SELECT job_id, title, location from proj_job NATURAL JOIN proj_curr_work WHERE cid='$user'")) {
        if($result->num_rows==0){
                echo("no current jobs");
        }
        else{
                while ($row = $result->fetch_assoc()) {
			$jid = $row["job_id"];
                        echo("title: " . $row["title"]. " - location: " . $row["location"]. "<a class='btn btn-light'  href='delCurr.php?jid=$jid' role='button'><i class='fas fa-trash-alt'></i></a><br>");
                }
        }
}
else{
        echo("no current jobs");
}
?>
                                            </div>
                                        </div>
                                        <hr />

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>

  </body>
</html>

<?php
}
?>

<!-- https://bootsnipp.com/snippets/E1nNa -->
<!-- blank profile photo: https://pixabay.com/vectors/blank-profile-picture-mystery-man-973460/ -->
