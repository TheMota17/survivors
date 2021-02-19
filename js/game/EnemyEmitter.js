class EnemyEmitter {
	constructor(game, enemys) 
	{
		this.game = game;

		this.enemys = enemys;
	}

	update(dt)
	{
		for(let i = 0; i < this.enemys.length; i++)
		{
			if (!this.enemys[i].die)
			{
				this.enemys[i].update(dt);
			}
		}
	}

	render()
	{
		for(let i = 0; i < this.enemys.length; i++)
		{
			if (!this.enemys[i].die)
			{
				this.enemys[i].render();
			}
		}
	}

	getEnemys()
	{
		return this.enemys;
	}
}

export {EnemyEmitter};