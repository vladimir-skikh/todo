<?php 
require('connectDB.php');
function getOptions() {
  $sortBy = $_GET['column'];
  $sortDir = $_GET['direction'];
  return array (
    'sort_by' => $sortBy,
    'sort_dir' => $sortDir,
  );
}

function getSort($options, $conn) {
  $sort_by = $options['sort_by'];
  $sort_dir = $options['sort_dir'];

  $pageno = $_SESSION['page'];

  $no_of_records_per_page = 3;
  $offset = ($pageno-1) * $no_of_records_per_page;

  $total_pages_sql = "SELECT COUNT(*) FROM tasks";
  $result = $conn->query($total_pages_sql);
  $total_rows = $result->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
  $total_pages = ceil($total_rows / $no_of_records_per_page);

  if ($pageno == 1) {
    $_SESSION['sort'] = array (
      'sort_by' => $sort_by,
      'sort_dir' => $sort_dir,
    );
    $query = "SELECT * FROM tasks ORDER BY $sort_by $sort_dir LIMIT $offset, $no_of_records_per_page";
  }
  else if ($pageno > 1 && $pageno < $total_pages) {
    if ($sort_dir === $_SESSION['sort']['sort_dir']) {
      $query = "SELECT * FROM tasks ORDER BY $sort_by $sort_dir LIMIT $offset, $no_of_records_per_page";
    }
    else if ($sort_dir !== $_SESSION['sort']['sort_dir']) {
      $revers_sort = $_SESSION['sort']['sort_dir'];
      $query = "SELECT * FROM (SELECT * FROM tasks ORDER BY $sort_by $revers_sort LIMIT $offset, $no_of_records_per_page) X ORDER BY $sort_by $sort_dir";
    }
  }
  else {
    if ($sort_by !== $_SESSION['sort']['sort_by']) {
      $last_sort = $_SESSION['sort']['sort_by'];
      if ($sort_dir === $_SESSION['sort']['sort_dir']) {
        $revers_sort = '';
        $query = "SELECT * FROM tasks ORDER BY $last_sort $sort_dir LIMIT $offset, $no_of_records_per_page";
      }
      else if ($sort_dir !== $_SESSION['sort']['sort_dir']) {
        $revers_sort = $_SESSION['sort']['sort_dir'];
        $query = "SELECT * FROM (SELECT * FROM tasks ORDER BY $last_sort $revers_sort LIMIT $offset, $no_of_records_per_page) X ORDER BY $last_sort $sort_dir";
      }
      $_SESSION['last-sort'] = array (
        'sort_by' => $last_sort,
        'sort_dir' => $sort_dir,
      );
    }
    else {
      if ($sort_dir === $_SESSION['sort']['sort_dir']) {
        $revers_sort = '';
        $query = "SELECT * FROM tasks ORDER BY $sort_by $sort_dir LIMIT $offset, $no_of_records_per_page";
        $_SESSION['last-sort'] = array (
          'sort_by' => $sort_by,
          'sort_dir' => $sort_dir,
        );
      }
      else if ($sort_dir !== $_SESSION['sort']['sort_dir']) {
        $revers_sort = $_SESSION['sort']['sort_dir'];
        $query = "SELECT * FROM (SELECT * FROM tasks ORDER BY $sort_by $revers_sort LIMIT $offset, $no_of_records_per_page) X ORDER BY $sort_by $sort_dir";
        $_SESSION['last-sort'] = array (
          'sort_by' => $sort_by,
          'sort_dir' => $sort_dir,
        );
      }

    }
  }

  $data = $conn->query($query);
  return array(
    'data' => $data->fetchAll(PDO::FETCH_ASSOC),
    'revers_sort' => $revers_sort
  );
}


try {
  $conn = connectDB();
  $options = getOptions();
  
  $tasks = getSort($options, $conn)['data'];
  $reverse_sort = getSort($options, $conn)['reverse_sort'];

  echo json_encode(array(
    'code' => 'success',
    'options' => $options,
    'tasks' => $tasks,
    'reverse_sort' => $reverse_sort,
    'session_page' => $_SESSION['page'],
    'session_sort' => $_SESSION['sort']
  ));
}
catch (Exception $e) {
  echo json_encode(array(
    'code' => 'error',
    'message' => $e->getMessage()
  ));
}





