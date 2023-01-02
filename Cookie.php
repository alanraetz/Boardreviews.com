<?php
     function checkAuth($userToCheck, $passToCheck) {
    
         $user = "foo";
         $pass = "bar";
         $encodedPass = md5($pass);
        
         if ($userToCheck == $user && $passToCheck == $encodedPass) {
             $check = TRUE;
         } else {
             $check = FALSE;
         }   
         return $check;
        
     }
    
     if (isset($relogin)) {
         setcookie ('cuser', "",time()-3600);
       setcookie ('cpass', "",time()-3600);
     }
    
     if (isset($login)) {
       $password = md5($formPass);
       $username = $formUser;
      
       if (checkAuth($username, $password)) {
           setcookie ('cuser', $username,time()+3600);
           setcookie ('cpass', $password,time()+3600);
           $msg = "Authentication was successful! The cookies have been sent!";
       }else{
           $msg = "Authentication error!";
       }
     }
 ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
      
<html>

   <head>
       <?php
           if (isset($login)) {
               echo("<meta http-equiv='refresh' content='5;url=Cookie.php'>");
           }
       ?>
        <title>Test Cookie</title>
   </head>
  
   <body>
       <?php
  
       if (isset($cuser) && !isset($relogin)) {
           if (checkAuth($cuser, $cpass)) {
               echo("<p>Hello $cuser!</p>" .
                     "<p>Your password (md5 encrypted) is: $cpass</p>" .
                     "<p><a href='Cookie.php?relogin=1'>Delete cookies and force login<a></p>");
           } else {
               echo("<p>Authentication error from cookies values</p>" .
                     "<p><a href='Cookie.php?relogin=1'>Delete cookies and force login<a></p>");
           }
       } else {
      
           if (!isset($login) || isset($relogin)) {
           ?>
            <form action="Cookie.php?login=1" method="POST">
               <p>User: <input type="text" name="formUser"></p>
               <p>Pass: <input type="password" name="formPass"></p>
               <p><input type="submit" name="submit" value="Log In"></p>
           </form>
           <?php
           } elseif (isset($login)) {
               echo("<p>$msg</p>" .
                     "<p>You'll be redirected in 5 seconds!</p>");
           }
       }
       ?>
    </body>
  
</html>