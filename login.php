<?php
        session_start();
        require "dbutil.php";
        $db = DbUtil::notLoggedIn();

        $stmt = $db->stmt_init();
        if($stmt->prepare("select cid, user_pass, role from proj_usertable where cid = ? and user_pass = ?") or die(mysqli_error($db))) {
                $stmt->bind_param("ss", $_POST["cid"], $_POST['password']);
                $stmt->execute();
                $stmt->bind_result($cid, $user_pass, $role);

                $_SESSION['login_status'] = false;
                ?>
                <script type="text/javascript">
                  sessionStorage.setItem("login_status", "false");
                </script>
                <?php

                if($stmt->fetch()) {
                  $_SESSION['login_status'] = true;
                  $_SESSION['user'] = $cid;

                  $_SESSION["role"] = $role;

                  ?>
                  <script type="text/javascript">
                    sessionStorage.setItem("login_status", "true");
                  </script>
                  <?php


                  $stmt->close();
                  $db->close();


                  if($role=="admin"){
                    $db = DbUtil::logInAdmin();
                    $stmt = $db->stmt_init();
                  }
                  elseif($role=="student"){
                    $db = DbUtil::logInUserB();
                    $stmt = $db->stmt_init();
                  }
                  else{
                    echo "<script>console.log('Role:: " . $role . "' );</script>";
                    ?>
                    <script type = "text/javascript">
                        window.location.replace("login.html");
                    </script>
                  <?php

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
