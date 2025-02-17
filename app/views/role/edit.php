<?php
$title = 'Редактировать роль';
ob_start();
?>
<form class="row g-3" action="<?="/roles/update/{$role['id']}"?>" method="POST">
  <div class="col-12">
    <label for="inputRoleName" class="form-label">Имя</label>
    <input type="text" name="role_name" class="form-control" id="inputRoleName" value="<?= $role['role_name'] ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Описание</label>
    <textarea name="role_description" class="form-control"><?= $role['role_description'] ?></textarea>

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
