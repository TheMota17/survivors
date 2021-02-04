let Updater = {
    update(data)
    {
        if (window.location.pathname !== '/game') clearInterval(this.timer);

        let params = new FormData();
    	params.append('x', data.player.x);
    	params.append('y', data.player.y);
    	params.append('s', data.player.s);
    	params.append('time', data.gameLive.time);
    	params.append('weather', data.gameLive.weather);
    	params.append('weatherTime', data.gameLive.weatherTime);
    	params.append('temp', data.gameLive.temp);
    	params.append('loc', data.world.loc);
    	params.append('loc_explored', data.world.loc_explored);
    	params.append('hp', data.player.hp);
    	params.append('hpTime', data.player.hpTime);
    	params.append('hung', data.player.hung);
    	params.append('hungTime', data.player.hungTime);
    	params.append('thirst', data.player.thirst);
    	params.append('thirstTime', data.player.thirstTime);
    	params.append('fatigue', data.player.fatigue);
    	params.append('fatigueTime', data.player.fatigueTime);
    	params.append('token', localStorage.getItem('token'));

        axios.post('/core/ajax/Game_load.php?action=update', params)
		.then((response) => {})
		.catch((error) => {
			console.log(error)
		})
    },
    pagedate(dt, data, Utils) 
    {
        let info_elems = ['hp', 'hung', 'thirst', 'fatigue'];

        for(let i = 0; i < info_elems.length; i++)
        {
            this.elems[ info_elems[ i ] ].innerHTML = data.player[ info_elems[ i ] ];
        }

        this.elems.time.innerHTML         = Utils.convertTime(Math.floor(data.gameLive.time));
        this.elems.temp.innerHTML         = data.temps[ data.gameLive.temp ]['nm'];
        this.elems.weather_name.innerHTML = data.weathers[ data.gameLive.weather ]['nm'];
        this.elems.weather_img.src        = data.weathers[ data.gameLive.weather ]['img'];
        this.elems.loc_name.innerHTML     = data.locs[ data.world.loc ]['nm'];
        this.elems.loc_explored.innerHTML = data.world.loc_explored;
        this.elems.fps.innerHTML          = Math.floor((1000 / dt) / 1000);
    },
    start()
    {
        this.elems = {
            hp: document.getElementById('hp'),
            hung: document.getElementById('hung'),
            thirst: document.getElementById('thirst'),
            fatigue: document.getElementById('fatigue'),
            time: document.getElementById('time'),
            temp: document.getElementById('temp'),
            weather_name: document.getElementById('weather_name'),
            weather_img: document.getElementById('weather_img'),
            loc_name: document.getElementById('loc_name'),
            loc_explored: document.getElementById('loc_explored'),
            fps: document.getElementById('fps')
        };
    }
}

export {Updater};