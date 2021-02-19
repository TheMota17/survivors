class BulletEmitter {
	constructor(game, bullets)
	{
		this.game = game;

		this.bullets = bullets;
	}

	update(dt)
	{
		for(let i = 0; i < this.bullets.length; i++)
		{
			this.bullets[i].update(dt, this.game);
		}
	}

	render()
	{
		for(let i = 0; i < this.bullets.length; i++) 
		{
			this.bullets[i].render(this.game);
		}
	}

	activate(x, y, from)
	{
		if (from == 'player')
		{
			for(let enemy of this.game.getEnemyEmitter().getEnemys()) 
			{
				if (!enemy.die)
				{
					let distX = Math.floor(enemy.x - x);
					let distY = Math.floor(enemy.y - y);

					let len   = Math.sqrt(distX * distX + distY * distY);
					let dist  = Math.abs(distX + distY);

					if (dist <= 300)
					{
						for(let bullet of this.bullets) 
						{
							if (!bullet.active) // Если нашли то активируем
							{
								bullet.active = true;
								bullet.x      = x;
								bullet.y      = y;
								bullet.dx     = distX/len;
								bullet.dy     = distY/len;
								bullet.dmg    = 10;
								bullet.from   = from;
								return;
							}
						}
						return;
					}
				}
			}
		} else if (from == 'enemy')
		{
			let distX = Math.floor(this.game.getPlayer().x - x);
			let distY = Math.floor(this.game.getPlayer().y - y);

			let len   = Math.sqrt(distX * distX + distY * distY);
			let dist  = Math.abs(distX + distY);

			if (dist <= 300)
			{
				for(let bullet of this.bullets) 
				{
					if (!bullet.active) // Если нашли то активируем
					{
						bullet.active = true;
						bullet.x      = x;
						bullet.y      = y;
						bullet.dx     = distX/len;
						bullet.dy     = distY/len;
						bullet.dmg    = 10;
						bullet.from   = from;
						return;
					}
				}
				return;
			}
		}
	}
}

export {BulletEmitter};