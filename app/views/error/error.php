<?php
$title = 'Страница не найдено';
ob_start();
?>

<h1>Page not found</h1>


<?php
$content = ob_get_clean();
include 'app/views/layout.php';
?>
