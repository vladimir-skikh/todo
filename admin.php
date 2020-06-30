<?php require('scripts/functions.php');
  if (isset($_GET['user'])) {
    $_SESSION['logged_user'] = $_GET['user'];
  }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Список задач</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://kit.fontawesome.com/6f7e1cd525.js" crossorigin="anonymous"></script>
</head>
<body>
  <header>
    <div class="container">
      <div class="row align-items-center justify-content-between">
        <h1 class="col-md-4">Список задач</h1>
        <div class="col-md-2">
          <div class="row">
            <div class="col-md-6" id="admin" data-user="<?php echo $_SESSION['logged_user'];?>">
              <span style="color: #000">
                <?php echo $_SESSION['logged_user'];?>
              </span>
            </div>
            <div class="col-md-6">
              <i class="far fa-user-circle icon" data-toggle="modal" data-target="#myModalLogout" id="modal-btn"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Модальное окно для выхода пользователя-->
    <div class="modal fade" id="myModalLogout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Выйти из учетной записи</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="scripts/logout.php" method="post">
              <button type="submit" class="btn btn-primary" id="logout">Выйти</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>
  <main>
    <div class="container">
      <div class="container" id="list">
        <table class="table table-striped">
          <thead>
            <?php require('scripts/tableHeaders.php');?>
          </thead>
          <tbody id="tasks">
            <?php 
              $tasks = getPages()['tasks'];
              foreach ($tasks as $task) {
                $status = getTaskStatus($task);
                if ($task['edit'] == 0) {
                  echo '
                    <tr>
                      <td>'.$task['user_name'].'</td>
                      <td>'.$task['email'].'</td>
                      <td class="edit-task-text" data-id="'.$task['id'].'" data-toggle="modal" data-target="#editTaskTextModal">'.$task['task_text'].'<span class="edit-span"></span></td>
                      <td class="edit-task-status" data-id="'.$task['id'].'" data-toggle="modal" data-target="#editTaskStatusModal">'.$status.'<span class="edit-span"></span></td>
                    </tr>
                  ';
                }
                else {
                  echo '
                    <tr>
                      <td>'.$task['user_name'].'</td>
                      <td>'.$task['email'].'</td>
                      <td class="edit-task-text edited" data-id="'.$task['id'].'" data-toggle="modal" data-target="#editTaskTextModal">'.$task['task_text'].'<span class="edit-span"></span><span class="edited-span" title="Отредактировано администратором"></span></td>
                      <td class="edit-task-status" data-id="'.$task['id'].'" data-toggle="modal" data-target="#editTaskStatusModal">'.$status.'<span class="edit-span"></span></td>
                    </tr>
                  ';
                }
              }
            ?>
            <script id="tasks-template" type="text/template">
              <% _.each(tasks, function(task) { %>
                <% if (task.edit == 0) { %>
                <tr>
                  <td><%= task.user_name %></td>
                  <td><%= task.email %></td>
                  <td class="edit-task-text" data-id="<%= task.id %>" data-toggle="modal" data-target="#editTaskTextModal"><%= task.task_text %><span class="edit-span"></span></td>
                  <td class="edit-task-status" data-id="<%= task.id %>." data-toggle="modal" data-target="#editTaskStatusModal">
                      <% if (task.status == 0) { %>
                        В процесе
                      <% } else { %>
                        Выполнена
                      <%}%>
                  <span class="edit-span"></span></td>
                </tr>
                <% } else { %>
                  <tr>
                    <td><%= task.user_name %></td>
                    <td><%= task.email %></td>
                    <td class="edit-task-text edited" data-id="<%= task.id %>" data-toggle="modal" data-target="#editTaskTextModal"><%= task.task_text %><span class="edit-span"></span><span class="edited-span" title="Отредактировано администратором"></span></td>
                    <td class="edit-task-status" data-id="<%= task.id %>." data-toggle="modal" data-target="#editTaskStatusModal">
                      <% if (task.status == 0) { %>
                        В процесе
                      <% } else { %>
                        Выполнена
                      <%}%>
                    <span class="edit-span"></span></td>
                  </tr>
                <%}%>
              <% });%>
            </script>
          </tbody>
        </table>
      </div>
      <div class="container">
        <nav aria-label="Page navigation example">
          <ul class="pagination" id="pegination">
            <?php pegination();?>
          </ul>
        </nav>
      </div>
      <div class="container">
        <form action="scripts/addTask.php" method="post">
          <div class="row">
            <input type="text" id="taskUser" placeholder="Имя пользователя" name="user" class="form-control col-md-3" required>
            <input type="email" id="taskEmail" placeholder="Email" name="email" class="form-control col-md-3 required">
            <input type="text" id="taskText" placeholder="Текст задачи" name="text" class="form-control col-md-3" required>
            <button type="submit" name="sendTask" class="btn btn-success col-md-3" id="sendTask">Сохранить</button>
          </div>
        </form>
      </div>
    </div>
    <!--Модальные окна для редактировния записей-->
    <div class="modal fade" id="editTaskTextModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Изменить тест задачи</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <textarea class="form-control" id="task-edit-area" style="min-height: 250px"></textarea>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button class="btn btn-primary" id="edit-text-btn">Сохранить</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="editTaskStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Изменить статус задачи</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <select class="custom-select" id="select">
                </select>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button class="btn btn-primary" id="edit-status-btn">Сохранить</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <script src="underscore-min.js" type="text/javascript"></script>
  <script src="tasksDB.js"></script>
  <script src="adminDB.js"></script>
  <script src="script.js"></script>
  <?php 
    if (isset($_GET['added']) && $_GET['added'] === 'true') {
      echo '<script type="text/javascript">';
      echo 'alert("Задача успешно добавлена!");';
      echo 'window.location.href = "/admin.php?page=1&clear=clear"';
      echo '</script>';
    }
  ?>
  <script>
  </script>
</body>
</html>