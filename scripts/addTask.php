<?php
require('connectDB.php');
function addTask($conn) {
  $user = htmlentities($_POST['user']);
  $email = htmlentities($_POST['email']);
  $text = htmlentities($_POST['text']);
  
  $sql = "INSERT INTO tasks (user_name, email, task_text) VALUES (:user, :email, :text)";
  
  $conn = connectDB();
  
  $query = $conn->prepare($sql);
  
  $query->execute([
    'user' => $user,
    'email' => $email,
    'text' => $text,
  ]);
}

try {
  $conn = connectDB();
  addTask($conn);
  unset($_SESSION['sort']);
  unset($_SESSION['last-sort']);
}
catch (Exception $e) {
  echo json_encode(array(
    'code' => 'error',
    'message' => $e->getMessage()
  ));
}
if ($_SESSION['logged_user'] !== NULL) {
  header('Location: /admin.php');
}
else {
  header('Location: /');
}