const io = require('socket.io')();
const mysql = require('mysql2');
const cookie = require('cookie');
const cfg = require('./modules/config');
const gameData = require('./modules/gameData');
const Server = require('./modules/server');

const connection = mysql.createConnection({
	host: 'localhost',
	user: 'mysql',
	database: 'survivors',
	password: 'mysql'
});

const server = new Server(io, connection, gameData, cfg);

io.on('connection', (socket) => {
	// Если у коннекта нет кукисов
	if (!socket.handshake.headers.cookie)
		return;

	// Парсим куки
	const cookies = cookie.parse(socket.request.headers.cookie);

	// Коннектим игрока
	server.playerConnect(socket, cookies);
});

io.listen(cfg.io.LISTEN);