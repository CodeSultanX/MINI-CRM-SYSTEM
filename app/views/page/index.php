<?php
$title = 'Список страниц';
ob_start();
?>

<a class="btn btn-success " href="<?='/pages/create'?>"">
   Создать страницу 
</a>
<div class="table-responsive" style="max-height: 700px; overflow-y: auto; margin-top: 15px;">
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Найменование</th>
      <th scope="col">slug</th>
      <th scope="col">Роль</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($pages)) : ?>
      <?php foreach ($pages as $page) : ?>
        <tr>
          <th scope="row"><?= htmlspecialchars($page['id']) ?></th>
          <td><?= htmlspecialchars($page['title']) ?></td>
          <td><?= htmlspecialchars($page['slug']) ?></td>
          <td><?= htmlspecialchars($page['role']) ?></td>
          <td>
            <a href="<?="/pages/edit/{$page['id']}"?>" class="btn btn-outline-primary">Редактировать</a>
            <form action=""<?="/pages/delete/{$page['id']}"?>" method="post">
              <button type="submit" onclick="return confirm('Действительно хотите удалить страницу?')" class="btn btn-outline-danger">Удалить</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="4">Нет страницы</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
</div>

<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
