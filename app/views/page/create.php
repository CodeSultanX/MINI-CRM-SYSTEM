<?php
$title = 'Создание станицы';
ob_start();
?>
<h1>Создание станицы</h1>
<form class="row g-3" action="<?="/pages/store"?>" method="POST">
  <div class="col-md-12">
    <label  class="form-label">Имя</label>
    <input type="text" name="title" class="form-control w-100">
  </div>
  <div class="col-md-12">
    <label  class="form-label">slug</label>
    <input type="text" name="slug" class="form-control w-100">
  </div>
  <div class="col-md-12">
    <label  class="form-label">Доступ к ролям</label><br>
    <?php foreach($roles as $role) : ?>
    <input class="form-check-input" value="<?=$role['id']?>" name="roles[]" type="checkbox" id="flexCheckChecked">
    <label class="form-check-label" for="flexCheckChecked"> <?=$role['role_name'] ?></label><br>
    <?php endforeach ?>
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
