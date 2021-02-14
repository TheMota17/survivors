class EnemyEmitter {
	constructor(ctx, enemys, bulletEmitter) 
	{
		this.ctx = ctx;

		this.enemys        = enemys;
		this.bulletEmitter = bulletEmitter;
	}

	update(dt)
	{
		for(let i = 0; i < this.enemys.length; i++)
		{
			if (!this.enemys[i].die)
			{
				this.enemys[i].update(dt, this.bulletEmitter);
			}
		}
	}

	render()
	{
		for(let i = 0; i < this.enemys.length; i++)
		{
			if (!this.enemys[i].die)
			{
				this.enemys[i].render(this.ctx);
			}
		}
	}
}

export {EnemyEmitter};