<?php
$title = 'Редактировать страницу';
ob_start();
?>
<form class="row g-3" action="<?="/pages/update/{$page['id']}"?>" method="POST">
  <div class="col-12">
    <label for="inputTitle" class="form-label">Имя</label>
    <input type="text" name="title" class="form-control" id="inputTitle" value="<?= $page['title'] ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" value="<?= $page['slug'] ?>" class="form-control">
  </div>
  <div class="col-md-12">
    <label  class="form-label">Доступ к ролям</label><br>
    <?php $page_roles = explode(',',$page['role']);
    foreach($roles as $role) : ?>
    <input class="form-check-input" value="<?=$role['id']?>" name="roles[]" type="checkbox" id="flexCheckChecked" <?= in_array($role['id'],$page_roles) ? 'checked' : '' ?>>
    <label class="form-check-label" for="flexCheckChecked"> <?=$role['role_name'] ?></label><br>
    <?php endforeach ?>
  </div>
 
  </div>
  <div class="col-12 mt-3">
    <button type="submit" class="btn btn-primary">Обновить</button>
  </div>
</form>


<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
