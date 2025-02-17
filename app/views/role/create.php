<?php
$title = 'Создание роля';
ob_start();
?>
<h1>Создание роля</h1>
<form class="row g-3" action="<?="/roles/store"?>" method="POST">
  <div class="col-md-12">
    <label  class="form-label">Имя</label>
    <input type="text" name="role_name" class="form-control w-100a">
  </div>
  <div class="col-md-12">
    <label  class="form-label">Описание</label>
    <textarea name="role_description" class="form-control"></textarea>
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
