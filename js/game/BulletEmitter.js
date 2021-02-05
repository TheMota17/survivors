class BulletEmitter {
	constructor(ctx, canv, player, camera, enemys, bullets)
	{
		this.ctx     = ctx;
		this.canv    = canv;
		this.player  = player;
		this.camera  = camera;
		this.enemys  = enemys;

		this.bullets = bullets;

		window.addEventListener('keydown', this.input.bind(this));
	}

	update(dt)
	{
		for(let i = 0; i < this.bullets.length; i++) 
		{
			if (this.bullets[i].active)
			{
				this.enemys.forEach((enemy) => {
					if (this.bullets[i].x < enemy.x + enemy.img.width/2 &&
						this.bullets[i].x + this.bullets[i].w/2 > enemy.x &&
						this.bullets[i].y < enemy.y + enemy.img.height/2 &&
						this.bullets[i].y + this.bullets[i].h/2 > enemy.y) 
					{
						this.bullets[i].active = false;
						enemy.takeDmg();
						return;
					} else if ((this.bullets[i].x >= this.camera.x + this.camera.viewWidth || this.bullets[i].x <= this.camera.x) ||
							   (this.bullets[i].y >= this.camera.y + this.camera.viewHeight || this.bullets.y <= this.camera.y))
					{
						this.bullets[i].active = false;
						return;
					} else
					{
						this.bullets[i].x += this.bullets[i].dx * (this.bullets[i].s * dt);
						this.bullets[i].y += this.bullets[i].dy * (this.bullets[i].s * dt);
						return;
					}
				});
			}
		}
	}

	render()
	{
		for(let i = 0; i < this.bullets.length; i++) 
		{
			if (this.bullets[i].active)
			{
				this.ctx.fillStyle = 'red';
				this.ctx.fillRect(this.bullets[i].x, this.bullets[i].y, this.bullets[i].w, this.bullets[i].h);
			}
		}
	}

	activate(x, y, dx, dy)
	{
		for(let i = 0; i < this.enemys.length; i++)
		{
			if (!this.enemys[i].die) 
			{
				let distX = Math.floor(this.enemys[i].x - this.player.x);
				let distY = Math.floor(this.enemys[i].y - this.player.y);

				let len   = Math.sqrt(distX * distX + distY * distY);
				let dist  = Math.abs(distX + distY);

				if (dist <= 300)
				{
					for(let i = 0; i < this.bullets.length; i++) // ищем свободный патрон в массиве
					{
						if (!this.bullets[i].active) // Если нашли то активируем
						{
							this.bullets[i].active = true;
							this.bullets[i].x      = x;
							this.bullets[i].y      = y;
							this.bullets[i].dx     = distX/len;
							this.bullets[i].dy     = distY/len;
							return;
						}
					}
					return;
				}
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