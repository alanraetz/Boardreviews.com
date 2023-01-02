<?php

$author_id = $HTTP_COOKIE_VARS['boardreviews_user'];

if ( isset($author_id) ) {

     # laptop database
     $db_user = 'al_raetz';
     $db_pass = 'revenge';

     $db_object = mysql_connect("localhost",$db_user,$db_pass)
                    or die("Could not connect to database.\n");

     mysql_select_db("boardreviews")
               or die("Could not select database boardreviews.\n");

     $result = mysql_query(
     
          "SELECT * FROM reviewers WHERE author_id = $author_id"
    
     ) or die("Unable to execute SELECT author_id<br>.\n");

     $user = mysql_fetch_assoc($result);
     
     echo("<p>Welcome back, $user[first_name]<br>\n");
}

$html = <<<SNOWFORM
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>
     <TITLE>Snowboard Reviews Entry Page</TITLE>
</HEAD>
<BODY BGCOLOR="#EFEFEF" LINK="#0000FF">
<table width=700 align="center"><tr><td width=40><br></td></tr>
<tr><td>
<table>
<tr><td colspan=2 align="center">
     <IMG SRC="SBR1.gif" WIDTH=117 HEIGHT=60 BORDER=0  ALT="">
</td></tr>
<TR><td>

<IMG SRC="davetweek.gif" WIDTH=200 HEIGHT=200 BORDER=1  ALIGN="LEFT" ALT="Snowboard reviews">

</td>
<td>
     <p><font size="+1">This form is for the rider who wishes to help out fellow Snowboarders, so that we will all know what snowboards rule and what boards blow. Thanks for submitting a review. </font></p></CENTER>
     </td>
</TR>
<TR><TD colspan=2 >

<FORM METHOD=POST ACTION="submitReview.php">

<p><center><b>Tell us about yourself:</b> 

<font size="-1">(* required fields)</font></center></p>

<blockquote>
        <table align="center">
            <tr>
                <td align="right"><em>First Name* </em></td>
                <td><input type="text" size="20"
                name="first_name" value="$user[first_name]" > </td>
            </tr>
            <tr>
                <td align="right"><em>Last Name* </em></td>
                <td><input type="text" size="20"
                name="last_name" value="$user[last_name]" > </td>
            </tr>
            <tr>
                <td align="right"><em>Age* </em></td>
                <td><input type="text" size="2"
                name="age"> </td>
            </tr>
            <tr>
                <td align="right"><em>Sex* </em></td>
                <td><input type="radio"
                name="sex" value="Female"> Female<input
                type="radio" checked name="sex" value="Male">
                Male</td>
            </tr>
            <tr>
                <td align="right"><em>Height* </em></td>
                <td><input type="text" size="6" maxlength="6"
                name="height">

                    <input type="radio" name="height_units" value="feet" checked >ft-in <input type="radio" name="height_units" value="cm">cm
                    </td>

            </tr>
            <tr>
                <td align="right"><em>Weight* </em></td>
                <td><input type="text" size="6" maxlength="6"
                name="weight">
                    <input type="radio" name="weight_units" value="pounds" checked >lbs <input type="radio" name="weight_units" value="kg">kg
                    </td>
            </tr>
            <tr>
               <td align="right">How many years experience* </td>
               <td>
               <input type="text" size="6" maxlength="2" name="years_exp">
               </td>
            </tr>
            <tr>
                <td align="right"><em>I'm from </em></td>
                <td><input type="text" size="30" name="location"></td>
            </tr>
            <tr>
                <td align="right"><em>My Favorite Boarding Spot </em></td>
                <td><input type="text" size="30" name="favorite_spot"></td>
            </tr>
            <tr>
                <td align="right"><em>Riding Style </em></td>
                <td><input type="text" size="50" name="riding_style"></td>
            </tr>
            <tr>
                <td align="right"><em>My URL / image is at http://</em></td>
                <td><input type="text" size="50" name="image_url"></td>
            </tr>
<tr>
     <td align="right">Email Address </td>
     <td><input type="text" size="30" maxlength="50" name="email"></td>
