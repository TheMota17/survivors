class Server {
	constructor(io, connection, gameData, cfg)
	{
		this.io = io;
		this.connection = connection;
		this.gameData = gameData;
		this.cfg = cfg;

		this.dt = Date.now();
		this.time = 36000;

		this.players = {};
		this.items = {
			1: this.generateServerItems(1)
		};

		this.gameLoopId = setInterval(() => this.mainGameLoop(), this.cfg.game.TICK);
	}

	mainGameLoop()
	{
		const dt = (Date.now() - this.dt) / 1000;

		// Время сервера
		this.moveServerTime(dt);

		// Проверка кол-ва предметов на карте, спавн
		for(const loc in this.items)
		{
			if (Object.keys( this.items[ loc ] ).length <= 0)
				this.items[ loc ] = this.generateServerItems(loc);
		}

		// Игроки
		for(const key in this.players)
		{
			const player = this.players[ key ];
			// Если в массиве игроков пока что нет игрока, выходим из функции
			if (!player)
				return;

			if (player.exited)
			{
				delete this.players[ player.socketId ];
				return;
			}

			// Движение игроков
			switch(player.moveDir)
			{
				case 'left':
					if ((player.x - this.cfg.game.PLAYERS_WH/2) - player.speed * dt <= 0)
						this.players[ player.socketId ].modeDir = false;
					else
						this.players[ player.socketId ].x -= player.speed * dt;
					break;
				case 'top':
					if ((player.y - this.cfg.game.PLAYERS_WH/2) - player.speed * dt <= 0)
						this.players[ player.socketId ].moveDir = false;
					else
						this.players[ player.socketId ].y -= player.speed * dt;
					break;
				case 'right':
					if ((player.x + this.cfg.game.PLAYERS_WH/2) + player.speed * dt >= this.gameData.locs[ player.loc ].width)
						this.players[ player.socketId ].moveDir = false;
					else
						this.players[ player.socketId ].x += player.speed * dt;
					break;
				case 'down':
					if ((player.y + this.cfg.game.PLAYERS_WH/2) + player.speed * dt >= this.gameData.locs[ player.loc ].height)
						this.players[ player.socketId ].moveDir = false;
					else
						this.players[ player.socketId ].y += player.speed * dt;
					break;
			}

			for(const key in this.items[ player.loc ])
			{
				const item = this.items[player.loc][key];

				// Если игрок зацепил предмет
				if (player.x - this.cfg.game.PLAYERS_WH/2 < item.x + this.cfg.game.ITEMS_WH/2 &&
					player.x + this.cfg.game.PLAYERS_WH/2 > item.x - this.cfg.game.ITEMS_WH/2 &&
					player.y - this.cfg.game.PLAYERS_WH/2 < item.y + this.cfg.game.ITEMS_WH/2 &&
					player.y + this.cfg.game.PLAYERS_WH/2 > item.y - this.cfg.game.ITEMS_WH/2)
				{
					// Если запись данного типа предмета уже есть
					const inventItem = this.srchItemInInventWithoutId(player.socketId, item.item, item.type);
					if (inventItem)
						// То добавляем кол-во подобранного предмета в бд
						this.itemAddMore(player.socketId, inventItem, item.colvo);
					else
						// Если нет, то создаем новую запись
						this.createItem(player.socketId, item.item, item.type, item.colvo);

					// Уведомление о подобранном предмете
					this.players[player.socketId].lastItem = {
						colvo: item.colvo,
						img: this.gameData.items[item.type][item.item].img,
						time: Date.now()
					};

					// Удаляем предмет из массива предметов сервера
					delete this.items[player.loc][key];

					// Уведомляем клиентов об удаленном предмете
					this.itemDeleteMess(key);
				}
			}

			this.io.to( player.socketId ).emit('update', this.getSendDataForPlayer(player.socketId));
		}

		this.dt = Date.now();
	}

	moveServerTime(dt)
	{
		// 86400 = 23:59
		if ((this.time + (dt * this.cfg.game.TIME_SCAL)) >= 86400)
		{
			this.time = 0;
		} else
		{
			this.time += dt * this.cfg.game.TIME_SCAL;
		}
	}

	playerConnect(socket, cookies)
	{
		if (!cookies.user || !cookies.hash)
		{
			return;
		}

		this.connection.execute('SELECT * FROM users WHERE id = ? AND sess_hash = ?', [cookies.user, cookies.hash], (err1, user) => {
			if (err1) console.log(err1);

			this.connection.execute('SELECT * FROM invent WHERE user_id = ?', [cookies.user], (err2, items) => {
				if (err2) console.log(err2);

				this.connection.execute('SELECT * FROM nadeto WHERE user_id = ?', [cookies.user], (err3, nadeto) => {
					if (err3) console.log(err3);

					// Получаем радиус атаки игрока
					const userRadius = (nadeto[0].type4 === 0) ? false : this.gameData['items'][ 4 ][ nadeto[0]['type4'] ]['radius'];

					// Добавляем игрока в массив
					this.players[ socket.id ] = {
						login: user[0].login,
						craftLvl: user[0].craft_lvl,

						x: user[0].x,
						y: user[0].y,
						loc: user[0].loc,
						hp: user[0].hp,
						maxhp: user[0].maxhp,
						hung: user[0].hung,
						thirst: user[0].thirst,
						rad: user[0].rad,
						speed: user[0].speed,
						atackRadius: userRadius,
						moveDir: false,

						items: items,
						nadeto: nadeto[0],

						userId: user[0].id,
						socketId: socket.id,
						lastMessTime: Date.now(),

						lastItem: false,
						popup: false,
						exitPlayer: false,
						delItem: false,
						givedDmg: false,

						exited: false
					};

					console.log(user[0].login + ' connected');

					// Событие дисконнекта игрока
					socket.on('disconnect', (reason) => {
						console.log(this.players[ socket.id ].login + ' diconnected');

						this.kickPlayer(socket);
						this.playerExitMess(socket);
					});

					socket.on('message', (message) => {
						switch(message.type)
						{
							case 'move':
								if (this.players[ socket.id ].moveDir === message.data.dir)
									this.players[ socket.id ].moveDir = false;
								else
									this.players[ socket.id ].moveDir = message.data.dir;
								break;
							case 'atack':
								this.atackPlayer(socket.id);
								break;
						}
					});
				});
			});
		});
	}

	kickPlayer(socket)
	{
		if (!this.players[ socket.id ])
			return;

		this.connection.execute('UPDATE users SET loc = ?, x = ?, y = ?, hp = ?, hung = ?, thirst = ?, rad = ? WHERE id = ?', [
			this.players[ socket.id ].loc,
			this.players[ socket.id ].x,
			this.players[ socket.id ].y,
			this.players[ socket.id ].hp,
			this.players[ socket.id ].hung,
			this.players[ socket.id ].thirst,
			this.players[ socket.id ].rad,
			this.players[ socket.id ].userId
		]);

		// Помечаем игрока как вышедшего, чтобы в главном цикле (при итерации) его удалить
		this.players[ socket.id ].exited = true;
	}

	playerExitMess(socket)
	{
		for(const key in this.players)
		{
			this.players[ key ].exitPlayer = {id: socket.id, time: Date.now()};
		}
	}

	getSendDataForPlayer(socketId, gameData)
	{
		return {
			server: {
				time: this.time,
				userLocName: this.gameData.locs[ this.players[ socketId ].loc ]['nm'],
				sendTime: Date.now()
			},
			players: this.players,
			items: this.items[ this.players[ socketId ].loc ],
			user: this.players[ socketId ]
		};
	}

	generateServerItems(loc)
	{
		let items = {};
		let location = this.gameData.locs[ loc ];

		for(const item of location.srch_items)
		{
			let lastKey = Object.keys(items).length + 1;

			item.x = Math.floor(Math.random() * (location.width - 0 + 1));
			item.y = Math.floor(Math.random() * (location.height - 0 + 1));
			item.colvo = Math.floor(Math.random() * (4 - 1 + 1)) + 1;

			items[ lastKey ] = item;
		}

		return items;
	}

	itemDeleteMess(itemId)
	{
		for(const key in this.players)
		{
			this.players[ key ].delItem = {id: itemId, time: Date.now()};
		}
	}

	srchItemInInvent(socket, itemId)
	{
		for(let item of this.players[ socket.id ].items)
		{
			if (item.id == itemId && item.colvo > 0)
			{
				item.idx = this.players[ socket.id ].items.indexOf(item);

				return item;
			}
		}
	}

	itemSubstr(socket, item, substrColvo)
	{
		this.connection.execute('UPDATE invent SET colvo = ? WHERE id = ?', [item.colvo - substrColvo, item.id]);
		this.players[ socket.id ].items[ item.idx ].colvo -= substrColvo;
	}

	srchItemInInventWithoutId(socketId, item, type)
	{
		for(let inventItem of this.players[ socketId ].items)
		{
			if (inventItem.item == item && inventItem.type == type)
			{
				inventItem.idx = this.players[ socketId ].items.indexOf(inventItem);
				return inventItem;
			}
		}
	}

	itemAddMore(socketId, item, colvo)
	{
		this.connection.execute('UPDATE invent SET colvo = ? WHERE id = ?', [item.colvo + colvo, item.id]);
		this.players[ socketId ].items[ item.idx ].colvo += colvo;
	}

	createItem(socketId, item, type, colvo)
	{
		this.connection.query('INSERT INTO invent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', [item, type, colvo, this.players[ socketId ].userId], (err, result) => {
			this.players[ socketId ].items.push({
				id: result.insertId,
				item: item,
				type: type,
				colvo: colvo,
				user_id: this.players[ socketId ].userId
			});
		});
	}

	nadetItem(socket, typeName, item)
	{
		this.connection.execute('UPDATE nadeto SET '+ typeName +' = ? WHERE user_id = ?', [item, this.players[ socket.id ].userId]);
		this.players[ socket.id ].nadeto[ typeName ] = item;
	}

	userItemsExist(socket, items, colvo)
	{
		const itemsColvo = item.length;
		let itemsExist = 0;

		for(const item of items)
		{
			const itemInInvent = this.srchItemInInventWithoutId(socket, item.item, item.type);
				if (itemInInvent)
				{
					if (itemInInvent.colvo >= (item.colvo * colvo))
						itemsExist++;
				}
		}

		if (itemsExist >= itemsColvo)
			return true;
	}

	eat(socket, gameData, itemId)
	{
		const inventItem = this.srchItemInInvent(socket, itemId);

		if (!inventItem)
			return;

		const item = this.gameData.items[ inventItem.type ][ inventItem.item ];

		if (item.move !== 'eat')
			return;

		if (this.players[ socket.id ].hung < item.eff['hung'])
		{
			this.players[ socket.id ].popup = {text: 'Вы не голодны', time: Date.now()};
		}
	}

	dist(x1, y1, x2, y2)
	{
		let x_d = (x2 - x1);
		let y_d = (y2 - y1);

		return Math.sqrt(x_d * x_d + y_d * y_d);
	}

	atackPlayer(socketId)
	{
		for(const key in this.players)
		{
			const player = this.players[ key ];
			const user   = this.players[ socketId ];

			if (player.socketId !== socketId)
			{
				let dist = this.dist(player.x, player.y, user.x, user.y) - this.cfg.game.PLAYERS_WH; // Все игроки рисуются относительно их центра, поэтому к дистанции добавляем две половины игроков

				if (user.atackRadius && user.atackRadius >= dist)
				{
					this.players[ key ].hp -= 10;

					this.players[ key ].givedDmg = {
						dmg: 10,
						time: Date.now()
					};
				}
			}
		}
	}
}

module.exports = Server;