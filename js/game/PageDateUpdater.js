let PageDateUpdater = {
	create()
	{
		this.elems.time         = document.getElementById('time');
		this.elems.weather_name = document.getElementById('weather_name');
		this.elems.weather_img  = document.getElementById('weather_img').src;
		this.elems.temp         = document.getElementById('temp');
		this.loc_name           = document.getElementById('loc_name');
		this.loc_explored       = document.getElementById('loc_explored');
		this.fps                = document.getElementById('fps');
	}
}

export {PageDateUpdater};