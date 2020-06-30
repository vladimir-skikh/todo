<?php require('scripts/functions.php');
  $_SESSION['logged_user'] = NULL;
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
        <div class="col-md-1">
          <div class="row">
            <div class="col-md-6" id="admin" data-user=""></div>
            <div class="col-md-12">
              <i class="far fa-user-circle icon" data-toggle="modal" data-target="#myModal" id="modal-btn"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Войти</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="loginInput">Имя пользователя</label>
                <input type="text" class="form-control" id="loginInput" aria-describedby="loginHelp" name="username" required>
              </div>
              <div class="form-group">
                <label for="passwordInput">Пароль</label>
                <input type="password" class="form-control" id="passwordInput" name="password" required>
              </div>
              <div class="form-group">
                <span id="login-err">
                  <script id="login-err-template" type="text/template">
                      <%= error %>
                  </script>
                </span>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary" id="login">Войти</button>
              </div>
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
                      <td>'.$task['task_text'].'</td>
                      <td>'.$status.'</td>
                    </tr>
                  ';
                }
                else {
                  echo '
                    <tr>
                      <td>'.$task['user_name'].'</td>
                      <td>'.$task['email'].'</td>
                      <td class="edited">'.$task['task_text'].'<span class="edited-span" data-toggle="tooltip" data-placement="bottom" title="Задача отредактирована администратором"></span></td>
                      <td>'.$status.'</td>
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
                  <td><%= task.task_text %></td>
                  <td>
                      <% if (task.status == 0) { %>
                        В процесе
                      <% } else { %>
                        Выполнена
                      <%}%>
                  </td>
                </tr>
                <% } else { %>
                  <tr>
                    <td><%= task.user_name %></td>
                    <td><%= task.email %></td>
                    <td class="edited"><%= task.task_text %><span class="edited-span" title="Отредактировано администратором"></span></td>
                    <td>
                      <% if (task.status == 0) { %>
                        В процесе
                      <% } else { %>
                        Выполнена
                      <%}%>
                    </td>
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
  </main>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <script src="underscore-min.js" type="text/javascript"></script>
  <script src="tasksDB.js"></script>
  <script src="adminDB.js"></script>
  <script src="script.js"></script>
</body>
</html>