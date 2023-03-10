<?php 

use core\Application;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Some default title</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">HEADER</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contacts">Контакты</a></li>
                    <?php if (Application::$app->user): ?>
                        <li class="nav-item pull-right"><a class="nav-link" href="/logout">Выйти</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/register">Регистрация</a></li>
                        <li class="nav-item"><a class="nav-link" href="/login">Вход</a></li>
                    <?php endif; ?>
                </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <?php  if (Application::$app->session->getFlash('success')): ?>
        <div class="alert alert-success"><?php echo Application::$app->session->getFlash('success'); ?></div>
        <?php endif; ?>
        <div class="container mt10">{{content}}</div>
    </main>
</body>
</html>