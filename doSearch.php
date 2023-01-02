<?php

require('Common.inc.php');
require('HTML.inc.php'); # import $header and $footer HTML

print $header;

if (0) {

  $vars = array();
     
  foreach( $HTTP_POST_VARS as $key=>$value) {

     $vars = $value;
     
     if ( is_array($value) ) {
    
          #print " $key = " . join(", ",$value) . "<br>";
          
     } else {
     
          # print " $key = $value<br>";
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
                au.author_id = rv.author_id AND (";

     $first = 1;
     
     foreach( $HTTP_POST_VARS[select] as $value) {
     
          $co = mysql_escape_string($value);

          if ( $first == 1 ) { $first = 0; } else { $query .= " OR "; }
          
          $query .= "rv.company = '$co' ";          
     }

     # preg_replace ( pattern, replacement, subject )
     # Searches 'subject' for matches to 'pattern' and replaces them with 'replacement'
     #     $query = preg_replace("/OR $/","",$query);
     
     $query .= ") ";
     
} else {

     print "You must select at least one company\n";
     print $footer;
     exit;
}

if ( strlen($HTTP_POST_VARS[product]) > 0 ) {

     $product = mysql_escape_string($HTTP_POST_VARS[product]);
     
     $query .= " AND rv.product = '$product' ";

} else {

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

$query .= " ORDER BY rv.company, rv.model, rv.year DESC, rv.size DESC";

print "<p>Query = $query\n";

$result = mysql_query($query);

print("$result, Unable to execute query $query.\n$footer");

######## PRINT OUTPUT ############

print $header;

print "<center>\n";

$row_count = mysql_num_rows($result);

if ( $row_count == 0 ) {

     print "<p><b>Sorry, there weren't any reviews for your search.
            <p><a href=\"javascript:history.go(-1)\">Go back</a> and 
                  try again!<p>$footer";
     exit;
} else {

     print "<P><b>Found $row_count reviews:</b>\n";
}

while ( $row = mysql_fetch_assoc($result) ) {

     if ( $row[size] < 100 ) { $row[size] = ''; }

     print "<p><a href=\"showReview.php?id=$row[review_id]\">
     
              $row[year] $row[company] $row[model] $row[size] by $row[first_name] $row[last_name]
              
              </a>
              
              ";

}

print "</center>\n";

print $footer;

?>



