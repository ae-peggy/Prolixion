<?php

$host = 'localhost';     
$username = 'root';      
$password = 'root';          
$database = 'portfolio_builder';  

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error){
    die("Connection Failed: ". $conn->connect_error);
}

