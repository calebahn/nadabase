<?php
        session_start();

        require "dbutil.php";
        $db = DbUtil::logInUserB();

        $user = $_SESSION['user'];
        $jid = $_GET['jid'];
	$start = $_GET['start'];

        if ($db->query("DELETE FROM proj_prev_worked WHERE cid='$user' AND job_id=$jid AND start_date='$start'")){
                ?>
                <script type = "text/javascript">
                        window.location.replace("profile.php");
                </script>
                <?php
        }
        else{
                ?>
               <script type = "text/javascript">
                        window.location.replace("profile.php");
                </script>
                <?php
	}
?>
