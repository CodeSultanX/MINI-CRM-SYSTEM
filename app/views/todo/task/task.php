<?php
$title = "Задача: {$task['title']} ";
ob_start(); 

?>
            <div class="card mb-4">
    <div class="card-header">
        <h1 class="card-title">
            <i class="fa-solid fa-square-up-right"></i> <strong><?php echo $task['title']; ?> </strong>
        </h1>
    </div>
    <div class="card-body">
        <p class="row">
            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-layer-group"></i> Категория:</strong> <?php echo htmlspecialchars($category['title'] ?? 'N/A'); ?></span>
            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-battery-three-quarters"></i> Статус:</strong> <?php echo htmlspecialchars($task['status']); ?></span>
        </p>
        <p class="row">
            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-person-circle-question"></i> Приоритет:</strong> <?php echo htmlspecialchars($task['priority']); ?></span>
            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-hourglass-start"></i> Дата начало:</strong> <?php echo htmlspecialchars($task['created_at']); ?></span>
        </p>
        <p class="row">
            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-person-circle-question"></i> Дата обновление:</strong> <?php echo htmlspecialchars($task['updated_at']); ?></span>
            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-hourglass-start"></i> Дата оканчание:</strong> <?php echo htmlspecialchars($task['finish_date']); ?></span>
        </p>
        <p><strong><i class="fa-solid fa-file-prescription"></i> Теги:</strong> 
            <?php foreach ($tags as $tag): ?>
                <a href="/todo/tasks/by-tag/<?= $tag['id'] ?>" class="tag"><?= htmlspecialchars($tag['name']) ?></a>
            <?php endforeach; ?>
        </p>
        <hr>
        <p><strong><i class="fa-solid fa-file-prescription"></i> Описание:</strong> <em><?php echo htmlspecialchars($task['description'] ?? ''); ?></em></p>
        <hr>
        <div class="d-flex justify-content-start action-task">
            <form action="/todo/tasks/update-status/<?php echo $task['id']; ?>" method="POST" class="me-2">
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="btn <?=$task['status'] == 'cancelled' ? 'btn-dark' : 'btn-secondary';?>">Отменен</button>
            </form>
            <form action="/todo/tasks/update-status/<?php echo $task['id']; ?>" method="post" class="me-2">
                <input type="hidden" name="status" value="new">
                <button type="submit" class="btn <?=$task['status'] == 'new' ? 'btn-dark' : 'btn-secondary';?>">Новый</button>
            </form>
            <form action="/todo/tasks/update-status/<?php echo $task['id']; ?>" method="post" class="me-2">
                <input type="hidden" name="status" value="in_progress">
                <button type="submit" class="btn <?=$task['status'] == 'in_progress' ? 'btn-dark' : 'btn-secondary';?>">В прогрессе</button>
            </form>
            <form action="/todo/tasks/update-status/<?php echo $task['id']; ?>" method="post" class="me-2">
                <input type="hidden" name="status" value="on_hold">
                <button type="submit" class="btn <?=$task['status'] == 'on_hold' ? 'btn-dark' : 'btn-secondary';?>">On hold</button>
            </form>
            <form action="/todo/tasks/update-status/<?php echo $task['id']; ?>" method="post" class="me-2">
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn <?=$task['status'] == 'completed' ? 'btn-dark' : 'btn-secondary';?>">Закончен</button>
            </form>
            <a href="/todo/tasks/edit/<?php echo $task['id']; ?>" class="btn btn-primary me-2">Edit</a>
            <a href="/todo/tasks/delete/<?php echo $task['id']; ?>" class="btn btn-danger me-2">Delete</a>
        </div>
    </div>
</div>
     

<?php $content = ob_get_clean(); 
include 'app/views/layout.php';
?>