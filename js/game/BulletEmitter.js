class BulletEmitter {
	constructor(bullets, player, canv, camera)
	{
		this.bullets = bullets;
		this.player  = player;
		this.canv    = canv;
		this.camera  = camera;

		window.addEventListener('keydown', this.input.bind(this));
	}

	update(dt) 
	{
		for(let i = 0; i < this.bullets.length; i++) 
		{
			if (this.bullets[i].active)
			{
				if ((this.bullets[i].x >= this.camera.x + this.camera.viewWidth || this.bullets[i].x <= this.camera.x) 
					|| 
					(this.bullets[i].y >= this.camera.y + this.camera.viewHeight || this.bullets.y <= this.camera.y)) 
				{
					this.bullets[i].active = false;
				} else 
				{
					this.bullets[i].x += this.bullets[i].dx * (this.bullets[i].s * dt);
					this.bullets[i].y += this.bullets[i].dy * (this.bullets[i].s * dt);
				}
			}
		}
	}

	render(ctx)
	{
		for(let i = 0; i < this.bullets.length; i++) 
		{
			if (this.bullets[i].active)
			{
				ctx.fillRect(this.bullets[i].x, this.bullets[i].y, this.bullets[i].w, this.bullets[i].h);
			}
		}
	}

	activate(x, y, dx, dy)
	{
		for(let i = 0; i < this.bullets.length; i++)
		{
			if (!this.bullets[i].active) 
			{
				this.bullets[i].active = true;
				this.bullets[i].x      = x;
				this.bullets[i].y      = y;
				this.bullets[i].dx     = dx;
				this.bullets[i].dy     = dy;
				return;
			}
		}
	}

	input(e) 
	{
		if (e.keyCode == 32) 
		{
			this.activate((this.player.x + 5), this.player.y, 1, 0);
		}
	}
}

export {BulletEmitter};