<?php
function getPageOptions() {
  if (isset($_GET['page'])) {
    $pageno = $_GET['page'];
  } 
  else {
    $pageno = 1;
  }
  $_SESSION['page'] = $pageno;
  return $pageno;
}

function getPageData($options, $conn) {
  $pageno = $options;

  $no_of_records_per_page = 3;
  $offset = ($pageno-1) * $no_of_records_per_page;
  
  $total_pages_sql = "SELECT COUNT(*) FROM tasks";
  $result = $conn->query($total_pages_sql);
  $total_rows = $result->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
  $total_pages = ceil($total_rows / $no_of_records_per_page);


  if (isset($_GET['clear']) && $_GET['clear'] == 'clear') {
    if (isset($_SESSION['last-sort'])) {
      $sort_by = $_SESSION['sort']['sort_by'];
      $sort_dir = $_SESSION['sort']['sort_dir'];
      if ($sort_dir !== $_SESSION['last-sort']['sort_dir']) {
        $revers_sort = $_SESSION['last-sort']['sort_dir'];
        $query = "SELECT * FROM (SELECT * FROM tasks ORDER BY $sort_by $sort_dir LIMIT $offset, $no_of_records_per_page) X ORDER BY $sort_by $revers_sort";
      }
      else {
        $query = "SELECT * FROM tasks ORDER BY $sort_by $sort_dir LIMIT $offset, $no_of_records_per_page";
      }
      $_SESSION['last-sort'] = NULL;
    }
    else {
      $sort_by = 'id';
      $sort_dir = 'DESC';
      $_SESSION['sort'] = array (
        'sort_by' => $sort_by,
        'sort_dir' => $sort_dir,
      );
      $query = "SELECT * FROM tasks ORDER BY $sort_by $sort_dir LIMIT $offset, $no_of_records_per_page";
    }
  }
  else {
    if (isset($_SESSION['sort'])) {
      $sort_by = $_SESSION['sort']['sort_by'];
      $sort_dir = $_SESSION['sort']['sort_dir'];
      $query = "SELECT * FROM tasks ORDER BY $sort_by $sort_dir LIMIT $offset, $no_of_records_per_page";
    }
    else {
      $sort_by = 'id';
      $sort_dir = 'DESC';
      $_SESSION['sort'] = array (
        'sort_by' => $sort_by,
        'sort_dir' => $sort_dir,
      );
      $query = "SELECT * FROM tasks ORDER BY $sort_by $sort_dir LIMIT $offset, $no_of_records_per_page";
    }
  }
  $data = $conn->query($query);
  return array(
    'data' => $data->fetchAll(PDO::FETCH_ASSOC),
    'total_pages' => $total_pages,
    'session_sort' => $_SESSION['sort'],
  );
}

function getPages() {
  try {
    $conn = connectDB();
    $options = getPageOptions();
    $tasks = getPageData($options, $conn)['data'];
    $pages = getPageData($options, $conn)['total_pages'];
    $sort = getPageData($options, $conn)['session_sort'];
    return array(
      'pages' => $pages,
      'tasks' => $tasks,
      'sort' => $sort
    );
  }
  catch (Exception $e) {
    echo json_encode(array(
      'code' => 'error',
      'message' => $e->getMessage()
    ));
  }
}