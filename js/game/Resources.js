let Resources = {
    ready: false,
    loadSprites(all_sprites)
    {
        this.load        = 0;
        this.all_sprites = all_sprites;
        let sprites      = {};

        for(let i = 0; i < all_sprites.length; i++)
        {
            let sprite    = new Image();
            sprite.src    = all_sprites[i].path + all_sprites[i].nm + '.png';
            sprite.onload = () => this.load += 1;

            sprites[ all_sprites[i]['nm'] ] = sprite;
        }

        return sprites;
    },
    checkLoad()
    {
        if (this.load >= this.all_sprites.length)
        {
            return true;
        }
    }
}

export {Resources};