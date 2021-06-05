<template>
  <div class="relative">
    <div class="hght25">
      <div class="info-table backgr2 flex j-s ai-c">
        <div class="info-item flex j-c ai-c ml5">
          <img src="/front/assets/icons/hp.png" class="item14-1" alt="Жизнь" />
          <span class="ml5 fnt14" id="hp">{{ UI.hp }}</span>
        </div>

        <div class="info-item flex j-c ai-c ml10">
          <img
            src="/front/assets/icons/hung.png"
            class="item14-1"
            alt="Голод"
          />
          <span class="ml5 fnt14" id="hung">{{ UI.hung }}</span>
        </div>

        <div class="info-item flex j-c ai-c ml10">
          <img
            src="/front/assets/icons/thirst.png"
            class="item14-1"
            alt="Жажда"
          />
          <span class="ml5 fnt14" id="thirst">{{ UI.thirst }}</span>
        </div>

        <div class="info-item flex j-c ai-c ml10">
          <img
            src="/front/assets/icons/rad.png"
            class="item14-1"
            alt="Радиация"
          />
          <span class="ml5 fnt14" id="rad">{{ UI.rad }}</span>
        </div>
      </div>
    </div>

    <menyoo></menyoo>

    <div class="relative flex j-c ai-c fl-di-co">
      <div class="game-ui flex fl-di-co pt5 pb5 ml5">
        <div class="flex j-c ai-c">
          <img src="/front/assets/icons/loc.png" class="mr5" />
          <span class="mr10" id="loc_name">{{ UI.loc_name }}</span>
          <img src="/front/assets/icons/time.png" />
          <span id="time">{{ UI.time }}</span>
        </div>
        <div class="mt10">
          <div class="flex fl-di-co">
            <div class="flex">
              FPS: <span class="ml5" id="fps">{{ UI.fps }}</span>
            </div>
            <div class="flex">
              Ping: <span class="ml5" id="ping">{{ UI.ping }}</span> ms
            </div>
          </div>
        </div>
      </div>

      <div class="game-canvas-btns flex j-e ai-e fl-di-co">
        <div class="atack-btn-wrapper">
          <button
            @click="input"
            @keyup.space="input"
            class="atack-btn backgr2"
            name="button"
            id="atack"
          ></button>
        </div>
        <div class="game-canvas-btns-wdth flex j-c ai-c fl-di-co">
          <div class="flex j-c ai-c">
            <button
              @click="input"
              class="game-btn game-btn-up"
              name="button"
              id="top"
            ></button>
          </div>
          <div class="flex j-c">
            <button
              @click="input"
              class="game-btn game-btn-left"
              name="button"
              id="left"
            ></button>
            <button
              @click="input"
              class="game-btn game-btn-right"
              name="button"
              id="right"
            ></button>
          </div>
          <div class="flex j-c">
            <button
              @click="input"
              class="game-btn game-btn-down"
              name="button"
              id="down"
            ></button>
          </div>
        </div>
      </div>

      <div v-if="lastItem.active" class="taked-item flex j-e ai-e">
        <div class="taked-item-info flex fl-di-co j-c">
          <div>Новый предмет</div>
          <div class="relative flex j-c mt5">
            <img :src="lastItem.img" />
            <div class="item-colvo-min flex ai-c">{{ lastItem.colvo }}</div>
          </div>
        </div>
      </div>

      <div class="game-canvas-wrapper" id="canvasWrapper"></div>
    </div>

    <div v-if="$root.gameModal.open" class="game-hide"></div>
    <invent v-if="$root.gameModals.invent"></invent>
    <craft v-if="$root.gameModals.craft"></craft>
    <refuge v-if="$root.gameModals.refuge"></refuge>
    <item v-if="$root.gameModals.item"></item>

    <close-back v-if="$root.gameModal.open"></close-back>
  </div>
</template>

<script>
const Menyoo = httpVueLoader("../components/Menyoo.vue");
const Invent = httpVueLoader("../views/Invent.vue");
const Craft = httpVueLoader("../views/Craft.vue");
const Item = httpVueLoader("../views/Item.vue");
const ItemOutput = httpVueLoader("../components/ItemOutput.vue");
const CloseBack = httpVueLoader("../components/CloseBack.vue");

