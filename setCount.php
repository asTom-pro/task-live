<?php
require_once('function.php');
if(!empty($_POST)){
  setLogs(sanitize($_POST['uri']), sanitize($_POST['ipaddress'])); 
}