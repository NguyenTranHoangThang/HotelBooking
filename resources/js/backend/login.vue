<template>
  <div>
    <v-app>
      <div class="page-wrapper">
        <div class="page-content--bge5">
          <div class="container" style="height: auto;">
            <div class="login-wrap">
              <div class="login-content">
                <div class="login-logo">
                  <a href="#">
                    <img src="../../../public/images/icon/logo.png" alt="CoolAdmin" />
                  </a>
                </div>
                <!-- color="#4172d7" -->
                <v-tabs grow dark slider-color="white">
                  <v-tab class="haha" to="login" ripple>
                    <span class="white--text">Login</span>
                  </v-tab>
                  <v-tab class="haha" to="register" ripple>
                    <span class="white--text">Register</span>
                  </v-tab>
                </v-tabs>
                <router-view class="mt-3"></router-view>
              </div>
            </div>
          </div>
        </div>
      </div>
    </v-app>
  </div>
</template>
<script>
export default {
  $_veeValidate: {
    validator: "new"
  },
  data: function() {
    return {
      messages: "",
      show: false,
      email: "",
      password: "",
    };
  },
  methods: {
    login: function() {
      axios({
        method: "post",
        url: "http://localhost:8000/api/login",
        params: {
          email: this.email,
          password: this.password
        }
      }).then(response => {
        console.log(response.data);
        // if (response.data.status == true) {
        //   if (response.data.role == "admin")
        //     window.location.href = "/admin/home";
        //   else window.location.href = "/manager/home";
        // } else {
        //   this.messages = response.data.messages;
        //   this.show = true;
        // }
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
  },
  mounted(){
    this.$validator.localize("en", this.dictionary);
  }
};
</script>
