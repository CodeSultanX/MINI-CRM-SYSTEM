<?php
$title = 'Редактирование категорий';
ob_start();
?>
<form class="row g-3" action="<?="/todo/category/update/{$category['id']}"?>" method="POST">
  <div class="col-12">
    <label for="inputcategoryName" class="form-label">Имя</label>
    <input type="text" name="title" class="form-control" id="inputcategoryName" value="<?= $category['title'] ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Описание</label>
    <input type="text" name="description" class="form-control"  value="<?= $category['description'] ?>">
  </div>

  <div class="col-md-12">
    <input class="form-check-input" value="1" name="usability" type="checkbox" id="flexCheckChecked" <?= $category['usability'] ? 'checked' : '' ?>>
    <label class="form-check-label" for="flexCheckChecked">usability</label><br>
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
