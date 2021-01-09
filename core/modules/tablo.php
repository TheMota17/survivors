<div class='hght25' id='tablo'>
    <div class='info-table backgr2 flex j-c ai-c'>
        
        <div class='info-hp fl1 flex j-e ai-c'>
            <img src='/assets/icons/hp.png' class='item14-1' alt='Жизнь' />
            <span class='ml5 fnt14' id='hp'><?=$Sys->user_info('userinfo', 'hp')?></span>
        </div>
        
        <div class='info-hung fl1 flex j-e ai-c'>
            <img src='/assets/icons/hung.png' class='item14-1' alt='Голод' />
            <span class='ml5 fnt14' id='hung'><?=$Sys->user_info('userinfo', 'hung')?></span>
        </div>
        
        <div class='info-thirst fl1 flex j-e ai-c'>
            <img src='/assets/icons/thirst.png' class='item14-1' alt='Жажда' />
            <span class='ml5 fnt14' id='thirst'><?=$Sys->user_info('userinfo', 'thirst')?></span>
        </div>
        
        <div class='info-fatigue fl1 flex j-e ai-c'>
            <img src='/assets/icons/sleep.png' class='item14-1' alt='Истощение' />
            <span class='ml5 fnt14' id='fatigue'><?=$Sys->user_info('userinfo', 'fatigue')?></span>
        </div>
        
        <div class='fl1 flex j-e ai-c mr10 relative'>
            <a class='menu-btn' id='menu-btn'></a>
        </div>
        
    </div>
</div>