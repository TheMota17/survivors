<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/sys.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/gamedata.php';

    $ivent = $pdo->fetchAll('SELECT * FROM `ivent` WHERE `user_id` = ? AND `colvo` > ?', array($Sys->user_info('userinfo', 'id'), 0));
    
    function items_info($item, $ivent) {
        $items = [0, 0];

        foreach($ivent as $iv) {
            if ($iv[ 'item' ] == 13 && $iv[ 'type' ] == 1) {
                $items[ 0 ] = $iv[ 'colvo' ];
            } else if ($iv[ 'item' ] == 2 && $iv[ 'type' ] == 1) {
                $items[ 1 ] = $iv[ 'colvo' ];
            }
        }

        switch( $item ) {
            case 'food':
                return $items[ 0 ];
            break;
            case 'water':
                return $items[ 1 ];
            break;
        }
    }

    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/modules/tablo.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/modules/menu.php';
?>

<div class='flex j-c mt10'>
    <div class='game-meteoсharact backgr2 flex j-sa pt5 pb5'>
        <div class='meteocharact-time'>
            <img class='item14-1 mr5' src='/img/icons/time.png'><span id='time'><?=convertTime( $Sys->user_info('userinfo', 'user_time') )?></span>
        </div>
        <div class='meteocharact-weather'>
            <img class='item14-1 mr5' src='<?=$game_weathers[ $Sys->user_info('userinfo', 'user_weather') ]['img']?>' id='weather_img'>
            <span id='weather_name'><?=$game_weathers[ $Sys->user_info('userinfo', 'user_weather') ]['nm']?></span>
        </div>
        <div class='meteocharact-temp'>
            <img class='item14-1 mr5' src='/img/icons/temp.png'><span id='temp'><?=$game_temps[$Sys->user_info('userinfo', 'user_temp')]['nm']?></span>°
        </div>
    </div>
</div>

<div class='flex j-c mt10'>
    <div class='game-location backgr2 pb5'>
        <div class='flex j-c ai-c pt5 pb5 fnt15'>
            <img src='/img/icons/loc.png' class='item14-1 mr5' />
            <div class='loc-name mr5' id='loc_name'>
                <? if ($Sys->user_info('userinfo', 'in_refuge')) : ?>
                    Убежище
                <? else : ?>
                    <?=$game_locs[ $Sys->user_info('userinfo', 'loc') ][ 'nm' ]?>
                <? endif; ?>
            </div>
        </div>
        <? if ($Sys->user_info('userinfo', 'in_refuge') == 0) : ?>
            <div class='flex j-c pb5 fnt12'>
                <span class='mr5'>Исследовано </span><span id='loc_explored'><?=$Sys->user_info('userinfo', 'loc_explored')?></span>%
            </div>
        <? endif; ?>

        <div class='flex j-c'>
            <? if ($game_locs[ $Sys->user_info('userinfo', 'loc') ][ 'img' ][ $Sys->user_info('userinfo', 'user_weather') ]) : ?>
                <img src='<?=$game_locs[ $Sys->user_info('userinfo', 'loc') ][ 'img' ][ $Sys->user_info('userinfo', 'user_weather') ]?>' class='game-loc-img' />
            <? else : ?>
                <img src='<?=$game_locs[ $Sys->user_info('userinfo', 'loc') ][ 'img' ][ 1 ]?>' class='game-loc-img' />
            <? endif; ?>
        </div>

        <div class='flex j-c mt5'>
            <div class='loc-whatsrch'>
                <div class='mb5 '>Можно найти:</div>
                <div class='game-avai-items flex' id='available_items'>
                    <? foreach($game_locs[ $Sys->user_info('userinfo', 'loc') ]['srch_items'] as $si) : ?>
                        <div class='item32-1 mr5 mb5 flex j-c ai-c'>
                            <a href='/item?item=<?=$si['i']?>&type=<?=$si['t']?>&view=1' class='ajax'>
                                <img src='<?=$game_items[ $si['t'] ][ $si['i'] ]['img']?>'/>
                            </a>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        </div>

        <div class='flex j-c ai-c fl-di-co mt5'>
            <button class='move-btn srchloc-btn relative flex j-s ai-c' id='srchloc'>
                <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
                    <img src='/img/icons/loc.png'/>
                </div>
                <span id='txt_srchloc'>Искать другое место</span>
                <div class='game-btn-bar' id='bar_srchloc'></div>
            </button>
            <button class='move-btn srchlut-btn mt5 relative flex j-s ai-c' id='srchlut'>
                <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
                    <img src='/img/icons/lut.png'/>
                </div>
                <span id='txt_srchlut'>Искать предметы</span>
                <div class='game-btn-bar' id='bar_srchlut'></div>
            </button>
        </div>
        
    </div>
</div>

<div class='flex j-c mt10'>
    <div class='game-actions backgr2 flex j-c ai-c fl-di-co pt5 pb5'>

        <div class='flex j-c wdth86'>
            <button class='move-btn game-eat-btn relative flex j-sb ai-c' id='eat'>
                <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
                    <img src='/img/items/others/bread.png' class='item14-1'/>
                </div>
                <span id='txt_eat'>Есть</span>
                <div class='game-item-colvo flex j-c ai-c mr5'>
                    <span id='food_colvo'><?=items_info('food', $ivent)?></span>
                </div>
                <div class='game-btn-bar' id='bar_eat'></div>
            </button>
        </div>

        <div class='flex j-c wdth86'>
            <button class='move-btn game-drink-btn relative mt5 flex j-sb ai-c' id='drink'>
                <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
                    <img src='/img/items/others/water.png' style='width: 10px; height: 14px;' />
                </div>
                <span id='txt_drink'>Пить</span>
                <div class='game-item-colvo flex j-c ai-c mr5'>
                    <span id='water_colvo'><?=items_info('water', $ivent)?></span>
                </div>
                <div class='game-btn-bar' id='bar_drink'></div>
            </button>
        </div>

        <div class='flex j-c wdth86' id='sleep_button'>
            <button class='game-sleep-btn relative mt5 flex j-s ai-c' id='sleep'>
                <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
                    <img src='/img/icons/sleep.png' />
                </div>
                <span id='txt_sleep'>Спать</span>
            </button>
        </div>

        <div class='sleep-range flex j-c fl-di-co wdth86 mt10 pt5 pb5 none' id='sleep_range'>
            <div class='flex j-c'>
                <img class='mr5' src='/img/icons/sleep.png' /> Спать: <div class='ml5' id='sleep_time'>1</div>ч.
            </div>
            <div class='flex j-c'>
                <input class='sleep-range-input mt10' id='sleep_range_input' type='range' min='1' max='24' step='1' value='1'>
            </div>
            <div class='flex j-c mt10'>
                <button class='cost-ready-btn mr10' id='sleep_no'>Отмена</button>
                <button class='cost-ready-btn' id='sleep_yes'>Спать</button>
            </div>
        </div>

    </div>
</div>