<?php
ob_start();
session_destroy();
session_start(); 

if (!isset($_SESSION['userId']))
{
  echo "<script type = \"text/javascript\">
  window.location = (\"../index.php\");
  </script>";
}
?>
