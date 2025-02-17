<?php
$title = 'Список категорий';
ob_start();
?>

<a class="btn btn-success " href="<?='/todo/category/create'?>">
   Создать категорию 
</a>
<div class="table-responsive" style="max-height: 700px; overflow-y: auto; margin-top: 15px;">
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Имя</th>
      <th scope="col">Описание</th>
      <th scope="col">Видимость</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($categories)) : ?>
      <?php foreach ($categories as $category) : ?>
        <tr>
          <th scope="row"><?= htmlspecialchars($category['id']) ?></th>
          <td><?= htmlspecialchars($category['title']) ?></td>
          <td><?= htmlspecialchars($category['description']) ?></td>
          <td><?= htmlspecialchars($category['usability'] == 1 ? 'Да' : 'Нет')  ?></td>
          <td>
            <a href="<?="/todo/category/edit/{$category['id']}"?>" class="btn btn-outline-primary">Редактировать</a>
            <form action="<?="/todo/category/delete/{$category['id']}"?>" method="post">
              <button type="submit" onclick="return confirm('Действительно хотите удалить категорию')" class="btn btn-outline-danger">Удалить</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="4">Нет категорий</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
</div>

<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
