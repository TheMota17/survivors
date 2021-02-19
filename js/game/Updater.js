let Updater = {
    update(game)
    {
        if (window.location.pathname !== '/game') clearInterval(this.timer);

        let params = new FormData();
    	params.append('x', game.Player.x);
    	params.append('y', game.Player.y);
    	params.append('time', game.GameLive.time);
    	params.append('weather', game.GameLive.weather);
    	params.append('weatherTime', game.GameLive.weatherTime);
    	params.append('temp', game.GameLive.temp);
    	params.append('hp', game.Player.hp);
    	params.append('hpTime', game.Player.hpTime);
    	params.append('hung', game.Player.hung);
    	params.append('hungTime', game.Player.hungTime);
    	params.append('thirst', game.Player.thirst);
    	params.append('thirstTime', game.Player.thirstTime);
    	params.append('fatigue', game.Player.fatigue);
    	params.append('fatigueTime', game.Player.fatigueTime);
    	params.append('token', localStorage.getItem('token'));

        axios.post('/core/GameLoad/?action=update', params)
    },
    pagedate(dt, game, Utils)
    {
        let info_elems = ['hp', 'hung', 'thirst', 'fatigue'];

        for(let i = 0; i < info_elems.length; i++)
        {
            this.elems[ info_elems[ i ] ].innerHTML = game.Player[ info_elems[ i ] ];
        }

        this.elems.time.innerHTML         = Utils.convertTime(Math.floor(game.GameLive.time));
        this.elems.temp.innerHTML         = game.temps[ game.GameLive.temp ]['nm'];
        this.elems.weather_name.innerHTML = game.weathers[ game.GameLive.weather ]['nm'];
        this.elems.weather_img.src        = game.weathers[ game.GameLive.weather ]['img'];
        this.elems.loc_name.innerHTML     = game.locs[ game.getAjaxData().game.loc ]['nm'];
        this.elems.loc_explored.innerHTML = game.getAjaxData().game.loc_explored;
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