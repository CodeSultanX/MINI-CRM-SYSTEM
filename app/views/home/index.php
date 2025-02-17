<?php

error_reporting(E_ALL);
ini_set('display errors',1);
$title = 'Главная страница';
ob_start();

?>
<h1>Главная страница</h1>

<div id="calendar"></div>



<?php $path = '/todo/tasks/task/' ?>

<script>
const tasksJson = <?=$tasks?>;
const events = tasksJson.map((task) => {
    return {
        title : task.title,
        start : new Date(task.created_at),
        end : new Date(task.finish_date),
        extendedProps : {
            task_id : task.id
        }
    }
})
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'ru',
        events: events, // Задачи в виде событий на календаре
        eventClick: function (info) {
            const taskId = info.event.extendedProps.task_id;
            // URL для  адреса страницы конкретной задачи
            const taskUrl = `<?=$path;?>${taskId}`;
            //переход на страницу задачи
            window.location.href = taskUrl;
        },
    });

  calendar.render();
});
</script>



<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
