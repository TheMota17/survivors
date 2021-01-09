<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/sys.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/gamedata.php';

    $ivent = $pdo->fetchAll('SELECT * FROM `ivent` WHERE `user_id` = ? AND `colvo` > ?', array($Sys->user_info('userinfo', 'id'), 0));
    $map   = $pdo->fetch('SELECT * FROM `map` WHERE `user_id` = ?', array($Sys->user_info('userinfo', 'id')));
    $map   = json_decode( $map['data'] );
    
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
        <div class='flex j-c ai-c'>
            <img class='item14-1 mr5' src='/assets/icons/time.png'><span id='time'><?=convertTime( $map->time )?></span>
        </div>
        <div class='flex j-c ai-c'>
            <img class='item14-1 mr5' src='<?=$game_weathers[ $map->weather ]['img']?>' id='weather_img'>
            <span id='weather_name'><?=$game_weathers[ $map->weather ]['nm']?></span>
        </div>
        <div class='flex j-c ai-c'>
            <img class='item14-1 mr5' src='/assets/icons/temp.png'><span id='temp'><?=$game_temps[ $map->temp ]['nm']?></span>°
        </div>
    </div>
</div>

<div class='flex j-c mt10'>
    <div class='game-location backgr2 pb5'>
        <div class='flex j-c ai-c pt5 pb5 fnt15'>
            <img src='/assets/icons/loc.png' class='item14-1 mr5' />
            <div class='loc-name mr5' id='loc_name'>
                <? if ($Sys->user_info('userinfo', 'in_refuge')) : ?>
                    Убежище
                <? else : ?>
                    <?=$game_locs[ $map->loc ][ 'nm' ]?>
                <? endif; ?>
            </div>
        </div>
        <? if ($Sys->user_info('userinfo', 'in_refuge') == 0) : ?>
            <div class='flex j-c pb5 fnt12'>
                <span class='mr5'>Исследовано </span><span id='loc_explored'><?=$map->loc_explored?></span>%
            </div>
        <? endif; ?>

        <div class='wdth100 relative flex j-c'>
            <div class='game-canvas-loader flex j-c ai-c' id='game_canv_loader'>
                <div class='flex j-c ai-c'>
                    Загрузка...
                </div>
            </div>
            <div class='game-canvas-btns flex j-e ai-e'>
                <div class='game-canvas-btns-wdth flex j-c ai-c fl-di-co'>
                    <div class='flex j-c ai-c'>
                        <button class='game-btn game-btn-min mb5 mr5' id='up_l'></button>
                            <button class='game-btn game-btn-big' id='up'></button>
                        <button class='game-btn game-btn-min mb5 ml5' id='up_r'></button>
                    </div>
                    <div class='flex j-c'>
                        <button class='game-btn game-btn-big mr10' id='left'></button>
                        <button class='game-btn game-btn-big ml10' id='right'></button>
                    </div>
                    <div class='flex j-c'>
                        <button class='game-btn game-btn-min mt5 mr5' id='down_l'></button>
                            <button class='game-btn game-btn-big' id='down'></button>
                        <button class='game-btn game-btn-min mt5 ml5' id='down_r'></button>
                    </div>
                </div>
            </div>
            <canvas class='game-canvas flex j-c ai-c' id='game_canv' width='300' height='300'>Данная технология не поддерживается вашим браузером</canvas>
        </div>

        <? if (! $Sys->user_info('userinfo', 'in_refuge')) : ?>
            <div class='flex j-c mt5'>
                <div class='loc-whatsrch'>
                    <div class='mb5 '>Можно найти:</div>
                    <div class='game-avai-items flex' id='available_items'>
                        <? foreach($game_locs[ $map->loc ]['srch_items'] as $si) : ?>
                            <div class='item32-1 mr5 mb5 flex j-c ai-c'>
                                <a href='/item?item=<?=$si['i']?>&type=<?=$si['t']?>&view=1' class='ajax'>
                                    <img src='<?=$game_items[ $si['t'] ][ $si['i'] ]['img']?>'/>
                                </a>
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
            </div>
        <? else : ?>
            <div class='flex j-c ai-c mt5 mb5'>
                В убежище нельзя вести поиски
            </div>
        <? endif; ?>
        
    </div>
