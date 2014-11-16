<?php
    $password = 'pippo';
    echo "<br>password $password";
    $hashed = sha1($password);
    echo "<br> hashata -> $hashed";
    $hashed = crypt($hashed);
    echo "<br> crypt -> $hashed";

    echo "<br> crypt again...".crypt($hashed);

?>