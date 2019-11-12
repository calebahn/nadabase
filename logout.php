<?php
session_start();
$_SESSION["login_status"]!=false;
?>
<script type = "text/javascript">
    sessionStorage.setItem("login_status", "false");
    window.location.replace("login.html");
</script>
