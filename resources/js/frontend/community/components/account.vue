<template>
  <v-layout row wrap class="mx-3">
    <v-flex shrink md8>
      <v-img :aspect-ratio="16/4" src="/blog/img/slider/slider.png" class="mr-2 radius mb-3">
        <v-layout row wrap fill-height class="lightbox white--text mt-5 mb-1 pl-5">
          <v-flex md9 shrink class="pl-5">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-avatar size="90px" tile flat color="white" v-on="on">
                  <img :src="'/img/user/'+user.avatar.image_link" alt />
                </v-avatar>
              </template>
              <span>{{user.name}}</span>
            </v-tooltip>
            <div class="subheading font-weight-black mt-2">{{user.name}}</div>
            <div class="body-1">{{user.email}}</div>
          </v-flex>
          <v-spacer></v-spacer>
        </v-layout>
      </v-img>
      <div v-if="flag ===false">
        <v-layout row wrap class="pa-0 ma-0" justify-center align-start>
          <v-flex md3 class="pa-2 ma-2">
            <v-img :aspect-ratio="1" src="/img/booking/load.gif" style="opacity:0.9">
              <v-layout fill-height align-center justify-center>
                <span class="pa-5 caption black--text font-weight-bold">đang tải...</span>
              </v-layout>
            </v-img>
          </v-flex>
        </v-layout>
      </div>
      <div v-else-if="user.review.length !=0">
        <v-layout class="row wrap mx-3 mb-5" v-for="(item,index) in user.review" :key="index">
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
                <v-speed-dial :transition="`slide-x-reverse-transition`" :direction="`left`">
                  <template v-slot:activator>
                    <v-btn fab depressed color="#6F1E51" small @click="item.cancel = !item.cancel">
                      <v-icon color="white" medium v-if="!item.cancel">settings</v-icon>
                      <v-icon color="white" medium>clear</v-icon>
                    </v-btn>
                  </template>
                  <v-tooltip top v-if="item.can_comment == 1">
                    <template v-slot:activator="{ on }">
                      <v-btn
                        fab
                        color="#0652DD"
                        depressed
                        small
                        @click.stop="updateReviewState(item.id)"
                        v-on="on"
                      >
                        <i class="fas fa-comment white--text fa-lg"></i>
                      </v-btn>
                    </template>
                    <span>Đang bật bình luận</span>
                  </v-tooltip>
                  <v-tooltip top v-else>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        fab
                        color="red"
                        depressed
                        small
                        @click.stop="updateReviewState(item.id)"
                        v-on="on"
                      >
                        <i class="fas fa-comment-slash white--text fa-lg"></i>
                      </v-btn>
                    </template>
                    <span>Đang tắt bình luận</span>
                  </v-tooltip>
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
                </v-speed-dial>
              </v-card-title>
              <router-link class="pointer" :to="{name:'review',query:{id:item.id}}" target="_blank">
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
                  <span>binh luận</span>
                </v-tooltip>
                <span class="grey--text subheading">{{item.comments}}</span>
              </v-card-actions>
              <v-divider v-show="item.comments>0 && item.model"></v-divider>
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
                          :disabled="comment.content.length ===0"
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
        <span class="text-uppercase">Chưa có bài đánh giá nào...</span>
      </div>
    </v-flex>
    <v-flex shrink md4>
      <v-expansion-panel light class="elevation-0" v-model="expand">
        <v-expansion-panel-content class="mb-3">
          <template v-slot:actions>
            <v-icon>expand_more</v-icon>
          </template>
          <template v-slot:header>
            <div class="title my-3 text-uppercase font-weight-black">
              <v-icon color="black">assignment_ind</v-icon>&nbsp;Thông Tin Tài Khoản
            </div>
          </template>
          <v-btn round depressed color="grey lighten-2" v-on:click.stop="openFormUserInfo">
            <v-icon small>create</v-icon>&nbsp;Cập nhật
          </v-btn>
          <v-btn
            round
            depressed
            color="grey lighten-2"
            v-on:click.stop="passDialog.check = true; form.pass=''"
          >
            <v-icon small>create</v-icon>&nbsp;Đổi Mật Khẩu
          </v-btn>
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
                <span class="font-weight-black subheading">{{user.username}}</span>
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
                <span class="font-weight-black">{{user.name}}</span>
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
                <span class="font-weight-black">{{user.email}}</span>
              </v-flex>
            </v-layout>
            <v-layout row wrap class="mb-2 ml-1" align-center>
              <v-flex md1>
                <i class="fas fa-phone green--text fa-lg"></i>
              </v-flex>
              <v-flex md3>
                <span class="font-weight-black">Số Điện Thoại:</span>
              </v-flex>
              <v-flex md8 class="pl-2">
                <span class="font-weight-black">{{user.phone_number}}</span>
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
                <span class="font-weight-black">{{user.customer.coin}}</span>
              </v-flex>
            </v-layout>
            <v-layout row wrap class="mb-2 ml-1" align-center>
              <v-flex md1>
                <i class="fas fa-home indigo--text fa-lg"></i>
              </v-flex>
              <v-flex md3>
                <span class="font-weight-black">Địa Chỉ:</span>
              </v-flex>
              <v-flex md8 class="pl-2">
                <span class="font-weight-black">{{user.customer.address}}</span>
              </v-flex>
            </v-layout>
          </v-container>
        </v-expansion-panel-content>
        <v-expansion-panel-content class="mb-3">
          <template v-slot:actions>
            <v-icon>expand_more</v-icon>
          </template>
          <template v-slot:header>
            <div class="title my-3 text-uppercase font-weight-black">
              <i class="fas fa-list-ol black--text"></i>&nbsp;Danh Sách Đơn
            </div>
          </template>
          <v-btn round depressed color="grey lighten-2" @click.stop="bookingListPage(0)">
            <v-icon small>notes</v-icon>&nbsp;Xem Tất Cả
          </v-btn>
          <v-divider class="ma-0 pa-0"></v-divider>
          <v-container class="pa-1 ma-0">
            <v-layout
              row
              wrap
              align-center
              class="pa-3 ma-3 grey lighten-2"
              v-for="(b,i) in user.booking"
              :key="i"
              v-show="i<5"
            >
              <v-flex md6>
                <div>
                  <div>
                    Mã:&nbsp;
                    <span class="font-weight-black">{{b.id}}</span>
                  </div>
                  <div>
                    Tổng Giá:&nbsp;
                    <span
                      class="font-weight-black"
                    >{{ b.total.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</span>
                  </div>
                </div>
              </v-flex>
              <v-flex md6>
                <v-chip color="orange" class="white--text caption">{{b.status.name}}</v-chip>
              </v-flex>
              <v-flex md12>
                <v-divider class="pa-0 ma-0 mt-2"></v-divider>
              </v-flex>
              <v-flex md12 class="mt-2">
                <div class="pl-3 body-2 font-italic" v-if="b.can_review == true">
                  Chưa đánh giá.&nbsp;
                  <span
                    class="pointer purple--text"
                    @click="openReviewForm(b)"
                  >Đánh giá?</span>
                </div>
                <div class="pl-3 body-2 font-italic">
                  <span class="pointer teal--text" @click.stop="bookingListPage(b.id)">Xem...</span>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-expansion-panel-content>
        <v-expansion-panel-content class="mb-3">
          <template v-slot:actions>
            <v-icon>expand_more</v-icon>
          </template>
          <template v-slot:header>
            <div class="title my-3 text-uppercase font-weight-black">
              <i class="fas fa-question black--text"></i>&nbsp;Danh sách câu hỏi
            </div>
          </template>
          <v-btn
            round
            depressed
            color="grey lighten-2"
            @click.stop="openQuestionListDialog"
            v-if="user.question.length!=0"
          >
            <v-icon small>notes</v-icon>&nbsp;xem tất cả
          </v-btn>
          <v-divider class="ma-0 pa-0"></v-divider>
          <v-container class="pa-1 ma-0">
            <v-layout
              row
              wrap
              align-center
              class="pa-3 ma-3 grey lighten-2 caption"
              v-for="(q,i) in user.question"
              :key="i"
              v-show="i<3"
            >
              <v-flex md12>
                <div>
                  <div>
                    <span class="font-weight-black">{{q.title}}</span>
                  </div>
                  <div>
                    <span class="font-italic">"{{q.content}}"</span>
                  </div>
                </div>
              </v-flex>
              <v-flex md12 v-if="q.reply!=null">
                <v-divider class="pa-0 ma-0 my-2"></v-divider>
              </v-flex>
              <v-flex md12 v-if="q.reply !=null">
                <div>
                  <span class="body-1">{{q.hotel.name}}:&nbsp;"{{q.reply.content}}"</span>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-flex>
    <v-dialog v-model="formDialog" width="700px" persistent>
      <v-card color="grey lighten-4" flat tile width="100%" class="pa-5">
        <v-form ref="form" data-vv-scope="form1">
          <v-layout row wrap align-top justify-center>
            <v-card-title class="title text-uppercase font-weight-bold">Thông tin</v-card-title>
            <v-flex md12 pa-2>
              <v-text-field outline name="name" color="teal" v-model="form.name" label="Họ tên"></v-text-field>
            </v-flex>
            <v-flex md12 pa-2>
              <v-text-field
                outline
                name="phone"
                color="teal"
                v-model="form.phone"
                label="Số điện thoại"
              ></v-text-field>
            </v-flex>
            <v-flex md12 px-2>
              <v-textarea
                outline
                name="address"
                auto-grow
                rows="1"
                color="teal"
                v-model="form.address"
                label="Địa chỉ"
              ></v-textarea>
              <v-btn color="teal" v-on:click="submit" dark depressed>Xác Nhận</v-btn>
              <v-btn color="grey" v-on:click="clear" dark depressed>Xóa</v-btn>
              <v-btn color="red" v-on:click="formDialog=false" dark depressed>Hủy</v-btn>
            </v-flex>
            <v-flex></v-flex>
          </v-layout>
        </v-form>
      </v-card>
    </v-dialog>
    <v-dialog v-model="passDialog.check" width="700px" persistent>
      <v-card color="grey lighten-4" flat tile width="100%" class="pa-5">
        <v-form ref="form">
          <v-layout row wrap align-top justify-center>
            <v-card-title class="title text-uppercase font-weight-bold">nhập mật khẩu!</v-card-title>
            <v-flex md12 px-2>
              <v-text-field
                outline
                color="teal"
                v-model="form.pass"
                label="Mật khẩu"
                type="password"
                append-icon="visibility"
                v-on:click:append="value = true"
                v-if="!value"
              ></v-text-field>
              <v-text-field
                outline
                color="teal"
                v-model="form.pass"
                label="Mật khẩu"
                type="text"
                append-icon="visibility_off"
                v-on:click:append="value = false"
                v-else
              ></v-text-field>
              <v-btn color="teal" v-on:click="checkPass" dark depressed>Xác Nhận</v-btn>
              <v-btn color="grey" v-on:click="clear" dark depressed>Xóa</v-btn>
              <v-btn color="red" v-on:click="passDialog.check=false" dark depressed>Hủy</v-btn>
            </v-flex>
            <v-flex></v-flex>
          </v-layout>
        </v-form>
      </v-card>
    </v-dialog>
    <v-dialog v-model="passDialog.main" width="700px" persistent>
      <v-card color="grey lighten-4" flat tile width="100%" class="pa-5">
        <v-form ref="form" data-vv-scope="form2">
          <v-layout row wrap align-top justify-center>
            <v-card-title class="title text-uppercase font-weight-bold">Mật Khẩu Mới</v-card-title>
            <v-flex md12 px-2>
              <v-text-field
                :error-messages="errors.collect('form2.password')"
                data-vv-name="password"
                ref="password"
                v-validate="'required|min:4'"
                type="password"
                outline
                color="teal"
                v-model="form.newPass"
                label="Nhập mật khẩu mới tại đây..."
                append-icon="visibility"
                v-on:click:append="value=true"
                v-if="!value"
              ></v-text-field>
              <v-text-field
                :error-messages="errors.collect('form2.password')"
                data-vv-name="password"
                ref="password"
                v-validate="'required|min:4'"
                type="text"
                outline
                color="teal"
                v-model="form.newPass"
                label="Nhập mật khẩu mới tại đây..."
                append-icon="visibility_off"
                v-on:click:append="value=false"
                v-else
              ></v-text-field>
              <v-text-field
                :error-messages="errors.collect('form2.password_confirmation')"
                data-vv-as="password"
                data-vv-name="password_confirmation"
                v-validate="'required|confirmed:password'"
                type="password"
                outline
                color="teal"
                v-model="form.rePass"
                label="Xác nhận lại!"
                append-icon="visibility"
                v-on:click:append="value1=true"
                v-if="!value1"
              ></v-text-field>
              <v-text-field
                :error-messages="errors.collect('form2.password_confirmation')"
                data-vv-as="password"
                data-vv-name="password_confirmation"
                v-validate="'required|confirmed:password'"
                type="text"
                outline
                color="teal"
                v-model="form.rePass"
                label="Xác nhận lại!"
                append-icon="visibility_off"
                v-on:click:append="value1=false"
                v-else
              ></v-text-field>
              <v-btn color="teal" v-on:click="submitPass" dark depressed>Xác Nhận</v-btn>
              <v-btn color="grey" v-on:click="clear" dark depressed>Xóa</v-btn>
              <v-btn color="red" v-on:click="passDialog.main=false" dark depressed>Hủy</v-btn>
            </v-flex>
            <v-flex></v-flex>
          </v-layout>
        </v-form>
      </v-card>
    </v-dialog>
    <v-dialog v-model="questionList.dialog" width="700px" persistent>
      <v-card flat tile light height="470px">
        <v-card-text>
          <v-card light flat width="100%" height="370px" style="overflow:auto">
            <v-card-title>
              <v-btn round depressed color="red" dark v-on:click="questionList.dialog = false">
                <span class="text-uppercase">Đóng</span>
              </v-btn>
              <v-spacer></v-spacer>
              <v-text-field
                color="teal"
                v-model="search"
                append-icon="search"
                label="Nhập tên nhà cung cấp bạn đã hỏi..."
                single-line
                hide-details
              ></v-text-field>
            </v-card-title>
            <v-data-table
              :rows-per-page-items='[2,4,6,{"text":"$vuetify.dataIterator.rowsPerPageAll","value":-1}]'
              :headers="tblHeaders.question"
              hide-headers
              :items="user.question"
              :search="search"
              class="elevation-0 border"
            >
              <template v-slot:items="q">
                <td>
                  <div class="ma-2">
                    <div>
                      <span
                        class="font-weight-black"
                        style="word-wrap: break-word;"
                      >{{q.item.title}}</span>
                      <span class="caption grey--text">-&nbsp;{{formatDate(q.item.created_at)}}</span>
                    </div>
                    <div>
                      <span class="font-italic" style="word-wrap: break-word;">"{{q.item.content}}"</span>
                    </div>
                  </div>
                  <div class="ma-2 pa-3 purple lighten-2" v-if="q.item.reply !=null">
                    <div>
                      <span class="font-weight-black" style="word-wrap: break-word;">
                        <router-link
                          :to="{name:'hotel', params:{id:q.item.hotel.id}}"
                          target="_blank"
                        >{{q.item.hotel.name}}</router-link>
                      </span>
                    </div>
                    <div>
                      <span
                        class="font-italic"
                        style="word-wrap: break-word;"
                      >"{{q.item.reply.content}}"</span>
                    </div>
                    <div class="pl-3">
                      <span
                        class="caption grey--text"
                      >-&nbsp;{{formatDate(q.item.reply.created_at)}}</span>
                    </div>
                  </div>
                  <div class="ma-2 pa-3 purple lighten-2" v-else>
                    <div>
                      <span class="font-weight-black" style="word-wrap: break-word;">
                        <router-link
                          :to="{name:'hotel', params:{id:q.item.hotel.id}}"
                          target="_blank"
                        >{{q.item.hotel.name}}</router-link>
                      </span>
                    </div>
                    <div>
                      <span class="font-italic" style="word-wrap: break-word;">Chưa phản hồi...</span>
                    </div>
                  </div>
                </td>
              </template>
              <template v-slot:no-results>
                <v-layout align-center white>
                  <v-flex md3>
                    <v-img :aspect-ratio="1" src="/img/booking/no-result.gif"></v-img>
                  </v-flex>
                  <v-flex offset-md1 class="font-weight-bold body-2">Rất tiếc, không tìm thấy.</v-flex>
                </v-layout>
              </template>
            </v-data-table>
          </v-card>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog scrollable v-model="reviewForm.dialog" width="500px" persistent>
      <v-card flat tile light height="470px">
        <v-card-actions>
          <span class="ml-5 font-weight-bold title">Đánh giá</span>
          <v-spacer></v-spacer>
          <div class="mr-3">
            <v-btn round color="teal" depressed class="white--text" @click="sendReview">send</v-btn>
            <v-btn
              round
              color="red"
              depressed
              class="white--text"
              @click="reviewForm.dialog = false"
            >close</v-btn>
          </div>
        </v-card-actions>
        <v-divider class="pa-0 ma-0"></v-divider>
        <v-card-title class="pa-0 ma-0">
          <v-card flat width="100%" style="overflow:auto;" height="380px" class="pa-0 ma-0">
            <v-card-title>
              <v-form ref="form" data-vv-scope="formReview">
                <v-layout row wrap class="pa-0 ma-0">
                  <v-flex md12>
                    <v-layout row wrap class="pa-0 ma-0" align-center>
                      <v-flex md8>
                        <v-rating
                          small
                          v-model="reviewForm.star"
                          color="indigo darken-3"
                          background-color="grey darken-1"
                          empty-icon="$vuetify.icons.ratingFull"
                          hover
                          :length="10"
                        ></v-rating>
                      </v-flex>
                      <v-flex md2>({{reviewForm.star}}/10)</v-flex>
                      <v-flex md6>
                        <v-checkbox
                          v-model="reviewForm.can_comment"
                          label="Bật bình luận"
                          color="#B53471"
                        ></v-checkbox>
                      </v-flex>
                      <v-flex md6>
                        <v-checkbox
                          :disabled="!reviewForm.can_comment"
                          v-model="reviewForm.notification"
                          label="Nhận thông báo"
                          color="#B53471"
                        ></v-checkbox>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                  <v-flex md12>
                    <v-text-field
                      :error-messages="errors.collect('formReview.title')"
                      data-vv-name="title"
                      v-validate="'required'"
                      v-model="reviewForm.title"
                      color="#B53471"
                      type="text"
                      outline
                      clearable
                      clear-icon="cancel"
                      label="Nhập tiêu đề tại đây"
                    ></v-text-field>
                  </v-flex>
                  <v-flex md12>
                    <v-textarea
                      :error-messages="errors.collect('formReview.content')"
                      data-vv-name="content"
                      v-validate="'required'"
                      v-model="reviewForm.content"
                      color="#B53471"
                      outline
                      clearable
                      clear-icon="cancel"
                      auto-grow
                      rows="4"
                      label="Nhập nội dung tại đây"
                    ></v-textarea>
                  </v-flex>
                </v-layout>
              </v-form>
            </v-card-title>
          </v-card>
        </v-card-title>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
export default {
  $_veeValidate: {
    validator: "new"
  },
  props: {},
  data() {
    return {
      search: "",
      tblHeaders: {
        question: [
          {
            text: "Câu Hỏi",
            value: "hotel.name",
            align: "center",
            class: "red--text",
            sortable: false
          }
        ]
      },
      comment: {
        review_id: 0,
        content: "",
        able: false
      },
      questionList: {
        dialog: false
      },
      followDialog: false,
      value: false,
      value1: false,
      user: {
        customer: {},
        avatar: {},
        booking: [],
        question: [],
        review: []
      },
      form: {
        email: "",
        pass: "",
        newPass: "",
        rePass: "",
        name: "",
        phone: "",
        address: ""
      },
      dictionary: {
        custom: {
          email: {
            required: () => "Email không được bỏ trống"
          },
          password: {
            required: () => "Mật khẩu không được bỏ trống",
            min: "Mật khẩu phải nhiều hơn 3 ký tự"
          },
          password_confirmation: {
            required: () => "Không được bỏ trống",
            confirmed: "Không khớp, nhập lại"
          },
          title: {
            required: () => "Tiêu đề không được bỏ trống"
          },
          content: {
            required: () => "Nội dung không được bỏ trống"
          }
        }
      },
      reviewForm: {
        dialog: false,
        title: "",
        content: "",
        star: 0,
        hotel_id: 0,
        can_comment: true,
        notification: true
      },
      // comment: true,
      like: false,
      model: false,
      expand: [true, true],
      color: {
        header: "#2c3e50",
        badge: "#1cc3b2",
        heart: "#ff6348",
        fullHeart: "#f8a5c2",
        comment: "#1cc3b2",
        fullComment: "#f5f6fa",
        check: "#fa8231"
      },
      dialog: false,
      formDialog: false,
      passDialog: {
        check: false,
        main: false
      },
      flag: false
    };
  },
  mounted() {
    this.$validator.localize("en", this.dictionary);
    window.Echo.channel("message").listen(".send-mess", e => {
      this.user.review.forEach((element, index) => {
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
          this.user.review[index].comment = [
            ...this.user.review[index].comment,
            comment
          ];
          this.user.review[index].comments =
            this.user.review[index].comments + 1;
        }
      });
    });
  },
  beforeCreate() {},
  created() {
    this.getInfo();
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
    },
    "reviewForm.can_comment": function() {
      if (this.reviewForm.can_comment == 0) {
        this.reviewForm.notification = 0;
      }
    }
  },
  methods: {
    openQuestionListDialog: function() {
      this.getInfo();
      if (localStorage.getItem("login_token") != null) {
        this.questionList.dialog = true;
      }
    },
    confirmAction: function(booking) {
      this.confirm.dialog = true;
      this.confirm.booking = booking;
      this.confirm.title = "bạn có chắc chắn?";
      if (booking.status.id == 4)
        this.confirm.content =
          "Đồng ý thực hiện hành động này, bạn đã cam kết với chúng tôi rằng bạn đã đọc và hiểu rõ mọi điều khoản liên quan.";
    },
    openFormUserInfo: function() {
      this.formDialog = true;
      this.form.username = this.user.username;
      this.form.name = this.user.name;
      this.form.email = this.user.email;
      this.form.address = this.user.customer.address;
      this.form.phone = this.user.phone_number;
    },
    getIndex(id) {
      var index = -1;
      if (this.user.review.length != 0) {
        this.user.review.forEach((element, i) => {
          if (element.id == id) index = i;
        });
      }
      return index;
    },
    likeReview: function(id) {
      var flag = true;
      var index = this.getIndex(id);
      switch (this.user.review[index].customer_review.like) {
        case 0:
          this.user.review[index].customer_review.like = 1;
          this.user.review[index].likes = this.user.review[index].likes + 1;
          break;
        case 1:
          this.user.review[index].customer_review.like = 0;
          this.user.review[index].likes = this.user.review[index].likes - 1;
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
            switch (this.user.review[index].customer_review.like) {
              case 0:
                this.user.review[index].customer_review.like = 1;
                this.user.review[index].likes =
                  this.user.review[index].likes + 1;
                break;
              case 1:
                this.user.review[index].customer_review.like = 0;
                this.user.review[index].likes =
                  this.user.review[index].likes - 1;
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
            this.$emit("loadSnackbar", "Thao tác lỗi, thử lại?");
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
    clear: function() {
      this.$refs.form.reset();
      this.$validator.reset();
    },
    submit: function() {
      this.$validator.validateAll("form1").then(valid => {
        if (valid) {
          axios({
            method: "put",
            url: "http://localhost:8000/api/user/" + this.user.id,
            params: {
              id: this.user.id,
              phone_number: this.form.phone,
              address: this.form.address,
              name: this.form.name
            }
          }).then(res => {
            console.log(res.data.status);
            if (res.data.status == true) {
              this.getInfo();
              this.$emit("loadLogin");
              this.formDialog = false;
              return;
            } else {
              this.$emit("loadSnackbar", "Thao tác lỗi, thử lại!");
              this.$validator.reset();
            }
          });
        }
      });
    },
    getInfo: function() {
      if (localStorage.getItem("login_token") != null) {
        axios({
          method: "get",
          url: "http://localhost:8000/api/getUser",
          headers: {
            Authorization: "Bearer " + localStorage.getItem("login_token")
          }
        })
          .then(res => {
            this.flag = true;
            this.user = res.data.user;
          })
          .catch(error => {
            console.log(error.response);
            console.log(error);
            if (error.response.status == 401) {
              localStorage.removeItem("login_token");
              this.$router.push({ name: "login" });
            }
          });
      } else {
        this.$router.push({ name: "login" });
      }
    },
    openReviewForm: function(booking) {
      if (localStorage.getItem("login_token") != null) {
        this.reviewForm.title = "";
        this.reviewForm.content = "";
        this.reviewForm.star = 0;
        this.reviewForm.booking = booking;
        this.reviewForm.can_comment = true;
        this.reviewForm.notification = true;
        this.reviewForm.dialog = true;
        this.$validator.reset();
      } else this.$router.push({ name: "login" });
    },
    sendReview: function() {
      this.$validator.validateAll("formReview").then(valid => {
        if (valid) {
          axios({
            method: "get",
            url: "http://localhost:8000/api/review/create",
            params: {
              title: this.reviewForm.title,
              content: this.reviewForm.content,
              star: this.reviewForm.star,
              hotel_id: this.reviewForm.booking.room.hotel.id,
              booking_id: this.reviewForm.booking.id,
              can_comment: this.reviewForm.can_comment,
              notification: this.reviewForm.notification
            },
            headers: {
              Authorization: "Bearer " + localStorage.getItem("login_token")
            }
          })
            .then(res => {
              if (res.data.status == true) {
                this.getInfo();
                this.$emit("loadLogin");
                this.reviewForm.dialog = false;
                this.$validator.reset();
              } else {
                this.$emit("loadSnackbar", "Thao tác lỗi, thử lại!");
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
        }
      });
    },
    checkPass: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/check-password",
        params: {
          id: this.user.id,
          password: this.form.pass
        }
      }).then(res => {
        if (res.data.mess) {
          this.passDialog.check = false;
          this.passDialog.main = true;
          this.value = false;
          this.form.newPass = this.form.rePass = "";
          this.$validator.reset();
          return;
        }
        this.$emit("loadSnackbar", "Sai mật khẩu!");
        return;
      });
    },
    submitPass: function() {
      this.$validator.validateAll("form2").then(valid => {
        if (valid) {
          axios({
            method: "get",
            url: "http://localhost:8000/api/update-password",
            params: {
              id: this.user.id,
              password: this.form.pass,
              newPassword: this.form.newPass
            }
          }).then(res => {
            console.log(res.data.status);
            if (res.data.status == false) {
              this.form.pass = this.form.rePass = "";
              return;
            }
            this.getInfo();
            this.$emit("loadLogin");
            this.$emit("loadSnackbar", "Đổi mật khẩu thành công!");
            this.passDialog.main = false;
            return;
          });
        }
      });
    },
    formatDate: function(date) {
      if (!date) return null;
      return this.$moment(date).format("DD-MM-YYYY");
    },
    bookingListPage: function(val) {
      if (val != 0) {
        this.$router.push({
          path: "booking-list",
          query: { code: "bookingcode" + val }
        });
      } else this.$router.push({ name: "booking-list" });
    },
    updateNotification: function(review_id) {
      var flag = true;
      var index = this.getIndex(review_id);
      switch (this.user.review[index].customer_review.status) {
        case 1:
          this.user.review[index].customer_review.status = 0;
          break;
        case 0:
          this.user.review[index].customer_review.status = 1;
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
            switch (this.user.review[index].customer_review.status) {
              case 1:
                this.user.review[index].customer_review.status = 0;
                break;
              case 0:
                this.user.review[index].customer_review.status = 1;
                break;
            }
          }
        });
    },
    updateReviewState: function(review_id) {
      var flag = true;
      var index = this.getIndex(review_id);
      switch (this.user.review[index].can_comment) {
        case 1:
          this.user.review[index].can_comment = 0;
          break;
        case 0:
          this.user.review[index].can_comment = 1;
          break;
      }
      axios({
        method: "put",
        url: "http://localhost:8000/api/review/" + review_id,
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
            switch (this.user.review[index].can_comment) {
              case 1:
                this.user.review[index].can_comment = 0;
                break;
              case 0:
                this.user.review[index].can_comment = 1;
                break;
            }
          }
        });
    }
  }
};
</script>
<style scoped>
.v-card--reveal,
.v-card--reveal1 {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: 0.7;
  position: absolute;
  width: 100%;
}
.v-card--reveal {
  padding-left: 30%;
  padding-top: 15%;
}
.v-card--reveal1 {
  padding-left: 30%;
  padding-top: 20%;
}

.pointer {
  cursor: pointer;
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

