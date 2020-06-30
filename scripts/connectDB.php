<?php

function connectDB() {
  $servername = 'localhost';
  $username = 'root';
  $password = '';
  $dbname = 'todo';
  
  $dsn = "mysql:host=$servername;dbname=$dbname";
  $errorMessage = 'Невозможно подключиться к серверу базы данных';
  $conn = new PDO($dsn, $username, $password);
  if (!$conn)
    throw new Exception($errorMessage);
  else {
  $query = $conn->query('set names utf8');
  if (!$query)
    throw new Exception($errorMessage);
  else
    return $conn;
  }
}
session_start();