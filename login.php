<?php
        session_start();
        require "dbutil.php";
        $db = DbUtil::notLoggedIn();

        $stmt = $db->stmt_init();
        if($stmt->prepare("select cid, user_pass from proj_usertable where cid = ? and user_pass = ?") or die(mysqli_error($db))) {
                $stmt->bind_param("ss", $_POST["cid"], $_POST['password']);
                $stmt->execute();
                $stmt->bind_result($cid, $user_pass);

                if($stmt->fetch()) {
                  $_SESSION['login_status'] = true;
                  $_SESSION['user'] = $cid;
                  
                  $role = $db->query("SELECT role FROM proj_usertable WHERE cid=$cid");
                  $_SESSION['role'] = $role;
                  
                  $stmt->close();
                  $db->close();
                  
                  if($role="admin"){
                    $db = DbUtil::logInAdmin();
                    $stmt = $db->stmt_init();
                  }
                  elseif($role="student"){
                    $db = DbUtil::logInUserB();
                    $stmt = $db->stmt_init();
                  }
                  else{
                    $db = DbUtil::notLoggedIn();
                    $stmt = $db->stmt_init();
                  }

                  ?>
                  <script type = "text/javascript">
				              window.location.replace("jobsList.html");
			            </script>
                  <?php
                }
                else{
                  ?>
                  <script type = "text/javascript">
				              window.location.replace("login.html");
			            </script>
                  <?php
                }
                $stmt->close();
        }
        $db->close();
?>
