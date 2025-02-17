<?php
$role =isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'No-name';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>

    <link rel="stylesheet" href="/app/css/style.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c07007720d.js" crossorigin="anonymous"></script>

    <!-- flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- JavaScript Calendar | https://fullcalendar.io/ -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
    <!-- fullcalendar локализация русского языка -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/locales/ru.js"></script>

  
</head>
<body>

<div class="wrapper">
<!-- Навигационная панель -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?='/'?>">CRM System for Telegram 
        <i class="fa-brands fa-telegram"></i>
    </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if($role == 5 || !PERMISSION_MANAGMENT) { ?>
                <li class="nav-item"><a class="nav-link <?=is_active('/' )?>" href="<?='/'?>">Главная</a></li>
                <li class="nav-item"><a class="nav-link <?=is_active('/users')?>" href="<?='/users'?>">Пользователи</a></li>
                <li class="nav-item"><a class="nav-link <?=is_active('/roles')?>" href="<?='/roles'?>">Роли</a></li>
                <li class="nav-item"><a class="nav-link <?=is_active('/pages')?>" href="<?='/pages'?>">Страницы</a></li>
                <?php } ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        To Do
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="<?='/todo/category'?>">Категорий</a></li>
                        <li><a class="dropdown-item" href="<?='/todo/tasks'?>">Срочные задачи</a></li>
                        <li><a class="dropdown-item" href="<?='/todo/tasks/completed'?>">Завершенные задачи</a></li>
                        <li><a class="dropdown-item" href="<?='/todo/tasks/expired'?>">Просроченные задачи</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $username?><i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="<?='/auth/register'?>">Регистрация</a></li>
                        <li><a class="dropdown-item" href="<?='/auth/login'?>">Авторизация</a></li>
                        <li><a class="dropdown-item" href="<?='/auth/logout'?>">Выход</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Контент -->
<main class="container my-4">
    <div class="card shadow p-4">
        <?php echo $content; ?>
    </div>
</main>

<!-- Подвал -->
<footer class="bg-dark text-white w-100 text-center py-3"">
    <p class="mb-0">© <?php echo date('Y'); ?> MINI CRM SYSTEM</p> 
</footer>
</div>


<!-- Bootstrap Bundle с Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="/app/js/script.js"></script>
</body>
</html>
