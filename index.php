<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/db.php';

    $online = $Pdo->rows('SELECT * FROM `users` WHERE `lastvisit` > ?', array(time() - 600));
?>

<!DOCTYPE html>
<html lang='ru'>
<head>
    <link rel='shortcut icon' href='/assets/favicon.png' type='image/x-icon'>

    <link rel='preconnect' href='https://fonts.gstatic.com'>
    <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swapp' rel='stylesheet'>

    <link rel='stylesheet' href='/styles/index.css?_=<?=time()?>'>
    <link rel='stylesheet' href='/styles/style.css?_=<?=time()?>'>

    <meta name='keywords' content='survivors, survs, srvs, выжившие, онлайн игра, выживание, браузерная игра, игра'>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, minimum-scale=1, maximum-scale=1' />

    <script src='/js/libs/vue.js' defer></script>
    <script src='/js/libs/httpVueLoader.js' defer></script>
    <script src='/js/libs/vue-router.js' defer></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js' defer></script>

    <title>Survivors | Выжившие</title>
</head>
<body>

<div id='app'>
    <popup></popup>
    <menyoo></menyoo>
    <page-bar></page-bar>
    <router-view></router-view>
</div>

<div class='flex j-c fl-di-co ai-c mt10 op5 pb5'>
    <div class='flex j-c ai-c'>
        <?=date('H:i:s')?> | В игре: <?=$online?>
    </div>
    <div class='flex j-c ai-c mt5'>
        <img class='item14-1 mr5' src='/assets/favicon.png' /> © by <a class='ml5 mr5' href='https://vk.com/mota17'>Mota</a> 2020-2021, 18+
    </div>
</div>

<script type='module' src='/js/app.js?_=<?=time()?>'></script>

</body>
</html>