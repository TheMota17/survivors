(() => {
  "use strict";

  const routes = [
    {
      path: "/auth",
      name: "Auth",
      component: httpVueLoader("./front/views/Auth.vue"),
    },
    {
      path: "/",
      name: "Game",
      component: httpVueLoader("./front/views/Game.vue"),
    },
    {
      path: "/404",
      name: "404",
      component: httpVueLoader("./front/views/404.vue"),
    },
    {
      path: "*",
      redirect: "/404",
    },
  ];

  const router = new VueRouter({
    mode: "history",
    routes,
  });

  new Vue({
    router,
    components: {
      Popup: httpVueLoader("./front/components/Popup.vue"),
    },
    data: () => ({
      popup: {
        text: "",
        active: false,
        checked: true,
        time: false,
      },
      invent: {
        sortType: 0,
      },
      craft: {
        id: 0,
        item: 0,
        type: 0,
        selected: false,
        sortType: 2,
      },
      gameModal: {
        open: false,
        last: false,
      },
      gameModals: {
        invent: false,
        craft: false,
        item: false,
      },
      item: {
        id: 0,
        type: "",
        item: 0,
      },
      websockets: null,
    }),
    methods: {
      changeModal(modal) {
        if (modal === "game") {
          this.gameModal.open = false;
          this.gameModal.last = this.getOpenedModalName();

          this.closeAllModals(false);
        } else {
          this.gameModal.open = true;
          this.gameModal.last = this.getOpenedModalName();
          this.gameModals[modal] = true;

          this.closeAllModals(modal);
        }
      },
      openItemInfo(item) {
        if (item.id) {
          this.item.id = item.id;

          this.gameModal.last = this.getOpenedModalName();

          this.gameModals.item = true;
          this.gameModal.open = true;

          this.closeAllModals("item");
        } else {
          this.item.id = 0;
          this.item.item = item.item;
          this.item.type = item.type;

          this.gameModal.last = this.getOpenedModalName();

          this.gameModals.item = true;
          this.gameModal.open = true;

          this.closeAllModals("item");
        }
      },
      getOpenedModalName() {
        let name = "";

        for (const modal in this.gameModals) {
          if (this.gameModals[modal] !== false) name = modal;
        }

        if (name === "") return "game";
        else return name;
      },
      closeAllModals(exception) {
        for (const modal in this.gameModals) {
          if (exception) {
            if (modal !== exception) this.gameModals[modal] = false;
          } else this.gameModals[modal] = false;
        }
      },
    },
  }).$mount("#app");
})();
