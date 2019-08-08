<template>
  <v-app id="keep">
    <v-navigation-drawer v-model="drawer.state" fixed clipped temporary class="grey lighten-4" app>
      <v-img :aspect-ratio="16/9" src="http://localhost:8000/img/cover/5.jpg">
        <v-layout column fill-height class="lightbox white--text">
          <v-spacer></v-spacer>
          <v-flex shrink class="pl-2">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-avatar size="70px" tile flat color="white" v-on="on">
                  <router-link style="width:70px" :to="{name:'account'}">
                    <img :src="'/img/user/'+user.avatar.image_link" :alt="user.name" />
                  </router-link>
                </v-avatar>
              </template>
              <span>{{user.name}}</span>
            </v-tooltip>
            <div class="subheading font-weight-black mt-2">{{user.name}}</div>
            <div class="body-1">{{user.email}}</div>
          </v-flex>
        </v-layout>
      </v-img>
      <v-list dense class="grey lighten-4">
        <template>
          <v-list-tile to="/" target="_blank">
            <v-list-tile-action>
              <v-icon :color="drawer.iconColor">{{drawer.websiteIcon}}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title :class="textClass.black">Trang Chủ</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>
        <v-divider></v-divider>
        <template>
          <v-list-tile :to="{name:'home'}">
            <v-list-tile-action>
              <v-icon :color="drawer.iconColor">{{drawer.communityIcon}}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title :class="textClass.black">Cộng Đồng</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>
        <v-divider></v-divider>
        <template>
          <v-list-tile :to="{name:'account'}">
            <v-list-tile-action>
              <v-icon :color="drawer.iconColor">{{drawer.accountIcon}}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title :class="textClass.black">Tài Khoản</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>
        <v-divider></v-divider>
        <template>
          <v-list-tile v-on:click="logOut">
            <v-list-tile-action>
              <v-icon :color="drawer.iconColor">{{drawer.logoutIcon}}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title :class="textClass.black">Đăng Xuất</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>
        <v-divider></v-divider>
        <v-spacer></v-spacer>
        <template>
          <v-layout class="ml-3 row wrap" justify-center align-top>
            <v-flex shrink md12>
              <p :class="textClass.teal">Copyright &copy;{{year}} | All rights reserved</p>
            </v-flex>
            <v-flex shrink md3>
              <a href="http://localhost:8000/" target="_blank" :class="textClass.grey">Trang Chủ</a>
              <br />
              <a
                href="http://localhost:8000/policies.html"
                target="_blank"
                :class="textClass.grey"
              >Chính Sách</a>
              <br />
              <a
                href="http://localhost:8000/about.html"
                target="_blank"
                :class="textClass.grey"
              >Về Chúng Tôi</a>
            </v-flex>
            <v-flex shrink md3>
              <a
                href="http://localhost:8000/contact.html"
                target="_blank"
                :class="textClass.grey"
              >Liên Hệ</a>
            </v-flex>
            <v-spacer></v-spacer>
          </v-layout>
        </template>
      </v-list>
    </v-navigation-drawer>
    <v-navigation-drawer v-model="notifications.state" fixed clipped class="grey lighten-4" right>
      <v-layout row wrap class="pa-0 ma-0 noti-margin-top">
        <v-flex md12 v-if="notifications.list.length>0">
          <v-layout
            row
            wrap
            class="pa-0 pl-1 pr-1 ma-0 caption"
            v-for="(notification,i) in notifications.list"
            :key="i"
          >
            <v-flex
              :class="notification.read == false? `grey lighten-2`:`white`"
              @click="markedNotification(notification)"
              v-if="notification.answer"
            >
              <div class="ml-3 my-2">
                <div class="font-weight-bold purple--text">{{notification.message}}</div>
                <div class="ml-1 pl-3 border-left border-light">
                  <div>{{notification.question.title}}</div>
                  <div class="font-italic">"{{notification.question.content}}"</div>
                  <div class="font-weight-bold body-1">-&nbsp;{{notification.answer}}</div>
                </div>
              </div>
            </v-flex>
            <v-flex
              :class="notification.read == false? `grey lighten-2`:`white`"
              @click="markedNotification(notification)"
              v-else-if="notification.coupon"
            >
              <div class="ml-3 my-2">
                <v-layout class="pa-0 ma-0" align-center justify-start>
                  <v-flex md2>
                    <v-avatar size="42px" color="black">
                      <v-avatar size="40px" color="white">
                        <img :src="notification.hotel.image" />
                      </v-avatar>
                    </v-avatar>
                  </v-flex>
                  <v-flex>
                    <div>{{notification.hotel.name}}</div>
                  </v-flex>
                  <v-spacer></v-spacer>
                </v-layout>
                <div class="font-weight-bold brown--text">"{{notification.message}}"</div>
              </div>
            </v-flex>
            <v-flex
              md12
              :class="notification.read == false? `grey lighten-2`:`white`"
              @click="markedNotification(notification)"
              v-else
            >
              <div class="ml-3 my-2">
                <v-layout class="pa-0 ma-0" align-center justify-start>
                  <v-flex md2>
                    <v-avatar size="42px" color="black">
                      <v-avatar size="40px" color="white">
                        <img :src="'/img/user/'+notification.user.avatar" />
                      </v-avatar>
                    </v-avatar>
                  </v-flex>
                  <v-flex>
                    <div>{{notification.user.name}}</div>
                  </v-flex>
                  <v-spacer></v-spacer>
                </v-layout>
                <div class="font-weight-bold teal--text">"{{notification.message}}"</div>
              </div>
            </v-flex>
            <v-flex md12 v-if="i<notifications.list.length-1">
              <v-divider class="pa-0 ma-0 my-2"></v-divider>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex md12 v-else>
          <div class="text-md-center">
            <span class="font-italic black--text caption">Không có thông báo...</span>
          </div>
        </v-flex>
      </v-layout>
    </v-navigation-drawer>
    <v-toolbar :color="drawer.color" app fixed clipped-right clipped-left flat>
      <v-toolbar-side-icon v-on:click="drawer.state = !drawer.state" class="teal accent-4"></v-toolbar-side-icon>
      <a class="title ml-3 mr-5 white--text text-uppercase" href="/community">Cộng đồng</a>
      <v-spacer></v-spacer>
      <v-speed-dial
        v-model="notifications.state"
        :transition="`slide-x-reverse-transition`"
        :direction="`left`"
        v-if="notifications.list.length>0"
      >
        <template v-slot:activator>
          <v-badge overlap color="red" class="mr-5" v-if="notifications.count>0">
            <template v-slot:badge>
              <span>{{notifications.count}}</span>
            </template>
            <v-avatar :color="drawer.iconColor" size="40px">
              <v-icon class="pointer" dark v-if="!notifications.state">notifications</v-icon>
              <v-icon class="pointer" dark v-else>clear</v-icon>
            </v-avatar>
          </v-badge>
          <v-avatar :color="drawer.iconColor" size="40px" class="mr-5" v-else>
            <v-icon class="pointer" dark v-if="!notifications.state">notifications</v-icon>
            <v-icon class="pointer" dark v-else>clear</v-icon>
          </v-avatar>
        </template>
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn fab dark small color="orange" @click.stop="markedAllNotifications" v-on="on">
              <v-icon>remove_red_eye</v-icon>
            </v-btn>
          </template>
          <span>Đánh dấu đã đọc</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn fab dark small color="red" @click.stop="deleteAllNotifications" v-on="on">
              <v-icon>delete</v-icon>
            </v-btn>
          </template>
          <span>Xóa thông báo</span>
        </v-tooltip>
      </v-speed-dial>
      <v-avatar
        :color="drawer.iconColor"
        size="40px"
        class="mr-5"
        @click="notifications.state = !notifications.state"
        v-else
      >
        <v-icon class="pointer" dark v-if="!notifications.state">notifications</v-icon>
        <v-icon class="pointer" dark v-else>clear</v-icon>
      </v-avatar>
    </v-toolbar>
    <div id="top"></div>
    <v-content>
      <v-container fluid fill-height class="grey lighten-2">
        <transition name="moveInUp">
          <router-view
            :user="user"
            :customer="user"
            v-on:loadLogin="getLogin"
            :snackbar="snackbar"
            v-on:loadSnackbar="eventSnackbar"
            :login="login"
            :check="login.check"
          ></router-view>
        </transition>
        <v-btn
          href="#top"
          color="#0e2737"
          dark
          fixed
          bottom
          right
          fab
          depressed
          class="mr-5 mb-3 top"
        >
          <v-icon color="white">arrow_upward</v-icon>
        </v-btn>
      </v-container>
    </v-content>
    <v-snackbar
      v-model="snackbar.state"
      multi-line="multi-line"
      :timeout="snackbar.timeout"
      top
      color="white"
      class="body-1 black--text font-weight-bold"
      style="margin-top:150px"
    >
      <v-layout align-center>
        <v-flex md5>
          <v-img :aspect-ratio="1" src="/img/booking/alert.gif"></v-img>
        </v-flex>
        <v-flex>{{snackbar.content}}</v-flex>
        <v-flex>
          <v-icon v-on:click="snackbar.state = !snackbar.state" large color="black">close</v-icon>
        </v-flex>
      </v-layout>
    </v-snackbar>
    <v-dialog
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
      v-model="login.dialog"
      persistent
    >
      <v-card title light width="100%" height="100%">
        <v-layout
          fill-height
          row
          wrap
          justify-center
          align-center
          class="pa-0 ma-0 login-background"
        >
          <div class="text-md-center" v-if="flag === false">
            <div>
              <v-icon large color="#9980FA">fas fa-circle-notch fa-spin</v-icon>
            </div>
            <div>
              <span class="font-weight-black red--text">đ</span>
              <span class="font-weight-black orange--text">a</span>
              <span class="font-weight-black amber--text">n</span>
              <span class="font-weight-black green--text">g</span>
              <span class="font-weight-black blue--text">&nbsp;t</span>
              <span class="font-weight-black indigo--text">ả</span>
              <span class="font-weight-black purple--text">i</span>
              <span class="font-weight-black teal--text">.</span>
              <span class="font-weight-black teal--text">.</span>
              <span class="font-weight-black teal--text">.</span>
            </div>
          </div>
          <v-flex md8 v-else>
            <v-hover v-slot:default="{ hover }">
              <v-layout
                row
                wrap
                justify-center
                align-center
                :class="hover? `pa-5 ma-0 white elevation-20`:`pa-5 ma-0 white elevation-5`"
              >
                <v-flex md5 mr-2>
                  <v-img :aspect-ratio="4/3" src="/blog/img/slider/slider.png" class="radius">
                    <v-layout fill-height justify-center align-center>
                      <div>
                        <div>
                          <a href="/" target="_blank" class="body-1">Trang Chủ</a>
                        </div>
                        <div class="ml-1 pl-3 border-left border-dark">
                          <div>
                            <a href="/about.html" target="_blank" class="caption">Về Chúng Tôi</a>
                          </div>
                          <div>
                            <a href="/contact.html" target="_blank" class="caption">Liên Hệ</a>
                          </div>
                        </div>
                      </div>
                    </v-layout>
                  </v-img>
                </v-flex>
                <v-flex md6 ml-2>
                  <v-form ref="form" data-vv-scope="form1">
                    <v-text-field
                      color="teal"
                      :error-messages="errors.collect('form1.username')"
                      data-vv-name="username"
                      v-validate="'required|min:4'"
                      type="text"
                      outline
                      v-model="login.username"
                      label="Username"
                    ></v-text-field>
                    <v-text-field
                      color="teal"
                      :error-messages="errors.collect('form1.password')"
                      data-vv-name="password"
                      v-validate="'required|min:4'"
                      type="password"
                      outline
                      v-model="login.password"
                      label="Mật khẩu"
                      append-icon="visibility"
                      v-on:click:append="login.value=true"
                      v-if="!login.value"
                    ></v-text-field>
                    <v-text-field
                      color="teal"
                      :error-messages="errors.collect('form1.password')"
                      data-vv-name="password"
                      v-validate="'required|min:4'"
                      type="text"
                      outline
                      v-model="login.password"
                      label="Mật khẩu"
                      append-icon="visibility_off"
                      v-on:click:append="login.value=false"
                      v-else
                    ></v-text-field>
                    <v-btn round color="teal" v-on:click="submitLogin" dark depressed>Đăng Nhập</v-btn>
                    <v-btn round color="grey" v-on:click="clear" dark depressed>Xóa</v-btn>
                  </v-form>
                </v-flex>
              </v-layout>
            </v-hover>
          </v-flex>
        </v-layout>
      </v-card>
    </v-dialog>
    <v-dialog v-model="couponDetail.dialog" max-width="500">
      <v-card flat title class="pa-2">
        <v-card-title>
          <v-layout class="pa-0 ma-0 border" align-center>
            <v-flex md3>
              <div
                class="indigo--text text-md-center font-weight-black display-2"
              >{{couponDetail.couponCode.discount_value}}%</div>
            </v-flex>
            <v-flex md8 offset-md1>
              <v-layout row wrap class="border-left pa-0 ma-0 px-3" align-center>
                <v-flex md4>
                  <span class="caption">Mã Coupon:</span>
                </v-flex>
                <v-flex md6 offset-md1>
                  <span class="font-weight-black brown--text title" style="word-wrap: break-word;">"{{couponDetail.couponCode.code}}"</span>
                </v-flex>
                <v-flex md12>
                  <span class="caption">Nội Dung:</span>                  
                  <div class="mx-2 pl-1">
                    <div>
                      <span class="font-weight-black body-1">{{couponDetail.couponCode.title}}</span>
                    </div>
                    <div v-if="couponDetail.couponCode.content && couponDetail.couponCode.content.length>0">
                      <span class="font-italic caption">"{{couponDetail.couponCode.content}}"</span>
                    </div>
                  </div>
                </v-flex>
                <v-flex md4>
                  <span class="caption">Ngày Bắt Đầu:</span>
                </v-flex>
                <v-flex md6 offset-md1>
                  <span class="font-weight-black caption">{{formatDate(couponDetail.couponCode.start_at)}}</span>
                </v-flex>
                <v-flex md4 v-if="couponDetail.couponCode.end_at && couponDetail.couponCode.end_at.length>0">
                  <span class="caption">Ngày Kết Thúc:</span>
                </v-flex>
                <v-flex md6 v-if="couponDetail.couponCode.end_at && couponDetail.couponCode.end_at.length>0" offset-md1>
                  <span class="font-weight-black caption">{{formatDate(couponDetail.couponCode.end_at)}}</span>
                </v-flex>
                <v-flex md4>
                  <span class="caption">Số Lượng:</span>
                </v-flex>
                <v-flex md6 offset-md1>
                  <span class="font-weight-black caption">{{couponDetail.couponCode.apply_amount}}</span>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-card-title>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
