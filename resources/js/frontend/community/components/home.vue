<template>
  <v-layout row wrap class="mx-3">
    <v-flex shrink md8 v-if="feeds.length !=0">
      <v-layout class="row wrap mx-3 mb-5" v-for="(r,index) in feeds" :key="index">
        <v-badge left overlap :color="color.badge">
          <template v-slot:badge>
            <v-tooltip top v-if="r.can_comment == 1">
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
              <v-layout row wrap class="pa-0 ma-0" align-center>
                <v-flex md9 class="text-md-left">
                  <router-link :to="{name:'user',params:{id:r.customer.id}}">
                    <v-avatar size="72px" color="black" flat>
                      <v-avatar size="70px" color="white">
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <img :src="'/img/user/'+r.customer.avatar.image_link" v-on="on" />
                          </template>
                          <span>{{r.customer.name }}</span>
                        </v-tooltip>
                      </v-avatar>
                    </v-avatar>
                    <span class="title pl-3 font-weight-light">{{r.customer.name}}</span>
                  </router-link>
                </v-flex>
                <v-spacer></v-spacer>
                <v-flex md1 class="text-md-right">
                  <v-speed-dial :transition="`slide-x-reverse-transition`" :direction="`left`">
                    <template v-slot:activator>
                      <v-btn fab depressed color="#6F1E51" small @click="r.cancel = !r.cancel">
                        <v-icon color="white" medium v-if="!r.cancel">settings</v-icon>
                        <v-icon color="white" medium>clear</v-icon>
                      </v-btn>
                    </template>
                    <v-tooltip top v-if="r.can_comment ==1 && r.customer.id == customer.id">
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          color="#0652DD"
                          depressed
                          small
                          @click.stop="updateReviewState(r.id)"                          
                          v-on="on"
                        >
                          <i class="fas fa-comment white--text fa-lg"></i>
                        </v-btn>  
                      </template>
                      <span>Đang bật bình luận</span>
                    </v-tooltip>
                    <v-tooltip top v-else-if="r.customer.id == customer.id">
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          color="red"
                          depressed
                          small
                          @click.stop="updateReviewState(r.id)"
                          v-on="on"
                        >
                          <i class="fas fa-comment-slash white--text fa-lg"></i>
                        </v-btn>
                      </template>
                      <span>Đang tắt bình luận</span>
                    </v-tooltip>
                    <v-tooltip top v-if="r.customer_review.status ==1">
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          color="#0652DD"
                          depressed
                          small
                          @click.stop="updateNotification(r.id)"
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
                          @click.stop="updateNotification(r.id)"
                          v-on="on"
                        >
                          <i class="far fa-bell-slash white--text fa-lg"></i>
                        </v-btn>
                      </template>
                      <span>Đang tắt thông báo</span>
                    </v-tooltip>
                  </v-speed-dial>
                </v-flex>
              </v-layout>
            </v-card-title>
            <v-divider class="m-0 p-0"></v-divider>
            <router-link class="pointer" :to="{name:'review',query:{id:r.id}}" target="_blank">
              <v-card-text>
                <span class="headline">" {{r.title}} "</span>
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
                    <v-card-text class="font-weight-bold font-italic">{{r.content}}</v-card-text>
                  </v-card>
                </v-layout>
                <span class="grey--text pl-2">-&nbsp;{{formatDate(r.created_at)}}</span>
                <v-layout
                  row
                  wrap
                  class="pa-0 ma-0 mt-2 ml-3 pl-3 border-left border-bottom caption"
                  align-center
                >
                  <v-flex md3>
                    <v-img :aspect-ratio="1" :src="'/blog/img/hotel/'+r.hotel.image"></v-img>
                  </v-flex>
                  <v-flex md8 offset-md1 class="pa-3 border-left">
                    <v-layout row wrap class="pa-0 ma-0">
                      <v-flex md12>
                        <div>
                          <router-link :to="{name:'hotel',params:{id:r.hotel.id}}" target="_blank">
                            <span class="font-weight-bold body-2">{{r.hotel.name}}</span>
                          </router-link>
                        </div>
                        <div v-if="r.hotel.stars_num > 0">
                          <v-chip color="#3B3B98" class="white--text">
                            <v-icon class="mr-1" small color="yellow">star</v-icon>
                            {{r.hotel.stars_num}}/5
                          </v-chip>
                          <v-chip
                            color="#A3CB38"
                            class="white--text font-weight-text"
                          >{{r.hotel.review_point}}/10</v-chip>
                        </div>
                        <div>
                          <v-chip color="#7d5fff" class="white--text">
                            <v-icon class="mr-1" small color="pink">room</v-icon>
                            {{r.hotel.address}}
                          </v-chip>
                        </div>
                      </v-flex>
                      <v-flex md4 v-for="(ser,i) in r.hotel.services" :key="i" class="pa-1">
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
                    v-on:click="likeReview(r.id)"
                    v-if="r.customer_review.like == 0"
                  >favorite_border</v-icon>
                  <v-icon
                    v-on="on"
                    class="ml-3 mr-1"
                    large
                    :color="color.heart"
                    v-on:click="likeReview(r.id)"
                    v-else
                  >favorite</v-icon>
                </template>
                <span>like</span>
              </v-tooltip>
              <span class="grey--text subheading">{{r.likes}}</span>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-icon
                    v-on="on"
                    class="ml-3 mr-1"
                    large
                    :color="color.comment"
                    v-on:click="r.model = true"
                    v-if="!r.model"
                  >chat_bubble_outline</v-icon>
                  <v-icon
                    v-on="on"
                    class="ml-3 mr-1"
                    large
                    :color="color.comment"
                    v-on:click="r.model = false"
                    v-else
                  >chat_bubble</v-icon>
                </template>
                <span>bình luận</span>
              </v-tooltip>
              <span class="grey--text subheading">{{r.comments}}</span>
            </v-card-actions>
            <v-divider v-show="r.comments>0 && r.model" class="pa-0 ma-0"></v-divider>
            <v-layout class="row wrap" justify-center v-if="r.model">
              <v-card
                light
                flat
                tile
                width="790px"
                class="mb-1"
                v-for="(c,i) in r.comment"
                :key="i"
              >
                <v-card-title>
                  <v-avatar size="42px" color="black" flat>
                    <router-link :to="{name:'user',params:{id:c.customer.id}}">
                      <v-avatar size="40px" flat color="white">
                        <img :src="'/img/user/'+c.customer.avatar.image_link" />
                      </v-avatar>
                    </router-link>
                  </v-avatar>
                  <router-link :to="{name:'user',params:{id:c.customer.id}}">
                    <span class="pl-3">{{c.customer.name}}</span>
                  </router-link>
                </v-card-title>
                <v-card-text>
                  <v-layout>
                    <v-card flat tile tag="div" color="grey lighten-2" width="100%" class="radius">
                      <v-card-text class="font-weight-bold font-italic black--text">{{c.content}}</v-card-text>
                    </v-card>
                  </v-layout>
                </v-card-text>
                <v-divider class="pa-0 ma-0"></v-divider>
              </v-card>
              <v-card flat tile width="790px" v-show="r.can_comment === 1">
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
                        :disabled="comment.content.length ===0"
                        color="teal"
                        class="white--text"
                        @click="sendComment(r.id)"
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
      <infinite-loading @distance="1" @infinite="loadFeeds">
        <span class="caption font-italic" slot="no-more">Bạn đã đến cuối trang...</span>
        <span
          class="caption font-italic"
          slot="no-results"
          v-if="this.feeds.length>0"
        >Bạn đã đến cuối trang...</span>
        <span class="caption font-italic" slot="no-results" v-else></span>
      </infinite-loading>
    </v-flex>
    <v-flex shrink md8 v-else-if="flag == true">
      <v-img src="/blog/img/slider/oops.png">
        <v-layout row wrap fill-height justify-center align-center>
          <v-flex md12>
            <div class="text-md-center headline font-italic">{{oops}}</div>
          </v-flex>
        </v-layout>
      </v-img>
    </v-flex>
    <v-flex shrink md8 v-else>
      <v-layout row wrap class="pa-0 ma-0" justify-center align-start>
        <v-flex md3 class="pa-2 ma-2">
          <v-img :aspect-ratio="1" src="/img/booking/load.gif" style="opacity:0.9">
            <v-layout fill-height align-center justify-center>
              <span class="pa-5 caption black--text font-weight-bold">đang tải...</span>
            </v-layout>
          </v-img>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  props: {
    customer: {
      type: Object
    }
  },
  mounted() {},
  data() {
    return {
      cancel: false,
      page: 1,
      comment: {
        review_id: 0,
        content: "",
        able: false
      },
      oops: "",
      user: {},
      feeds: [],
      color: {
        header: "#2c3e50",
        badge: "#1cc3b2",
        heart: "#ff6348",
        fullHeart: "#f8a5c2",
        comment: "#1cc3b2",
        fullComment: "#f5f6fa",
        grey: "#CAD3C8",
        news: "#1B1464",
        follow: "#2d98da"
      },
      avatar: {
        link: null
      },
      flag: false
    };
  },
  mounted() {
    window.Echo.channel("message").listen(".send-mess", e => {
      this.feeds.forEach((element, index) => {
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
          this.feeds[index].comment = [...this.feeds[index].comment, comment];
          this.feeds[index].comments = this.feeds[index].comments + 1;
        }
      });
    });
  },
  created() {
    this.user = this.customer;
    this.load();
  },
  watch: {
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
    load: function() {
      this.$emit("loadLogin");
      if (localStorage.getItem("login_token") != null) {
        this.getFeeds();
        this.oops = "Hôm nay chẳng có gì...";
      }
    },
    getFeeds: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/review",
        params: {
          page: this.page
        },
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          this.feeds = res.data.data;
        })
        .catch(error => {
          console.log(error);
          console.log(error.response);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.login.token = localStorage.getItem("login_token");
            this.$router.push({ name: "login" });
          }
        }).then(()=>{
          this.flag = true;
        });
    },
    loadFeeds($state) {
      this.page = this.page + 1;
      axios({
        method: "get",
        url: "http://localhost:8000/api/review",
        params: {
          page: this.page
        },
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          if (res.data.data.length != 0) {
            this.feeds = this.feeds.concat(res.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
        });
    },
    getIndex(id) {
      var index = -1;
      if (this.feeds.length != 0) {
        this.feeds.forEach((element, i) => {
          if (element.id == id) index = i;
        });
      }
      return index;
    },
    likeReview: function(id) {
      var flag = true;
      var index = this.getIndex(id);
      switch (this.feeds[index].customer_review.like) {
        case 0:
          this.feeds[index].customer_review.like = 1;
          this.feeds[index].likes = this.feeds[index].likes + 1;
          break;
        case 1:
          this.feeds[index].customer_review.like = 0;
          this.feeds[index].likes = this.feeds[index].likes - 1;
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
          console.log(error);
          console.log(error.response);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.login.token = localStorage.getItem("login_token");
            this.$router.push({ name: "login" });
          }
        })
        .then(() => {
          if (!flag) {
            switch (this.feeds[index].customer_review.like) {
              case 0:
                this.feeds[index].customer_review.like = 1;
                this.feeds[index].likes = this.feeds[index].likes + 1;
                break;
              case 1:
                this.feeds[index].customer_review.like = 0;
                this.feeds[index].likes = this.feeds[index].likes - 1;
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
            this.$emit(
              "loadSnackbar",
              "Rất tiếc, thực hiện chưa hoàn thành. Thử lại?"
            );
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
      switch (this.feeds[index].customer_review.status) {
        case 1:
          this.feeds[index].customer_review.status = 0;
          break;
        case 0:
          this.feeds[index].customer_review.status = 1;
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
            switch (this.feeds[index].customer_review.status) {
              case 1:
                this.feeds[index].customer_review.status = 0;
                break;
              case 0:
                this.feeds[index].customer_review.status = 1;
                break;
            }
          }
        });
    },
    updateReviewState: function(review_id) {
      var flag = true;
      var index = this.getIndex(review_id);
      switch (this.feeds[index].can_comment) {
        case 1:
          this.feeds[index].can_comment = 0;
          break;
        case 0:
          this.feeds[index].can_comment = 1;
          break;
      }
      axios({
        method: "put",
        url: "http://localhost:8000/api/review/"+review_id,
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
            switch (this.feeds[index].can_comment) {
              case 1:
                this.feeds[index].can_comment = 0;
                break;
              case 0:
                this.feeds[index].can_comment = 1;
                break;
            }
          }
        });
    }
  }
};
</script>
<style scoped>
a {
  text-decoration-line: none !important;
  color: #000;
}
.pointer {
  cursor: pointer;
}
.follow-color {
  color: #dfe4ea;
}
.unfollow-color {
  color: #2d98da;
}
.v-tooltip__content {
  opacity: 1 !important;
  padding: 4px !important;
  box-shadow: none !important;
}
.v-menu__content {
  box-shadow: none !important;
}
.radius {
  border-bottom-left-radius: 20%;
}
</style>
