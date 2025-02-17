<?php
$title = 'Авторизация';
ob_start();
?>
<h1>Авторизация</h1>
<form class="row g-3" action="<?='/auth/authontificate'?>" method="POST">
  <div class="col-12">
    <label for="inputEmail" class="form-label">Email</label>
    <input type="email" name="email" class="form-control" id="inputEmail">
  </div>
  <div class="col-12">
    <label for="inputPassword1" class="form-label">Пароль</label>
    <input type="password" name="password" class="form-control" id="inputPassword1">
  </div>
  <div class="form-check">
    <input class="form-check-input" name="remember" type="checkbox" value="on" id="flexCheckChecked">
    <label class="form-check-label" for="flexCheckChecked">
        Запомнить меня
    </label>
  </div>
  <div class="col-12 mt-3">
    <button type="submit" class="btn btn-primary">Авторизоваться</button>
  </div>
  <div class="col-12 mt-3">
    Нет аккаунта? <a href="<?='/auth/register'?>"><?=htmlspecialchars( "Регистрация") ?></a>
  </div>
</form>


<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
