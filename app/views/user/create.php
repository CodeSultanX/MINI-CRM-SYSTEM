<?php
$title = 'Создание пользователя';
ob_start();
?>
<h1>Создание пользователя</h1>
<form class="row g-3" action="<?='/users/store'?>" method="POST">
  <div class="col-md-12">
    <label for="inputEmail4" class="form-label">Имя</label>
    <input type="text" name="username" class="form-control w-100" id="inputEmail4">
  </div>
  <div class="col-md-12">
    <label for="input_email" class="form-label">Email</label>
    <input type="email" name="email" class="form-control" id="input_email">
  </div>
  <div class="col-md-12">
    <label for="inputPassword1" class="form-label">Пароль</label>
    <input type="password" name="password" class="form-control" id="inputPassword1">
  </div>
  <div class="col-md-12">
    <label for="inputPassword2" class="form-label">Подверждение паролья</label>
    <input type="password" name="password_repeat" class="form-control" id="inputPassword2">
  </div>
 
  </div>
  <div class="col-12 mt-3">
    <button type="submit" class="btn btn-primary">Создать</button>
  </div>
</form>


<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
