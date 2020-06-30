<?php
require('connectDB.php');
require('goToPage.php');

function getTaskStatus($task) {
  if ($task['status'] == 0) {
    return 'В процессе';
  }
  else {
    return 'Выполнена';
  }
}

function pegination() {
  $pages = getPages()['pages'];
  if ( $pages > 1) {
    for ($i = 1; $i <= $pages; $i++) {
      if ($i == 1) {
        echo '<li class="page-item"><a class="page-link" href="?page='.$i.'&clear=clear">'.$i.'</a></li>';
      }
      else {
        echo '<li class="page-item"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
      }
    }
  }
}