</tr>
<tr><td colspan=2><center>
<FONT SIZE="-1" COLOR="#FF8000">(We will never share your address or send spam, unless you really want our spam)</FONT>
</center>
</td></tr>
<tr>
     <td align="right">Hide my email on review page</td>
     <td> &nbsp <input type=checkbox name="hide_email"></td>
</tr><tr>
     <td align="right">Email me comments on this review</td>
     <td> &nbsp <input type=checkbox name="email_comments"> 
</td></tr>
     <td align="right">I really want your spam (our mailing list)</td>
     <td> &nbsp <input type=checkbox name="spam_me"> &nbsp (no more that 1 email / month)
</td></tr>

</table>
<p><center><hr color="red" width=200></center>

     <p><center><b>Tell us about this product:</b></center></p>

    <blockquote>
<table border="0">
<tr>
     <td align="right"><em>Product Type</em></td>
     <td>
     <SELECT NAME="product">
          <option value="snowboard">Snowboard </option>
          <option value="snowboard_boots">Snowboard Boots</option>
          <option value="snowboard_bindings">Snowboard Bindings</option>
     </SELECT>
</td></tr>
<tr>
     <td align="right"><em>Company name</em></td>
<TD>
<NOBR>
<SELECT NAME="company">
            <option value="not selected">select one </option>
            <option value="247">247 </option>
            <option value="5150">5150 </option>
            <option value="a">A snowboards </option>
            <option value="aggression">Aggression </option>
            <option value="airwalk">Airwalk </option>
            <option value="apocalypse">Apocalypse </option>
            <option value="arbor">Arbor </option>
            <option value="atlantis">Atlantis </option>
            <option value="avalanche">Avalanche </option>
            <option value="barfoot">Barfoot </option>
            <option value="burton">Burton </option>
            <option value="beyond">Beyond </option>
            <option value="blacksmith">Blacksmith </option>
            <option value="blankboard">Blankboard </option>
            <option value="blax">Blax </option>
            <option value="bline">Bline </option>
            <option value="coiler">Coiler</option>
            <option value="crazy_creek">Crazy Creek </option>
            <option value="custom_craft">Custom Craft </option>
            <option value="division_23">Division 23 </option>
            <option value="donek">Donek </option>
            <option value="duotone">Duotone </option>
            <option value="echos">Echos </option>
            <option value="elan">Elan </option>
            <option value="esp">ESP </option>
            <option value="empire">Empire </option>
            <option value="evolution">Evolution </option>
            <option value="forum">Forum </option>
            <option value="gecko">Gecko </option>
            <option value="generics">Generics </option>
            <option value="geronimo ">Geronimo </option>
            <option value="glissade">Glissade/Crap </option>
            <option value="gnu">Gnu </option>
            <option value="gothic">Gothic </option>
            <option value="hammer">Hammer </option>
            <option value="hazmat">Hazmat </option>
            <option value="heetrz">Heetrz </option>
            <option value="hot">Hot </option>
            <option value="hyperlite">Hyperlite </option>
            <option value="identity">Identity </option>
            <option value="inca">Inca </option>
            <option value="innovation">Innovation </option>
            <option value="jobe">Jobe </option>
            <option value="joyride">Joyride </option>
            <option value="jolt">Jolt </option>
            <option value="juice">Juice </option>
            <option value="k2">K2 </option>
            <option value="kemper">Kemper </option>
            <option value="killer_loop">Killer Loop </option>
            <option value="kuu">Kuu </option>
            <option value="lamar">Lamar </option>
            <option value="libtech">Libtech </option>
            <option value="limited">Limited </option>
            <option value="liquid">Liquid </option>
            <option value="look">Look </option>
            <option value="lust">Lust </option>
            <option value="m3">M3 </option>
            <option value="madd">Madd </option>
            <option value="marker">Marker </option>
            <option value="mazzo">Mazzo </option>
            <option value="mercury">Mercury </option>
            <option value="mistral">Mistral </option>
            <option value="mly">MLY</option>
            <option value="morrow">Morrow </option>
            <option value="never_summer">Never Summer </option>
            <option value="nidecker">Nidecker </option>
            <option value="nitro">Nitro </option>
            <option value="no_limitz">No Limitz </option>
            <option value="option">Option </option>
            <option value="oracle ">Oracle </option>
            <option value="original_sin">Original Sin </option>
            <option value="oxygen">Oxygen </option>
            <option value="palmer">Palmer </option>
            <option value="pifer">Pifer </option>
            <option value="pil">Pil </option>
            <option value="pl">PL </option>
            <option value="prior">Prior </option>
            <option value="pure_carve">Pure Carve </option>
            <option value="revalation">Revalation </option>
            <option value="rad_air">Rad Air </option>
            <option value="rage">Rage </option>
            <option value="ride">Ride </option>
            <option value="rossignal">Rossignal </option>
            <option value="salomon">Salomon </option>
            <option value="santa_cruz">Santa Cruz </option>
            <option value="scott">Scott </option>
            <option value="shortys">Shortys </option>
            <option value="silence">Silence </option>
            <option value="sims">Sims </option>
            <option value="skratch">Skratch </option>
            <option value="smelly_tuna">Smelly Tuna </option>
            <option value="smokin">Smokin </option>
            <option value="snowjam">Snowjam </option>
            <option value="staple">Staple </option>
            <option value="static">Static </option>
            <option value="town_n_country">Town-n-Country </option>
            <option value="tucker">Tucker </option>
            <option value="tweaked">Tweaked </option>
            <option value="twelve">Twelve </option>
            <option value="typea">TypeA </option>
            <option value="vision">Vision </option>
            <option value="visle">Visle </option>
            <option value="volkl">Volkl </option>
            <option value="volant">Volant </option>
            <option value="whelan">Whelan </option>
            <option value="winterstick">Winterstick </option>
            <option value="world_industries">World Industries </option>
            <option value="not_listed">Not Listed </option>
          </SELECT>
