<?php
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/sys.php';
    require ''.$_SERVER['DOCUMENT_ROOT'].'/core/gamedata.php';

    Class Panel {
        private $pdo;

        private $message;
                
        private $user;

    	public function __construct($pdo, $user)
        {
        	
            $this->pdo = $pdo;

            $this->user = $user;

    	}

        public function answer( $ans ) {

        	switch( $ans ) {
                case 'done':
                    exit( json_encode( ['message' => 'done', 'popup' => false] ) );
                break;
                case 'mess':
                    exit( json_encode( ['message' => $this->message, 'popup' => true] ) );
                break;
                case 'exit':
                    exit( $this->message );
                break;
            }

        }

        public function isAdmin() {

            if ($this->user['adm'] !== 1) exit();

        }

    	public function main() {

    		$this->isAdmin();

    	}
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

        $Panel = new Panel($pdo, $Sys->getUser());
        $Panel->main();

    } else { exit('Hi!'); }

    require 'core/modules/tablo.php';
    require 'core/modules/menu.php';
?>

<div class='flex j-c fl-di-co mt10'>
    <div class='bolder'>
        Create
    </div>
    <div class='panel-moves pt5 pb5 mt5'>
        <div class='create-newuser'>
            <div class='flex j-c'>
                Добавить игрока
            </div>
            <div class='flex j-s fl-di-co ml5 mt5'>
                Имя <input type='text' id='name_newuser'>
            </div>
            <div class='flex j-s fl-di-co ml5 mt5'>
                Пароль <input type='text' id='pass_newuser'>
            </div>
            <div class='flex j-s fl-di-co ml5 mt5'>
                Почта <input type='text' id='mail_newuser'>
            </div>
            <div class='flex j-c'>
                <button id='create_newuser'>Добавить</button>
            </div>
        </div>
    </div>
    <div class='bolder mt5'>
        Read
    </div>
    <div class='panel-moves pt5 pb5 mt5'>
        <div class='read-getuserinfo'>
            <div class='flex j-c'>
                Информация об игроке
            </div>
            <div class='flex j-c fl-di-co ml5 mt5'>
                ID игрока или Ник <input type='text' id='id_getuserinfo'>
            </div>
            <div class='flex j-c ai-c fl-di-co mt5 mb5' id='read_userinfo'></div>
            <div class='flex j-c'>
                <button id='read_getuserinfo'>Получить</button>
            </div>
        </div>
    </div>
    <div class='bolder mt5'>
        Update
    </div>
    <div class='panel-moves pt5 pb5 mt5'>
        <div class='update-info'>
            <div class='flex j-c'>
                Обновить данные
            </div>
            <div class='flex j-c fl-di-co ml5 mt5'>
                Имя таблицы <input type='text' id='tablename_data'>
            </div>
            <div class='flex j-c fl-di-co ml5 mt5'>
                Имя ячейки <input type='text' id='cellname_data'>
            </div>
            <select class='flex j-c ml5 mt5' id='select_idtype_data'>
                <option value='1'>ID записи</option>
                <option value='2'>ID игрока</option>
            </select>
            <div class='flex j-c fl-di-co ml5 mt5'>
                <input type='text' id='id_data'>
            </div>
            <div class='flex j-c fl-di-co ml5 mt5'>
                Изменить на <input type='text' id='changeon_data'>
            </div>
            <div class='flex j-c'>
                <button id='update_data'>Изменить</button>
            </div>
        </div>
    </div>
    <div class='bolder mt5'>
        Delete
    </div>
    <div class='panel-moves pt5 pb5 mt5'>
        <div class='delete-ban'>
            <div class='flex j-c'>
                Заблокировать игрока
            </div>
            <div class='flex j-c fl-di-co ml5 mt5'>
                ID игрока <input type='text' id='id_ban'>
            </div>
            <div class='flex j-c fl-di-co ml5 mt5'>
                На сколько <input type='text' id='time_ban'>
            </div>
            <div class='flex j-c'>
                <button id='delete_ban'>Бан</button>
            </div>
        </div>
    </div>
</div>

<script defer>
    let panel = {
        data: {
            'workpath': '/panel',
        },
        methods: {
            messHand: function( data ) {
                if ( data['dom_data'] ) {
                    panel.render( data );
                } else if ( data['popup'] ) {
                    popup.methods.activate( data['message'] );
                } else if ( data['page'] ) {
                    pageLoader.update('loadPage', data['page']);
                } else if ( data['reload'] ) {
                    pageLoader.update('loadPage', window.location.pathname + window.location.search);
                }
            }
        },
        pageAvai: function() {
            if (panel.data['workpath'] === window.location.pathname) return true;
        },
        update: function(move, unmove) {
            if (panel.pageAvai()) {
                switch( move ) {
                    case 'create':

                        switch( unmove ) {
                            case 'newuser':
                                jQuery.ajax({
                                url: 'core/panel.php?move=create&unmove=newuser',
                                type: 'POST',
                                data: {name: $('#name_newuser').val(), pass: $('#pass_newuser').val(), mail: $('#mail_newuser').val()},
                                success: function( data ) {
                                    if ( data ) {
                                        data = JSON.parse( data );
                                        panel.methods.messHand( data );
                                    }
                                }
                                });
                            break;
                        }

                    break;
                    case 'read':

                        switch( unmove ) {
                            case 'userinfo':
                                jQuery.ajax({
                                url: 'core/panel.php?move=read&unmove=userinfo',
                                type: 'POST',
                                data: {id: $('#id_getuserinfo').val()},
                                success: function( data ) {
                                    if ( data ) {
                                        data = JSON.parse( data );
                                        panel.methods.messHand( data );
                                    }
                                }
                                });
                            break;
                        }

                    break;
                    case 'update':

                        switch( unmove ) {
                            case 'update':
                                //
                            break;
                        }

                    break;
                    case 'delete':

                        switch( unmove ) {
                            case 'ban':
                                jQuery.ajax({
                                url: 'core/panel.php?move=delete&unmove=ban',
                                type: 'POST',
                                data: {id: $('#id_ban').val(), time: $('#time_ban').val()},
                                success: function( data ) {
                                    if ( data ) {
                                        data = JSON.parse( data );
                                        panel.methods.messHand( data );
                                    }
                                }
                                });
                            break;
                        }

                    break;
                }
            }
        },
        render: function( data ) {
            
            $('#read_userinfo').html( data['data']['userinfo'] );

        },
        start: function() {
            $('#create_newuser').on('click', function( e ) {
                panel.update('create', 'newuser');
            });
            $('#read_getuserinfo').on('click', function( e ) {
                panel.update('read', 'userinfo');
            });
            $('#delete_ban').on('click', function( e ) {
                panel.update('delete', 'ban');
            });
            $('#update_data').on('click', function( e ) {
                panel.update('update', 'data');
            });
        }
    }; panel.start();
</script>