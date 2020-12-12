<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/db.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/funcs.php';

    // Создаем токен если его нету
    if (! $_SESSION['token'])
        $_SESSION['token'] = token();

    // Ищем игроков которые онлайн
    $online = $pdo->rows('SELECT * FROM `users` WHERE `lastvisit` > ?', array(time() - 600));
?>

<!DOCTYPE html>
<html lang='ru'>
<head>
    <link rel='shortcut icon' href='/favicon.png' type='image/x-icon'>

    <link rel='stylesheet' href='/style/index.css'>
    <link rel='stylesheet' href='/style/style.css'>

    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, minimum-scale=1, maximum-scale=1' />

    <script src='/js/libs/jquery.js' defer></script>
    <script src='/js/ui.js' defer></script>
    <script src='/js/app.js' defer></script>

    <title>Survivors | Created by Mota</title>
</head>
<body>

<div class='page-load-bar' id='page_load_bar'>
    <div class='load-bar' id='load_bar'></div>
</div>

<div class='pop-up none' id='pop-up'>
    <div class='pop-up-body'>
        <div class='pop-up-content'>
            <div class='pop-up-maincontent txt-center fnt13' id='main-txt'></div>
            <div class='flex j-c'>
            <button class='close-btn' id='close-btn'>Ок</button>
            </div>
        </div>
    </div>
</div>

<input class='flex j-c none' id='token' value='<?=$_SESSION['token']?>'>

<div class='container' id='container'></div>

<div class='flex j-c fl-di-co ai-c txt-center fnt12 mt10 op5 pb5'>
    <div class='flex j-c ai-c'>
    <span><img class='item14-1' src='/favicon.png' /> Survivors (v0.1) created by Mota</span> 
    </div>
    <div class='flex j-c ai-c mt5'>
        Онлайн <?=$online?>
    </div>
</div>

</body>
</html>