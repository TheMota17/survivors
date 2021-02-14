class BulletEmitter {
	constructor(ctx, canv, player, camera, enemys, bullets)
	{
		this.ctx     = ctx;
		this.canv    = canv;
		this.player  = player;
		this.camera  = camera;
		this.enemys  = enemys;

		this.bullets = bullets;
	}

	update(dt)
	{
		for(let i = 0; i < this.bullets.length; i++) 
		{
			if (this.bullets[i].active)
			{
				if (this.bullets[i].from == 1)
				{
					this.enemys.forEach((enemy) => 
					{
						if (this.bullets[i].x < enemy.x + enemy.img.width/2 &&
							this.bullets[i].x + this.bullets[i].w/2 > enemy.x &&
							this.bullets[i].y < enemy.y + enemy.img.height/2 &&
							this.bullets[i].y + this.bullets[i].h/2 > enemy.y) 
						{
							this.bullets[i].active = false;
							enemy.takeDmg(this.bullets[i].dmg);
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
				} else if (this.bullets[i].from == 0)
				{
					if (this.bullets[i].x < this.player.x + this.player.width/2 &&
						this.bullets[i].x + this.bullets[i].w/2 > this.player.x &&
						this.bullets[i].y < this.player.y + this.player.height/2 &&
						this.bullets[i].y + this.bullets[i].h/2 > this.player.y) 
					{
						this.bullets[i].active = false;
						this.player.takeDmg(this.bullets[i].dmg);
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
				}
			}
		}
	}

	render()
	{
		for(let i = 0; i < this.bullets.length; i++) 
		{
			this.bullets[i].render(this.ctx);
		}
	}

	activate(x, y, from)
	{
		if (from == 'player')
		{
			for(let i = 0; i < this.enemys.length; i++)
			{
				if (!this.enemys[i].die)
				{
					let distX = Math.floor(this.enemys[i].x - x);
					let distY = Math.floor(this.enemys[i].y - y);

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
								this.bullets[i].dmg    = 10;
								this.bullets[i].from   = this.bullets[i].froms[ from ];
								return;
							}
						}
						return;
					}
				}
			}
		} else 
		{
			let distX = Math.floor(this.player.x - x);
			let distY = Math.floor(this.player.y - y);

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
						this.bullets[i].dmg    = 10;
						this.bullets[i].from   = this.bullets[i][ from ];
						return;
					}
				}
				return;
			}
		}
	}
}

export {BulletEmitter};