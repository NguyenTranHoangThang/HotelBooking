<template>
  <v-layout row wrap class="mx-3">
    <v-flex shrink md8>
      <v-img :aspect-ratio="16/4" src="/blog/img/slider/slider.png" class="mr-2 mb-3 radius">
        <v-layout row wrap fill-height class="lightbox white--text mt-5 mb-1 pl-5">
          <v-spacer></v-spacer>
          <v-flex md9 shrink class="pl-5">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-avatar size="90px" tile flat color="white" v-on="on">
                  <img :src="'/img/user/'+data.avatar.image_link" alt />
                </v-avatar>
              </template>
              <span>{{data.name}}</span>
            </v-tooltip>
            <div class="subheading font-weight-black mt-2">{{data.name}}</div>
            <div class="body-1">{{data.email}}</div>
          </v-flex>
          <v-flex md3>
            <v-btn
              round
              dark
              depressed
              color="indigo darken-2"
              class="mr-3"
              v-if="!data.follow"
              v-on:click="followUser(data)"
            >
              <i class="fas fa-plus"></i> &nbsp;theo dõi
            </v-btn>
            <v-btn
              round
              dark
              depressed
              color="blue darken-2"
              class="mr-3"
              v-else
              v-on:click="unfollowUser(data)"
            >
              <i class="fas fa-check"></i>&nbsp;đang theo dõi
            </v-btn>
          </v-flex>
        </v-layout>
      </v-img>
      <div v-if="data.review.length!=0">
        <v-layout class="row wrap mx-3 mb-5" v-for="(item,index) in data.review" :key="index">
          <v-badge left overlap :color="color.badge">
            <template v-slot:badge>
              <v-tooltip top v-if="item.can_comment == 1">
                <template v-slot:activator="{ on }">
                  <v-icon small color="white" v-on="on" class="pointer">lock_open</v-icon>
                </template>
                <span>Bình luận</span>
              </v-tooltip>
              <v-tooltip top v-else>
                <template v-slot:activator="{ on }">
                  <v-icon small color="black" v-on="on" class="pointer">lock</v-icon>
                </template>
                <span>Khóa bình luận</span>
              </v-tooltip>
            </template>
            <v-card light min-height="120px" class="pa-1" flat tile width="800px">
              <v-card-title>
                <v-divider></v-divider>
                <v-tooltip top v-if="item.customer_review.status ==1">
                    <template v-slot:activator="{ on }">
                    <v-btn
                      fab
                      color="#0652DD"
                      depressed
                      small
                      @click.stop="updateNotification(item.id)"
                      v-on="on"
                    >
                      <i class="far fa-bell white--text fa-lg"></i>
                    </v-btn>
                  </template>
                  <span>Đang nhận thông báo</span>
                </v-tooltip>
                <v-tooltip top v-else>
                  <template v-slot:activator="{ on }">
                    <v-btn
                      fab
                      color="red"
                      depressed
                      small
                      @click.stop="updateNotification(item.id)"
                      v-on="on"
                    >
                      <i class="far fa-bell-slash white--text fa-lg"></i>
                    </v-btn>
                  </template>
                  <span>Đang tắt thông báo</span>
                </v-tooltip>
              </v-card-title>
              <router-link class="pointer" :to="{name:'review',query:{id:item.id}}" target="_blank">
                <v-spacer></v-spacer>
                <v-card-text>
                  <span class="headline">{{item.title}}</span>
                  <v-layout>
                    <v-card
                      flat
                      tile
                      tag="div"
                      width="100%"
                      class="radius"
                      color="grey lighten-2"
                      light
                    >
                      <v-card-text class="font-weight-bold font-italic">{{item.content}}</v-card-text>
                    </v-card>
                  </v-layout>
                  <span class="grey--text pl-2">-{{formatDate(item.created_at)}}</span>
                  <v-layout
                    row
                    wrap
                    class="pa-0 ma-0 mt-2 ml-3 pl-3 border-left border-bottom"
                    align-center
                  >
                    <v-flex md3>
                      <v-img :aspect-ratio="1" :src="'/blog/img/hotel/'+item.hotel.image"></v-img>
                    </v-flex>
                    <v-flex md8 offset-md1 class="pa-3 border-left">
                      <v-layout row wrap class="pa-0 ma-0">
                        <v-flex md12>
                          <div>
                            <router-link
                              :to="{name:'hotel',params:{id:item.hotel.id}}"
                              target="_blank"
                            >
                              <span class="font-weight-bold body-2">{{item.hotel.name}}</span>
                            </router-link>
                          </div>
                          <div v-if="item.hotel.stars_num > 0">
                        <v-chip color="#3B3B98" class="white--text">
                          <v-icon class="mr-1" small color="yellow">star</v-icon>
                          {{item.hotel.stars_num}}/5
                        </v-chip>
                        <v-chip
                          color="#A3CB38"
                          class="white--text font-weight-text"
                        >{{item.hotel.review_point}}/10</v-chip>
                      </div>
                      <div>
                        <v-chip color="#7d5fff" class="white--text">
                          <v-icon class="mr-1" small color="pink">room</v-icon>
                          {{item.hotel.address}}
                        </v-chip>
                      </div>
                        </v-flex>
                        <v-flex md4 v-for="(ser,i) in item.hotel.services" :key="i" class="pa-1">
                          <div class="text-md-center border pa-2">
                            <div>
                              <i :class="'fa-lg fas fa-'+ser.icon"></i>
                            </div>
                            <div>
                              <span class="caption font-italic">{{ser.name}}</span>
                            </div>
                          </div>
                        </v-flex>
                      </v-layout>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </router-link>
              <v-card-actions>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-icon
                      v-on="on"
                      class="ml-3 mr-1"
                      large
                      :color="color.heart"
                      v-on:click="likeReview(item.id)"
                      v-if="item.customer_review.like == 0"
                    >favorite_border</v-icon>
                    <v-icon
                      v-on="on"
                      class="ml-3 mr-1"
                      large
                      :color="color.heart"
                      v-on:click="likeReview(item.id)"
                      v-else
                    >favorite</v-icon>
                  </template>
                  <span>like</span>
                </v-tooltip>
                <span class="grey--text subheading">{{item.likes}}</span>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-icon
                      v-on="on"
                      class="ml-3 mr-1"
                      large
                      :color="color.comment"
                      v-on:click="item.model = true"
                      v-if="!item.model"
                    >chat_bubble_outline</v-icon>
                    <v-icon
                      v-on="on"
                      class="ml-3 mr-1"
                      large
                      :color="color.comment"
                      v-on:click="item.model = false"
                      v-else
                    >chat_bubble</v-icon>
                  </template>
                  <span>bình luận</span>
                </v-tooltip>
                <span class="grey--text subheading">{{item.comments}}</span>
              </v-card-actions>
              <v-divider v-show="item.model"></v-divider>
              <v-layout class="row wrap" justify-center v-if="item.model">
                <v-card
                  light
                  flat
                  tile
                  width="790px"
                  class="mb-1"
                  v-for="(value,i) in item.comment"
                  :key="i"
                >
                  <v-card-title>
                    <v-avatar size="42px" color="black" flat>
                      <v-avatar size="40px" flat color="white">
                        <img :src="'/img/user/'+value.customer.avatar.image_link" />
                      </v-avatar>
                    </v-avatar>
                    <span class="pl-3">{{value.customer.name}}</span>
                  </v-card-title>
                  <v-card-text>
                    <v-layout>
                      <v-card
                        flat
                        tile
                        tag="div"
                        color="grey lighten-2"
                        width="100%"
                        class="radius"
                      >
                        <v-card-text
                          class="font-weight-bold font-italic black--text"
                        >{{value.content}}</v-card-text>
                      </v-card>
                    </v-layout>
                  </v-card-text>
                  <v-divider v-show="item.can_comment === 1"></v-divider>
                </v-card>
                <v-card flat tile width="790px" v-show="item.can_comment === 1">
                  <v-layout row wrap align-center>
                    <v-flex md10 class="pl-3">
                      <v-card-title>
                        <v-textarea
                          v-model="comment.content"
                          outline
                          auto-grow
                          rows="2"
                          color="teal"
                          label="Nhập bình luận mới tại đây..."
                          clear-icon="$vuetify.icons.clear"
                          clearable
                        ></v-textarea>
                      </v-card-title>
                    </v-flex>
                    <v-flex>
                      <v-card-actions>
                        <v-btn
                          round
                          large
                          depressed
                          :disabled="!comment.able"
                          color="teal"
                          @click="sendComment(item.id)"
                        >
                          <span>Gửi</span>
                        </v-btn>
                      </v-card-actions>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-layout>
            </v-card>
          </v-badge>
        </v-layout>
      </div>
      <div v-else class="mx-3 pa-4 text-md-center border">
        <span class="text-uppercase">không có bài đánh giá nào...</span>
      </div>
    </v-flex>
    <v-flex shrink md4>
      <v-expansion-panel light class="elevation-0" expand v-model="expand">
        <v-expansion-panel-content class="mb-3">
          <template v-slot:actions>
            <v-icon>expand_more</v-icon>
          </template>
          <template v-slot:header>
            <div class="title my-3 text-uppercase font-weight-black">
              <v-icon color="black">assignment_ind</v-icon>&nbsp;thông tin tài khoản
            </div>
          </template>
          <v-divider class="ma-0 pa-0"></v-divider>
          <v-container>
            <v-layout row wrap class="mb-2 ml-1" align-center>
              <v-flex md1>
                <i class="fas fa-user-circle red--text fa-lg"></i>
              </v-flex>
              <v-flex md3>
                <span class="font-weight-black">Username:</span>
              </v-flex>
              <v-flex md8 class="pl-2">
                <span class="font-weight-black subheading">{{data.username}}</span>
              </v-flex>
            </v-layout>
            <v-layout row wrap class="mb-2 ml-1" align-center>
              <v-flex md1>
                <i class="fas fa-signature orange--text fa-lg"></i>
              </v-flex>
              <v-flex md3>
                <span class="font-weight-black">Họ Tên:</span>
              </v-flex>
              <v-flex md8 class="pl-2">
                <span class="font-weight-black">{{data.name}}</span>
              </v-flex>
            </v-layout>
            <v-layout row wrap class="mb-2 ml-1" align-center>
              <v-flex md1>
                <i class="fas fa-envelope yellow--text fa-lg"></i>
              </v-flex>
              <v-flex md3>
                <span class="font-weight-black">Email:</span>
              </v-flex>
              <v-flex md8 class="pl-2">
                <span class="font-weight-black">{{data.email}}</span>
              </v-flex>
            </v-layout>
            <v-layout row wrap class="mb-2 ml-1" align-center>
              <v-flex md1>
                <i class="fas fa-phone green--text fa-lg"></i>
              </v-flex>
              <v-flex md3>
                <span class="font-weight-black">Số điện thoại:</span>
              </v-flex>
              <v-flex md8 class="pl-2">
                <span class="font-weight-black">{{data.phone_number}}</span>
              </v-flex>
            </v-layout>
            <v-layout row wrap class="mb-2 ml-1" align-center>
              <v-flex md1>
                <i class="fab fa-creative-commons-nd blue--text fa-lg"></i>
              </v-flex>
              <v-flex md3>
                <span class="font-weight-black">Điểm:</span>
              </v-flex>
              <v-flex md8 class="pl-2">
                <span class="font-weight-black">{{data.customer.coin}}</span>
              </v-flex>
            </v-layout>
            <v-layout row wrap class="mb-2 ml-1" align-center>
              <v-flex md1>
                <i class="fas fa-home indigo--text fa-lg"></i>
              </v-flex>
              <v-flex md3>
                <span class="font-weight-black">Địa chỉ:</span>
              </v-flex>
              <v-flex md8 class="pl-2">
                <span class="font-weight-black">{{data.customer.address}}</span>
              </v-flex>
            </v-layout>
          </v-container>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  props: {},
  mounted() {
    window.Echo.channel("message").listen(".send-mess", e => {
      this.data.review.forEach((element, index) => {
        if (element.id == e.link.id) {
          var comment = {
            content: e.content,
            customer: {
              id: e.user.id,
              name: e.user.name,
              avatar: {
                image_link: e.user.avatar
              }
            }
          };
          this.data.review[index].comment = [...this.data.review[index].comment,comment];
          this.data.review[index].comments = this.data.review[index].comments + 1;
        }
      });
    });
  },
  data() {
    return {
      user: {},
      comment: {
        review_id: 0,
        content: "",
        able: false
      },
      followDialog: false,
      like: false,
      model: false,
      expand: [true, true, true],
      id: this.$route.params.id,
      data: {
        customer: {},
        avatar: {},
        review: []
      },
      color: {
        header: "#2c3e50",
        badge: "#1cc3b2",
        heart: "#ff6348",
        fullHeart: "#f8a5c2",
        comment: "#1cc3b2",
        fullComment: "#f5f6fa",
        check: "#fa8231"
      }
    };
  },
  created() {
    this.getData();
  },
  watch: {
    $route: "getData",
    "comment.content": function(val) {
      if (val.length == 0) {
        this.comment.able = false;
        this.comment.review_id = 0;
        this.comment.content = "";
      } else {
        this.comment.able = true;
      }
    }
  },
  methods: {
    getData: function() {
      this.id = this.$route.params.id;
      axios({
        method: "get",
        url: "http://localhost:8000/api/userInfo",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        },
        params: {
          id: this.id
        }
      })
        .then(res => {
          if (res.data.status == false) {
            this.$router.push({ name: "account" });
            return;
          }
          this.data = res.data.user;
          return;
        })
        .catch(error => {
          this.$emit("loadLogin");
          console.log(error.response);
          console.log(error);
        });
    },
    followUser: function(value) {
      this.data.follow = true;
      var flag = true;
      axios({
        method: "get",
        url: "http://localhost:8000/api/following",
        params: {
          type: 0,
          id: this.user.id,
          followed: value.id
        },
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          if (res.data.data == null) {
            flag = false;
            this.$emit("loadSnackbar", "Xảy ra lỗi, thử lại?");
          }
          this.getData();
        })
        .catch(error => {
          flag = false;
          this.$emit("loadLogin");
          console.log(error.response);
          console.log(error);
        }).then(()=>{
          if (!flag) this.data.follow = false;
        })
    },
    unfollowUser: function(value) {
      this.data.follow = false;
      var flag = true;
      axios({
        method: "get",
        url: "http://localhost:8000/api/un-following",
        params: {
          type: 0,
          id: this.user.id,
          followed: value.id
        },
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          if (res.data.data == null) {
            flag = false;
            this.$emit("loadSnackbar", "Đã xảy ra lỗi, thử lại?");
          }
          this.getData();
        })
        .catch(error => {
          this.$emit("loadLogin");
          console.log(error.response);
          console.log(error);
          flag = false;
        }).then(()=>{
          if (!flag) this.data.follow = true;
        });
    },
    getIndex(id) {
      var index = -1;
      if (this.data.review.length != 0) {
        this.data.review.forEach((element, i) => {
          if (element.id == id) index = i;
        });
      }
      return index;
    },
    likeReview: function(id) {
      var flag = true;
      var index = this.getIndex(id);
      switch (this.data.review[index].customer_review.like) {
        case 0:
          this.data.review[index].customer_review.like = 1;
          this.data.review[index].likes = this.data.review[index].likes + 1;
          break;
        case 1:
          this.data.review[index].customer_review.like = 0;
          this.data.review[index].likes = this.data.review[index].likes - 1;
          break;
      }
      axios({
        method: "get",
        url: "http://localhost:8000/api/like",
        params: {
          review_id: id
        },
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          if (res.data.status == false) {
            flag = false;
          }
        })
        .catch(error => {
          flag = false;
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.login.token = localStorage.getItem("login_token");
            this.$router.push({ name: "login" });
          }
        })
        .then(() => {
          if (!flag) {
            switch (this.data.review[index].customer_review.like) {
              case 0:
                this.data.review[index].customer_review.like = 1;
                this.data.review[index].likes = this.data.review[index].likes + 1;
                break;
              case 1:
                this.data.review[index].customer_review.like = 0;
                this.data.review[index].likes = this.data.review[index].likes - 1;
                break;
            }
          }
        });
    },
    sendComment: function(reviewID) {
      var flag = true;
      var index = this.getIndex(reviewID);
      this.comment.review_id = reviewID;
      axios({
        method: "post",
        url: "http://localhost:8000/api/comment",
        data: {
          comment: this.comment
        },
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          if (res.data.status == false) {
            this.$emit("loadSnackbar", "Đã xảy ra lỗi, thử lại?");
          }
        })
        .catch(error => {          
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.$router.push({ name: "login" });
          }
        }).then(()=>{
          this.comment.able = false;
          this.comment.content = "";
          this.comment.review_id = 0;
        });
    },
    formatDate: function(date) {
      if (!date) return null;
      return this.$moment(date).format("DD-MM-YYYY");
    },
    updateNotification: function(review_id) {
      var flag = true;
      var index = this.getIndex(review_id);
      switch (this.data.review[index].customer_review.status) {
        case 1:
          this.data.review[index].customer_review.status = 0;
          break;
        case 0:
          this.data.review[index].customer_review.status = 1;
          break;
      }
      axios({
        method: "get",
        url: "http://localhost:8000/api/get-notification",
        params: {
          review_id: review_id
        },
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          if (res.data.status == false) {
            flag = false;
          }
        })
        .catch(error => {
          flag = false;
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.login.token = localStorage.getItem("login_token");
            this.$router.push({ name: "login" });
          }
        })
        .then(() => {
          if (!flag) {
            switch (this.data.review[index].customer_review.status) {
              case 1:
                this.data.review[index].customer_review.status = 0;
                break;
              case 0:
                this.data.review[index].customer_review.status = 1;
                break;
            }
          }
        });
    },
  }
};
</script>

<style scoped>
.follow-color {
  color: #dfe4ea;
}
.unfollow-color {
  color: #2d98da;
}
.v-tooltip__content {
  opacity: 1 !important;
  padding: 4px !important;
  border-bottom-left-radius: 20%;
  box-shadow: none !important;
}
.v-menu__content {
  box-shadow: none !important;
}
.radius {
  border-bottom-left-radius: 20%;
}
</style>