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
        
        echo "<script>console.log('Role:: " . $_SESSION['role'] . "' );</script>";

        if($_SESSION['role']=="student"){
                $db = DbUtil::logInUserB();
        }
        elseif($_SESSION['role']=="admin"){
                $db = DbUtil::logInAdmin();
        }
        else{
                $db = DbUtil::notLoggedIn();
        }
        $stmt = $db->stmt_init();
        $source="jobsList.html";
        setcookie("backButton", "jobsList.html");

        if ($result = $db->query("SELECT * from proj_job")) {
            while($out = $result->fetch_row()) {
                $job_id=$out[0];
                $title=$out[1]; 
                $description=$out[2];
                $hrs=$out[3];
                $wages=$out[4];
                $location=$out[5];
                $work_study=$out[6];
                $name=$out[7];

                echo "
                <div class='main'>
                        <div class='card w-90'>
                                <div class='card-header'>
                                        $name $title
                                </div>
                        <div class='card-body'>
                        <h5 class='card-title'>
                                $name
                        </h5>
                        <p class='card-text'>
                                $description</br>
                                Hourly Pay: $$wages
                        </p>
                        </div>
                                <a class='btn btn-primary btn-sm' href='GetJob.php?jid=$job_id' role='button'>
                                        More Information
                                </a>
                        </div>
                </div>

                </br>
                </br>
                " ;
            }
            $result->close();
        }
        $db->close();


?>