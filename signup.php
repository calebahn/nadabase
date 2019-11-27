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

    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">

    <style>
      body {
        /* <!-- background-color: #F3EBF6; --> */
        font-family: 'Ubuntu', sans-serif;
      }

      .main {
          background-color: #FFFFFF;
          width: 400px;
          height: 500px;
          margin: 7em auto;
          border-radius: 1.5em;
          box-shadow: 0px 11px 35px 2px rgba(0, 0, 0, 0.14);
      }

      .sign {
          padding-top: 40px;
          color: #8C55AA;
          font-family: 'Ubuntu', sans-serif;
          font-weight: bold;
          font-size: 23px;
      }

      .un {
      width: 76%;
      color: rgb(38, 50, 56);
      font-weight: 700;
      font-size: 14px;
      letter-spacing: 1px;
      background: rgba(136, 126, 126, 0.04);
      padding: 10px 20px;
      border: none;
      border-radius: 20px;
      outline: none;
      box-sizing: border-box;
      border: 2px solid rgba(0, 0, 0, 0.02);
      margin-bottom: 50px;
      margin-left: 46px;
      text-align: center;
      margin-bottom: 27px;
      font-family: 'Ubuntu', sans-serif;
      }

      form.form1 {
          padding-top: 30px;
      }

      .pass {
              width: 76%;
      color: rgb(38, 50, 56);
      font-weight: 700;
      font-size: 14px;
      letter-spacing: 1px;
      background: rgba(136, 126, 126, 0.04);
      padding: 10px 20px;
      border: none;
      border-radius: 20px;
      outline: none;
      box-sizing: border-box;
      border: 2px solid rgba(0, 0, 0, 0.02);
      margin-bottom: 50px;
      margin-left: 46px;
      text-align: center;
      margin-bottom: 27px;
      font-family: 'Ubuntu', sans-serif;
      }

      .lname {
              width: 76%;
      color: rgb(38, 50, 56);
      font-weight: 700;
      font-size: 14px;
      letter-spacing: 1px;
      background: rgba(136, 126, 126, 0.04);
      padding: 10px 20px;
      border: none;
      border-radius: 20px;
      outline: none;
      box-sizing: border-box;
      border: 2px solid rgba(0, 0, 0, 0.02);
      margin-bottom: 50px;
      margin-left: 46px;
      text-align: center;
      margin-bottom: 27px;
      font-family: 'Ubuntu', sans-serif;
      }

      .fname {
              width: 76%;
      color: rgb(38, 50, 56);
      font-weight: 700;
      font-size: 14px;
      letter-spacing: 1px;
      background: rgba(136, 126, 126, 0.04);
      padding: 10px 20px;
      border: none;
      border-radius: 20px;
      outline: none;
      box-sizing: border-box;
      border: 2px solid rgba(0, 0, 0, 0.02);
      margin-bottom: 50px;
      margin-left: 46px;
      text-align: center;
      margin-bottom: 27px;
      font-family: 'Ubuntu', sans-serif;
      }

      .un:focus, .pass:focus .fname:focus .lname:focus {
          border: 2px solid rgba(0, 0, 0, 0.18) !important;
      }

      .submit {
        cursor: pointer;
          border-radius: 5em;
          color: #fff;
          background: linear-gradient(to right, #9C27B0, #E040FB);
          border: 0;
          padding-left: 40px;
          padding-right: 40px;
          padding-bottom: 10px;
          padding-top: 10px;
          font-family: 'Ubuntu', sans-serif;
          margin-left: 35%;
          font-size: 13px;
          box-shadow: 0 0 20px 1px rgba(0, 0, 0, 0.04);
      }

      .submit:hover {
        background: linear-gradient(to right, rgb(122, 32, 138), #9C27B0);
      }

      .forgot {
          text-shadow: 0px 0px 3px rgba(117, 117, 117, 0.12);
          color: #E1BEE7;
          padding-top: 15px;
      }

      .signup {
          text-shadow: 0px 0px 3px rgba(117, 117, 117, 0.12);
          color: #E1BEE7;
          margin-top: -1em;
      }

      a {
          text-shadow: 0px 0px 3px rgba(117, 117, 117, 0.12);
          color: #E1BEE7;
          text-decoration: none
      }

      @media (max-width: 600px) {
          .main {
              border-radius: 0px;
          }
      }

    </style>


    <title>Job Description</title>
  </head>
  <body>
    <div id='navbar'>
    <script>
        var el = document.getElementById('navbar');
        var loginStatus = sessionStorage.getItem("login_status");
        var content;

        if (loginStatus=="true") {
            content = "<div id='cssmenu'><ul><li><a href='index.html'>Home</span></a></li><li><a href='jobsList.html'><span>Browse Jobs</span></a></li><li><a href='searchJobs.html'><span>Search Jobs</span></a></li><li class='last'><a href='logout.php'><span>Logout</span></a></li></ul></div>";
        }
        else {
            content = "<div id='cssmenu'><ul><li><a href='index.html'>Home</span></a></li><li><a href='login.html'><span>Login</span></a></li></ul></div>";
        }

        el.insertAdjacentHTML('afterbegin', content);

    </script>
    </div>
    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <h1 class="display-4">Welcome to [insert app name here]</h1>
        <p class="lead">
          This is a platform for people to search for on-grounds jobs and leave
          reviews!
        </p>
      </div>
    </div>
<?php
        session_start();
        require "dbutil.php";
        $db = DbUtil::newUser();


        $first = $_POST["fname"];
        $last = $_POST["lname"];
        $cid = $_POST["cid"];
        $pass = $_POST["password"];

        /*
        echo "<script>console.log('first:: " . $first . "' );</script>";
        echo "<script>console.log('last:: " . $last . "' );</script>";
        echo "<script>console.log('cid:: " . $cid . "' );</script>";
        echo "<script>console.log('Pass:: " . $pass . "' );</script>";
        */

        if (mysqli_num_rows($db->query("SELECT cid from proj_usertable WHERE cid='$cid'"))!=0){
            echo "<center><h3>You seem to already have an account!</h3><a class='btn btn-primary btn-sm' href='login.html' role='button'>Login Here</a></center>";
          }
        else {
            if($db->query("INSERT INTO proj_usertable VALUES ('$cid', '$pass', 'student')")){
                if($db->query("INSERT INTO proj_student VALUES ('$cid', '$first', '$last')")){
                    $_SESSION['login_status'] = true;
		?>
                <script type="text/javascript">
                  sessionStorage.setItem("login_status", "true");
                </script>
                <?php
                    $_SESSION['user'] = $cid;
                    $_SESSION['role'] = "student";
                    ?>
                  <script type = "text/javascript">
				              window.location.replace("jobsList.html");
			            </script>
                  <?php
                }
                else {
                  echo "<center>
              <h3>Something went wrong!</h3>
              <a class='btn btn-primary btn-sm' href='index.html' role='button'>Return Home</a>
            </center>";
                    //echo "<script>console.log('Error 1' );</script>";
                    //echo mysqli_error($db);
                }
            }
            else {
              echo "<center>
              <h3>Something went wrong!</h3>
              <a class='btn btn-primary btn-sm' href='index.html' role='button'>Return Home</a>
            </center>";
                //echo "<script>console.log('Error 2' );</script>";
                //echo mysqli_error($db);
            }
        }

        $db->close();
?>
    </body>
</html>