<FONT SIZE="-1" COLOR="#FF8000">fill-in if not listed >></FONT>
<input type="text" size="20" name="company_fill_in">
</TD>
            </tr>
            <tr>
                <td align="right"><em>Year</em></td>
                <td><input type="text" size="10"
                name="year"> </td>
            </tr>
            <tr>
                <td align="right"><em>Model</em></td>
                <td><input type="text" size="20"
                name="model"> </td>
            </tr>
            <tr>
                <td align="right"><em>Size</em></td>
                <td><input type="text" size="10"
                name="size"> </td>
            </tr>
            <tr>
                <td align="right"><em><nobr>Warranty Length (if known)</nobr></em></td>
                <td><input type="text" size="10" name="warranty"> </td>
</tr>
</table>

<p><nobr>Give this product an overall rating of 1 to 10 </nobr>
<input type="text" size="5" maxlength="5" name="rating_1">
<FONT SIZE="-1" COLOR="#FF8000">(10 being the highest rating)</FONT>

<p><center>What do you think about this product?<br> Please give us as much info as possible. Explain how it performs ( how is the flex? How well does it turn? Does it hold a carve well? Is it light or heavy? Give some good points and some bad ones) please be as critical as you can. The more we get the better the site will be. We will only accept reviews that are more than 7 sentences long and that are informative on how the snowboard performs. You can also write the review using a text editor on your computer, then copy and paste the text into the box below.</center>

<p>Full Review: <textarea name="review_text" rows="10" cols="70"></textarea>

<p>One Sentence Summary: <input type="text" size="58" name="final_word">
        
<p><center><input type="submit" value="Submit Review" >

<p>(May take up to 15 seconds to process your review)

<p><A HREF="mailto:support@chicodigital.com">Comments or suggestions regarding this form or our site?</A>

<p><h3>Fresh tracks for all</H3><br>

</form>

</td></tr></table>

</td></tr><tr><td width=20><br></td></tr></table>
</body>
</html>
SNOWFORM;

echo $html;


