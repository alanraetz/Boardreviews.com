<?php

require('Common.inc.php');
require('HTML.inc.php'); # import $header and $footer HTML

$vars = array();

foreach( $HTTP_POST_VARS as $key=>$value) {

    $vars[$key] = $value;
    # print " $key = $value<br>";
}

$current_datestamp = time();

print $header;

print "<FORM METHOD=POST ACTION=\"editReview.php?action=submit\">";

# print $html;


if ( $vars[action] ) {

     # UPDATE database with changes

} elseif ( $vars[review_id] ) {

     print "<center><h2>Edit Review $vars[review_id]</h2></center><hr>\n";

     $result = mysql_query(
     
          "SELECT 
                  au.first_name,
                  au.last_name,
                  au.height,
                  au.weight,
                  au.age,
                  au.years_exp,
                  au.location,
                  au.favorite_spot,
                  au.email,
                  au.riding_style,
                  au.image_url,
                  rv.datestamp,
                  rv.product,
                  rv.company,
                  rv.model,
                  rv.year,
                  rv.size,
                  rv.warranty,
                  rv.rating_1,
                  rv.review_text,
                  rv.final_word                  
                  
                  FROM reviews rv

          LEFT JOIN reviewers au ON (rv.author_id = au.author_id)
          
          WHERE rv.review_id = $vars[review_id]"
                    
          ) or die ("Query failed: " . mysql_error() . "<br>\n");
     
     }

     $row = mysql_fetch_assoc($result); # only one row! 

     $date = date ("F dS Y (l) h:i:s A",$row[datestamp]);
     
     print "<p><b>$date &nbsp &nbsp </b>\n";
     
     unset( $row['datestamp'] );
     
     foreach( $row as $key=>$value ) {

          echo("$key: $value <b>//</b> ");
     }
     
          echo("<br>\n");
     
     
     echo("<HR>\n");
}

$ids = join(",",$list_of_ids);

print "</FORM>" . $footer;

?>



