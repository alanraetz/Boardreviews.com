<?php

require('Common.inc.php');
require('HTML.inc.php'); # import $header and $footer HTML

$max_columns = 5;

show_company_list_page();

exit;

#################   END OF MAIN PROGRAM   ####################

function show_company_list_page() {

    $query = "SELECT DISTINCT company, count(*) co_count FROM reviews 
 
               WHERE approved = 1 GROUP BY company"; 

    $result = mysql_query($query) or die("<p>Sorry!");

    $big = array();
    $small = array();

    while ( $row = mysql_fetch_assoc($result) ) {

        if ( $row[co_count] < 4 ) { 

            $small[ $row[company] ] = $row[co_count];

        } else {

            $big[ $row[company] ] = $row[co_count];
        }
    }

#while ( list( $co, $text ) = each( $big ) ) { print "$co = $text<br>\n"; }
#exit;

    print '<center>
       <img src="whitrev.gif" width="288" height="72" align="middle">
       </center>';

    show_table($big,'Major','(companies with 20 or more reviews are highlighted in <b>bold</b>)', 5, 20);

    print "<HR>"; 

    show_table($small,'Smaller','(not all these are valid, we\'re behind on our housecleaning!)<p>', 1, 2);

    print $footer;
}


function show_table ($hash,$label,$desc,$padding,$show_bold) { 

  global $max_columns;
  ksort($hash);
  $entry_count = count($hash);
  $rows = $entry_count / $max_columns;
  $remainder = $entry_count % $max_columns;
  if ( $remainder ) { $rows++; }

  print "<center><h3>There are $entry_count $label Companies in our Database</h3>"; 

  print "<p><small>$desc</small>";

  print "<TABLE CELLSPACING=$padding CELLPADDING=$padding>";

  for ( $x=0; $x < $rows; $x++ ) {

     print "<TR>";

     for ( $y=0; $y < $max_columns; $y++ ) {

        do { list($company,$count) = each( $hash ); }

            while ( $company == 'not selected' );

        if ( $company && $count ) { 

            $s = ''; $e = '';

            if ( $count >= $show_bold ) { $s = '<b>'; $e = '</b>'; }

            print "<TD align=center>$s<nobr>&nbsp<a href=\"Search.php?company=" .

            urlencode($company) . "&count=" . $count 

            . "\">" .  $company . " ( " 

            . $count . " )</a>&nbsp</nobr>$e</TD>\n";

        } else {

             print "<TD>&nbsp</TD>";
        }
     }

     print "</TR>";

  }
   print "</TABLE>";
}

?>

