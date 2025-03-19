<?php

include('../../database.php');

$con = mysqli_connect($hostname, $username, $password, $database);
//Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
//define('PAYTM_MERCHANT_KEY', '1&RrZJZhEyxmYBr7');
$row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM pg_detail"));
define('PAYTM_MERCHANT_KEY', $row['mkey']);

define('PAYTM_MERCHANT_ID', $row['mid']);

?>
