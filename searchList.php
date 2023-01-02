<?php

require('Common.inc.php');
require('HTML.inc.php'); # import $header and $footer HTML

$query = "SELECT html FROM search_queue WHERE hash_id = '$HTTP_GET_VARS[search_id]'";
          
$result = mysql_query($query) or die("$header " . mysql_error() . "<p>Unable to execute your search.\n$footer");

$row_count = mysql_num_rows($result);

if ( $row_count == 0 ) {

     print "<p><b>Sorry, there weren't any reviews for your search.
            <p><a href=\"javascript:history.go(-1)\">Go back</a> and 
                  try again!<p>$footer";
     exit;
     
} 

$row = mysql_fetch_array($result);

print $black_header . $row[0] . $black_footer;


?>



