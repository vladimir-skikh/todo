<tr>
  <th scope="col">Пользователь
    <?php 
      if (isset($_SESSION['sort']) && $_SESSION['sort']['sort_by'] == 'user_name' && $_SESSION['sort']['sort_dir'] == 'ASC' && $_SESSION['sort'] !== NULL) {
        if (isset($_SESSION['last-sort']) && $_SESSION['sort']['sort_dir'] !== $_SESSION['last-sort']['sort_dir'] && $_SESSION['last-sort']['sort_by'] == 'user_name' && $_SESSION['last-sort'] !== NULL) {
          if ($_SESSION['last-sort']['sort_dir'] == 'ASC') {
            echo '<span class="table-order-arrow table-order-arrow-active" data-col="user_name"></span>';
          }
          else {
            echo '<span class="table-order-arrow" data-col="user_name"></span>';
          }
        }
        else {
          echo '<span class="table-order-arrow table-order-arrow-active" data-col="user_name"></span>';
        }
      }
      else {
        echo '<span class="table-order-arrow" data-col="user_name"></span>';
      }
    ?>
  </th>
  <th scope="col">Email
    <?php 
      if (isset($_SESSION['sort']) && $_SESSION['sort']['sort_by'] == 'email' && $_SESSION['sort']['sort_dir'] == 'ASC') {
        if (isset($_SESSION['last-sort']) && $_SESSION['sort']['sort_dir'] !== $_SESSION['last-sort']['sort_dir'] && $_SESSION['last-sort']['sort_by'] == 'email' && $_SESSION['last-sort'] !== NULL) {
          if ($_SESSION['last-sort']['sort_dir'] == 'ASC') {
            echo '<span class="table-order-arrow table-order-arrow-active" data-col="email"></span>';
          }
          else {
            echo '<span class="table-order-arrow" data-col="email"></span>';
          }
        }
        else {
          echo '<span class="table-order-arrow table-order-arrow-active" data-col="email"></span>';
        }
      }
      else {
        echo '<span class="table-order-arrow" data-col="email"></span>';
      }
    ?>
  </th>
  <th scope="col">Текст задачи</th>
  <th scope="col">Статус
    <?php 
      if (isset($_SESSION['sort']) && $_SESSION['sort']['sort_by'] == 'status' && $_SESSION['sort']['sort_dir'] == 'ASC') {
        if (isset($_SESSION['last-sort']) && $_SESSION['sort']['sort_dir'] !== $_SESSION['last-sort']['sort_dir'] && $_SESSION['last-sort']['sort_by'] == 'status' && $_SESSION['last-sort'] !== NULL) {
          if ($_SESSION['last-sort']['sort_dir'] == 'ASC') {
            echo '<span class="table-order-arrow table-order-arrow-active" data-col="status"></span>';
          }
          else {
            echo '<span class="table-order-arrow" data-col="status"></span>';
          }
        }
        else {
          echo '<span class="table-order-arrow table-order-arrow-active" data-col="status"></span>';
        }
      }
      else {
        echo '<span class="table-order-arrow" data-col="status"></span>';
      }
    ?>
  </th>
</tr>