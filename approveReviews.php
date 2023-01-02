<?php

require('Common.inc.php');
require('HTML.inc.php'); # import $header and $footer HTML

$author_id = $HTTP_COOKIE_VARS['boardreviews_user'];

$author_id = 'testing1234567';

if ( $author_id != 'testing1234567' ) { print "Exiting...<br>\n"; exit; }


print $header;

if ( $HTTP_POST_VARS[all_reviews] ) {

     $all_reviews = $HTTP_POST_VARS[all_reviews];

     if ( $HTTP_POST_VARS[approve_all_reviews] == "on" && preg_match("/\d/",$all_reviews) ) {
     
          $update = "UPDATE reviews SET approved = 1 WHERE review_id IN ( $all_reviews )";
          
          $result = mysql_query($update) or die("Unable to execute: $update<br>\n" . mysql_error());
          
          print "<p><b>Approved these review IDs = $all_reviews</b>\n";

          print $footer;
     
          exit;
     }

     $ids_to_approve = array();

     $ids_to_delete = array();

     foreach( $HTTP_POST_VARS as $key=>$value) {

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

     print $footer;
}


?>



