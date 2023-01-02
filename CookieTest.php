<?php

$value = 'something from somewhere';

setcookie ("TestCookie", $value,time()+3600);  /* expire in 1 hour */

// Print an individual cookie


echo $HTTP_COOKIE_VARS["TestCookie"];

?> 