class EnemyEmitter {
	constructor(ctx, enemys) 
	{
		this.ctx = ctx;

		this.enemys = enemys;
	}

	update(dt)
	{
		for(let i = 0; i < this.enemys.length; i++)
		{
			if (!this.enemys[i].die)
			{
				if (this.enemys[i].hp <= 0) 
				{
					this.enemys[i].die = true;
					return;
				}
				this.enemys[i].x += this.enemys[i].dx * (this.enemys[i].s * dt);
				this.enemys[i].y += this.enemys[i].dy * (this.enemys[i].s * dt);
			}
		}
	}

	render()
	{
		for(let i = 0; i < this.enemys.length; i++)
		{
			if (!this.enemys[i].die)
			{
				this.ctx.fillStyle = 'red';
				this.ctx.fillText(this.enemys[i].nm, (this.enemys[i].x - this.ctx.measureText(this.enemys[i].nm).width/2), this.enemys[i].y - 16);

				this.ctx.fillStyle = 'black';
				this.ctx.fillRect((this.enemys[i].x - this.enemys[i].img.width/2) - 12, this.enemys[i].y - 14, 43, 3);
				this.ctx.fillStyle = 'green';
				this.ctx.fillRect((this.enemys[i].x - this.enemys[i].img.width/2) - 10, this.enemys[i].y - 14, this.hpPercent(this.enemys[i].hp), 1);

				this.ctx.drawImage(this.enemys[i].img, this.enemys[i].x - this.enemys[i].img.width/2, this.enemys[i].y - this.enemys[i].img.height/2);
			}
		}
	}

	hpPercent(hp)
	{
		return 40 * (hp / 100);
	}
}

export {EnemyEmitter};