class GameWorld {
	constructor(game, sprites, width, height)
	{
		this.game    = game;
		this.sprites = sprites;

		this.width  = width;
		this.height = height;
	}

	getWidth()
	{
		return this.width;
	}

	getHeight()
	{
		return this.height;
	}

	render()
	{
		this.game.getCtx().drawImage(
			this.sprites['loc_' + this.game.getPlayer().loc],
			0,
			0,
			this.width,
			this.height,
			0,
			0,
			this.width,
			this.height
		);

		for(let y = 0; y < 25; y++)
		{
			for(let x = 0; x < 25; x++)
			{
				this.game.getCtx().save();
			    this.game.getCtx().lineWidth = 0.1;
			    this.game.getCtx().strokeStyle = 'rgba(255, 255, 255, 0.2)';
			    this.game.getCtx().strokeRect(x * 40, y *40, 40, 40);
			    this.game.getCtx().restore();
			}
		}
	}
}

export {GameWorld};