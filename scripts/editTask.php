<?php
require('connectDB.php');
function editTask($conn) {
  if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'] !== NULL) {
    if (isset($_POST['taskText']) && isset($_POST['taskId'])) {
      $taskText = htmlentities($_POST['taskText']);
      $taskId = $_POST['taskId'];
    
      $sql = "UPDATE tasks SET task_text = :text, edit = '1' WHERE id = :id";
      
      $query = $conn->prepare($sql);
      
      $data = $query->execute([
        'text' => $taskText,
        'id' => $taskId,
      ]);
      return $data;
    }
    else if (isset($_POST['taskStatus']) && isset($_POST['taskId'])) {
      if ($_POST['taskStatus'] == 'done') {
        $taskStatus = 1;
      }
      else if ($_POST['taskStatus'] == 'intime') {
        $taskStatus = 0;
      }
      $taskId = $_POST['taskId'];
    
      $sql = "UPDATE tasks SET status = :status WHERE id = :id";
      
      $query = $conn->prepare($sql);
      
      $data = $query->execute([
        'status' => $taskStatus,
        'id' => $taskId,
      ]);
      return $data;
    }
    else {
      return 'ERROR';
    }
  }
  else {
    return 'authErr';
  }
}


try {
  $conn = connectDB();
  $data = editTask($conn);
  //unset($_SESSION['sort']);
  //unset($_SESSION['last-sort']);
  if ($data === 'ERROR') {
    echo json_encode(array(
      'code' => 'noPostData',
      'message' => 'Запрос не найдет'
    ));
  }
  else if ($data === 'authErr') {
    echo json_encode(array(
      'code' => 'authErr',
      'message' => 'Ошибка аутентификации'
    ));
  }
  else {
    echo json_encode(array(
      'code' => 'success',
      'message' => 'выполнено',
      'data' => $data
    ));
  }
}
catch (Exception $e) {
  echo json_encode(array(
    'code' => 'error',
    'message' => $e->getMessage()
  ));
}

//header('Location: /admin.php?user='.$_SESSION['logged_user']);
