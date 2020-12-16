<div class='menu-target none' id='menu-target'></div>
<div class='menu relative none' id='menu'>
    <div class='menu-list pl5 pr5'>
        <div class='menu-zag flex j-c mt10'>Игра</div>
            <a href='/game' class='menu-a ajax flex j-c ai-c mt10'>
                <span class='ml5'> <img src='/img/icons/menu/game.png' class='item14-1'> Игра</span>
            </a>
            <a href='/ivent?page=1' class='menu-a ajax flex j-c ai-c mt10'>
                <span class='ml5'> <img src='/img/icons/menu/ivent.png' class='item14-1'> Инвентарь</span>
            </a>
            <a href='/craft?type=2' class='menu-a ajax flex j-c ai-c mt10'>
                <span class='ml5'> <img src='/img/icons/menu/craft.png' class='item14-1'> Крафт</span>
            </a>
            <a href='/refuge' class='menu-a ajax flex j-c ai-c mt10'>
                <span class='ml5'> <img src='/img/icons/menu/refuge.png' class='item14-1'> Убежище</span>
            </a>
        <div class='menu-zag flex j-c mt10'>Online</div>
            <a href='/duel' class='menu-a ajax flex j-c ai-c mt10'>
                <span class='ml5'> <img src='/img/icons/menu/duel.png' class='item14-1'> Дуэль</span>
            </a>
            <a href='/shop' class='menu-a ajax flex j-c ai-c mt10'>
                <span class='ml5'> <img src='/img/icons/menu/shop.png' class='item14-1'> Магазин</span>
            </a>
            <a href='/chat' class='menu-a ajax flex j-c ai-c mt10'>
                <span class='ml5'> <img src='/img/icons/menu/chat.png' class='item14-1'> Общий чат</span>
            </a>
        <div class='menu-zag flex j-c mt10'>Система</div>
            <? $user = $Sys->get_user(); ?>
            <? if ($user['adm'] > 0) : ?>
                <a href='/panel' class='menu-a ajax flex j-c ai-c mt10'>
                    <span class='ml5'> <img src='/img/icons/adm.png' class='item14-1'> Панель</span>
                </a>
            <? endif; ?>
            <a href='/settings' class='menu-a ajax flex j-c ai-c mt10'>
                <span class='ml5'> <img src='/img/icons/menu/settings.png' class='item14-1'> Настройки</span>
            </a>
            <a href='/' class='menu-a ajax flex j-c ai-c mt10' id='upd-page'>
                <span class='ml5'> <img src='/img/icons/menu/reload.png' class='item14-1'> Обновить</span>
            </a>
    </div>
</div>