</div>

<div class='flex j-c mt10'>
    <div class='game-actions backgr2 flex j-c ai-c fl-di-co pt5 pb5'>

        <div class='flex j-c wdth86'>
            <button class='move-btn game-eat-btn relative flex j-sb ai-c' id='eat'>
                <div class='game-btn-icon ml5 mr5 flex j-c ai-c'>
                    <img src='/assets/items/others/bread.png' class='item14-1'/>
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
                    <img src='/assets/items/others/water.png' style='width: 10px; height: 14px;' />
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
                    <img src='/assets/icons/sleep.png' />
                </div>
                <span id='txt_sleep'>Спать</span>
            </button>
        </div>

        <div class='sleep-range flex j-c fl-di-co wdth86 mt10 pt5 pb5 none' id='sleep_range'>
            <div class='flex j-c'>
                <img class='mr5' src='/assets/icons/sleep.png' /> Спать: <div class='ml5' id='sleep_time'>1</div>ч.
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

<script type='module'>

import {GameLive} from '../js/game/GameLive.js';
import {Camera} from '../js/game/Camera.js';
import {GameWorld} from '../js/game/GameWorld.js';
import {Player} from '../js/game/Player.js';

(function() {

    let Resources = {
        loadSprites: function(all_sprites)
        {
            this.load        = 0;
            this.all_sprites = all_sprites;
            let sprites      = {};

            for(let i = 0; i < all_sprites.length; i++)
            {
                let sprite     = new Image();
                sprite.src     = all_sprites[i].path + all_sprites[i].nm + '.png';
                sprite.onload  = () => {this.load+=1};

                sprites[ all_sprites[i]['nm'] ] = sprite;
            }

            this.check = setInterval(function() { Resources.checkLoad() }, 1);

            return sprites;
        },
        checkLoad: function()
        {
            if (this.load >= this.all_sprites.length)
            {
                Game.create();
                clearInterval(this.check);
            }
        }
    };

    let Updater = {
        update: function() 
        {
            if (window.location.pathname !== '/game') clearInterval(this.timer);

            $.ajax({
                url: 'core/ajax/gameload.php?action=update',
                method: 'POST',
                data: {
                    x:            Game.player.x,
                    y:            Game.player.y,
                    s:            Game.player.s,
                    time:         Game.gameLive.time,
                    weather:      Game.gameLive.weather,
                    weatherTime:  Game.gameLive.weatherTime,
                    temp:         Game.gameLive.temp,
                    loc:          Game.world.loc,
                    loc_explored: Game.world.loc_explored,
                    hp:           Game.player.hp,
                    hung:         Game.player.hung,
                    thirst:       Game.player.thirst,
                    fatigue:      Game.player.fatigue,
                    token:        $('#token').val()
                }
            });
        },
        pagedate: function() 
        {
            let info_elems = ['hp', 'hung', 'thirst', 'fatigue'];

            for(let i = 0; i < info_elems.length; i++)
            {
                this.elems[ info_elems[ i ] ].html(Game.player[ info_elems[ i ] ]);
            }

            this.elems.time.html( Utils.convertTime(Math.floor(Game.gameLive.time)) );
            this.elems.temp.html( Game.temps[ Game.gameLive.temp ]['nm'] );
            this.elems.weather_name.html( Game.weathers[ Game.gameLive.weather ]['nm'] );
            this.elems.weather_img.attr('src', Game.weathers[ Game.gameLive.weather ]['img'] );
        },
        start: function()
        {
            this.elems = {
                hp: $('#hp'),
                hung: $('#hung'),
                thirst: $('#thirst'),
                fatigue: $('#fatigue'),
                time: $('#time'),
                temp: $('#temp'),
                weather_name: $('#weather_name'),
                weather_img: $('#weather_img') 
            };

            this.timer = setInterval(function() {
                Updater.update();
            }, 5000);
        }
    };
    Updater.start();

    let Utils = {
        rand: function(min, max) 
        {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
        },
        convertTime: function( time ) 
        {
            let minutes = Math.floor(time / 60);
            let hours   = Math.floor(minutes / 60);
            minutes     = minutes - (hours * 60);

            if (hours < 10) {
                if (minutes < 10) 
                    return '0' + hours + ':0' + minutes;
                else 
                    return '0' + hours + ':' + minutes;
            } else {
                if (minutes < 10) 
                    return hours + ':0' + minutes;
                else 
                    return hours + ':' + minutes;
            }
        }
    };

    let Game = {
        loop: function() 
        {
            // Sys
            let dt = (Date.now() - Game.lastDt) / 1000;

            Game.update( dt );
            Game.render( dt );

            Game.lastDt = Date.now();

            if (window.location.pathname == '/game') window.requestAnimationFrame(Game.loop);
        },

        update: function(dt)
        {
            this.gameLive.update(dt);
            this.player.update(dt, this.loc.width, this.loc.height);
            this.camera.update(dt, this.player.x, this.player.y, this.loc.width, this.loc.height);
            
            Updater.pagedate();
        },

        render: function(dt)
        {
            // Sys
            this.ctx.clearRect(0, 0, this.canv.width, this.canv.height);

            // Game
            this.ctx.save();
            this.ctx.translate(-this.camera.x, -this.camera.y);

                    this.world.render(this.ctx, this.camera);
                    this.player.render(this.ctx);

            this.ctx.restore();

            this.gameLive.render(this.ctx, this.canv);

            this.ctx.fillStyle = '#dac09c';
            this.ctx.fillText('FPS: ' + Math.floor((1000 / dt) / 1000), 2, 10);
        },

        start: function()
        {
            Game.loop();
            document.getElementById('game_canv_loader').style.display = 'none'; // Загрузка...
        },

        create: function()
        {
            // Sys
            this.canv                      = document.getElementById('game_canv');
            this.canv.width                = 400;
            this.canv.height               = 400;

            this.ctx                       = this.canv.getContext('2d', {alpha: false});
            this.ctx.imageSmoothingEnabled = false;
            this.ctx.font                  = '13px Courier new';
            this.ctx.fillStyle             = '#dac09c';

            this.lastDt = Date.now();
            this.pause  = false;

            // Game
            this.gameLive = new GameLive(this.data.game.time, this.data.game.weather, this.data.game.temp, this.data.game.weatherTime);
            this.camera   = new Camera(0, 0, this.canv.width, this.canv.height);
            this.world    = new GameWorld(this.sprites, this.data.game.loc, this.data.game.loc_explored);
            this.player   = new Player(
                this.data.game.x,
                this.data.game.y,
                this.data.game.s,
                this.data.user.hp,
                this.data.user.hung,
                this.data.user.thirst,
                this.data.user.fatigue,
                this.sprites['pl']
            );

            this.weathers = this.data.sys.weathers;
            this.temps    = this.data.sys.temps;

            this.loc      = {width: 1024, height: 1024};

            this.start();
        },

        load: function()
        {
            $.ajax({
                url: 'core/ajax/gameload.php?action=load',
                method: 'POST',
                data:{ token: $('#token').val() },
                success: function(data)
                {
                    Game.data       = JSON.parse(data);
                    let all_sprites = [
                        {nm: 'pl', path: '/assets/game/'}, 
                        {nm: 'loc_' + Game.data.game.loc, path: '/assets/game/'}
                    ];
                    Game.sprites    = Resources.loadSprites(all_sprites);
                }
            });
        }
    }; // end of game
    Game.load();
    
})(); // 176
</script>