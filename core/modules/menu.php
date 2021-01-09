<div class='menu-target none' id='menu-target'></div>
<div class='menu relative none' id='menu'>
    <div class='menu-list pl5 pr5'>
        <div class='menu-zag flex j-c fnt14 mt10'>Игра</div>
            <a href='/game' class='menu-a ajax flex j-c ai-c mt10'>
                <img src='/assets/icons/menu/game.png' class='item14-1 ml5 mr5'> Игра
            </a>
            <a href='/ivent?page=1' class='menu-a ajax flex j-c ai-c mt10'>
                <img src='/assets/icons/menu/ivent.png' class='item14-1 ml5 mr5'> Инвентарь
            </a>
            <a href='/craft?type=2' class='menu-a ajax flex j-c ai-c mt10'>
                <img src='/assets/icons/menu/craft.png' class='item14-1 ml5 mr5'> Крафт
            </a>
            <a href='/refuge' class='menu-a ajax flex j-c ai-c mt10'>
                <img src='/assets/icons/menu/refuge.png' class='item14-1 ml5 mr5'> Убежище
            </a>
        <div class='menu-zag flex j-c fnt14 mt10'>Online</div>
            <a href='/duel' class='menu-a ajax flex j-c ai-c mt10'>
                <img src='/assets/icons/menu/duel.png' class='item14-1 ml5 mr5'> Дуэль
            </a>
            <a href='/shop' class='menu-a ajax flex j-c ai-c mt10'>
                <img src='/assets/icons/menu/shop.png' class='item14-1 ml5 mr5'> Магазин
            </a>
            <a href='/chat' class='menu-a ajax flex j-c ai-c mt10'>
                <img src='/assets/icons/menu/chat.png' class='item14-1 ml5 mr5'> Общий чат
            </a>
        <div class='menu-zag flex j-c fnt14 mt10'>Система</div>
            <? $user = $Sys->get_user(); ?>
            <? if ($user['adm'] > 0) : ?>
                <a href='/panel' class='menu-a ajax flex j-c ai-c mt10'>
                    <img src='/assets/icons/adm.png' class='item14-1 ml5 mr5'> Панель
                </a>
            <? endif; ?>
            <a href='/settings' class='menu-a ajax flex j-c ai-c mt10'>
                <img src='/assets/icons/menu/settings.png' class='item14-1 ml5 mr5'> Настройки
            </a>
            <a href='/' class='menu-a ajax flex j-c ai-c mt10' id='upd-page'>
                <img src='/assets/icons/menu/reload.png' class='item14-1 ml5 mr5'> Обновить
            </a>
    </div>
</div>