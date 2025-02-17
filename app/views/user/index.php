<?php
$title = 'Список пользователей';
ob_start();

?>

<a class="btn btn-success " href="<?='/users/create'?>">
   Создать Пользователя
</a>
<div class="table-responsive" style="max-height: 700px; overflow-y: auto; margin-top: 15px;">
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Имя</th>
      <th scope="col">Email</th>
      <th scope="col">Email verification</th>
      <th scope="col">Администратор</th>
      <th scope="col">Роль</th>
      <th scope="col">Is active</th>
      <th scope="col">Last Login</th>
      <th scope="col">Дата Создания</th>
    </tr>
  </thead>
  <tbody>
    <?php  if (!empty($users)) : ?>
      <?php foreach ($users as $user) : ?>
        <tr>
          <th scope="row"><?= htmlspecialchars($user['id']) ?></th>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= $user['email_verification'] ? 'Да' : 'Нет' ?></td>
          <td><?= $user['is_admin'] ? 'Да' : 'Нет' ?></td>
          <td><?= htmlspecialchars($user['role']) ?></td>
          <td><?= $user['is_active'] ? 'Да' : 'Нет' ?></td>
          <td><?= htmlspecialchars(isset($user['last_login'])) ?? $user['last_login'] ?></td>
          <td><?= htmlspecialchars($user['created_at']) ?></td>
          <td>
            <a href="<?="/users/edit/{$user['id']}"?>" class="btn btn-outline-primary">Редактировать</a>
            <form action="<?="/users/delete/{$user['id']}"?>" method="post">
              <button type="submit" onclick="return confirm('Действительно хотите удалить пользователя?')" class="btn btn-outline-danger">Удалить</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="4">Нет пользователей</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
</div>
<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
