<template>
  <div v-if="api" class="main-modal-window">
    <div class="ivent-user backgr2 flex j-c ai-c fl-di-co mt5 pb5">
      <div class="flex j-c mt5">
        {{ user.login }}
      </div>
      <div class="flex j-c mt5">
        <div class="maneken relative flex j-c ai-c">
          <img class="man" src="/front/assets/man/man.png" />
        </div>

        <div class="wdth100 flex j-sb ai-c fl-di-co">
          <div class="nadeto-item flex">
            <div class="flex j-c ai-c">
              <item-output
                v-if="nadeto[`type2`] > 0"
                :items="[{ id: false, item: nadeto[`type2`], type: 2 }]"
                :sys-items="items"
                :sys-rares="rares"
              >
              </item-output>
              <img
                v-else
                src="/front/assets/icons/ivent-helm.png"
                class="item32-1"
              />
            </div>
          </div>

          <div class="nadeto-item flex">
            <div class="flex j-c ai-c">
              <item-output
                v-if="nadeto[`type3`] > 0"
                :items="[{ id: false, item: nadeto[`type3`], type: 3 }]"
                :sys-items="items"
                :sys-rares="rares"
              >
              </item-output>
              <img
                v-else
                src="/front/assets/icons/ivent-arm.png"
                class="item32-1"
              />
            </div>
          </div>

          <div class="nadeto-item flex">
            <div class="flex j-c ai-c">
              <item-output
                v-if="nadeto[`type4`] > 0"
                :items="[{ id: false, item: nadeto[`type4`], type: 4 }]"
                :sys-items="items"
                :sys-rares="rares"
              >
              </item-output>
              <img
                v-else
                src="/front/assets/icons/ivent-weap.png"
                class="item32-1"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex j-c ai-c fl-di-co mt5">
      <div class="zag-style flex j-c ai-c mb5">
        <img src="/front/assets/icons/menu/ivent.png" class="item14-1 mr5" />
        Инвентарь {{ invent.length }} / 50
      </div>
      <div class="ivent-items backgr2 flex j-c ai-c fl-di-co">
        <div class="wdth100 flex j-c ai-c fl-di-co mt5">
          <div class="sort-menu flex j-s ai-c">
            <button @click="changeType(2)" class="flex j-c ai-c mr5">
              <img
                class="item14-1"
                src="/front/assets/items/helms/wood-helm.png"
              />
            </button>
            <button @click="changeType(3)" class="flex j-c ai-c mr5">
              <img class="item14-1" src="/front/assets/icons/abs.png" />
            </button>
            <button @click="changeType(4)" class="flex j-c ai-c mr5">
              <img class="item14-1" src="/front/assets/icons/dmg.png" />
            </button>
            <button @click="changeType(1)" class="flex j-c ai-c">
              <img class="item14-1" src="/front/assets/icons/diff.png" />
            </button>
          </div>
        </div>
        <div
          v-if="
            (invent.length && item.type == $root.invent.sortType) ||
            $root.invent.sortType == 0
          "
          v-for="item in paginatedData"
          @click="
            $root.openItemInfo({
              id: item[`id`] ? item[`id`] : false,
              item: item[`item`],
              type: item[`type`],
            })
          "
          class="item-div backgr1 flex j-sb mt5 mb5 pt5 pb5"
        >
          <div class="fl1 flex j-s ai-c">
            <div
              :class="
                rares[items[item[`type`]][item[`item`]][`rare`]][`border`]
              "
              class="item-link"
            >
              <img :src="items[item[`type`]][item[`item`]][`img`]" />
            </div>
          </div>

          <div class="fl2 flex j-c fl-di-co color3 ml5">
            <div class="ivent-item-name flex j-c">
              {{ items[item[`type`]][item[`item`]][`nm`] }}
            </div>
            <div class="flex j-c">
              Тип: {{ items[item[`type`]][item[`item`]][`type`] }}
            </div>
          </div>

          <div class="fl1 flex j-e">
            <div class="item-colvo backgr2 flex j-c ai-c">
              {{ item[`colvo`] }}
            </div>
          </div>
        </div>
      </div>
      <div class="ivent-nav backgr2 flex j-c pt5 pb5">
        <div class="wdth96 flex j-sb">
          <button :disabled="page == 1" @click="prevPage" class="nav-btn ml5">
            ◄
          </button>
          <span class="nav-btn">{{ page }}</span>
          <button
            :disabled="page >= pageCount"
            @click="nextPage"
            class="nav-btn mr5"
          >
            ►
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const ItemOutput = httpVueLoader("../components/ItemOutput.vue");

module.exports = {
  name: "Invent",
  data: () => ({
    api: false,

    nadeto: null,
    invent: null,
    user: null,
    items: null,
    rares: null,

    max: 5,
    page: 1,
  }),
  components: {
    ItemOutput,
  },
  beforeMount() {
    axios.post("/back/Api/?path=/invent").then((response) => {
      const data = response.data;

      if (data?.moves?.popup) {
        this.$root.popup.active = true;
        this.$root.popup.text = data.message;
      }

      if (data?.moves?.page) this.$router.push(data.moves.page);

      this.items = data.items;
      this.rares = data.rares;

      this.nadeto = data.nadeto;
      this.invent = data.invent;
      this.user = data.user;

      this.api = true;
    });
  },
  methods: {
    nextPage() {
      this.page += 1;
    },
    prevPage() {
      this.page -= 1;
    },
    changeType(type) {
      this.$root.invent.sortType = type;
    },
    getInventItems() {
      if (this.$root.invent.sortType) {
        let items = [];

        for (let item of this.invent) {
          if (item.type == this.$root.invent.sortType) items.push(item);
        }

        return items;
      } else {
        return this.invent;
      }
    },
  },
  computed: {
    pageCount() {
      let l = this.getInventItems().length,
        s = this.max;

      return Math.ceil(l / s);
    },
    paginatedData() {
      const start = (this.page - 1) * this.max,
        end = start + this.max;

      return this.getInventItems().slice(start, end);
    },
  },
};
</script>
