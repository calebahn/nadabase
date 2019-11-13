<?php
        session_start();

        require "dbutil.php";
        $db = DbUtil::logInUserB();

        $user = $_SESSION['user'];
        $jid = $_GET['jid'];

        if ($db->query("DELETE FROM proj_prev_worked WHERE cid='$user' AND job_id=$jid")){
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
