class GameWorld {
	constructor(game, sprites)
	{
		this.game    = game;
		this.sprites = sprites;
	}

	render()
	{
		this.game.getCtx().drawImage(
			this.sprites['loc_' + this.game.getPlayer().loc],
			0,
			0,
			this.sprites['loc_' + this.game.getPlayer().loc].width,
			this.sprites['loc_' + this.game.getPlayer().loc].height,
			0,
			0,
			this.sprites['loc_' + this.game.getPlayer().loc].width,
			this.sprites['loc_' + this.game.getPlayer().loc].height
		);
	}
}

export {GameWorld};