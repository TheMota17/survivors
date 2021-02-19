class Bullet {
	constructor()
	{
		this.x      = 0;
		this.y      = 0;
		this.dx     = 0;
		this.dy     = 0;
		this.s      = 1000;
		this.dmg    = 0;
		this.w      = 4;
		this.h      = 4;
		this.active = false;
		this.from   = undefined;
	}

	update(dt, game)
	{	
		if (this.active)
		{
			if (this.from == 'player')
			{
				for(let enemy of game.getEnemyEmitter().getEnemys()) 
				{
					if (this.x < enemy.x + enemy.img.width/2 &&
						this.x + this.w/2 > enemy.x &&
						this.y < enemy.y + enemy.img.height/2 &&
						this.y + this.h/2 > enemy.y)
					{
						this.active = false;
						enemy.takeDmg(this.dmg);
					} else if ((this.x >= game.getCamera().x + game.getCamera().viewWidth || this.x <= game.getCamera().x) ||
							   (this.y >= game.getCamera().y + game.getCamera().viewHeight || this.y <= game.getCamera().y))
					{
						this.active = false;
					} else
					{
						this.x += this.dx * (this.s * dt);
						this.y += this.dy * (this.s * dt);
					}
				}
			} else if (this.from == 'enemy')
			{
				if (this.x < game.getPlayer().x + game.getPlayer().width/2 &&
					this.x + this.w/2 > game.getPlayer().x &&
					this.y < game.getPlayer().y + game.getPlayer().height/2 &&
					this.y + this.h/2 > game.getPlayer().y)
				{
					this.active = false;
					game.getPlayer().takeDmg(this.dmg);
				} else if ((this.x >= game.getCamera().x + game.getCamera().viewWidth || this.x <= game.getCamera().x) ||
						   (this.y >= game.getCamera().y + game.getCamera().viewHeight || this<= game.getCamera().y))
				{
					this.active = false;
				} else
				{
					this.x += this.dx * (this.s * dt);
					this.y += this.dy * (this.s * dt);
					
				}
			}
		}
	}

	render(game)
	{
		if (this.active)
		{
			game.getCtx().fillStyle = 'red';
			game.getCtx().fillRect(this.x - (this.w/2), this.y - (this.h/2), this.w, this.h);
		}
	}
}

export {Bullet};