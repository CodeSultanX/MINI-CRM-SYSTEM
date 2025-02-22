<?php
$title = 'Редактирование задачи';
ob_start();
?>
<h1 class="mb-4">Редактирование задачи</h1>

<form action="/todo/tasks/update" method="POST">
    <input type="hidden" name="id" value="<?= $task['id'] ?>">
    <div class="row">
        <!-- Title field -->
        <div class="col-12 col-md-6 mb-3">
          <label for="inputTaskName" class="form-label">Найменование</label>
          <input type="text" name="title" class="form-control" id="inputTaskName" value="<?= $task['title'] ?>">
        </div>
        <!-- Reminder date field -->
        <div class="col-12 col-md-6 mb-3">
          <label class="form-label">Напоминание</label>
          <select class="form-control" id="reminder_at" name="reminder_at">
            <option value="30_minutes">30 минут</option>
            <option value="1_hour">1 час</option>
            <option value="2_hours">2 часа</option>
            <option value="12_hours">12 часов</option>
            <option value="24_hours">24 часа</option>
            <option value="7_days">7 дней</option>
          </select>
        </div>
    </div>
    <div class="row">
        <!-- Category field -->
        <div class="col-12 col-md-6 mb-3">
          <label class="form-label">Категория</label>
          <select class="form-control" id="category_id" name="category_id" required>
            <?php foreach($categories as $category) :?>
              <option value="<?=$category['id'] ?>" <?= $task['category_id'] == $category['id'] ? 'selected' : '' ?> ><?=$category['title'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <!-- Finish date field -->
        <div class="col-12 col-md-6 mb-3">
          <label class="form-label">Дата окончания</label>
          <input type="datetime-local" class="form-control" id="finish_date" name="finish_date" 
          value="<?= $task['finish_date'] !== null ? htmlspecialchars(str_replace(' ','T',$task['finish_date'])) : '' ?>">
        </div>
    </div>
    <div class="row">
        <!-- Status field -->
        <div class="col-12 col-md-6 mb-3">
          <label class="form-label">Статус</label>
          <select class="form-control" id="status" name="status" required>
              <option value="new" <?= $task['status'] == 'new' ? 'selected' : '' ?>>Новый</option>
              <option value="in_progress" <?= $task['status'] == 'in_progress' ? 'selected' : '' ?>>В прогрессе</option>
              <option value="completed" <?= $task['status'] == 'completed' ? 'selected' : '' ?>>Завершен</option>
              <option value="on_hold" <?= $task['status'] == 'on_hold' ? 'selected' : '' ?>>On Hold</option>
              <option value="cancelled" <?= $task['status'] == 'cancelled' ? 'selected' : '' ?>>Отменен</option>
          </select>
        </div>
        <!-- Priority field -->
        <div class="col-12 col-md-6 mb-3">
          <label class="form-label">Приоритет</label>
          <select class="form-control" id="priority" name="priority" required>
              <option value="low" <?= $task['priority'] == 'low' ? 'selected' : '' ?>>Низкий</option>
              <option value="medium" <?= $task['priority'] == 'medium' ? 'selected' : '' ?>>Средний</option>
              <option value="high" <?= $task['priority'] == 'high' ? 'selected' : '' ?>>Высокий</option>
              <option value="urgent" <?= $task['priority'] == 'urgent' ? 'selected' : '' ?>>Срочный</option>
          </select>
        </div>
    </div>
    <div class="row">
        <!-- Tags field -->
        <div class="col-12 col-md-6 mb-3">
            <label for="tags">Теги</label>
            <div class="tags-container form-control">
                <?php
                $tagNames = array_map(function ($tag) {
                    return $tag['name'];
                }, $tags);
                foreach ($tagNames as $tagName) {
                    echo "<div class='tag'>
                            <span>$tagName</span>
                            <button type='button'>×</button>
                        </div>";
                }
                ?>
                <input class="form-control" type="text" id="tag-input">
            </div>
            <input class="form-control" type="hidden" name="tags" id="hidden-tags" value="<?= htmlspecialchars(implode(', ', $tagNames)) ?>">
        </div>
        <!-- Description field -->
        <div class="col-12 col-md-6 mb-3">
            <label for="description">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($task['description'] ?? ''); ?></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Обновить задачу</button>
        </div>
    </div>
</form>


<script>
    const tagInput = document.querySelector('#tag-input');
    const tagsContainer = document.querySelector('.tags-container');
    const hiddenTags = document.querySelector('#hidden-tags');
    const existingTags = '<?= htmlspecialchars(isset($task['tags']) ? $task['tags'] : '') ?>';
    function createTag(text) {
        const tag = document.createElement('div');
        tag.classList.add('tag');
        const tagText = document.createElement('span');
        tagText.textContent = text;
        const closeButton = document.createElement('button');
        closeButton.innerHTML = '&times;';
        closeButton.addEventListener('click', () => {
            tagsContainer.removeChild(tag);
            updateHiddenTags();
        });
        tag.appendChild(tagText);
        tag.appendChild(closeButton);
        return tag;
    }
    function updateHiddenTags(){
        const tags = tagsContainer.querySelectorAll('.tag span');
        const tagText = Array.from(tags).map(tag => tag.textContent);
        hiddenTags.value = tagText.join(',');
    }
    tagInput.addEventListener('input', (e) => {
        if(e.target.value.includes(',')){
            const tagText = e.target.value.slice(0, -1).trim();
            if (tagText.length > 1) {
                const tag = createTag(tagText);
                tagsContainer.insertBefore(tag, tagInput);
                updateHiddenTags();
            }
            e.target.value = '';
        }
    });
    tagsContainer.querySelectorAll('.tag button').forEach(button =>{
        button.addEventListener('click', () => {
            tagsContainer.removeChild(button.parentElement);
            updateHiddenTags();
        });
    });
</script>

<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