module.exports = {
  name: "Game",
  data: () => ({
    UI: {
      lastUpdTime: Date.now(),
      hp: "-",
      hung: "-",
      thirst: "-",
      rad: "-",
      loc_name: "-",
      time: "--:--",
      online: "-",
      fps: "-",
      ping: "-",
    },
    consts: {
      WS_PORT: "http://localhost:1327",
      MAX_CANV_WDTH: 500,
      MAX_CANV_HGHT: 800,
      LASTITEM_ACTIVE_MS: 2000,
      ITERPOLATE_SCAL: 0.16,
    },
    lastItem: {
      img: "",
      colvo: 0,
      time: 0,
      active: false,
    },
  }),
  components: {
    Menyoo,
    Invent,
    Craft,
    Item,
    ItemOutput,
    CloseBack,
  },
  mounted() {
    axios
      .post("/back/Api/?path=" + window.location.pathname + "")
      .then((response) => {
        const data = response.data;

        if (data?.moves?.popup) {
          this.$root.popup.active = true;
          this.$root.popup.text = data.message;
        }

        if (data?.moves?.page) this.$router.push(data.moves.page);

        this.game();
      });
  },
  methods: {
    game() {
      // Init
      let firstTouch = false;

      let wsData = false;

      let locImg = false;
      let players = {};
      let items = {};

      let notifs = {
        exitPlayer: false,
        delItem: false,
      };

      const pixi = new PIXI.Application({
        width:
          window.innerWidth > this.consts.MAX_CANV_WDTH
            ? this.consts.MAX_CANV_WDTH
            : window.innerWidth,
        height:
          window.innerHeight > this.consts.MAX_CANV_HGHT
            ? this.consts.MAX_CANV_HGHT
            : window.innerHeight - 25,
        backgroundColor: 0x212121,
        antialias: true,
        resolution: 1,
      });
      document.getElementById("canvasWrapper").appendChild(pixi.view);

      const loader = new PIXI.Loader();

      pixi.view.name = "canvas";

      window.addEventListener("click", () => (firstTouch = true));

      window.addEventListener("resize", () => {
        pixi.view.width =
          window.innerWidth > this.consts.MAX_CANV_WDTH
            ? this.consts.MAX_CANV_WDTH
            : window.innerWidth;
        pixi.view.height =
          window.innerHeight > this.consts.MAX_CANV_HGHT
            ? this.consts.MAX_CANV_HGHT
            : window.innerHeight - 25;
      });

      pixi.view.addEventListener("click", (e) => this.input(e));

      loader
        .add("player", "/front/assets/game/player.png")
        .add("healthBar", "/front/assets/game/hp.png")
        .add("loc_1", "/front/assets/game/loc_1.png")
        .add("11", "/front/assets/items/others/meat.png")
        .add("12", "/front/assets/items/others/water.png")
        .add("13", "/front/assets/items/others/wood.png")
        .add("14", "/front/assets/items/others/stick.png")
        .add("15", "/front/assets/items/others/rock.png")
        .add("16", "/front/assets/items/others/grin_rock.png")
        .add("17", "/front/assets/items/others/rope.png")
        .add("18", "/front/assets/items/others/dur_rope.png")
        .add("19", "/front/assets/items/others/arrow.png")
        .add("110", "/front/assets/items/others/craft_book2.png")
        .add("111", "/front/assets/items/others/craft_book3.png")
        .add("112", "/front/assets/items/others/craft_book4.png")
        .add("113", "/front/assets/items/others/bread.png")
        .add("114", "/front/assets/items/others/canned.png")
        .add("115", "/front/assets/items/others/soda.png")
        .add("116", "/front/assets/items/others/treated_wood.png")
        .add("117", "/front/assets/items/others/scrap.png")
        .add("118", "/front/assets/items/others/metall.png")

        .add("back", "/front/assets/sounds/back.mp3");

      loader.load((loader, resources) => {
        this.$root.ws = io(this.consts.WS_PORT, { transports: ["websocket"] });
        this.$root.ws.on(
          "connect_error",
          () =>
            console.log(
              "Connection failed..."
            ) /*window.location.pathname = '/'*/
        );
        this.$root.ws.on("update", (response) => (wsData = response));

        pixi.ticker.add((delta) => {
          if (!wsData) return;

          // Если есть первое касание по странице, и если фон музыка закончилась
          if (firstTouch && resources["back"].data.pause)
            resources["back"].data.play();

          if (wsData.user.exitPlayer)
            if (
              !notifs.exitPlayer ||
              notifs.exitPlayer.time !== wsData.user.exitPlayer.time
            ) {
              notifs.exitPlayer = wsData.user.exitPlayer;
              notifs.exitPlayer.checked = false;
            }

          if (notifs.exitPlayer.checked === false) {
            pixi.stage.removeChild(players[notifs.exitPlayer.id]);

            delete players[notifs.exitPlayer.id];
            notifs.exitPlayer.checked = true;
          }

          if (wsData.user.delItem)
            if (
              !notifs.delItem ||
              notifs.delItem.time !== wsData.user.delItem.time
            ) {
              notifs.delItem = wsData.user.delItem;
              notifs.delItem.checked = false;
            }

          if (notifs.delItem.checked === false) {
            pixi.stage.removeChild(items[notifs.delItem.id]);

            delete items[notifs.delItem.id];
            notifs.delItem.checked = true;
          }

          if (wsData.user.lastItem)
            if (this.lastItem.time !== wsData.user.lastItem.time) {
              this.lastItem.img = wsData.user.lastItem.img;
              this.lastItem.colvo = wsData.user.lastItem.colvo;
              this.lastItem.time = wsData.user.lastItem.time;
              this.lastItem.active = true;
            }

          if (this.lastItem.active)
            if (
              Date.now() - this.lastItem.time >
              this.consts.LASTITEM_ACTIVE_MS
            ) {
              this.lastItem.active = false;
            }

          if (wsData.user.popup)
            if (this.$root.popup.time !== wsData.user.popup.time) {
              this.$root.popup.text = wsData.user.popup.text;
              this.$root.popup.active = true;
              this.$root.popup.checked = false;
              this.$root.popup.time = wsData.user.popup.time;
            }

          // UI data
          if (Date.now() - this.UI.lastUpdTime >= 1000) {
            this.UI.lastUpdTime = Date.now();
            this.UI.fps = Math.floor(PIXI.ticker.shared.FPS);
            this.UI.ping = Date.now() - wsData.server.sendTime;
            this.UI.hp = wsData.user.hp;
            this.UI.hung = wsData.user.hung;
            this.UI.thirst = wsData.user.thirst;
            this.UI.rad = wsData.user.rad;
            this.UI.time = this.convertTime(wsData.server.time);
            this.UI.loc_name = wsData.server.userLocName;
            this.UI.online = Object.keys(wsData.players).length;
          }

          // Location
          if (!locImg) {
            locImg = new PIXI.Sprite(
              resources["loc_" + wsData.user.loc].texture
            );
            pixi.stage.addChild(locImg);
          }

          // players
          for (const id in wsData.players) {
            const player = wsData.players[id];

            if (!players[id]) {
              players[id] = this.createNewPlayer(
                resources["player"].texture,
                resources["healthBar"].texture,
                player.login,
                Number(player.x),
                Number(player.y),
                player.hp,
                player.maxhp,
                player.atackRadius
              );
              pixi.stage.addChild(players[id]);
            } else {
              // Gived dmg
              if (player.givedDmg) {
                let givedDmg = players[id].getChildByName("givedDmg"); // Text

                if (
                  !players[id].givedDmg ||
                  players[id].givedDmg.time !== player.givedDmg.time
                ) {
                  players[id].givedDmg = player.givedDmg;

                  givedDmg.alpha = 1;
                  givedDmg.text = "-" + player.givedDmg.dmg;
                } else if (Date.now() - players[id].givedDmg.time >= 2000)
                  givedDmg.alpha = 0;
              }

              // Position
              players[id].x +=
                (Number(player.x) - players[id].x - players[id].width / 2) *
                this.consts.ITERPOLATE_SCAL;
              players[id].y +=
                (Number(player.y) - players[id].y - players[id].height / 2) *
                this.consts.ITERPOLATE_SCAL;

              // Hp bar
              let healthBar = players[id].getChildByName("healthBar");
              healthBar.width = this.hpPercent(
                healthBar.maxWidth,
                player.hp,
                player.maxhp
              );
            }
          }

          // Camera
          pixi.stage.position.set(
            -(players[wsData.user.socketId].x - pixi.view.width / 2),
            -(players[wsData.user.socketId].y - pixi.view.height / 2)
          );

          // items
          for (const id in wsData.items) {
            const item = wsData.items[id];

            if (!items[id]) {
              items[id] = this.createNewItem(
                resources[item.type + "" + item.item].texture,
                item.x,
                item.y
              );
              pixi.stage.addChild(items[id]);
            }
          }
        });
      });
    },
    convertTime(time) {
      let minutes = Math.floor(time / 60);
      let hours = Math.floor(minutes / 60);
      minutes = minutes - hours * 60;

      if (hours < 10) {
        if (minutes < 10) return "0" + hours + ":0" + minutes;
        else return "0" + hours + ":" + minutes;
      } else {
        if (minutes < 10) return hours + ":0" + minutes;
        else return hours + ":" + minutes;
      }
    },
    hpPercent(width, hp, maxhp) {
      return Number(width) * (Number(hp) / Number(maxhp));
    },
    createNewPlayer(
      playerTexture,
      healthBarTexture,
      login,
      x,
      y,
      hp,
      maxHp,
      atackRadius
    ) {
      let Player = new PIXI.Sprite(playerTexture);
      Player.x = x - playerTexture.width / 2;
      Player.y = y - playerTexture.height / 2;
      Player.givedDmg = false;

      let atackRadiusCircle = new PIXI.Graphics();
      atackRadiusCircle.lineStyle(0.5, 0x000000, 1);
      atackRadiusCircle.beginFill(0xffffff, 0.07);
      atackRadiusCircle.drawCircle(
        playerTexture.width / 2,
        playerTexture.width / 2,
        playerTexture.width / 2 + atackRadius
      );
      atackRadiusCircle.endFill();

      let givedDmg = new PIXI.Text(0, {
        fontFamily: "Arial",
        fontSize: 12,
        fill: 0xff0000,
      });
      givedDmg.name = "givedDmg";
      givedDmg.x = healthBarTexture.width / 2 + 13;
      givedDmg.y = -12;
      givedDmg.alpha = 0;

      let healthBar2 = new PIXI.Sprite(healthBarTexture);
      healthBar2.tint = 0x000000;
      healthBar2.x = playerTexture.width / 2 - healthBarTexture.width / 2;
      healthBar2.y = -6;

      let healthBar = new PIXI.Sprite(healthBarTexture);
      healthBar.name = "healthBar";
      healthBar.x = playerTexture.width / 2 - healthBarTexture.width / 2;
      healthBar.y = -6;
      healthBar.maxWidth = healthBarTexture.width;
      healthBar.width = this.hpPercent(healthBarTexture.width, hp, maxHp);

      let name = new PIXI.Text(login, {
        fontFamily: "Arial",
        fontSize: 14,
        fill: 0xdac09c,
        stroke: "black",
        strokeThickness: 2,
      });
      name.x = playerTexture.width / 2 - name.width / 2;
      name.y = -22;

      Player.addChild(atackRadiusCircle);
      Player.addChild(givedDmg);
      Player.addChild(healthBar2);
      Player.addChild(healthBar);
      Player.addChild(name);

      return Player;
    },
    createNewItem(texture, x, y) {
      let Item = new PIXI.Sprite(texture);
      Item.x = x - texture.width / 2 / 2; // Спрайт уменьшается в два раза, поэтому делим на половину два раза
      Item.y = y - texture.height / 2 / 2;

      Item.width = texture.width / 2;
      Item.height = texture.height / 2;

      let outline = new PIXI.filters.OutlineFilter(1, 0xffffff);
      Item.filters = [outline];

      return Item;
    },
    input(e) {
      switch (e.target.name) {
        case "canvas":
          break;
        case "button":
          switch (e.target.id) {
            case "left":
              this.$root.ws.emit("message", {
                type: "move",
                data: { dir: "left" },
              });
              break;
            case "top":
              this.$root.ws.emit("message", {
                type: "move",
                data: { dir: "top" },
              });
              break;
            case "right":
              this.$root.ws.emit("message", {
                type: "move",
                data: { dir: "right" },
              });
              break;
            case "down":
              this.$root.ws.emit("message", {
                type: "move",
                data: { dir: "down" },
              });
              break;
            case "atack":
              this.$root.ws.emit("message", {
                type: "atack",
              });
              break;
          }
          break;
      }
    },
  },
};
</script>
