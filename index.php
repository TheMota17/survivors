<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/db.php';

    // Ищем игроков которые онлайн
    $online = $pdo->rows('SELECT * FROM `users` WHERE `lastvisit` > ?', array(time() - 600));
?>

<!DOCTYPE html>
<html lang='ru'>
<head>
    <link rel='shortcut icon' href='/assets/favicon.png' type='image/x-icon'>

    <link rel='preconnect' href='https://fonts.gstatic.com'>
    <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swapp' rel='stylesheet'>

    <link rel='stylesheet' href='/style/index.css?17'>
    <link rel='stylesheet' href='/style/style.css?17'>

    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, minimum-scale=1, maximum-scale=1' />

    <script src='/js/libs/vue.js' defer></script>
    <script src='/js/libs/httpVueLoader.js' defer></script>
    <script src='/js/libs/vue-router.js' defer></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js' defer></script>

    <title>Survivors | Created by Mota</title>
</head>
<body>

<div id='app'>
    <popup></popup>
    <tablo></tablo>
    <menyoo></menyoo>
    <page-bar></page-bar>
    <router-view></router-view>
</div>

<div class='flex j-c fl-di-co ai-c txt-center fnt12 mt10 op5 pb5'>
    <div class='flex j-c ai-c'>
    <img class='item14-1 mr5' src='/assets/favicon.png' /> Survivors (v0.1) created by Mota
    </div>
    <div class='flex j-c ai-c mt5'>
        Онлайн <?=$online?>
    </div>
</div>

<script type='module' src='/js/app.js?17'></script>

</body>
</html>