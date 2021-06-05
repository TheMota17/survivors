<template>
  <div v-if="api" class="main-modal-window">
    <div class="flex j-c ai-c fl-di-co mt5">
      <div class="zag-style flex j-c ai-c mb5">Крафт</div>
      <div class="craft-items backgr2 flex j-c ai-c fl-di-co">
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
        <div
          v-if="$root.craft.selected"
          class="craft-item backgr1 flex j-c fl-di-co mt5 mb5 pt5 pb5"
        >
          <div class="craft-first-info flex">
            <item-output
              :items="[{ item: $root.craft.item, type: $root.craft.type }]"
              :sys-items="items"
              :sys-rares="rares"
              class="ml5"
            >
            </item-output>
            <div class="flex j-s fl-di-co ml5">
              <div class="item-name flex j-s">
                {{ items[$root.craft.type][$root.craft.item][`nm`] }}
              </div>
              <div class="item-rare flex j-s">
                <span
                  :class="
                    rares[items[$root.craft.type][$root.craft.item][`rare`]][
                      `class`
                    ]
                  "
                >
                  {{
                    rares[items[$root.craft.type][$root.craft.item][`rare`]][
                      `word`
                    ]
                  }}
                </span>
              </div>
            </div>
          </div>
          <div class="craft-info backgr2 flex j-c fl-di-co ml5 mr5 mt5">
            <div class="flex j-c mt10">
              <div class="wdth96 flex j-c">
                <hr class="hr-style mr5" />
                Инфо
                <hr class="hr-style ml5" />
              </div>
            </div>

            <item-info
              :sys-items="items"
              :item="$root.craft.item"
              :type="$root.craft.type"
              :colvo="false"
              :nadeto="false"
            ></item-info>

            <div class="flex j-c mt10">
              <div class="wdth96 flex j-с">
                <hr class="hr-style mr5" />
                Компоненты
                <hr class="hr-style ml5" />
              </div>
            </div>
            <div class="flex j-c mt5">
              <div class="wdth96 flex j-s">
                <div
                  v-for="(craft_i, idx) in crafts[$root.craft.id][
                    `craft_items`
                  ]"
                  :class="[idx !== 0 ? `ml5` : ``]"
                  class="flex fl-di-co"
                >
                  <item-output
                    :items="[{ item: craft_i[`item`], type: craft_i[`type`] }]"
                    :sys-items="items"
                    :sys-rares="rares"
                  >
                  </item-output>
                  <div class="item-colvo backgr1 flex j-c ai-c">
                    {{ craft_i[`colvo`] }}
                  </div>
                </div>
              </div>
            </div>
            <div v-if="crafts[$root.craft.id][`tools`]">
              <div class="flex j-c mt10">
                <div class="wdth96 flex j-с">
                  <hr class="hr-style mr5" />
                  Инструменты
                  <hr class="hr-style ml5" />
                </div>
              </div>
              <div class="flex j-c mt5">
                <div class="wdth96 flex j-s">
                  <div
                    v-for="(tool, idx) in crafts[$root.craft.id][`tools`]"
                    :class="[idx !== 0 ? `ml5` : ``]"
                    class="flex j-c ai-c fl-di-co"
                  >
                    <item-output
                      :items="[{ item: tool[`item`], type: tool[`type`] }]"
                      :sys-items="items"
                      :sys-rares="rares"
                    >
                    </item-output>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="items[$root.craft.type][$root.craft.item][`ammu`]">
              <div class="flex j-c mt10">
                <div class="wdth96 flex j-c">
                  <hr class="hr-style mr5" />
                  Боеприпасы
                  <hr class="hr-style ml5" />
                </div>
              </div>
              <div class="flex j-c ai-c pt5 pb5">
                <div class="wdth96 flex j-c">
                  <div class="wdth100 flex j-s">
                    <div
                      v-for="(ammu, idx) in items[$root.craft.type][
                        $root.craft.item
                      ][`ammu`]"
                      :class="[idx !== 0 ? `ml5` : ``]"
                      class="flex j-c ai-c fl-di-co"
                    >
                      <item-output
                        :items="[{ item: ammu[`i`], type: ammu[`t`] }]"
                        :sys-items="items"
                        :sys-rares="rares"
                      >
                      </item-output>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex j-c mt10">
              <div class="wdth96 flex j-s">
                <hr class="hr-style mr5" />
                Количество
                <hr class="hr-style ml5" />
              </div>
            </div>
            <div class="flex j-c mt5 mb5">
              <div class="item-colvo backgr1 color3 flex j-c ai-c">
                {{ colvo }}
              </div>
            </div>
            <div class="flex j-c mb5">
              <input
                v-model="colvo"
                class="colvo-range-input mt5"
                type="range"
                min="1"
                max="50"
                step="1"
                value="1"
              />
            </div>
            <div class="flex j-c mt5 mb5">
              <button
                @click="craft"
                class="moves-btn relative mt5 flex j-s ai-c"
              >
                <div class="game-btn-icon ml5 mr5 flex j-c ai-c">
                  <img src="/front/assets/icons/menu/craft.png" />
                </div>
                <div class="flex j-c ai-c">Создать</div>
                <div class="game-btn-bar"></div>
              </button>
            </div>
          </div>
        </div>
        <div v-else class="wdth100 flex j-c ai-c fl-di-co pb5">
          <div
            v-if="
              craft[`craft_lvl`] <= user[`craft_lvl`] &&
              $root.craft.sortType == craft[`type`]
            "
            v-for="(craft, idx) in crafts"
            class="craft-item backgr1 flex j-c fl-di-co mt5 pt5 pb5"
          >
            <div class="craft-first-info flex">
              <div
                class="flex j-c ai-c ml5"
                :class="
                  rares[items[craft[`type`]][craft[`item`]][`rare`]][`border`]
                "
              >
                <img :src="items[craft[`type`]][craft[`item`]][`img`]" />
              </div>
              <div class="flex j-s fl-di-co">
                <div class="item-name ml5 flex j-s">
                  {{ items[craft[`type`]][craft[`item`]][`nm`] }}
                </div>
                <div class="item-rare ml5 flex j-s">
                  <span
                    :class="
                      rares[items[craft[`type`]][craft[`item`]][`rare`]][
                        `class`
                      ]
                    "
                  >
                    {{
                      rares[items[craft[`type`]][craft[`item`]][`rare`]][`word`]
                    }}
                  </span>
                </div>
              </div>
              <div class="ml5 mr5 fl1 flex j-e">
                <button
                  @click="selectItem(idx, craft[`item`], craft[`type`])"
                  class="craft-item-info flex j-c ai-c"
                >
                  <img src="/front/assets/icons/menu/craft.png" />
                </button>
              </div>
            </div>
          </div>
        </div>
        <div v-if="$root.craft.selected" class="wdth100 flex j-c mt5 mb5">
          <div class="wdth96 flex j-e">
            <button @click="awayItem" class="craft-back-btn">Назад</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const ItemOutput = httpVueLoader("../components/ItemOutput.vue");
