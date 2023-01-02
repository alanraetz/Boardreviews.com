<?php

require('Common.inc.php');
require('HTML.inc.php');
include('class.template.inc');

$vars = array();

foreach( $HTTP_POST_VARS as $key=>$value) {

    $vars[$key] = $value;
    # print " $key = $value<br>";
}

if ( strlen($vars[review_id]) == 32 ) {

     # only execute this section after final submit by reviewer

     $update = "UPDATE reviews SET approved = 0 WHERE review_hash = '$vars[review_id]'";
     
     mysql_query($update) or error_exit("<p>".$update."<p>".mysql_error()); 
     
print <<<HTML
     <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
     <HTML>
     <HEAD>
          <TITLE>Thanks!</TITLE>
     </HEAD><STYLE TYPE="text/css">
     <!--
     A:hover { color:#FF0000; }
     A:link { color:#0080C0; }
     -->
     </STYLE> 
     
     <BODY BGCOLOR="#000000" TEXT="#FFFF80" LINK="#0080C0" VLINK="#808040" ALINK="#FF0000"><CENTER>
     <H3>Your Review will be posted soon!</H3>
     <IMG SRC="thanks.JPG" WIDTH=504 HEIGHT=504 BORDER=0  ALIGN="MIDDLE" ALT="thank you">
     <br>
     <A HREF="http://www.boardreviews.com" NAME="back"><H2>Back to main page</H2></A><BR>
     </BODY>
     </HTML>
HTML;

     exit;
}

if ( strlen($vars[first_name]) == 0 || 
     strlen($vars[sex]) == 0 ||
     strlen($vars[height]) == 0 ||
     strlen($vars[weight]) == 0 ||
     strlen($vars[model]) == 0 ||
     strlen($vars[size]) == 0 ||
     strlen($vars[review_text]) == 0 
   ) {
   
     error_exit("Name, Age, Sex, Height, Weight, Years Experience <br>
       Company, Model, Size and Review information are <b>Required</b>");
}


if (!is_numeric($vars[year_born]) || strlen($vars[year_born]) != 4) {

     error_exit("Hello! Year born must be a four digit number.");
}

if ( !is_numeric($vars[size]) || strlen($vars[size]) <= 2 ) {

     error_exit("You must know the length of the board in centimeters to submit 
     a review. To convert from inches to centimeters, multiply by 2.54.");
}

if ( strlen($vars[company]) == 0 
          || $vars[company] == "not_selected"
          || $vars[company] == "not_listed" ) {

     if ( strlen($vars[company_fill_in]) == 0) {

          error_exit("You must know the company name to submit a product review,
                         use the fill-in text area if it is not listed.");
          
     } else { 

          $vars[company] = $vars[company_fill_in];
     }
} 

if (!is_numeric($vars[years_exp]) ) {

     error_exit("Years experience must be a number.");
}

if (!is_numeric($vars[year]) || strlen($vars[year]) != 4) {

     $vars[year] = date("Y");
}

$next_year = date("Y") + 1;

if ( $vars[year] > $next_year 

          || ( date("Y") - $vars[year_born] <= $vars[years_exp] )

              || $vars[year_born] > $next_year 
          
                 || $vars[year_born] < 1900 ) {
 
     error_exit("Put down the crack pipe!");
}

if ( strlen($vars[review_text]) < 70 ) {

     error_exit("Can you say a little bit more about this board? <p>Your review is really short... ");
}

if ( $vars[spam_me] == 'on' ) {
          
     $spam_me = 1;
               
} else {
          
     $spam_me = 0;
}

$current_datestamp = time();

# Insert reviewer into reviewer table:
#    - If there is no last name, create a random number for last name
#    - If the first+last name match a previous reviewer, update that entry
#    - If the first+last name do not match, add a new entry
#    - Must pass an author_id number to reference a new line in reviews table

if ( $vars[last_name] == '' ) {

     $vars[last_name] = rand(1,10000);

} else {

     $check_user = mysql_query(
     
          "SELECT author_id, cookie_id FROM reviewers 
               
          WHERE first_name = '$vars[first_name]'
          
          AND last_name = '$vars[last_name]'"
     );
}

$new_id = md5($current_datestamp . $vars[first_name] . $vars[review_text]);

if ( $check_user && $row_array = mysql_fetch_array($check_user) ) {
          
     $author_id = $row_array[0]; 
     $cookie_id = $row_array[1] || $new_id; 
          
     $update = "UPDATE reviewers 
          
               SET height = '$vars[height]',
                   weight = '$vars[weight]',
                   location = '$vars[location]',
                   favorite_spot = '$vars[favorite_spot]',
                   email = '$vars[email]',
                   spam_me = $spam_me,
                   last_access = $current_datestamp
                   
             WHERE author_id = $author_id";
             
             mysql_query($update) or error_exit("Update Reviewers Failed<p>"+mysql_error());                   
          
} else { # new reviewer or no last name, so add new entry to reviewers table
                                   
    $insert = "INSERT INTO reviewers VALUES ( NULL,
                                          '$new_id',
                                          '$vars[first_name]',
                                          '$vars[last_name]',
                                           $vars[year_born],
                                          '$vars[sex]',
                                          '$vars[height]',
                                          '$vars[weight]',
                                          '$vars[location]',
                                          '$vars[favorite_spot]',
                                          '$vars[email]',
                                          $spam_me,
                                          '$vars[riding_style]',
                                          '$vars[image_url]',
                                           0,
                                          $current_datestamp,
                                          $current_datestamp
                                          )";

     mysql_query($insert) or error_exit("Unable to add user<br>$insert<br>.\n");
          
     $author_id = mysql_insert_id();                    
}
     
#
# Now insert new review into "reviews" table using $author_id
#

if ( $vars[hide_email] == 'on' ) { $hide_email = 1; } else { $hide_email = 0; }

if ( $vars[email_comments] == 'on' ) { $email_com = 1; } else { $email_com = 0; }


# rating is stored * 10 to get a 1/10 decimal point as an integer

$rating = $vars[rating_1] * 10; 
if ( $rating > 100 ) { $rating = 100; }
$rating = intval($rating);

$review_hash = $new_id;

$insert = "INSERT INTO reviews VALUES ( NULL,
                                        $author_id,
                                        '$review_hash',
                                        '$vars[years_exp]',
                                        $current_datestamp,
                                        '$vars[product]',
                                        '$vars[company]',
                                        '$vars[model]',
                                        '$vars[year]',
                                        '$vars[size]',
                                        '$vars[warranty]',
                                        $rating,
                                        0,
                                        0,
                                        '$vars[review_text]',
                                        '$vars[final_word]',
                                        $hide_email,
                                        $email_com,
                                        9999,
                                        0,
                                        0
                                        )";

# echo("<p>insert = $insert <br>\n");

$result = mysql_query($insert) or error_exit("Unable to insert this review<p>\n".mysql_error());

$review_id = mysql_insert_id();


# Done with database insert, marked review table "approved" field as 9999
# (not yet approved by submitter)--can either go back and re-submit, or
# approve, using cookie_id to obfuscate the table entry number.

# Display the review using the form information:

     $first_name = $vars[first_name];

     if ( ! is_numeric($vars[last_name]) ) {

          $last_name = $vars[last_name];
     }

     $submit_date = date("F j, Y");   

     $this_year = date("Y");
     $age = $this_year - $vars[year_born];
     $weight = $vars[weight];
     $location = $vars[location];
     $favorite_spot = $vars[favorite_spot];
     $riding_style = $vars[riding_style];
     $years_exp = $vars[years_exp];

     if ( $row[hide_email] == 0 ) { $email = $row[email]; } 

     $company = $vars[company];
     $model = $vars[model];
     $year = $vars[year];
     $size = $vars[size];
     $warranty = $vars[warranty];

     # preg_replace ( pattern, replacement, subject )
     # Searches 'subject' for matches to 'pattern' and replaces them with 'replacement'
     
     $review_text = preg_replace("#\\\#","",$vars[review_text]);
     
     $height = preg_replace("#\\\#","",$vars[height]);

     $final_word = $vars[final_word];
     
     $rating = $vars[rating_1];

     if (0) {
     
         foreach( $row as $key=>$value) {
          
               print " $key = $value<br>";
         }
     }

     $tpl = new template;

     $tpl->load_file('complete','review-template.html');

     $tpl->parse_if('complete','submit_date,location,favorite_spot,riding_style,email,final_word');

     print "<p><center><h3>Preview -- Click \"Approve Review\" below to submit. </h3></center>";
     
     print "<p><center><img src=\"blue.gif\" width=\"600\" height=\"5\"></center>";
     
     print "<FORM METHOD=POST ACTION=\"submitReview.php\">";

     $tpl->pprint('complete', 
                  'first_name,
                   last_name,
                   submit_date,
                   age,
                   height,
                   weight,
                   location,
                   favorite_spot,
                   riding_style,
                   years_exp,
                   email,
                   company,
                   model,
                   year,
                   size,
                   warranty,
                   review_text,
                   final_word,
                   rating');

     print "<p><center><img src=\"blue.gif\" width=\"600\" height=\"5\"></center>";

     print "<input type=\"hidden\" name=\"review_id\" value=\"$review_hash\">";

     print "<p><center><input type=\"submit\" value=\"Approve Review\"</center>";

     print "<p><center><a href=\"javascript:history.go(-1)\">Click Here to Edit Again.</a></center><p>";
     
     print "</FORM>";

     print $footer;

     mail("al_raetz@yahoo.com", "submitReview okay $vars[company] $vars[model] $vars[year]", $review_text);
     
     exit;

function error_exit ($error_msg) {   


     # mail("al_raetz@yahoo.com", "submitReview error", "$error_msg");
     
     global $header;
     global $footer;

     print $header; # $header and $footer from HTML.inc.php
   
     echo("<br><center><h1>Form Error</h1></center>");

     echo("<p><center><h2>$error_msg</h2></center><p>");

     echo("<p><center><a href=\"javascript:history.go(-1)\">Click Here to go back to the Form</a></center><p>");
   
     print $footer;
      
     exit;
}

?>
