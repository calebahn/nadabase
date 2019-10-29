<?php
        session_start();
        require "dbutil.php";
        $db = DbUtil::loginConnection();

        $stmt = $db->stmt_init();
        if($stmt->prepare("select cid, user_pass from proj_usertable where cid = ? and user_pass = ?") or die(mysqli_error($db))) {
                $stmt->bind_param("ss", $_POST["cid"], $_POST['password']);
                $stmt->execute();
                $stmt->bind_result($cid, $user_pass);

                if($stmt->fetch()) {
                  $_SESSION['login_status'] = true;
                  $_SESSION['user'] = $cid;
                  
                  $stmt->close();
                  $db->close();
                  
                  $db = DbUtil::loggedInUser("cs4750.cs.virginia.edu", "cha4yw_b", "yo2ohXee", "cha4yw");
                  $stmt = $db->stmt_init();

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
