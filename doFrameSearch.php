<?php

require('Common.inc.php');
require('HTML.inc.php'); # import $header and $footer HTML

if (0) {

  $vars = array();
     
  foreach( $HTTP_POST_VARS as $key=>$value) {

     $vars = $value;
     
     if ( is_array($value) ) {
    
          print " $key = " . join(", ",$value) . "<br>";
          
     } else {
          
          print " $key = $value<br>";
     }
  }
}

if ( is_array($HTTP_POST_VARS[select]) ) {

     $query .= "SELECT rv.review_id, 
                       rv.year,
                       rv.company, 
                       rv.model, 
                       rv.size, 
                       au.first_name,
                       au.last_name
                       
                FROM reviewers au, reviews rv WHERE 
                rv.approved = 1  AND 
                au.author_id = rv.author_id AND (";

     $first = 1;
     
     foreach( $HTTP_POST_VARS[select] as $value) {
     
          # $co = mysql_escape_string($value);
          
          $co = $value;

          if ( $first == 1 ) { $first = 0; } else { $query .= " OR "; }
          
          $query .= "rv.company = '$co' ";          
     }

     # preg_replace ( pattern, replacement, subject )
     # Searches 'subject' for matches to 'pattern' and replaces them with 'replacement'
     #     $query = preg_replace("/OR $/","",$query);
     
     $query .= ") ";
     
} else {

     print $header;
     print "You must select at least one company\n";
     print $footer;
     exit;
}

if ( strlen($HTTP_POST_VARS[product]) > 0 ) {

     $product = mysql_escape_string($HTTP_POST_VARS[product]);
     
     $query .= " AND rv.product = '$product' ";

} else {

     print $header;
     print "You must select at least one product type\n";
     print $footer;
     exit;
}

if ( strlen($HTTP_POST_VARS[year_start]) > 0 && strlen($HTTP_POST_VARS[year_end]) > 0 ) {

     $query .= " AND rv.year >= $HTTP_POST_VARS[year_start] AND rv.year <= $HTTP_POST_VARS[year_end] ";
}
if ( strlen($HTTP_POST_VARS[min_length]) > 0 && strlen($HTTP_POST_VARS[max_length]) > 0 ) {

     $query .= " AND rv.size >= $HTTP_POST_VARS[min_length] AND rv.size <= $HTTP_POST_VARS[max_length] ";
}

if ( is_numeric($HTTP_POST_VARS[min_rating]) && is_numeric($HTTP_POST_VARS[max_rating]) ) {

     $min_rating = intval($HTTP_POST_VARS[min_rating] * 10);
     $max_rating = intval($HTTP_POST_VARS[max_rating] * 10);

     $query .= " AND rv.rating_1 >= $min_rating AND 
                     rv.rating_1 <= $max_rating ";
}

if ( is_numeric($HTTP_POST_VARS[min_exp]) && is_numeric($HTTP_POST_VARS[max_exp]) ) {

     $query .= " AND au.years_exp >= $HTTP_POST_VARS[min_exp] AND au.years_exp <= $HTTP_POST_VARS[max_exp] ";

}

if ( is_numeric($HTTP_POST_VARS[min_age]) && is_numeric($HTTP_POST_VARS[max_age]) ) {

     $query .= " AND au.age >= $HTTP_POST_VARS[min_age] AND au.age <= $HTTP_POST_VARS[max_age] ";
}

if ( is_numeric($HTTP_POST_VARS[min_weight]) && is_numeric($HTTP_POST_VARS[max_weight]) ) {

     $query .= " AND au.weight >= $HTTP_POST_VARS[min_weight] AND au.age <= $HTTP_POST_VARS[max_weight] ";
}

if ( !isset($HTTP_POST_VARS[male])  ) {

     $query .= " AND au.sex = 'female' ";
}

if ( !isset($HTTP_POST_VARS[female]) ) {

     $query .= " AND au.sex = 'male' ";
}

$query .= " ORDER BY rv.company, rv.model, rv.size DESC, rv.year DESC ";

# print "<p>Query = $query\n";

$result = mysql_query($query) or die("$header Unable to execute query.\n$footer");

$row_count = mysql_num_rows($result);

if ( $row_count == 0 ) {

     error_exit("<b>Sorry, there weren't any reviews matching your search.</b>");

} else {

     $search_list = "<center><h3>$row_count Reviews Found</h3>
     
     <p><a href=\"searchForm.php\" target=\"_top\"><font size=\"-1\">New Search </font></a></center>";
}

while ( $row = mysql_fetch_assoc($result) ) {

     if ( $row[size] < 100 ) { $row[size] = ''; }
     
     if ( $row[model] == $row[size] ) { $row[model] = ''; }

     $search_list .= "<p><a href=\"showReview.php?id=$row[review_id]\" target=\"reviewWindow\"> $row[company] $row[model] $row[size] ($row[year]) </a> ";

}

$current_time = time();

$search_id = md5( $current_time . $search_list );

# $search_list = mysql_escape($search_list);

$insert = "INSERT INTO search_queue VALUES ( NULL, '$search_id', '$search_list', $current_time )";

mysql_query($insert) or die("$header Unable to insert search list.\n$footer");

# print "<p>" . mysql_error();

if (0) { # show banners based on what company was searched for

  $company_query = "SELECT ov.banner_html 
                  
                  FROM companies c, company_banners cb, online_vendors ov
                  
                  WHERE c.company IN ('$row[company]')
                  
                  AND cb.company_id = c.company_id
                  
                  AND cb.vendor_id = ov.vendor_id
                  
                  ";

  $banners = mysql_query($company_query);

  if ( mysql_num_rows($banners) > 0 ) {

     # create a showBanner.php?list_id=xxxx 
     # and place a link below instead of showing choose.htm
  }
}

print <<<FRAME
<html><head><title>Board Reviews Search Results</title></head>
<frameset COLS="25%,75%">
<frame NAME="reviewList" SRC="searchList.php?search_id=$search_id">
<frame NAME="reviewWindow" SRC="choose.htm">
</frameset>
FRAME;

$one_hour_ago = $current_time - 3600;

$delete = "DELETE FROM search_queue WHERE timestamp < $one_hour_ago";

mysql_query($delete);

exit;

function error_exit ($error_msg) {   

     global $header;
     global $footer;

     print $header; # $header and $footer from HTML.inc.php
     
     print "<p><center><img src=\"BoardReviewsLogo.gif\" width=\"288\" height=\"72\"></center></p>";
   
     echo("<p><center><h2>$error_msg</h2></center>");

     echo("<p><center><a href=\"javascript:history.go(-1)\">Click Here To Try Again!</a></center><p>");
   
     print $footer;
      
     exit;
}

?>



