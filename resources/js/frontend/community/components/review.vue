<template>
  <v-layout row wrap class="mx-3">
    <v-flex md6 offset-md1>
      <v-layout class="row wrap mx-3 mb-5">
        <v-badge left overlap :color="color.badge">
          <template v-slot:badge>
            <v-tooltip top v-if="review.can_comment == 1">
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
                <v-flex md9 class="text-md-left" v-if="review.customer">
                  <v-avatar size="72px" color="black" flat>
                    <v-avatar size="70px" color="white">
                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <img :src="'/img/user/'+review.customer.avatar.image_link" v-on="on" />
                        </template>
                        <span>{{review.customer.name }}</span>
                      </v-tooltip>
                    </v-avatar>
                  </v-avatar>
                  <span class="title pl-3 font-weight-light">{{review.customer.name}}</span>
                </v-flex>
                <v-spacer v-if="review.customer"></v-spacer>
                <v-flex md11 v-else><v-divider></v-divider></v-flex>
                <v-flex md1 class="text-md-right">
                  <v-speed-dial :transition="`slide-x-reverse-transition`" :direction="`left`">
                    <template v-slot:activator>
                      <v-btn fab depressed color="#6F1E51" small @click="cancel = !cancel">
                        <v-icon color="white" medium v-if="!cancel">settings</v-icon>
                        <v-icon color="white" medium>clear</v-icon>
                      </v-btn>
                    </template>
                    <v-tooltip top v-if="review.can_comment ==1 && !review.customer">
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          color="#0652DD"
                          depressed
                          small
                          @click.stop="updateReviewState(review.id)"
                          v-on="on"
                        >
                          <i class="fas fa-comment white--text fa-lg"></i>
                        </v-btn>
                      </template>
                      <span>Đang bật bình luận</span>
                    </v-tooltip>
                    <v-tooltip top v-else-if="!review.customer">
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          color="red"
                          depressed
                          small
                          @click.stop="updateReviewState(review.id)"
                          v-on="on"
                        >
                          <i class="fas fa-comment-slash white--text fa-lg"></i>
                        </v-btn>
                      </template>
                      <span>Đang tắt bình luận</span>
                    </v-tooltip>
                    <v-tooltip top v-if="review.customer_review.status ==1">
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          color="#0652DD"
                          depressed
                          small
                          @click.stop="updateNotification(review.id)"
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
                          @click.stop="updateNotification(review.id)"
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
            <v-divider class="m-0 p-0" v-if="review.customer"></v-divider>
            <v-card-text>
              <span class="headline">" {{review.title}} "</span>
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
                  <v-card-text class="font-weight-bold font-italic">{{review.content}}</v-card-text>
                </v-card>
              </v-layout>
              <span class="grey--text pl-2">-{{formatDate(review.created_at)}}</span>
              <v-layout
                row
                wrap
                class="pa-0 ma-0 mt-2 ml-3 pl-3 border-left border-bottom"
                align-center
              >
                <v-flex md3>
                  <v-img :aspect-ratio="1" :src="'/blog/img/hotel/'+review.hotel.image"></v-img>
                </v-flex>
                <v-flex md8 offset-md1 class="pa-3 border-left">
                  <v-layout row wrap class="pa-0 ma-0">
                    <v-flex md12>
                      <div>
                        <router-link
                          :to="{name:'hotel',params:{id:review.hotel.id}}"
                          target="_blank"
                        >
                          <span class="font-weight-bold body-2">{{review.hotel.name}}</span>
                        </router-link>
                      </div>
                      <div v-if="review.hotel.stars_num > 0">
                        <v-chip color="#3B3B98" class="white--text">
                          <v-icon class="mr-1" small color="yellow">star</v-icon>
                          {{review.hotel.stars_num}}/5
                        </v-chip>
                        <v-chip
                          color="#A3CB38"
                          class="white--text font-weight-text"
                        >{{review.hotel.review_point}}/10</v-chip>
                      </div>
                      <div>
                        <v-chip color="#7d5fff" class="white--text">
                          <v-icon class="mr-1" small color="pink">room</v-icon>
                          {{review.hotel.address}}
                        </v-chip>
                      </div>
                    </v-flex>
                    <v-flex md4 v-for="(ser,i) in review.hotel.services" :key="i" class="pa-1">
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
            <v-card-actions>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-icon
                    v-on="on"
                    class="ml-3 mr-1"
                    large
                    :color="color.heart"
                    v-on:click="likeReview(review.id)"
                    v-if="review.customer_review.like == 0"
                  >favorite_border</v-icon>
                  <v-icon
                    v-on="on"
                    class="ml-3 mr-1"
                    large
                    :color="color.heart"
                    v-on:click="likeReview(review.id)"
                    v-else
                  >favorite</v-icon>
                </template>
                <span>like</span>
              </v-tooltip>
              <span class="grey--text subheading">{{review.likes}}</span>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-icon
                    v-on="on"
                    class="ml-3 mr-1"
                    large
                    :color="color.comment"
                    v-on:click="review.model = true"
                    v-if="!review.model"
                  >chat_bubble_outline</v-icon>
                  <v-icon
                    v-on="on"
                    class="ml-3 mr-1"
                    large
                    :color="color.comment"
                    v-on:click="review.model = false"
                    v-else
                  >chat_bubble</v-icon>
                </template>
                <span>bình luận</span>
              </v-tooltip>
              <span class="grey--text subheading">{{review.comments}}</span>
            </v-card-actions>
            <v-divider v-show="review.comments>0 && review.model" class="pa-0 ma-0"></v-divider>
            <v-layout class="row wrap" justify-center v-if="review.model">
              <v-card
                light
                flat
                tile
                width="790px"
                class="mb-1"
                v-for="(c,i) in review.comment"
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
              <v-card flat tile width="790px" v-show="review.can_comment === 1">
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
                        @click="sendComment()"
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
    </v-flex>
    <v-spacer></v-spacer>
  </v-layout>
