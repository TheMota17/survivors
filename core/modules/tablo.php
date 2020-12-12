<div class='hght30' id='tablo'>
    <div class='info-table backgr2 flex j-c ai-c'>
        
        <div class='info-hp fl1 flex j-e'>
            <div class='hp-img'>
            <img src='/img/icons/hp.png' alt='Жизнь'/>
            </div>
            <div class='hp-quantity flex ai-c ml5' id='hp'><?=$Sys->user_info('userinfo', 'hp')?></div>
        </div>
        
        <div class='info-hung fl1 flex j-e'>
            <div class='hung-img'>
            <img src='/img/icons/hung.png' alt='Голод'/>
            </div>
            <div class='hung-quantity flex ai-c ml5' id='hung'><?=$Sys->user_info('userinfo', 'hung')?></div>
        </div>
        
        <div class='info-thirst fl1 flex j-e'>
            <div class='thirst-img'>
            <img src='/img/icons/thirst.png' alt='Жажда'/>
            </div>
            <div class='thirst-quantity flex ai-c ml5' id='thirst'><?=$Sys->user_info('userinfo', 'thirst')?></div>
        </div>
        
        <div class='info-fatigue fl1 flex j-e'>
            <div class='fatigue-img'>
            <img src='/img/icons/sleep.png' alt='Истощение'/>
            </div>
            <div class='fatigue-quantity flex ai-c ml5' id='fatigue'><?=$Sys->user_info('userinfo', 'fatigue')?></div>
        </div>
        
        <div class='fl1 flex j-e mr10 relative'>
            <a class='menu-btn' id='menu-btn'></a>
        </div>
        
    </div>
</div>