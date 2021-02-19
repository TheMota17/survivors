class GameWorld {
	constructor(game, sprites)
	{	
		this.game    = game;
		this.sprites = sprites;
	}

	render()
	{
		this.game.getCtx().drawImage(
			this.sprites['loc_' + this.game.getAjaxData().game.loc],
			0,
			0,
			this.sprites['loc_' + this.game.getAjaxData().game.loc].width,
			this.sprites['loc_' + this.game.getAjaxData().game.loc].height,
			0,
			0,
			this.sprites['loc_' + this.game.getAjaxData().game.loc].width,
			this.sprites['loc_' + this.game.getAjaxData().game.loc].height
		);
	}
}

export {GameWorld};