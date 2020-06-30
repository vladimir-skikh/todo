'use strict';

var adminDB = (function($) {
  var ui = {
    $login: $('#login'),
    $loginErr: $('#login-err'),
    $errTemplate: $('#login-err-template'),
  };
  var errTemplate = _.template(ui.$errTemplate.html());
  var data = '';


  function init() {
    _bindHandlers();
  }

  function _bindHandlers() {
    ui.$login.on('click', _getUser);
  }

  function _userSuccess(responce) {
    window.location.href = "/admin.php?user=" + responce.user; 
  }

  function _userError(responce) {
    console.error('responce', responce);
  }
  function _userLoginError(responce) {
    ui.$loginErr.html(errTemplate({error: responce.error}));
  }

  function _getUser(e) {
    var username = $('#loginInput').val();
    var password = $('#passwordInput').val();
    data = 'username=' + username + '&' + 'password='+ password;
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "scripts/login.php",
      data: data,
      cache: false,
      dataType: "json",
      success: function (responce) {
        if (responce.code === 'success') {
          _userSuccess(responce);
        } 
        else if (responce.code == 'failed') {
          _userLoginError(responce);
        }
        else {
          _userError(responce);
        }
      }
    });
    username = '';
    password = '';
    data = '';
  }
  return {
    init: init
  }
})(jQuery);