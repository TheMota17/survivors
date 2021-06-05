<template>
  <div v-if="api" class="flex j-c ai-c fl-di-co">
    <div class="auth-logo mt5">
      <div class="logo"></div>
      <div class="auth-info wdth100 flex j-c ai-e mt5 bolder">
        Исследуйте, стройте и уничтожайте в игре - которая перевернет все ваши
        стереотипы о Выживших!
      </div>
    </div>
    <div class="flex j-c">
      <div @click="regModelMoves" class="auth-select">
        <span :class="{ mess: regModel, underline: regModel }"
          >Начать путь</span
        >
        |
        <span :class="{ mess: !regModel, underline: !regModel }">Войти</span>
      </div>
    </div>
    <div class="form-wrapper">
      <div v-if="regModel" class="wdth96 flex j-c ai-c fl-di-co">
        <div class="wdth86 relative">
          <div v-if="!regData.name" class="error-star">*</div>
          <input
            v-model="regData.name"
            type="text"
            placeholder="Придумайте ник"
            class="input"
          />
        </div>

        <div class="wdth86 relative mt10">
          <div v-if="!regData.pass" class="error-star">*</div>
          <input
            v-model="regData.pass"
            type="password"
            placeholder="Придумайте пароль"
            class="input"
          />
        </div>

        <div class="wdth86 relative mt10">
          <div v-if="!regData.mail" class="error-star">*</div>
          <input
            v-model="regData.mail"
            type="mail"
            placeholder="Ваша почта"
            class="input"
          />
        </div>

        <div class="auth-code flex j-c fnt15 bolder mt10">
          {{ authCode }}
        </div>
        <div class="wdth86 relative mt5">
          <div v-if="!regData.authCode" class="error-star">*</div>
          <input
            v-model="regData.authCode"
            type="text"
            placeholder="Проверочный код"
            class="input"
          />
        </div>

        <div class="flex j-c mb5">
          <button @click="auth(`reg`)" type="submit" class="auth-button">
            Начать
          </button>
        </div>
      </div>
      <div v-else class="wdth96 flex j-c ai-c fl-di-co">
        <div class="wdth86">
          <input
            v-model="enterData.name"
            type="text"
            placeholder="Ник"
            class="input"
          />
        </div>

        <div class="wdth86 mt10">
          <input
            v-model="enterData.pass"
            type="password"
            placeholder="Пароль"
            class="input"
          />
        </div>

        <div class="flex j-c mb5">
          <button @click="auth(`enter`)" name="enter" class="auth-button">
            Войти
          </button>
        </div>

        <div class="flex j-c mt10">
          <a href="#" class="bottom-line fnt14">Забыли пароль?</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
module.exports = {
  name: "Auth",
  data: () => ({
    api: false,
    authCode: null,
    regModel: true,
    regData: {
      name: null,
      pass: null,
      mail: null,
      authCode: null,
    },
    enterData: {
      name: null,
      pass: null,
    },
  }),
  beforeMount() {
    let params = new FormData();
    params.append("token", localStorage.getItem("token"));

    axios.post("/back/AuthTokens/", params).then((response) => {
      const data = response.data;

      if (data?.moves?.popup) {
        this.$root.popup.active = true;
        this.$root.popup.text = data.message;
      }

      if (data?.moves?.page) this.$router.push(data.moves.page);

      this.authCode = data.authCode;
      this.api = true;
    });
  },
  methods: {
    auth(type) {
      let params = new FormData();

      switch (type) {
        case "reg":
          params.append("name", this.regData.name);
          params.append("pass", this.regData.pass);
          params.append("mail", this.regData.mail);
          params.append("authCode", this.regData.authCode);
          break;
        case "enter":
          params.append("name", this.enterData.name);
          params.append("pass", this.enterData.pass);
          break;
      }

      axios.post(`/back/Auth/?action=${type}`, params).then((response) => {
        const data = response.data;

        if (data?.moves?.popup) {
          this.$root.popup.active = true;
          this.$root.popup.text = data.message;
        }

        if (data?.moves?.page) this.$router.push(data.moves.page);
      });
    },
    regModelMoves() {
      this.regModel = !this.regModel;
    },
  },
};
</script>
