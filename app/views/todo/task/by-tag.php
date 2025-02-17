<?php
$title = "Список задач тега $tagname";
ob_start(); 

?>
    <h1 class="mb-4 by-task-tag-title">Задачи тега: 
        <span>
            "<?=$tagname ?> "
        </span>
    </h1>
    <a href="/todo/tasks/create" class="btn btn-success mb-3">Создать Задачу</a>
    <div class="d-flex justify-content-around row filter-priority">
        <a data-priority="low" class="btn mb-3 col-2 sort-btn" style="background: #51A5F4">Low</a>
        <a data-priority="medium" class="btn mb-3 col-2 sort-btn" style="background: #3C7AB5">Medium</a>
        <a data-priority="high" class="btn mb-3 col-2 sort-btn" style="background: #274F75">High</a>
        <a data-priority="urgent" class="btn mb-3 col-2 sort-btn" style="background: #122436">Urgent</a>
    </div>
    <div class="accordion" id="tasks-accordion">
        <?php foreach ($tasks as $task): ?>
            <?php
                $priorityColor = '';
                switch ($task['priority']) {
                    case 'low':
                        $priorityColor = '#51A5F4';
                        break;
                    case 'medium':
                        $priorityColor = '#3C7AB5';
                        break;
                    case 'high':
                        $priorityColor = '#274F75';
                        break;
                    case 'urgent':
                        $priorityColor = '#122436';
                        break;
                    }
                ?>
            <div class="accordion-item mb-2" >
                <div class="accordion-header d-flex justify-content-between align-items-center row" id="task-<?php echo $task['id']; ?>">
                    <h2 class="accordion-header col-12 col-md-6">
                        <button style="background-color: <?=$priorityColor ?>;" class="accordion-button collapsed" data-priority=<?=$task['priority']?> type="button" data-bs-toggle="collapse" data-bs-target="#task-collapse-<?php echo $task['id']; ?>" aria-expanded="false" aria-controls="task-collapse-<?php echo $task['id']; ?>">
                            <span class="col-12 col-md-5"><i class="fa-solid fa-square-up-right"></i> <strong><?php echo $task['title']; ?> </strong></span>
                            <span class="col-5 col-md-3"><i class="fa-solid fa-person-circle-question"></i> <?php echo $task['priority']; ?> </span>
                            <span class="col-5 col-md-3"><i class="fa-solid fa-hourglass-start"></i><span class="due-date"><?php echo $task['finish_date']; ?></span></span>
                        </button>
                    </h2>
                </div>
                <div id="task-collapse-<?php echo $task['id']; ?>" class="accordion-collapse collapse row" aria-labelledby="task-<?php echo $task['id']; ?>" data-bs-parent="#tasks-accordion">
                    <div class="accordion-body">
                        <p class="row">
                            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-layer-group"></i> Категория:</strong> <?php echo htmlspecialchars($task['category']['title'] ?? 'N/A'); ?></span>
                            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-battery-three-quarters"></i> Статус:</strong> <?php echo htmlspecialchars($task['status']); ?></span>
                        </p>
                        <p class="row">
                            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-person-circle-question"></i> Приоритет:</strong> <?php echo htmlspecialchars($task['priority']); ?></span>
                            <span class="col-12 col-md-6"><strong><i class="fa-solid fa-hourglass-start"></i> Due Date:</strong> <?php echo htmlspecialchars($task['finish_date']); ?></span>
                        </p>
                        <p><strong><i class="fa-solid fa-file-prescription"></i> Теги:</strong> 
                            <?php foreach ($task['tags'] as $tag): ?>
                                <a href="/todo/tasks/by-tag/<?= $tag['id'] ?>" class="tag"><?= htmlspecialchars($tag['name']) ?></a>
                            <?php endforeach; ?>
                        </p>
                        <p>
                            <strong><i class="fa-solid fa-file-prescription"></i> Описание:</strong> <em><?php echo htmlspecialchars($task['description'] ?? ''); ?></em>
                        </p>
                        <hr>
                        <div class="d-flex justify-content-start action-task">
                            <a href="/todo/tasks/edit/<?php echo $task['task_id']; ?>" class="btn btn-primary me-2">Редактировать</a>
                            <form action=""<?="/todo/tasks/delete/{$task['task_id']}"?>" method="post">
                                <button type="submit" onclick="return confirm('Действительно хотите удалить задачу?')" class="btn btn-outline-danger">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php $content = ob_get_clean(); 
include 'app/views/layout.php';
?>