</template>

<script>
export default {
  props: {
    customer: {
      type: Object
    }
  },
  data() {
    return {
      cancel:false,
      id: this.$route.query.id,
      comment: {
        review_id: 0,
        content: "",
        able: false
      },
      review: {
        customer_review: {},
        hotel: {
          id: 0,
          image: "default.png",
          services: []
        }
      },
      user: {},
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
      }
    };
  },
  mounted() {
    window.Echo.channel("message").listen(".send-mess", e => {
      if (e.link.id == this.id) {
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
        this.review.comment = [...this.review.comment, comment];
        this.review.comments = this.review.comments + 1;
      }
    });
  },
  watch: {
    $route: "load",
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
  created() {
    this.user = this.customer;
    if (Object.keys(this.$route.query).length == 0) {
      this.$router.push({ name: "home" });
    }
    this.load();
  },
  methods: {
    load: function() {
      this.id = this.$route.query.id;
      axios({
        method: "get",
        url: "http://localhost:8000/api/review/" + this.id,
        params: {},
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          if (res.data.status != true) {
            this.$router.push({ name: "home" });
          } else {
            this.review = res.data.review;
          }
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
    formatDate: function(date) {
      if (!date) return null;
      return this.$moment(date).format("DD-MM-YYYY");
    },
    sendComment: function() {
      this.comment.review_id = this.id;
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
    likeReview: function(id) {
      var flag = true;
      switch (this.review.customer_review.like) {
        case 0:
          this.review.customer_review.like = 1;
          this.review.likes = this.review.likes + 1;
          break;
        case 1:
          this.review.customer_review.like = 0;
          this.review.likes = this.review.likes - 1;
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
            switch (this.review.customer_review.like) {
              case 0:
                this.review.customer_review.like = 1;
                this.review.likes = this.review.likes + 1;
                break;
              case 1:
                this.review.customer_review.like = 0;
                this.review.likes = this.review.likes - 1;
                break;
            }
          }
        });
    },
    updateNotification: function(review_id) {
      var flag = true;
      switch (this.review.customer_review.status) {
        case 1:
          this.review.customer_review.status = 0;
          break;
        case 0:
          this.review.customer_review.status = 1;
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
            switch (this.review.customer_review.status) {
              case 1:
                this.review.customer_review.status = 0;
                break;
              case 0:
                this.review.customer_review.status = 1;
                break;
            }
          }
        });
    },
    updateReviewState: function(review_id) {
      var flag = true;
      switch (this.review.can_comment) {
        case 1:
          this.review.can_comment = 0;
          break;
        case 0:
          this.review.can_comment = 1;
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
            switch (this.review.can_comment) {
              case 1:
                this.review.can_comment = 0;
                break;
              case 0:
                this.review.can_comment = 1;
                break;
            }
          }
        });
    }
  }
};
</script>
