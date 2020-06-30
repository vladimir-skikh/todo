'use strict';

var tasksDB = (function($) {
  var ui = {
    $sort: $('.table-order-arrow'),
    $template: $('#tasks-template'),
    $tasks: $('#tasks'),
    $editText: $('.edit-task-text'),
    $editStatus: $('.edit-task-status'),
    $select: $('#select'),
    $editArea: $('#task-edit-area'),
    $editTextBtn: $('#edit-text-btn'),
    $editStatusBtn: $('#edit-status-btn'),
  };

  var direction = '';
  var column = '';
  var data = '';
  var value = '';
  var template = _.template(ui.$template.html());

  function init() {
    _bindHandlers();
  }

  function _bindHandlers() {
    ui.$sort.on('click', _changeSort);
    ui.$editText.on('click', _editTaskText);
    ui.$editTextBtn.on('click', _editText);
    ui.$editStatus.on('click', _editTaskStatus);
    ui.$editStatusBtn.on('click', _editStatus);
  }
  var dataId = '';
  function _editTaskText() {
    var $this = $(this);
    ui.$editArea.val($this.text());
    dataId = $this.attr('data-id');
  }

  function _editText() {
    value = ui.$editArea.val();
    console.log(value);
    data = 'taskText=' + value + '&' + 'taskId=' + dataId;
    $.ajax({
      type: "POST",
      url: "scripts/editTask.php",
      data: data,
      cache: false,
      dataType: "json",
      success: function (responce) {
        if (responce.code === 'success') {
          _editTasksSuccess(responce);
          console.log(responce.data);
        } else {
          _editTasksError(responce);
        }
      }
    });
    value = '';
    data = '';
  }

  var selectValues = {};
  function _editTaskStatus() {
    var $this = $(this);
    console.log($this.text());
    if ($this.text() == 'Выполнена') {
      ui.$select.children().remove();
      selectValues = {
        'done': $this.text(),
        'intime': 'В процессе',
      };
    }
    else {
      ui.$select.children().remove();
      selectValues = {
        'intime': $this.text(),
        'done': 'Выполнена',
      };
    }
    $.each(selectValues, function(key, value) {
      ui.$select
          .append($("<option></option>")
          .attr("value",key)
          .text(value));
    });
    selectValues = {};
    dataId = $this.attr('data-id');
    console.log(ui.$select);
  }
  function _editStatus() {
    value = ui.$select.val();
    data = 'taskStatus=' + value + '&' + 'taskId=' + dataId;
    console.log(value);
    $.ajax({
      type: "POST",
      url: "scripts/editTask.php",
      data: data,
      cache: false,
      dataType: "json",
      success: function (responce) {
        if (responce.code === 'success') {
          _editTasksSuccess(responce);
        } else if (responce.code === 'authErr') {
          _editTasksError(responce);
        }
      }
    });
    value = '';
    data = '';
  }


  function _editTasksSuccess(responce) {
    console.log('Выполнено!');
  }
  function _editTasksError(responce) {
    alert(responce.message);
    window.location.href = "/"; 
  }

  function _changeSort() {
    var $this = $(this);
    if ($this.hasClass('table-order-arrow-active')) {
      ui.$sort.removeClass('table-order-arrow-active');
      direction = 'DESC';
    }
    else {
      ui.$sort.removeClass('table-order-arrow-active');
      $this.addClass('table-order-arrow-active');
      direction = 'ASC';
    }
    column = $this.attr('data-col');
    _getData();
  }

  function _tasksSuccess(responce) {
    ui.$tasks.html(template({tasks: responce.tasks}));
  }
  function _tasksError(responce) {
    console.error('responce', responce);
  }

  function _getData() {
    data = 'column=' + column + '&' + 'direction='+ direction;
    $.ajax({
      type: "GET",
      url: "scripts/select.php",
      data: data,
      cache: false,
      dataType: "json",
      success: function (responce) {
        if (responce.code === 'success') {
          _tasksSuccess(responce);
        } else {
          _tasksError(responce);
        }
      }
    });
    data = '';
  }
  return {
    init: init
  }
})(jQuery);