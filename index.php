<!DOCTYPE html>
<html lang='ru'>
<head>
    <link rel='shortcut icon' href='/front/assets/favicon.png' type='image/x-icon'>

    <link rel='preconnect' href='https://fonts.gstatic.com'>
    <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swapp' rel='stylesheet'>

    <link rel='stylesheet' href='/front/styles/index.css?_=<?=time()?>'>
    <link rel='stylesheet' href='/front/styles/style.css?_=<?=time()?>'>

    <meta charset='UTF-8'>
    <meta name='keywords' content='survivors, survs, srvs, выжившие, онлайн игра, выживание, браузерная игра, игра, сталкер'>
    <meta name='viewport' content='width=device-width, minimum-scale=1, maximum-scale=1' />

    <title>The Survivors | Выжившие</title>
</head>
<body>

<div id='app'>
    <popup></popup>
    <router-view></router-view>
</div>

<div class='flex j-c fl-di-co ai-c mt5 op5 pb5'>
    <div class='flex j-c ai-c'>
        <?=date('H:i:s')?> по Мск.
    </div>
    <div class='flex j-c ai-c mt5'>
        <img class='item14-1 mr5' src='/front/assets/favicon.png' /> © by <a class='ml5 mr5' href='https://vk.com/mota17'>Mota</a> 2020-2021, 18+
    </div>
</div>

<script src='/front/libs/httpVueLoader.js' defer></script>
<script src='/front/libs/vue.js' defer></script>
<script src='/front/libs/vue-router.js' defer></script>
<script src='/front/libs/axios.min.js' defer></script>
<script src='/front/libs/socket.io.js' defer></script>
<script src='/front/libs/pixi.min.js' defer></script>
<script src='/front/libs/pixi-filters.js' defer></script>

<script type='module' src='/front/App.js'></script>

</body>
</html>