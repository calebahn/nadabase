<?php
        session_start();

        require "dbutil.php";
        $db = DbUtil::loginConnection();

        $USERNAME = $_POST["cid"];
        $PASSWORD = $_POST["password"];

        $stmt = $db->stmt_init();

        if($stmt->prepare("select cid, user_pass from proj_usertable where cid = ? and user_pass = ?") or die(mysqli_error($db))) {
                $stmt->bind_param("ss", $USERNAME, $PASSWORD);
                $stmt->execute();
                $stmt->bind_result($cid, $user_pass);

                if($stmt->fetch()) {
                  $_SESSION['login_status'] = true;
                  $_SESSION['user'] = $cid;
                  ?>
                  <script type = "text/javascript">
				              window.location = "jobList.html";
			            </script>
                  <?php
                }
                else{
                  ?>
                  <script type = "text/javascript">
				              window.location = "login.html";
			            </script>
                  <?php
                }
                $stmt->close(); -->
        }

        $db->close();


?>
