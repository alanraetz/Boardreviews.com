<?php

require('Common.inc.php');

function show_recent () {

    $query = show_recent_reviews();

    #print "<p>Query = $query\n";

    show_list_as_frames($query);
}

function show_recent_reviews () {

     $seven_days_ago = mktime(0, 0, 0, date("m"), date("d")-7, date("Y"));

     $query = "SELECT rv.review_id, 
                       rv.year,
                       rv.company, 
                       rv.model, 
                       rv.size, 
                       rv.datestamp,
                       rv.rating_1,
                       au.first_name,
                       au.last_name
                       
                FROM reviewers au, reviews rv WHERE 
                rv.approved = 1  AND 
                au.author_id = rv.author_id 

                ORDER BY rv.datestamp DESC

                LIMIT 20";

     return $query;
}

function show_list_as_frames ($query) {

     $result = mysql_query($query) or die("$header <p>Sorry, try another query! $footer");

     $row_count = mysql_num_rows($result);

     $list = "<TABLE align=center>";

     while ( $row = mysql_fetch_assoc($result) ) {

         if ( $row[size] < 100 ) { $row[size] = ''; }
     
         if ( $row[model] == $row[size] ) { $row[model] = ''; }
 
         $rating = round($row[rating_1] / 10);

         $submit_date = date("M j",$row[datestamp]);

         $list .= "<TR><TD>";

         $list .= "<center>$submit_date: <a href=\"showReview.php?id=$row[review_id]\"> $row[year] $row[company] $row[model] $row[size] </a> by $row[first_name] $row[last_name] <font color=\"red\">rating: $rating</font> 
        </a></center>";

         $list .= "</TD></TR>";
     }

     $list .= "</TABLE>";

     #$handle = fopen("./recentReviews.html","w");
     #fwrite($handle,$list);
     #fclose($handle);
     echo "$list"; 
}

?>
