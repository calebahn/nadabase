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
    <title>Job Listings</title>
    <script>
      $(document).ready(function() {
        $("#jobinput").change(function() {
          $.ajax({
            url: "searchJob.php",
            data: { searchjob: $("#jobinput").val() },
            success: function(data) {
              $("#jobresult").html(data);
            }
          });
        });
      });
    </script>
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
    <a class="btn btn-primary btn-sm" href="jobsList.html" role="button">
      Browse Jobs
    </a>
    <div class="container">
      <div class="row">
        <!-- BEGIN SEARCH RESULT -->
        <div class="col-md-12">
          <div class="grid search">
            <div class="grid-body">
              <div class="row">
                <!-- BEGIN FILTERS -->
                <div class="col-md-3">
                  <h2 class="grid-title"><i class="fa fa-filter"></i> Filters</h2>
                  <hr>
    
                  <form method="post">
                  <?php 
                        require "dbutil.php";
                        session_start();
                        $db = DbUtil::logInUserB();
                        
                        $user = $_SESSION['user'];
                        //$user='cha4yw';
                        $stmt = $db->stmt_init();
                        $job_id = $_GET['jid'];
                        $backButton=$_GET['backButton'];
                        $review_count=0;
                        $starNumber = 0;

                        echo  "<label>Employer:<input list='employer' name='myEmployer' /></label><datalist id='employer'>";
                        if ($result = $db->query("SELECT * from proj_employer")) {
                                while($out = $result->fetch_row()) {
                                        $name=$out[0];
                                        $cat_word=$out[1]; 
                                        echo "<option value=$name>";
                                }
                        }
                        echo "</datalist>";

                        
                        echo  "<label>Job:<input list='job' name='myJob' /></label><datalist id='job'>";
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
                                echo "<option value=$title>";
                              }
                      }
                      
                       echo "</datalist>";


                       echo  "<label>Skill:<input list='skill' name='mySkill' /></label><datalist id='skill'>";

                //        if ($result = $db->query("SELECT * from proj_skills_required")) {
                //             while($out = $result->fetch_row()) {
                //               $job_id=$out[0];
                //               $skill_word=$out[1]; 
                //               echo "<option value=$skill_word>";
                //             }
                //         }

                        if ($result1 = $db->query("SELECT skill_word from proj_skills_required")) {
                                $i=0;
                                while($out1 = $result1->fetch_row()) {
                                  $sw=$sw . $out1[0] . ', ';
                                  $i=+1;
                                }
                                $sw=substr($sw, 0, -1);
                                echo "<option value=$sw>";
                              }
                  
                    
                        echo "</datalist>";

                           $db->close();
                          
                          
                        echo "<a class='btn btn-primary btn-sm' href='filteredSearch.php?jid=$job_id' role='button'>Apply filter</a>";
                        ?>
     
                        <!-- <a class="btn btn-primary btn-sm" href="searchJob.php" role="button">
                            Search
                        </a> -->
                  </form>
                </div>
                <!-- END FILTERS -->
                <!-- BEGIN RESULT -->
                <div class="col-md-9">
                  <h3>Search for a Job</h3>
                  <input
                    class="xlarge"
                    id="jobinput"
                    type="search"
                    size="30"
                    placeholder="Job"
                  />
            
                  <div id="jobresult"></div>
                  <hr>
                  <div class="padding"></div>
                  <div class="row">
                  </div>
    
                </div>
                <!-- END RESULT -->
              </div>
            </div>
          </div>
        </div>
        <!-- END SEARCH RESULT -->
      </div>
    </div>
    <!-- <center>
      <h3>Search for a Job</h3>
      <input
        class="xlarge"
        id="jobinput"
        type="search"
        size="30"
        placeholder="Job"
      />

      <div id="jobresult"></div>
    </center> -->
  </body>
</html>

