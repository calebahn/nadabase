<?php
        require "dbutil.php";
        $db = DbUtil::loginConnection();

        $stmt = $db->stmt_init();

        if($stmt->prepare("select * from proj_job where title like ?") or die(mysqli_error($db))) {
                $searchString = '%';
                $stmt->bind_param(s, $searchString);
                $stmt->execute();
                $stmt->bind_result($job_id, $title, $description, $hrs, $wages, $location, $work_study, $name);
                
                while($stmt->fetch()) {
                        echo "<div class='card w-90'><div class='card-header'>$title</div><div class='card-body'><h5 class='card-title'>$name</h5><p class='card-text'>$description</br>Hourly Pay: $wages</p></div><a class='btn btn-primary btn-sm' href='GetJob.php?jid=$job_id' role='button'>More Information</a></div></br></br>" ;
                }
                $stmt->close();
        }

        $db->close();


?>