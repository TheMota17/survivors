class Player {
	constructor(x, y, s, hp, hung, thirst, fatigue, hpTime, hungTime, thirstTime, fatigueTime, img) 
	{
		this.x       = x;
		this.y       = y;
		this.s       = s; // speed
		this.hp      = hp;
		this.hung    = hung;
		this.thirst  = thirst;
		this.fatigue = fatigue;

		this.hpTime      = hpTime;
		this.hungTime    = hungTime;
		this.thirstTime  = thirstTime;
		this.fatigueTime = fatigueTime;

		this.img     = img;
		this.width   = img.width;
		this.height  = img.height;

		this.left  = false;
		this.right = false;
		this.up    = false;
		this.down  = false;

		window.addEventListener('keydown', this.input.bind(this));
		window.addEventListener('keyup', this.input.bind(this));
		$('.game-btn').on('touchstart touchend', this.input.bind(this));
	}

	input(e, camera)
	{
		switch(e.type[0]) {
			case 't':
				switch(e.target.id) {
					case 'up_l':
						if (e.type == 'touchstart') {
							this.left = true;
							this.up = true;
						} else {
							this.left = false;
							this.up = false;
						}
					break;
					case 'up_r':
						if (e.type == 'touchstart')
						{
							this.right = true;
							this.up = true;
						} else {
							this.right = false;
							this.up = false;
						}
					break;
					case 'down_l':
						if (e.type == 'touchstart') {
							this.left = true;
							this.down = true;
						} else {
							this.left = false;
							this.down = false;
						}
					break;
					case 'down_r':
						if (e.type == 'touchstart') {
							this.right = true;
							this.down = true;
						} else {
							this.right = false;
							this.down = false;
						}
					break;
					case 'left':
						if (e.type == 'touchstart') {
							this.left = true;
						} else this.left = false;
						break;
					case 'up':
						if (e.type == 'touchstart') {
							this.up = true;
						} else this.up = false;
						break;
					case 'right':
						if (e.type == 'touchstart') {
							this.right = true;
						} else this.right = false;
						break;
					case 'down':
						if (e.type == 'touchstart') {
							this.down = true;
						} else this.down = false;
						break;
				}
			break;
			case 'k':
				if (e.keyCode == 37) 
				{	
					if (e.type == 'keydown') {
						this.left = true;
					} else this.left = false;
				}
				if (e.keyCode == 38) 
				{
					if (e.type == 'keydown') {
						this.up = true;
					} else this.up = false;
				}
				if (e.keyCode == 39)
				{
					if (e.type == 'keydown') {
						this.right = true;
					} else this.right = false;
				}
				if (e.keyCode == 40)
				{
					if (e.type == 'keydown') {		
						this.down = true;
					} else this.down = false;
				}
			break;
		}
	}

	update(dt, worldWidth, worldHeight) 
	{
		if (this.left && 0 < (this.x - this.width/2))
		{
			this.x += -1 * (this.s * dt);
		} 
		if (this.right && (this.x + this.width/2) < worldWidth) 
		{
			this.x += 1 * (this.s * dt);
		} 
		if (this.up && 0 < (this.y - this.height/2))
		{
			this.y += -1 * (this.s * dt);
		} 
		if (this.down && (this.y + this.height/2) < worldHeight)
		{
			this.y += 1 * (this.s * dt);
		}

		this.changeAllCharacts(dt);
	}

	render(ctx)
	{
		ctx.fillText('Вы', (this.x - ctx.measureText('Вы').width/2), this.y - 12);
		ctx.drawImage(this.img, 0, 0, this.width, this.height, this.x - this.width/2, this.y - this.height/2, this.width, this.height);
	}

	changeAllCharacts(dt)
	{
		if (this.hungTime >= 300) 
		{
			if (this.hung < 100)
			{
				this.hung++;
				this.hungTime = 0;
			}
		} else this.hungTime += 10 * dt;

		if (this.thirstTime >= 150)
		{
			if (this.thirst < 100)
			{
				this.thirst++;
				this.thirstTime = 0;
			}
		} else this.thirstTime += 10 * dt;

		if (this.fatigueTime >= 500)
		{
			if (this.fatigue < 100)
			{
				this.fatigue++;
				this.fatigueTime = 0;
			}
		} else this.fatigueTime += 10 * dt;

		if (this.hpTime >= 600)
		{
			if (this.hp > 0)
			{
				let dmgForHp  = (this.hung >= 100)    ? 1 : 0;
					dmgForHp += (this.thirst >= 100)  ? 1 : 0;
					dmgForHp += (this.fatigue >= 100) ? 1 : 0;

				if (dmgForHp == 0) 
				{
					if (this.hp < 100)
					{
						this.hp++;
					}
				} else {
					if ((this.hp - dmgForHp) < 0)
					{
						this.hp = 0;
					} else {
						this.hp -= dmgForHp;
					}
				}

				this.hpTime = 0;
			}
		} else this.hpTime += 10 * dt;
	}

	distance(p1, p2)
	{
    	return Math.sqrt(((p2.x - p1.x) ** 2) + ((p2.y - p1.y) ** 2));
 	}
}

export {Player};