<?php
$title = 'Редактировать пользователя';
ob_start();

?>

<form class="row g-3" action="<?="/users/update/{$user['id']}"?>" method="POST">
  <div class="col-12">
    <label for="inputUserName" class="form-label">Имя</label>
    <input type="text" name="username" class="form-control" id="inputUserName" value="<?= $user['username'] ?>">
  </div>
  <div class="col-12">
    <label for="inputEmail" class="form-label">Email</label>
    <input type="email" name="email" class="form-control" id="inputEmail" value="<?= $user['email'] ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Роль</label>
    <select class="form-select" name="role">
      <?php  foreach($roles as $role) : ?>
        <option value="<?=$role['id']?>" <?php echo $user['role'] == $role['id'] ? 'selected' : '' ?>><?=$role['role_name']?></option>
      <?php endforeach ?>
    </select>
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
