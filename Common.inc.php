<?php

# Add the following line to include this file:
# require('Common.inc.php');

$database = 1;

if ( $database == 0 ) {

     # laptop database
     $db_user = 'al_raetz';
     $db_pass = 'notheoriginalpassword';
     $db_name = 'boardreviews';

} elseif ( $database == 1 ) {

     # Host Matters Site
     $db_user = 'alraetz_alraetz';
     $db_pass = 'sorrythisisnotreal';
     $db_name = 'alraetz_reviewengine';

} elseif ( $database == 2 ) {

     # etc.
}

$db_object = mysql_connect("localhost",$db_user,$db_pass)
                    or die("Could not connect to database.\n");

mysql_select_db($db_name)
               or die("Could not select database $db_name.\n");


?>



