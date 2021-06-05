<template>
  <div v-if="api" class="main-modal-window">
    <div class="flex j-c ai-c fl-di-co mt5">
      <div class="zag-style flex j-c ai-c">Предмет</div>
      <div class="item-iteminfo backgr2 pt5 pb5 mt5">
        <div class="flex ml5 mt5">
          <div class="iteminfo-img flex j-s ml5">
            <item-output
              :items="[{ item: item[`item`], type: item[`type`] }]"
              :sys-items="items"
              :sys-rares="rares"
            >
            </item-output>
          </div>
          <div class="flex fl-di-co">
            <div class="iteminfo-name ml5">
              {{ items[item[`type`]][item[`item`]][`nm`] }}
            </div>
            <div class="iteminfo-rare ml5">
              <span
                :class="
                  rares[items[item[`type`]][item[`item`]][`rare`]][`class`]
                "
              >
                {{ rares[items[item[`type`]][item[`item`]][`rare`]][`word`] }}
              </span>
            </div>
          </div>
        </div>
        <div class="flex j-c mt5">
          <div class="wdth96 flex j-c">
            <hr class="hr-style mr5" />
            Инфо
            <hr class="hr-style ml5" />
          </div>
        </div>

        <item-info
          :sys-items="items"
          :item="item[`item`]"
          :type="item[`type`]"
          :colvo="item[`colvo`]"
          :nadeto="nadeto"
        >
        </item-info>

        <div v-if="items[item[`type`]][item[`item`]][`ammu`]">
          <div class="flex j-c mt10">
            <div class="wdth96 flex j-c">
              <hr class="hr-style mr5" />
              Боеприпасы
              <hr class="hr-style ml5" />
            </div>
          </div>
          <div class="flex j-c ai-c pt5 pb5">
            <div class="wdth96 flex j-c">
              <div class="wdth96 flex j-s">
                <item-output
                  :items="items[item[`type`]][item[`item`]][`ammu`]"
                  :sys-items="items"
                  :sys-rares="rares"
                >
                </item-output>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="$root.item.id" class="flex j-c">
      <div class="item-moves backgr2 flex j-c ai-c fl-di-co pb5">
        <use-item :move="items[item[`type`]][item[`item`]][`move`]"></use-item>
      </div>
    </div>

    <div
      v-if="
        item[`type`] !== 1 &&
        item[`type`] !== 5 &&
        $root.item.id &&
        nadeto[`type` + item[`type`]] > 0
      "
      class="flex j-c ai-c fl-di-co mt10"
    >
      <div class="item-iteminfo backgr2 pt5 pb5 mt5">
        <div class="flex ml5">
          <item-output
            :items="[
              {
                id: false,
                item: nadeto[`type` + item[`type`]],
                type: item[`type`],
              },
            ]"
            :sys-items="items"
            :sys-rares="rares"
          >
          </item-output>
          <div class="flex fl-di-co">
            <div class="iteminfo-name ml5">
              {{ items[item[`type`]][nadeto[`type` + item[`type`]]][`nm`] }}
              <span class="bolder">(На вас надето)</span>
            </div>
            <div class="iteminfo-rare ml5">
              <span
                :class="
                  rares[
                    items[item[`type`]][nadeto[`type` + item[`type`]]][`rare`]
                  ][`class`]
                "
              >
                {{
                  rares[
                    items[item[`type`]][nadeto[`type` + item[`type`]]][`rare`]
                  ][`word`]
                }}
              </span>
            </div>
          </div>
        </div>
        <div class="flex j-c mt10">
          <div class="wdth96 flex j-c">
            <hr class="hr-style mr5" />
            Инфо
            <hr class="hr-style ml5" />
          </div>
        </div>
        <item-info
          :sys-items="items"
          :item="nadeto[`type` + item[`type`]]"
          :type="item[`type`]"
          :colvo="false"
          :nadeto="false"
        >
        </item-info>
      </div>
    </div>
  </div>
</template>

<script>
const ItemOutput = httpVueLoader("../components/ItemOutput.vue");
const UseItem = httpVueLoader("../components/UseItem.vue");
const ItemInfo = httpVueLoader("../components/ItemInfo.vue");

module.exports = {
  name: "Item",
  data: () => ({
    api: false,

    item: null,
    items: null,
    rares: null,
    nadeto: null,
  }),
  components: {
    ItemOutput,
    UseItem,
    ItemInfo,
  },
  beforeMount() {
    let params = new FormData();
    params.append("id", this.$root.item.id);

    axios.post("/back/Api/?path=/item", params).then((response) => {
      const data = response.data;

      if (data?.moves?.popup) {
        this.$root.popup.active = true;
        this.$root.popup.text = data.message;
      }

      if (data?.moves?.page) this.$router.push(data.moves.page);

      this.items = data.items;
      this.rares = data.rares;

      this.nadeto = data.nadeto;

      if (data.item) {
        this.item = data.item;
      } else {
        if (this.$root.item.type && this.$root.item.item)
          this.item = {
            type: Number(this.$root.item.type),
            item: Number(this.$root.item.item),
          };
        else this.$root.changeModal("invent");
      }

      this.api = true;
    });
  },
};
</script>
