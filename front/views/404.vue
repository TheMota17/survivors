<template>
  <div class="txt-center mt5">Страница не найдена</div>
</template>

<script>
module.exports = {
  name: "404",
  beforeMount() {
    axios
      .post("/back/Api/?path=" + window.location.pathname + "")
      .then((response) => {
        const data = response.data;

        if (data?.moves?.popup) {
          this.$root.popup.active = true;
          this.$root.popup.text = data.message;
        }

        if (data?.moves?.page) this.$router.push(data.moves.page);
      });
  },
};
</script>
