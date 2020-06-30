<?php
require('connectDB.php');
function getUserOptions() {
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
  }
  return array (
    'user' => $username,
    'pass' => $password
  );
}

function getUser($options, $conn) {

  $user = $options['user'];
  $pass = $options['pass'];

  $query = 'SELECT * FROM users WHERE username = ? AND password = ?';

  $result = $conn->prepare($query);
  $result->execute(array($user, $pass));
  $user = $result->fetch(PDO::FETCH_ASSOC);
  return $user;
}

try {
  $conn = connectDB();
  $userOptions = getUserOptions();
  $user = getUser($userOptions, $conn);

  if ($user === NULL) {
    session_write_close();
    echo json_encode(array(
      'code' => 'failed',
      'error' => 'Неверное имя пользователя или пароль!',
    ));
  }
  else if ($user === false) {
    session_write_close();
    echo json_encode(array(
      'code' => 'failed',
      'error' => 'Неверное имя пользователя или пароль!',
    ));
  }
  else {
    $_SESSION['logged_user'] = $user;
    unset($_SESSION['sort']);
    unset($_SESSION['last-sort']);
    $username = $_SESSION['logged_user']['username'];

    echo json_encode(array(
      'code' => 'success',
      'user' => $username 
    ));
  }

}
catch (Exception $e) {
  echo json_encode(array(
    'code' => 'error',
    'message' => $e->getMessage()
  ));
}



