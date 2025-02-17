<?php
$title = 'Создание категорий';
ob_start();
?>
<h1>Создание категорий</h1>
<form class="row g-3" action="<?="/todo/category/store"?>" method="POST">
  <div class="col-md-12">
    <label class="form-label">Имя</label>
    <input type="text" name="title" class="form-control">
  </div>
  <div class="col-md-12">
    <label class="form-label">Описание</label>
    <input type="text" name="description" class="form-control">
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
