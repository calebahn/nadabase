<?php
session_start();
echo($_SESSION["login_status"]);
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

    <title>Profile</title>
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

    <link rel="stylesheet" href="https://bootswatch.com/4/simplex/bootstrap.min.css"/>


    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <div class="card-title mb-4">
                            <div class="d-flex justify-content-start">
                                <div class="image-container">
                                    <img src="blank_profile.png" id="imgProfile" style="width: 150px; height: 150px" class="img-thumbnail" />
                                </div>
                                <div class="userData ml-3">
                                    <h2 class="d-block" style="font-size: 1.5rem; font-weight: bold"><?php echo($_SESSION['user']);?></h2>
                                </div>
<?php
	require "dbutil.php";
        $db = DbUtil::logInUserB();
        // $user = $_SESSION['user'];
        $user='cha4yw';
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
                                            </div>
                                            <div class="col-md-8 col-6">
<?php
$result = $db->query("SELECT title, location from proj_job NATURAL JOIN proj_favorite WHERE cid=$user");
if($result->num_rows > 0){
	echo("yes");
	while($row = $result->fetch_assoc()) {
		echo("title: " . $row["title"]. " - location: " . $row["location"]. "<br>");
 }
}
else{
	echo("no");
}
//if ($result = $db->query("SELECT * from proj_favorite NATURAL JOIN proj_job  where cid=$user")) {
//	echo("helo");
//	$out=$result->fetch_array(MYSQLI_ASSOC);
//}
//if($stmt->prepare("select title, location from proj_favorite NATURAL JOIN proj_job where cid=?") or die(mysqli_error($db))) {
//                $stmt->bind_param(s, $user);
  //              $stmt->execute();
    //            $stmt->bind_result($title, $location);
                //echo "<table border=1><th>sid</th><th>title</th><th>location</th><th>age</th>\n";
//		echo("hello");
		//while($stmt->fetch()) {
                //        echo "<tr><td>$title</td><td>$location</td>";
                //}
                //echo "</table>";
//		$stmt->close();
//}
?>                                            
    Something
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Previously Worked</label>
                                            </div>
                                            <div class="col-md-8 col-6">
                                                Something
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Currently Working</label>
                                            </div>
                                            <div class="col-md-8 col-6">
                                                Something
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



<!-- https://bootsnipp.com/snippets/E1nNa -->
<!-- blank profile photo: https://pixabay.com/vectors/blank-profile-picture-mystery-man-973460/ -->