const ItemInfo = httpVueLoader("../components/ItemInfo.vue");

module.exports = {
  name: "Craft",
  data: () => ({
    api: false,

    items: null,
    crafts: null,
    rares: null,
    user: null,

    colvo: 1,
  }),
  components: {
    ItemOutput,
    ItemInfo,
  },
  beforeMount() {
    axios.post("/back/Api/?path=/craft").then((response) => {
      const data = response.data;

      if (data?.moves?.popup) {
        this.$root.popup.active = true;
        this.$root.popup.text = data.message;
      }

      if (data?.moves?.page) this.$router.push(data.moves.page);

      this.items = response.data.items;
      this.crafts = response.data.crafts;
      this.rares = response.data.rares;

      this.user = response.data.user;

      this.api = true;
    });
  },
  methods: {
    changeType(type) {
      this.$root.craft.sortType = type;
    },
    selectItem(idx, item, type) {
      this.$root.craft.id = idx;
      this.$root.craft.item = item;
      this.$root.craft.type = type;
      this.$root.craft.selected = true;
    },
    awayItem() {
      this.$root.craft.id = 0;
      this.$root.craft.item = 0;
      this.$root.craft.type = 0;
      this.$root.craft.selected = false;
    },
    craft() {
      this.$root.websockets.send(
        JSON.stringify({
          type: "action",
          data: {
            action: "craft",
            craft_idx: this.$root.craft.id,
            colvo: this.colvo,
          },
        })
      );
    },
  },
};
</script>
