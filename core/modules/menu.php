<div class='menu-target none' id='menu-target'></div>
<div class='menu relative none' id='menu'>
    <div class='menu-list pl5 pr5'>
        <div class='menu-zag flex j-c mt10'>Игра</div>
            <a href='/game' class='menu-a ajax mt10'>
                <span class='ml5'> <img src='/img/icons/menu/game.png'> Игра</span>
            </a>
            <a href='/ivent?page=1' class='menu-a ajax mt10'>
                <span class='ml5'> <img src='/img/icons/menu/ivent.png'> Инвентарь</span>
            </a>
            <a href='/craft?type=2' class='menu-a ajax mt10'>
                <span class='ml5'> <img src='/img/icons/menu/craft.png'> Крафт</span>
            </a>
            <a href='/refuge' class='menu-a ajax mt10'>
                <span class='ml5'> <img src='/img/icons/menu/refuge.png'> Убежище</span>
            </a>
        <div class='menu-zag flex j-c mt10'>Online</div>
            <a href='/duel' class='menu-a ajax mt10'>
                <span class='ml5'> <img src='/img/icons/menu/duel.png'> Дуэль</span>
            </a>
            <a href='/shop' class='menu-a ajax mt10'>
                <span class='ml5'> <img src='/img/icons/menu/shop.png'> Магазин</span>
            </a>
            <a href='/chat' class='menu-a ajax mt10'>
                <span class='ml5'> <img src='/img/icons/menu/chat.png'> Общий чат</span>
            </a>
        <div class='menu-zag flex j-c mt10'>Система</div>
            <? $user = $Sys->get_user(); ?>
            <? if ($user['adm'] > 0) : ?>
                <a href='/panel' class='menu-a ajax mt10'>
                    <span class='ml5'> <img src='/img/icons/adm.png'> Панель</span>
                </a>
            <? endif; ?>
            <a href='/settings' class='menu-a ajax mt10'>
                <span class='ml5'> <img src='/img/icons/menu/settings.png'> Настройки</span>
            </a>
            <a href='/' class='menu-a ajax mt10' id='upd-page'>
                <span class='ml5'> <img src='/img/icons/menu/reload.png'> Обновить</span>
            </a>
    </div>
</div>