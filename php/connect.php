<?php 
try{
    $connect = new PDO ('mysql:host=localhost;dbname=advantage_db','root','');
}catch(PDOException $error){
    $error;
}

?>