<?php

require('Common.inc.php');
require('HTML.inc.php'); # import $header and $footer HTML

$vars = array();

foreach( $HTTP_POST_VARS as $key=>$value) {

    $vars[$key] = $value;
    # print " $key = $value<br>";
}

#
# Do some database cleanup here: remove old preview entries
#
$current_datestamp = time();

$delete = "DELETE FROM reviews 

          WHERE approved = 9999 AND (datestamp < $current_datestamp-7200)";
          
mysql_query($delete) or die("Unable to execute: $delete<br>\n" . mysql_error());


print $header;

if ( $vars[all_reviews] ) {

     ###########################################################
     # PROCESS PHP FORM SELECTIONS AND APPROVE/DELETE REVIEWS
     ###########################################################

     print "<center><h2>Updating database...</h2></center><hr>\n";

     $all_reviews = $vars[all_reviews];

     if ( $vars[approve_all_reviews] == "on" && preg_match("/\d/",$all_reviews) ) {
     
          $update = "UPDATE reviews SET approved = 1 WHERE review_id IN ( $all_reviews )";
          
          $result = mysql_query($update) or die("Unable to execute: $update<br>\n" . mysql_error());
          
          print "<p><b>Approved these review IDs = $all_reviews</b>\n";

          print $footer;
     
     } else {

          $ids_to_approve = array();

          $ids_to_delete = array();

          foreach( $vars as $key=>$value) {

               if ( preg_match("/^\d+/",$key) ) { 
     
                    if ( $value == "1" ) {
          
                         $ids_to_approve[] = $key;               
                    } 
     
                    if ( $value == "0" ) {
          
                         $ids_to_delete[] = $key;               
                    } 
              }
          
              $approval_list = join(",",$ids_to_approve);
    
              $delete_list = join(",",$ids_to_delete);
          }

          $current_datestamp = time();

          if ( $approval_list != "" ) {

               $result = mysql_query("

                    UPDATE reviews SET approved = 1
     
                    WHERE review_id IN ( $approval_list )
     
                    ") or die("<p>Unable to approve ids $approval_list!");

               print "<p><b>Approved these review IDs = $approval_list</b>\n";
          }

          if ( $delete_list != "" ) {

               $result = mysql_query("

                    DELETE FROM reviews WHERE review_id IN ( $delete_list )
     
                    ") or die("<p>Unable to delete IDs $delete_list!");

               print "<p><b>Deleted these review IDs = $delete_list</b>\n";
          }

          print "<p><b>Done processing all changes.</b><hr><p>\n";
     }

}

#
# DISPLAY LIST OF REVIEWS TO BE APPROVED
#

print "<FORM METHOD=POST ACTION=\"checkReviews.php\">";

# print $html;

if ( ! $vars[all_reviews] ) {     
     print "<center><h2>Reviews to be Approved</h2></center><hr>\n";
}

$result = mysql_query(
     
          "SELECT rv.review_id,
                  au.first_name,
                  au.last_name,
                  au.height,
                  au.weight,
                  au.year_born,
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
          
          WHERE rv.approved = 0
          
          ORDER BY rv.datestamp DESC"
          
     ) or die ("Query failed: " . mysql_error() . "<br>\n");
     
if ( mysql_num_rows($result) == 0 ) {

     print "<br><br><center><h3>All reviews have been approved.</h2>
  
     <br><br><hr>\n";
     
     exit;

} else { 

     print "

     <p><b>Approve All Reviews Shown <input type=checkbox name=\"approve_all_reviews\"></b>

     <input type=\"submit\" value=\"Submit\" >
     
     <p>";
}

$list_of_ids = array();

while ( $row = mysql_fetch_assoc($result) ) {

     $list_of_ids[] = $row[review_id];
     
     if ( is_numeric($row[last_name]) ) { $row[last_name] = "&lt; none &gt;"; }

     $date = date ("F dS Y (l) h:i:s A",$row[datestamp]);
     
     print "<p><b>$date &nbsp &nbsp </b>\n";
     
     unset( $row['datestamp'] );
     
     foreach( $row as $key=>$value ) {
     
          echo("$key: $value <b>//</b> ");
     }
     
     echo("<p>\n");
     
     #print "<a href=\"editReview.php?this_id=$row[review_id]\"> Edit Review</a> ";
     print "&nbsp;&nbsp;";
     print " Approve <input type=\"radio\" name=\"$row[review_id]\" value=\"1\" > ";
     print " &nbsp &nbsp Delete<input type=\"radio\" name=\"$row[review_id]\" value=\"0\"> ";
     print " &nbsp &nbsp Leave Here<input type=\"radio\" name=\"$row[review_id]\" value=\"99\"> ";
          
     echo("<HR>\n");
}

$ids = join(",",$list_of_ids);

print "<p><b>Approve All Reviews Shown <input type=checkbox name=\"approve_all_reviews\"></b>

     <input type=\"hidden\" name=\"all_reviews\" value=\"$ids\" >
     
     <input type=\"submit\" value=\"Submit\" >
     
     <p>";

print "</FORM>" . $footer;

?>