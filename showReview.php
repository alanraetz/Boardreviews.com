<?php

require('Common.inc.php');
require('HTML.inc.php'); # import $header and $footer HTML
include('class.template.inc');

if ( is_numeric($HTTP_GET_VARS[id]) ) {

     $query .= "SELECT * FROM reviewers au, reviews rv WHERE 
                au.author_id = rv.author_id AND 
                rv.review_id = $HTTP_GET_VARS[id]";     
} else {

     print "Could not find this review!\n";
     print $footer;
     exit;
}

# print "<p>Query = $query\n";

$result = mysql_query($query) or die("Unable to execute query $query.\n$footer");

$row_count = mysql_num_rows($result);

if ( $row_count == 0 ) {

     print "<p><b>Sorry, this review is missing! 
            <p><a href=\"javascript:history.go(-1)\">Go back</a> and 
                  try another!<p>$footer";
     exit;
} 

# should only get 1 row back

$row = mysql_fetch_assoc($result);

#$count = mysql_num_rows($result);
#print "found $count rows.\n";

# must extract hash to send to template
$first_name = $row[first_name];

if ( ! is_numeric($row[last_name]) ) {

     $last_name = $row[last_name];
}

# database entries inserted during the initial db load
# on 10/11/2003 are marked by setting datestamp = 0
if ( $row[datestamp] == 0 ) { 

     $submit_date = 0;
     
} else {

     $submit_date = date("F j, Y",$row[datestamp]);   
}

$age = date("Y") - $row[year_born];
$height = $row[height];
$weight = $row[weight];
$location = $row[location];
$favorite_spot = $row[favorite_spot];
$riding_style = $row[riding_style];
$years_exp = $row[years_exp];

if ( $row[hide_email] == 0 ) { $email = $row[email]; } 

$company = $row[company];
$model = $row[model];
$year = $row[year];
$size = $row[size];
$warranty = $row[warranty];

# preg_replace ( pattern, replacement, subject )
# Searches 'subject' for matches to 'pattern' and replaces them with 'replacement'
     
$review_text = preg_replace("#\\\#","",$row[review_text]);

$final_word = $row[final_word];
$rating = $row[rating_1] / 10;

if (0) {
     
    foreach( $row as $key=>$value) {
          
          print " $key = $value<br>";
    }
}

$tpl = new template;

$tpl->load_file('complete','review-template.html');

$tpl->parse_if('complete','submit_date,location,favorite_spot,riding_style,email,final_word');

$tpl->pprint('complete', 
                  'first_name,
                   submit_date,
                   last_name,
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

?>



