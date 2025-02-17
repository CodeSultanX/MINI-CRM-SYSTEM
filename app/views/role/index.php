<?php
$title = 'Список Ролей';
ob_start();
?>

<a class="btn btn-success " href="<?='/roles/create'?>">
   Создать Роль
</a>
<div class="table-responsive" style="max-height: 700px; overflow-y: auto; margin-top: 15px;">
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Имя</th>
      <th scope="col">Описание</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($roles)) : ?>
      <?php foreach ($roles as $role) : ?>
        <tr>
          <th scope="row"><?= htmlspecialchars($role['id']) ?></th>
          <td><?= htmlspecialchars($role['role_name']) ?></td>
          <td><?= htmlspecialchars($role['role_description']) ?></td>
          <td>
            <a href="<?="/roles/edit/{$role['id']}"?>" class="btn btn-outline-primary">Редактировать</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="4">Нет ролей</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
</div>

<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