export default {
  $_veeValidate: {
    validator: "new"
  },
  data() {
    return {
      fab: false,
      flag: false,
      couponDetail: {
        dialog: false,
        couponCode:{},
        hotel: {},
      },
      dictionary: {
        custom: {
          username: {
            required: () => "Username không được bỏ trống",
            min: "Username phải nhiều hơn 3 ký tự"
          },
          password: {
            min: "Password phải nhiều hơn 3 ký tự"
          }
        }
      },
      login: {
        dialog: true,
        check: false,
        token: "",
        username: "",
        password: "",
        value: false
      },
      button: false,
      snackbar: {
        state: false,
        content: "",
        timeout: 10000
      },
      hello: "hello",
      year: new Date().getFullYear(),
      textClass: {
        black: "black--text",
        grey: "grey--text",
        teal: "teal--text"
      },
      user: {
        id: 0,
        customer: {},
        avatar: {},
        followers: [],
        customerFollowings: [],
        hotelFollowings: [],
        booking: [],
        question: []
      },
      drawer: {
        state: false,
        color: "#0e2737",
        iconColor: "#1cc3b2",
        communityIcon: "refresh",
        accountIcon: "folder_shared",
        logoutIcon: "logout",
        websiteIcon: "check_box_outline_blank"
      },
      notifications: {
        state: false,
        iconColor: "#1cc3b2",
        commentIcon: "message",
        followingIcon: "person_add",
        systemMessageIcon: "announcement",
        newsIcon: "fas fa-poll-h",
        count: 0,
        list: []
      }
    };
  },
  created() {
    this.getLogin();
  },
  mounted() {
    this.$validator.localize("en", this.dictionary);
    window.Echo.channel("message").listen(".send-mess", e => {
      this.getNotifications();
    });
    window.Echo.channel("question").listen(".hotel-answer", e => {
      this.getNotifications();
    });
    window.Echo.channel("coupon").listen(".new-coupon-code", e => {
      this.getNotifications();
    });
  },
  watch: {
    $route: function() {
      this.getLogin();
      this.notifications.state = false;
    }
  },
  methods: {
    accountPage: function() {
      this.$router.push({ name: "account" });
    },
    communityPage: function() {
      this.$router.push({ name: "home" });
    },
    logOut: function() {
      this.getLogin();
      if (this.login.check) {
        axios({
          method: "post",
          url: "http://localhost:8000/api/logout",
          headers: {
            Authorization: "Bearer " + this.login.token
          }
        }).then(res => {
          if (res.data.status) {
            this.login.check = false;
            this.login.dialog = true;
            this.flag = true;
            this.login.token = "";
            localStorage.removeItem("login_token");
          }
        });
      }
    },
    openCouponDetail: function(hotel,coupon){
      this.couponDetail.hotel = hotel;
      this.couponDetail.couponCode = coupon;
      this.couponDetail.dialog = true;
    },
    event: function(val) {
      this.hello = val;
    },
    eventSnackbar: function(val) {      
      this.snackbar.state = !this.snackbar.state;
      this.snackbar.content = val + "";
    },
    getLogin: function() {
      this.login.token = localStorage.getItem("login_token");
      if (this.login.token != null) {
        axios({
          method: "get",
          url: "http://localhost:8000/api/getUser",
          headers: {
            Authorization: "Bearer " + this.login.token
          }
        })
          .then(res => {
            this.user = res.data.user;
            this.login.check = true;
            this.login.dialog = false;
            this.getNotifications();
            if (this.$route.name == "login")
              this.$router.push({ name: "home" });
          })
          .catch(error => {
            console.log(error.response);
            console.log(error);
            if (error.response.status == 401) {
              localStorage.removeItem("login_token");
              this.login.token = localStorage.getItem("login_token");
              this.login.check = false;
              this.login.dialog = true;
              this.flag = true;
              this.$router.push({ name: "login" });
            }
          });
      } else {
        this.flag = true;
        this.$router.push({ name: "login" });
        this.login.check = false;
        this.login.dialog = true;
      }
    },
    clear: function() {
      this.$refs.form.reset();
      this.$validator.reset();
    },
    getNotifications: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/notifications",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          this.notifications.list = res.data.data;
          this.notifications.count = res.data.count;
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.$router.push({ name: "login" });
          }
        });
    },
    submitLogin: function() {
      this.$validator.validateAll("form1").then(valid => {
        if (valid) {
          axios({
            method: "post",
            url: "http://localhost:8000/api/login",
            data: {
              username: this.login.username,
              password: this.login.password
            }
          })
            .then(res => {
              if (!res.data.status) {
                if(res.data.errors && res.data.errors.length>0)
                  this.eventSnackbar(res.data.errors);
                else
                this.eventSnackbar("Rất tiếc, thực hiện không thành công!");
                this.login.check = false;
                this.login.password = "";
                this.$validator.reset();
                return;
              }
              localStorage.login_token = res.data.token;
              this.login.dialog = false;
              this.flag = true;
              this.login.check = true;
              this.clear();
              localStorage.login_token = res.data.token;
              this.getLogin();
              this.$router.push({ name: "account" });
              return;
            })
            .catch(error => {
              console.log(error.response);
              console.log(error);
              if (error.response.status == 401) {
                localStorage.removeItem("login_token");
                this.login.token = localStorage.getItem("login_token");
                this.login.password = "";
                this.$validator.reset();
                return;
              }
            });
        }
      });
    },
    markedAllNotifications: function() {
      var oldList = this.notifications.list;
      var count = this.notifications.count;
      this.notifications.list.forEach(element => {
        element.read = true;
      });
      this.notifications.count = 0;
      var flag = true;
      axios({
        method: "get",
        url: "http://localhost:8000/api/update-all-notifications",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {})
        .catch(error => {
          flag = false;
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.$router.push({ name: "login" });
          }
        });
      if (!flag) {
        this.notifications.list = [];
        this.notifications.count = count;
        this.notifications.list = oldList;
      }
    },
    deleteAllNotifications: function() {
      var oldList = this.notifications.list;
      var count = this.notifications.count;
      this.notifications.list = [];
      this.notifications.count = 0;
      var flag = true;
      axios({
        method: "get",
        url: "http://localhost:8000/api/delete-all-notifications",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {})
        .catch(error => {
          flag = false;
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.$router.push({ name: "login" });
          }
        });
      if (!flag) {
        this.notifications.list = oldList;
        this.notifications.count = count;
      }
    },    
    formatDate: function(date) {
      if (!date) return null;
      return this.$moment(date).format("DD-MM-YYYY");
    },
    getIndex(id) {
      var number = 0;
      this.notifications.list.forEach((element, index) => {
        if (element.id == id) number = index;
      });
      return number;
    },
    markedNotification: function(val) {
      if(val.coupon){
        this.openCouponDetail(val.hotel,val.coupon);
      }
      if (val.read != true) {
        var index = this.getIndex(val.id);
        this.notifications.list[index].read = true;
        this.notifications.count = this.notifications.count - 1;
        var flag = true;
        axios({
          method: "put",
          url: "http://localhost:8000/api/notifications/" + val.id,
          headers: {
            Authorization: "Bearer " + localStorage.getItem("login_token")
          }
        })
          .then(res => {
            if (res.data.status == true) {
              if (val.link) {
                this.$router.push({
                  name: val.link.name,
                  query: { id: val.link.id }
                });
              }
            } else {
              flag = false;
            }
          })
          .catch(error => {
            flag = false;
            console.log(error.response);
            console.log(error);
            if (error.response.status == 401) {
              localStorage.removeItem("login_token");
              this.$router.push({ name: "login" });
            }
          })
          .then(() => {
            if (!flag) {
              this.notifications.list[index].read = false;
              this.notifications.count = this.notifications.count + 1;
            }
          });
      } else {
        if (val.link)
          this.$router.push({
            name: val.link.name,
            query: { id: val.link.id }
          });
      }
    }
  }
};
</script>

<style scoped>
.v-navigation-drawer__border {
  display: none;
}
.v-tooltip__content {
  opacity: 1 !important;
  padding: 4px !important;
  border-bottom-left-radius: 20%;
  box-shadow: none !important;
}
.radius {
  border-bottom-left-radius: 20%;
}
.v-menu__content {
  box-shadow: none !important;
}
.noti-margin-top {
  margin-top: 70px !important;
}
</style>