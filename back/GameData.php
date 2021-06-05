<?php

    $game_items = [ // Предметы
        1 => [ // Others: food, wood and more...
            1 => ['nm' => 'Мясо', 'eff' => ['hung' => 35, 'hp' => 10], 'move' => 'eat', 'rare' => 1, 'img' => '/front/assets/items/others/meat.png', 'type' => 'Пища'],
            2 => ['nm' => 'Вода', 'eff' => ['thirst' => 10, 'hp' => 2], 'move' => 'drink', 'rare' => 1, 'img' => '/front/assets/items/others/water.png', 'type' => 'Пища'],
            3 => ['nm' => 'Дрова', 'rare' => 1, 'img' => '/front/assets/items/others/wood.png', 'type' => 'Материал'],
            4 => ['nm' => 'Палка', 'rare' => 1, 'img' => '/front/assets/items/others/stick.png', 'type' => 'Материал'],
            5 => ['nm' => 'Камень', 'rare' => 1, 'img' => '/front/assets/items/others/rock.png', 'type' => 'Материал'],
            6 => ['nm' => 'Заточенный камень', 'rare' => 1, 'img' => '/front/assets/items/others/grin_rock.png', 'type' => 'Материал'],
            7 => ['nm' => 'Веревка', 'rare' => 1, 'img' => '/front/assets/items/others/rope.png', 'type' => 'Материал'],
            8 => ['nm' => 'Крепкая веревка', 'rare' => 1, 'img' => '/front/assets/items/others/dur_rope.png', 'type' => 'Материал'],
            9 => ['nm' => 'Стрела', 'rare' => 1, 'img' => '/front/assets/items/others/arrow.png', 'type' => 'Боеприпас'],
            10 => ['nm' => 'Крафт 2', 'move' => 'read', 'rare' => 2, 'craft_lvl' => 2, 'img' => '/front/assets/items/others/craft_book2.png', 'type' => 'Книга'],
            11 => ['nm' => 'Крафт 3', 'move' => 'read', 'rare' => 3, 'craft_lvl' => 3, 'img' => '/front/assets/items/others/craft_book3.png', 'type' => 'Книга'],
            12 => ['nm' => 'Крафт 4', 'move' => 'read', 'rare' => 4, 'craft_lvl' => 4, 'img' => '/front/assets/items/others/craft_book4.png', 'type' => 'Книга'],
            13 => ['nm' => 'Хлеб', 'eff' => ['hung' => 10, 'hp' => 3], 'move' => 'eat', 'rare' => 1, 'img' => '/front/assets/items/others/bread.png', 'type' => 'Пища'],
            14 => ['nm' => 'Консервы', 'eff' => ['hung' => 20, 'hp' => 5], 'move' => 'eat', 'rare' => 1, 'img' => '/front/assets/items/others/canned.png', 'type' => 'Пища'],
            15 => ['nm' => 'Газировка', 'eff' => ['thirst' => 25, 'hp' => 5], 'move' => 'drink', 'rare' => 1, 'img' => '/front/assets/items/others/soda.png', 'type' => 'Пища'],
            16 => ['nm' => 'Обработанное дерево', 'rare' => 1, 'img' => '/front/assets/items/others/treated_wood.png', 'type' => 'Материал'],
            17 => ['nm' => 'Металлолом', 'rare' => 1, 'img' => '/front/assets/items/others/scrap.png', 'type' => 'Материал'],
            18 => ['nm' => 'Металл', 'rare' => 1, 'img' => '/front/assets/items/others/metall.png', 'type' => 'Материал'],
        ],
        2 => [ // Helms
            1 => ['nm' => 'Шапка из дерева', 'move' => 'nadet', 'power' => 2, 'dmgabs' => 2, 'rare' => 1, 'img' => '/front/assets/items/helms/wood-cap.png', 'type' => 'Шлем'],
            2 => ['nm' => 'Деревянный шлем', 'move' => 'nadet', 'power' => 4, 'dmgabs' => 4, 'rare' => 1, 'img' => '/front/assets/items/helms/wood-helm.png', 'type' => 'Шлем']
        ],
        3 => [ // Armors
            1 => ['nm' => 'Накидка', 'move' => 'nadet', 'power' => 2, 'dmgabs' => 2, 'rare' => 1, 'img' => '/front/assets/items/arms/nakidka.png', 'type' => 'Броня'],
            2 => ['nm' => 'Броня из дерева', 'move' => 'nadet', 'power' => 4, 'dmgabs' => 4, 'rare' => 1, 'img' => '/front/assets/items/arms/wood-arm.png', 'type' => 'Броня']
        ],
        4 => [ // Weaps
            1 => ['nm' => 'Нож', 'move' => 'nadet', 'power' => 5, 'dmgmin' => 2, 'dmgmax' => 4, 'radius' => 10,  'rare' => 1, 'img' => '/front/assets/items/weaps/knife.png', 'type' => 'Оружиe'],
            2 => ['nm' => 'Большой нож', 'move' => 'nadet', 'power' => 10, 'dmgmin' => 4, 'dmgmax' => 8, 'radius' => 20, 'rare' => 1, 'img' => '/front/assets/items/weaps/big_knife.png', 'type' => 'Оружие'],
            3 => ['nm' => 'Деревянный топор', 'move' => 'nadet', 'power' => 14, 'dmgmin' => 8, 'dmgmax' => 12, 'radius' => 22, 'rare' => 1, 'img' => '/front/assets/items/weaps/axe.png', 'type' => 'Оружие'],
            4 => ['nm' => 'Лук', 'move' => 'nadet', 'ammu' => [['i' => 9, 't' => 1]], 'power' => 18, 'dmgmin' => 12, 'dmgmax' => 14, 'radius' => 36, 'rare' => 1, 'img' => '/front/assets/items/weaps/bow.png', 'type' => 'Оружие'],
            5 => ['nm' => 'Арбалет', 'move' => 'nadet', 'ammu' => [['i' => 9, 't' => 1]], 'power' => 24, 'dmgmin' => 14, 'dmgmax' => 18, 'radius' => 37, 'rare' => 2, 'img' => '/front/assets/items/weaps/rebalet.png', 'type' => 'Оружие']
        ]
    ];

    $game_crafts = [ // Рецепты крафтов
        ['item' => 1, 'type' => 2, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 20], ['item' => 4, 'type' => 1, 'colvo' => 5] ]],
        ['item' => 2, 'type' => 2, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 50], ['item' => 4, 'type' => 1, 'colvo' => 15] ]],

        ['item' => 1, 'type' => 3, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 25], ['item' => 4, 'type' => 1, 'colvo' => 10], ['item' => 7, 'type' => 1, 'colvo' => 5] ]],
        ['item' => 2, 'type' => 3, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 60], ['item' => 4, 'type' => 1, 'colvo' => 20], ['item' => 7, 'type' => 1, 'colvo' => 10] ]],

        ['item' => 1, 'type' => 4, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 10], ['item' => 7, 'type' => 1, 'colvo' => 5], ['item' => 6, 'type' => 1, 'colvo' => 1] ]],
        ['item' => 2, 'type' => 4, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 15], ['item' => 7, 'type' => 1, 'colvo' => 10], ['item' => 6, 'type' => 1, 'colvo' => 4] ]],
        ['item' => 3, 'type' => 4, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 25], ['item' => 8, 'type' => 1, 'colvo' => 5], ['item' => 6, 'type' => 1, 'colvo' => 6] ]],
        ['item' => 4, 'type' => 4, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 25], ['item' => 8, 'type' => 1, 'colvo' => 10] ]],
        ['item' => 5, 'type' => 4, 'craft_lvl' => 2, 'craft_items' => [ ['item' => 3, 'type' => 1, 'colvo' => 20], ['item' => 4, 'type' => 1, 'colvo' => 30], ['item' => 8, 'type' => 1, 'colvo' => 20] ]],

        ['item' => 6, 'type' => 1, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 5, 'type' => 1, 'colvo' => 3] ]],
        ['item' => 8, 'type' => 1, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 7, 'type' => 1, 'colvo' => 5] ]],
        ['item' => 9, 'type' => 1, 'craft_lvl' => 1, 'craft_items' => [ ['item' => 4, 'type' => 1, 'colvo' => 1], ['item' => 6, 'type' => 1, 'colvo' => 1], ['item' => 7, 'type' => 1, 'colvo' => 1] ]],
    ];

    $game_locs = [ // Локации
        1 => [
            'id' => 1,
            'nm' => 'Небольшая деревня',
            'srch_items' => [
                ['item' => 13, 'type' => 1],
                ['item' => 14, 'type' => 1],
                ['item' => 2,  'type' => 1],
                ['item' => 15, 'type' => 1],
                ['item' => 3,  'type' => 1],
                ['item' => 4,  'type' => 1],
                ['item' => 5,  'type' => 1],
            ],
            'prev_img' => '/front/assets/locs/village.jpg',
            'loc_img'  => '/front/assets/game/loc_1.png',
            'loc_width' => 1000,
            'loc_height' => 1000
        ],
        2 => [
            'id' => 2,
            'nm' => 'Большая деревня',
            'srch_items' => [
                ['item' => 1,  'type' => 1],
                ['item' => 2,  'type' => 1],
                ['item' => 13, 'type' => 1],
                ['item' => 14, 'type' => 1],
                ['item' => 3,  'type' => 1],
                ['item' => 4,  'type' => 1],
                ['item' => 5,  'type' => 1],
                ['item' => 7,  'type' => 1],
                ['item' => 10, 'type' => 1]
            ],
            'prev_img' => '/front/assets/locs/big_village.jpg',
            'loc_img'  => '/front/assets/game/loc_1.png',
            'loc_width' => 1000,
            'loc_height' => 1000
        ]
    ];

    $game_rares = [ // Рамка, цвет, слово предметов
        1 => ['border' => 'usual_border', 'class' => 'usual', 'word' => 'Обычное'],
        2 => ['border' => 'rare_border', 'class' => 'rare', 'word' => 'Редкое'],
        3 => ['border' => 'epic_border', 'class' => 'epic', 'word' => 'Эпическое'],
        4 => ['border' => 'legend_border', 'class' => 'legend', 'word' => 'Легендарное']
    ];