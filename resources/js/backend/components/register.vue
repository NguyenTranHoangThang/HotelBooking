<template>
  <div>
    <div class="login-form">
      <form action method="post">
        <div class="form-group">
          <span v-if="usernameError != ''" class="red--text">{{usernameError}}</span>
          <input
            v-model="username"
            class="au-input au-input--full"
            type="text"
            name="username"
            placeholder="Username"
            @change="change"
          >
        </div>
        <div class="form-group">
          <span v-if="emailError != ''" class="red--text">{{emailError}}</span>
          <input
            v-model="email"
            class="au-input au-input--full"
            type="email"
            name="email"
            placeholder="email"
            @change="change"
          >
        </div>
        <div class="form-group">
          <span v-if="passwordError != ''" class="red--text">{{passwordError}}</span>
          <input
            v-model="password"
            class="au-input au-input--full"
            type="password"
            name="password"
            placeholder="password"
            @change="change"
          >
        </div>
        <div class="form-group">
          <span
            v-if="password_confirmationError != ''"
            class="red--text"
          >{{password_confirmationError}}</span>
          <input
            v-model="password_confirmation"
            class="au-input au-input--full"
            type="password"
            name="password_confirmation"
            placeholder="password confirmation"
            @change="change"
          >
        </div>
        <div class="login-checkbox">
          <label>
            <input type="checkbox" name="aggree">Agree the terms and policy
          </label>
        </div>
        <!-- <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">register</button> -->
        <v-btn color="success" ripple block @click="register">Register</v-btn>
      </form>
    </div>
  </div>
</template>
<script>
export default {
  data: function() {
    return {
      username: "",
      usernameError: "",
      emailError: "",
      passwordError: "",
      password: "",
      passwordError: "",
      password_confirmation: "",
      password_confirmationError: "",
      show: false,
      email: ""
    };
  },
  methods: {
    change: function() {
      this.usernameError = "";
      this.emailError = "";
      this.passwordError = "";
      this.password_confirmationError = "";
    },
    register: function() {
      axios({
        method: "post",
        url: "http://localhost:8000/api/manager/register",
        params: {
          email: this.email,
          username: this.username,
          password: this.password,
          password_confirmation: this.password_confirmation
        }
      })
        .then(response => {
          // console.log(response.data);
          if (response.data.status == true) {
            window.location.href = "/manager/home";
          } else {
            if (response.data.errors.username)
              this.usernameError = response.data.errors.username[0];
            if (response.data.errors.email)
              this.emailError = response.data.errors.email[0];
            if (response.data.errors.password)
              this.passwordError = response.data.errors.password[0];
            if (response.data.errors.password_confirmation)
              this.password_confirmationError =
                response.data.errors.password_confirmation[0];
            console.log(response.data.errors);
          }
        })
        .catch(error => {
          console.log(error.response);
        });
    }
    //},
    // mounted () {
    // axios
    //   .get('http://localhost:8000/api/user')
    //   .then(response => {
    //     this.info = response.data
    //   })
    //   .catch(error => {
    //     console.log(error)
    //     this.errored = true
    //   })
    //   .finally(() => this.loading = false);

    //   axios({
    // 	  method: 'post',
    // 	  url: 'http://localhost:8000/api/login',
    // 	  params: {
    // 		  name : "thang",
    // 	  }
    // 	});
  }
};
</script>
