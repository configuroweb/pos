<?php

try{
    $pdo = new PDO('mysql:host=localhost;dbname=ipos','root','');
    //echo 'Connection Successfull';
}catch(PDOException $error){
    echo $error->getmessage();
}


?>