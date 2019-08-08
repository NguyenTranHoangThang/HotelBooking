<template>
  <div class="container">
    <!-- Breadcrumb Area Start -->
    <div
      class="breadcrumb-area bg-img bg-overlay jarallax"
      style="background-image: url('/blog/img/bg-img/16.jpg');"
    >
      <div class="container h-100">
        <div class="row h-100 align-items-center">
          <div class="col-12">
            <div class="breadcrumb-content text-center">
              <h2 class="page-title">Login</h2>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                  <li class="breadcrumb-item">
                    <a href="#">
                      <router-link :to="{name:'home'}">Home</router-link>
                    </a>
                  </li>
                  <li class="breadcrumb-item active" aria-current="page">Login</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Breadcrumb Area End -->
    <!-- Items Area Start -->
    <div class="roberto-news-area section-padding-100-0">
      <div class="container">
        <div class="row justify-content-center">
          <!-- Left-->
          <div class="col-12 col-lg-3"></div>
          <!-- Login Area Start -->
          <div class="col-12 col-lg-6">
            <form action="#" method="post" enctype="multipart/form-data">
              <div class="form-group mb-30">
                <div class="row">
                  <div class="col-12">
                    <span class="text-danger">{{error}}</span>
                  </div>
                </div>
                <label for="checkInDate">
                  Username
                  <span class="text-danger">*</span>
                </label>
                <div>
                  <div class="row">
                    <div class="col-12">
                      <input
                        v-on:change="change"
                        type="text"
                        class="form-control"
                        name="username"
                        placeholder="Username or email"
                        required
                        v-model.trim="username"
                      >
                    </div>
                    <div class="col-12">
                      <span class="text-danger">{{usernameError}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mb-30">
                <label for="guests">
                  Password
                  <span class="text-danger">*</span>
                </label>
                <div class="row">
                  <div class="col-12">
                    <input
                      v-on:change="change"
                      type="password"
                      name="password"
                      class="form-control"
                      placeholder="Password"
                      required
                      v-model.trim="password"
                    >
                  </div>
                  <div class="col-12">
                    <span class="text-danger">{{passError}}</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <button type="button" class="btn roberto-btn w-25" v-on:click="onSubmit">Login</button>
              </div>
            </form>
          </div>
          <!-- Right-->
          <div class="col-12 col-lg-3"></div>
        </div>
      </div>
    </div>
    <!-- Items Area End -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      status: false,
      error: "",
      usernameError: "",
      passError: "",
      username: "",
      password: ""
    };
  },
  methods: {
    change: function() {
      this.error = "";
      this.usernameError = "";
      this.passError = "";
    },
    onSubmit: function() {
      axios({
        method: "post",
        url: "http://localhost:8000/api/login",
        params: {
          username: this.username,
          password: this.password
        }
      }).then(res => {
        console.log(res.data.status);
        if (res.data.status) {
          this.$router.push({ name: "home" });
          return;
        } else {
          this.error = "Username or password is incorrect!";
          if (res.data.errors["username"])
            this.usernameError = res.data.errors["username"][0];
          if (res.data.errors["password"])
            this.passError = res.data.errors["password"][0];
          return;
        }
      });
    }
  }
};
</script>
