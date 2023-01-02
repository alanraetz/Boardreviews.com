<?php

require('Common.inc.php');

$query = "SELECT author_id, year_started FROM reviewers";

$result = mysql_query($query) or die("Unable to execute query $query.\n$footer");

print "Starting loop...<p>";

while ( $row = mysql_fetch_assoc($result) ) {

     $update = "UPDATE reviews SET years_exp = (2003 - $row[year_started])
     
               WHERE author_id = $row[author_id]";

     mysql_query($update) or die("Failed on author_id = $row[author_id]," . mysql_error());               
     
     print "updated author_id = $row[author_id]<br>";
}

print "<p>Done!";

?>