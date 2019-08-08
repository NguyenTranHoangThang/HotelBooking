<template>
  <v-layout row wrap>
    <v-flex shrink md12>
      <v-layout class="top-search" row wrap justify-center align-center>
        <input class="search-input" style="width:250px;" type="text" v-model="placeVal" />
        <v-menu
          ref="checkIn"
          v-model="mn.menu2"
          :return-value.sync="checkInVal"
          :close-on-content-click="false"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          max-width="290px"
          min-width="290px"
          :nudge-top="-10"
        >
          <template v-slot:activator="{ on }">
            <input
              class="search-input"
              type="text"
              v-model="checkInFormattedVal"
              readonly
              v-on="on"
            />
          </template>
          <v-date-picker light no-title scrollable v-model="checkInVal" :min="now">
            <v-spacer></v-spacer>
            <v-btn flat v-on:click="mn.menu2 = false">Hủy</v-btn>
            <v-btn flat v-on:click="$refs.checkIn.save(checkInVal)">OK</v-btn>
          </v-date-picker>
        </v-menu>
        <v-menu
          ref="checkOut"
          v-model="mn.menu1"
          :return-value.sync="checkOutVal"
          :close-on-content-click="false"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          max-width="290px"
          min-width="290px"
          :nudge-top="-10"
        >
          <template v-slot:activator="{ on }">
            <input
              class="search-input"
              type="text"
              v-model="checkOutFormattedVal"
              readonly
              v-on="on"
            />
          </template>
          <v-date-picker light no-title scrollable v-model="checkOutVal" :min="checkInVal+1">
            <v-spacer></v-spacer>
            <v-btn flat @click="mn.menu1 = false">Hủy</v-btn>
            <v-btn flat @click="$refs.checkOut.save(checkOutVal)">OK</v-btn>
          </v-date-picker>
        </v-menu>
        <v-btn
          color="teal"
          dark
          icon
          depressed
          style="width:30px; height:30px;"
          v-on:click="getSearch"
        >
          <v-icon small>search</v-icon>
        </v-btn>
      </v-layout>
    </v-flex>
    <v-flex shrink md12>
      <v-layout row wrap class="pa-0 ma-0" v-if="flag.detail">
        <v-flex md12 v-if="!softDelete">
          <v-layout row wrap class="pa-0 ma-0">
            <v-flex shrink md3 class="detail-container">
              <span class="headline font-weight-black">{{data.name}}</span>
              <v-img :aspect-ratio="4/3" :src="'/images/hotel/'+data.image" class="my-2">
                <v-layout row wrap justify-center align-end fill-height v-if="data.stars_num>0">
                  <v-flex md10>
                    <v-chip color="white">
                      <v-rating
                        v-model="data.stars_num"
                        color="#fff200"
                        background-color="grey darken-1"
                        empty-icon="$vuetify.icons.ratingFull"
                        half-incrementss
                        readonly
                        small
                      ></v-rating>
                    </v-chip>
                  </v-flex>
                </v-layout>
              </v-img>
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Đánh giá:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span v-if="data.review_point>0">{{data.review_point}}/10</span>
                  <span v-else>Chưa có đánh giá.</span>
                </v-flex>
              </v-layout>
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Số điện thoại:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span>{{data.phone_number}}</span>
                </v-flex>
              </v-layout>
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Email:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span style="word-wrap: break-word;">{{data.email}}</span>
                </v-flex>
              </v-layout>
              
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Mã số thuế:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span>{{data.tax_code}}</span>
                </v-flex>
              </v-layout>
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Địa chỉ:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span style="word-wrap: break-word;">{{data.address}}</span>
                </v-flex>
              </v-layout>
              <div>
                <v-btn
                  round
                  v-if="data.followed == false"
                  depressed
                  dark
                  v-on:click="followHotel(data,1)"
                >theo dõi</v-btn>
                <v-btn round v-else depressed dark v-on:click="followHotel(data,0)">hủy theo dõi</v-btn>
              </div>
            </v-flex>
            <v-flex md9 class="detail-container">
              <v-tabs centered grow color="grey lighten-2" light class="ma-1">
                <v-tabs-slider color="black"></v-tabs-slider>
                <v-tab href="#tab-1">Danh sách phòng</v-tab>
                <v-tab href="#tab-2">Thông tin</v-tab>
                <v-tab href="#tab-3" @click="getHotelReviews">Đánh giá</v-tab>
                <v-tab href="#tab-4" @click="getHotelQuestion">Đặt câu hỏi</v-tab>
                <v-tab href="#tab-5" @click="getAddress(data.address)">Map</v-tab>
                <v-tab-item value="tab-1">
                  <v-card light flat tile v-if="loadingRoom == false && rooms.length !=0">
                    <v-layout
                      class="search-item"
                      row
                      wrap
                      v-for="(room,index) in rooms"
                      :key="index"
                    >
                      <v-flex xs3>
                        <v-layout row wrap class="pl-3">
                          <v-flex class="pa-1" md12>
                            <v-img :aspect-ratio="4/3" :src="'/images/room/'+room.image"></v-img>
                          </v-flex>
                          <v-flex class="pa-1" md6 v-for="(image,i) in room.images" :key="i">
                            <v-img
                              :aspect-ratio="1"
                              :src="'/images/room/'+image.image_link"
                              v-if="i<2"
                            >
                              <v-layout
                                fill-height
                                justify-center
                                align-center
                                v-if="room.images.length>3 && i==1"
                              >
                                <v-chip
                                  color="white"
                                  @click="openImagesDialog(1,room)"
                                  class="red--text"
                                >Thêm...</v-chip>
                              </v-layout>
                            </v-img>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                      <v-spacer></v-spacer>
                      <v-flex md6 style="border-right:1px solid #000;border-left:1px solid #000;">
                        <v-card-title>
                          <div>
                            <div class="headline">Phòng&nbsp;{{room.room_name}}</div>
                            <div>
                              Mức Giá:&nbsp;
                              <v-chip
                                color="indigo"
                                class="pa-2 white--text font-weight-bold headline"
                              >{{room.price.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}&nbsp;/đêm</v-chip>
                            </div>
                            <div>
                              Người Lớn:&nbsp;
                              <span
                                class="font-weight-bold"
                              >{{room.max_adult_amount}}</span>&nbsp;|&nbsp;Trẻ Em:&nbsp;
                              <span
                                class="font-weight-bold"
                              >{{room.max_child_amount}}&nbsp;(dưới&nbsp;{{data.child_age}}&nbsp;tuổi)</span>
                            </div>
                            <div>
                              Kiểu Phòng:&nbsp;
                              <span
                                class="font-weight-bold"
                              >{{room.room_mode.name}}</span>
                            </div>
                            <div>
                              Loại Phòng:&nbsp;
                              <span
                                class="font-weight-bold"
                              >{{room.room_type.name}}</span>
                            </div>
                            <div>
                              Kích Thước:&nbsp;
                              <span
                                class="font-weight-bold"
                              >{{room.room_size}}&nbsp;m²</span>
                            </div>
                            <div v-show="room.amount>0">
                              Số Lượng:&nbsp;
                              <input
                                class="amount-room-input"
                                type="number"
                                v-model="room.bookingAmount"
                                readonly
                              />
                              <v-btn
                                small
                                class="amount-btn"
                                depressed
                                fab
                                v-on:click="room.bookingAmount++;if(room.bookingAmount>room.amount){room.bookingAmount = room.amount}"
                              >
                                <i class="fa-lg fas fa-plus"></i>
                              </v-btn>
                              <v-btn
                                small
                                class="amount-btn"
                                depressed
                                fab
                                v-on:click="room.bookingAmount--;if(room.bookingAmount<=0){room.bookingAmount = 1}"
                              >
                                <i class="fa-lg fas fa-minus"></i>
                              </v-btn>
                            </div>
                          </div>
                          <v-card-text>
                            <v-layout row wrap class="pa-0 ma-0 caption">
                              <v-flex md6 class="service-item">
                                <div>
                                  <span>Dịch Vụ</span>
                                </div>
                                <v-divider></v-divider>
                                <div v-for="(service,i) in room.service" :key="i">
                                  <i :class="'fas fa-'+service.icon"></i>&nbsp;
                                  <span>{{service.name}}</span>
                                </div>
                              </v-flex>
                              <v-flex md6 class="feature-item">
                                <div>
                                  <span>Nội Thất</span>
                                </div>
                                <v-divider></v-divider>
                                <div v-for="(feature,i) in room.feature" :key="i">
                                  <span>{{feature.name}}</span>
                                </div>
                              </v-flex>
                            </v-layout>
                          </v-card-text>
                        </v-card-title>
                      </v-flex>
                      <v-flex md2 class="text-md-center pt-5 pl-1">
                        <v-btn
                          round
                          :disabled="room.amount <= 0"
                          depressed
                          large
                          color="#EE5A24"
                          @click.stop="openDialog(room)"
                          class="white--text font-weight-bold"
                        >Chọn</v-btn>
                        <div
                          class="font-weight-bold red--text body-2"
                        >Còn&nbsp;{{room.amount}}&nbsp;phòng</div>
                      </v-flex>
                      <!-- <v-flex md12>
                        <v-card dark>
                          <v-card-text>ABC</v-card-text>
                        </v-card>
                      </v-flex>-->
                    </v-layout>
                    <infinite-loading @distance="1" @infinite="loadRoom">
                      <span class="caption font-italic" slot="no-more">Bạn đã đến cuối trang...</span>
                    </infinite-loading>
                  </v-card>
                  <v-card light flat tile width="100%" v-else-if="loadingRoom == true">
                    <v-layout row wrap class="pa-0 ma-0" justify-center align-start>
                      <v-flex md3 class="pa-2 ma-2">
                        <v-img :aspect-ratio="1" src="/img/booking/load.gif" style="opacity:0.9">
                          <v-layout fill-height align-center justify-center>
                            <span class="pa-5 caption black--text font-weight-bold">đang tải...</span>
                          </v-layout>
                        </v-img>
                      </v-flex>
                    </v-layout>
                  </v-card>
                  <v-card v-else light flat tile>
                    <v-card-title caption>
                      <span>Không có dữ liệu phòng</span>
                    </v-card-title>
                  </v-card>
                </v-tab-item>
                <v-tab-item value="tab-2">
                  <v-card light flat tile>
                    <v-layout row wrap class="hotel-tab">
                      <v-flex md12>
                        <v-card-title>
                          <v-layout row wrap class="pa-0 ma-2" align-center>
                            <v-flex md12>
                              <div class="pl-4">
                                <span class="font-weight-bold font-italic display-1">
                                  {{data.name}}&nbsp;
                                  <v-tooltip right>
                                    <template v-slot:activator="{ on }">
                                      <i
                                        class="blue--text fas fa-check-circle"
                                        v-on="on"
                                        v-show="data.verified!=0"
                                      ></i>
                                    </template>
                                    <span>đã xác thực</span>
                                  </v-tooltip>
                                </span>
                              </div>
                            </v-flex>
                            <v-flex md12>
                              <v-divider></v-divider>
                            </v-flex>
                            <v-flex md12>
                              <v-layout row wrap class="pa-0 ma-0" v-if="data.images.length !=0">
                                <v-flex
                                  md3
                                  class="pa-1"
                                  v-for="(item,i) in data.images"
                                  :key="i"
                                  v-show="i<=3 && i<data.images.length"
                                >
                                  <v-img
                                    :aspect-ratio="1/1"
                                    :src="'/images/hotel/'+item.image_link"
                                  >
                                    <v-layout
                                      fill-height
                                      justify-center
                                      align-center
                                      v-show="i===3"
                                    >
                                      <v-chip
                                        color="white"
                                        @click="openImagesDialog(0,null)"
                                        class="red--text"
                                      >Thêm...</v-chip>
                                    </v-layout>
                                  </v-img>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                            <v-flex md12>
                              <v-divider></v-divider>
                            </v-flex>
                            <v-flex md12>
                              <div>
                                <span class="font-weight-bold subheading">Mô Tả:</span>
                              </div>
                              <div class="pa-3 my-1 grey lighten-2">
                                <span style="word-wrap: break-word">{{data.description}}</span>
                              </div>
                            </v-flex>
                            <v-flex md12>
                              <v-divider></v-divider>
                            </v-flex>
                            <v-flex md12>
                              <div>
                                <span class="font-weight-bold subheading">
                                  <v-icon color="pink" medium>room</v-icon>
                                  &nbsp;{{data.address}}
                                </span>
                              </div>
                            </v-flex>
                            <v-flex md12>
                              <v-divider></v-divider>
                            </v-flex>
                            <v-flex md12>
                              <div>
                                <span class="font-weight-bold subheading">Dịch Vụ:</span>
                              </div>
                              <v-layout row wrap class="pa-0 ma-0 my-1">
                                <v-flex
                                  md2
                                  v-for="(ser,i) in data.service"
                                  :key="i"
                                  class="pa-2 border"
                                >
                                  <div class="text-md-center">
                                    <div>
                                      <i :class="'fa-2x fas fa-'+ser.icon"></i>
                                    </div>
                                    <div>
                                      <span class="font-italic">{{ser.name}}</span>
                                    </div>
                                  </div>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                            <v-flex md12>
                              <v-divider></v-divider>
                            </v-flex>
                            <v-flex md12>
                              <div>
                                <div>
                                  <span class="font-weight-bold subheading">Chính Sách:</span>
                                  <span
                                    class="ml-2 font-weight-bold font-italic"
                                  >{{data.policy.content}}</span>
                                </div>
                                <div class="ml-2 pl-2 border-left">
                                  <div v-if="data.policy.check_in !=null">
                                    <span
                                      class="font-weight-bold subheading"
                                    >Thời Gian Check-in:&nbsp;{{data.policy.check_in}}</span>
                                  </div>
                                  <div v-if="data.policy.check_out !=null">
                                    <span
                                      class="font-weight-bold subheading"
                                    >Thời Gian Check-out:&nbsp;{{data.policy.check_out}}</span>
                                  </div>
                                  <div v-for="(p,i) in paymentMethods" :key="i">
                                    <span class="font-weight-bold subheading">{{p.method.name}}:</span>
                                    <span class="font-weight-bold font-italic">{{p.content}}</span>
                                  </div>
                                </div>
                              </div>
                            </v-flex>
                          </v-layout>
                        </v-card-title>
                      </v-flex>
                    </v-layout>
                  </v-card>
                </v-tab-item>
                <v-tab-item value="tab-3">
                  <v-card light flat tile>
                    <v-layout row wrap class="hotel-tab">
                      <v-flex md12>
                        <v-card-title>
                          <v-layout row wrap class="pa-3 ma-0">
                            <v-flex md12>
                              <span class="font-weight-bold subheading">Đánh Giá Của Khách Hàng</span>
                            </v-flex>
                            <v-flex md12 class="pl-2 ma-1" v-if="flag.review === true">
                              <v-layout row wrap class="pa-0 ma-0" v-if="reviewList.length !=0">
                                <v-flex
                                  md12
                                  class="pa-2 ma-2 grey lighten-2 caption"
                                  v-for="(r,i) in reviewList"
                                  :key="i"
                                >
                                  <v-card light flat tile class="pa-4">
                                    <v-card-title class="pa-0 ma-0">
                                      <v-spacer></v-spacer>
                                      <i
                                        v-show="r.likes >=10"
                                        class="green--text fa-lg fas fa-cannabis pl-3 pr-1"
                                      ></i>
                                      <i
                                        v-show="r.likes >=100"
                                        class="grey--text fa-lg fas fa-bowling-ball pl-3 pr-1"
                                      ></i>
                                      <i
                                        v-show="r.likes >=500"
                                        class="orange--text fa-lg fas fa-award pl-3 pr-1"
                                      ></i>
                                    </v-card-title>
                                    <v-card-text class="pa-0 ma-0">
                                      <v-layout row wrap align-center class="pa-0 ma-0">
                                        <v-flex md2 class="border-right pa-1 mr-4">
                                          <v-avatar size="110px" color="black">
                                            <v-avatar size="100px" color="blue">
                                              <div
                                                class="white--text text-md-center title"
                                              >{{r.point}}/10</div>
                                            </v-avatar>
                                          </v-avatar>
                                        </v-flex>
                                        <v-flex md8>
                                          <router-link
                                            class="pointer"
                                            :to="{name:'review',query:{id:r.id}}"
                                            target="_blank"
                                          >
                                            <div>
                                              <div>
                                                <v-avatar size="42px" color="black">
                                                  <router-link
                                                    :to="{name:'user',params:{id:r.customer.id}}"
                                                    target="_blank"
                                                  >
                                                    <v-avatar size="40px" color="white">
                                                      <img
                                                        :src="'/img/user/'+r.customer.avatar.image_link"
                                                        alt
                                                      />
                                                    </v-avatar>
                                                  </router-link>
                                                </v-avatar>
                                                <span class="subheading">
                                                  <router-link
                                                    :to="{name:'user',params:{id:r.customer.id}}"
                                                    target="_blank"
                                                  >{{r.customer.name}}</router-link>
                                                </span>
                                                <span
                                                  class="grey--text"
                                                >&nbsp;-{{formatDate(r.created_at)}}</span>
                                              </div>
                                              <div>
                                                <div>
                                                  <span class="pl-2">{{r.title}}</span>
                                                </div>
                                                <div class="ml-3 pl-2 border-left">
                                                  <span class="font-italic">"{{r.content}}"</span>
                                                </div>
                                              </div>
                                            </div>
                                          </router-link>
                                        </v-flex>
                                        <v-flex md12 class="py-3 pl-5">
                                          <div>Đã ở&nbsp;{{r.booking.days}}&nbsp;ngày.</div>
                                        </v-flex>
                                      </v-layout>
                                    </v-card-text>
                                    <v-divider></v-divider>
                                    <v-card-actions>
                                      <i
                                        @click="takeUseful(r.id,i)"
                                        class="purple--text fa-3x far fa-thumbs-up pl-3 pr-1"
                                        v-if="r.useful == true"
                                      ></i>
                                      <i
                                        @click="takeUseful(r.id,i)"
                                        class="blue--text fa-lg far fa-thumbs-up pl-3 pr-1"
                                        v-else
                                      ></i>
                                      <span
                                        class="grey--text font-weight-bold"
                                      >Bài đăng này hữu ích đối với bạn?</span>
                                    </v-card-actions>
                                  </v-card>
                                </v-flex>
                              </v-layout>
                              <v-layout row wrap class="pa-0 ma-0" v-else>
                                <v-flex md12 class="pa-2 ma-2 border">
                                  <div class="text-md-center">
                                    <span class="title text-uppercase">chưa có đánh giá nào...</span>
                                  </div>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                            <v-flex md12 class="pl-2 ma-1" v-else>
                              <v-layout row wrap class="pa-0 ma-0" justify-center align-start>
                                <v-flex md3 class="pa-2 ma-2">
                                  <v-img
                                    :aspect-ratio="1"
                                    src="/img/booking/load.gif"
                                    style="opacity:0.9"
                                  >
                                    <v-layout fill-height align-center justify-center>
                                      <span
                                        class="pa-5 caption black--text font-weight-bold"
                                      >đang tải...</span>
                                    </v-layout>
                                  </v-img>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                          </v-layout>
                        </v-card-title>
                      </v-flex>
                    </v-layout>
                  </v-card>
                </v-tab-item>
                <v-tab-item value="tab-4">
                  <v-card light flat tile>
                    <v-layout row wrap class="hotel-tab">
                      <v-flex md12>
                        <v-card-title>
                          <v-layout row wrap class="pa-3 ma-0">
                            <v-flex md12>
                              <v-layout row wrap class="pa-0 ma-0" justify-center align-center>
                                <v-flex md6 class="font-weight-bold subheading text-md-left">
                                  <span>Câu Hỏi Của Khách Hàng</span>
                                </v-flex>
                                <v-flex md6 class="text-md-right pr-3">
                                  <v-btn
                                    round
                                    color="#B53471"
                                    class="white--text"
                                    depressed
                                    @click="openQuestionDialog()"
                                  >hỏi&nbsp;{{data.hotel_type.name}}</v-btn>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                            <v-flex md12 class="pl-2 ma-1" v-if="flag.question === true">
                              <v-layout row wrap class="pa-0 ma-0" v-if="questionList.length !=0">
                                <v-flex
                                  md12
                                  class="pa-2 ma-2 border"
                                  v-for="(q,i) in questionList"
                                  :key="i"
                                >
                                  <v-card light flat tile class="pl-4">
                                    <v-card-title class="pa-0 ma-0">
                                      <div>
                                        <div>
                                          <div>
                                            <v-avatar size="42px" color="black">
                                              <router-link
                                                :to="{name:'user',params:{id:q.customer.id}}"
                                                target="_blank"
                                              >
                                                <v-avatar size="40px" color="white">
                                                  <img
                                                    :src="'/img/user/'+q.customer.avatar.image_link"
                                                    alt
                                                  />
                                                </v-avatar>
                                              </router-link>
                                            </v-avatar>
                                            <span class="subheading">
                                              <router-link
                                                :to="{name:'user',params:{id:q.customer.id}}"
                                                target="_blank"
                                              >{{q.customer.name}}</router-link>
                                            </span>
                                            <span
                                              class="grey--text"
                                            >&nbsp;-&nbsp;{{formatDate(q.created_at)}}</span>
                                          </div>
                                          <div>
                                            <div>
                                              <span class="font-weight-black">{{q.title}}</span>
                                            </div>
                                            <div>
                                              <span class="pl-2 font-italic">"{{q.content}}"</span>
                                            </div>
                                          </div>
                                        </div>
                                        <div
                                          class="pl-3 border-left border-warning"
                                          v-if="q.reply !=null"
                                        >
                                          <div>
                                            <span class="font-weight-bold">{{data.name}}</span>
                                            <span
                                              class="grey--text"
                                            >&nbsp;-&nbsp;{{formatDate(q.reply.created_at)}}</span>
                                          </div>
                                          <div class="pl-3">"{{q.reply.content}}"</div>
                                        </div>
                                      </div>
                                    </v-card-title>
                                  </v-card>
                                </v-flex>
                              </v-layout>
                              <v-layout row wrap class="pa-0 ma-0" v-else>
                                <v-flex md12 class="pa-2 ma-2 border">
                                  <div class="text-md-center">
                                    <span class="title text-uppercase">chưa có câu hỏi nào...</span>
                                  </div>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                            <v-flex md12 class="pl-2 ma-1" v-else>
                              <v-layout row wrap class="pa-0 ma-0" justify-center align-start>
                                <v-flex md3 class="pa-2 ma-2">
                                  <v-img
                                    :aspect-ratio="1"
                                    src="/img/booking/load.gif"
                                    style="opacity:0.9"
                                  >
                                    <v-layout fill-height align-center justify-center>
                                      <span
                                        class="pa-5 caption black--text font-weight-bold"
                                      >đang tải...</span>
                                    </v-layout>
                                  </v-img>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                          </v-layout>
                        </v-card-title>
                      </v-flex>
                    </v-layout>
                  </v-card>
                </v-tab-item>
                <v-tab-item value="tab-5">
                  <v-card light flat tile>
                    <v-layout justify-center align-center row wrap class="hotel-tab">
                      <v-flex md12 class="mb-3">
                        <div id="map"></div>
                      </v-flex>
                      <v-flex md3>
                        <h3 class="blue--text">
                          <i class="far fa-credit-card"></i> ATM
                        </h3>
                        <ul>
                          <li v-for="(item,i) in atm" :key="i">{{item.name}}</li>
                        </ul>
                      </v-flex>
                      <v-flex md3>
                        <h3 class="orange--text">
                          <i class="fas fa-utensils"></i> Nhà hàng
                        </h3>
                        <ul>
                          <li v-for="(item,i) in restaurant" :key="i">{{item.name}}</li>
                        </ul>
                      </v-flex>
                      <v-flex md3>
                        <h3 class="pink--text">
                          <i class="fas fa-cocktail"></i> Pub
                        </h3>
                        <ul>
                          <li v-for="(item,i) in pub" :key="i">{{item.name}}</li>
                        </ul>
                      </v-flex>
                      <v-flex md3>
                        <h3 class="green--text">
                          <i class="fas fa-shopping-basket"></i> Siêu thị
                        </h3>
                        <ul>
                          <li v-for="(item,i) in supermarket" :key="i">{{item.name}}</li>
                        </ul>
                      </v-flex>
                    </v-layout>
                  </v-card>
                </v-tab-item>
              </v-tabs>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex md12 class="detail-container" v-else>
          <v-layout row wrap justify-center align-center class="pa-3 ma-4 border">
            <v-flex md3>
              <span class="headline font-weight-black">{{data.name}}</span>
              <v-img :aspect-ratio="4/3" :src="'/images/hotel/'+data.image" class="my-2">
                <v-layout row wrap justify-center align-end fill-height v-if="data.stars_num>0">
                  <v-flex md10>
                    <v-chip color="white">
                      <v-rating
                        v-model="data.stars_num"
                        color="#fff200"
                        background-color="grey darken-1"
                        empty-icon="$vuetify.icons.ratingFull"
                        half-incrementss
                        readonly
                        small
                      ></v-rating>
                    </v-chip>
                  </v-flex>
                </v-layout>
              </v-img>
            </v-flex>
            <v-flex md4 class="ml-4 pa-3 grey lighten-2">
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Đánh giá:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span v-if="data.review_point>0">{{data.review_point}}/10</span>
                  <span v-else>Chưa có đánh giá.</span>
                </v-flex>
              </v-layout>
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Số điện thoại:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span>{{data.phone_number}}</span>
                </v-flex>
              </v-layout>
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Email:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span style="word-wrap: break-word;">{{data.email}}</span>
                </v-flex>
              </v-layout>
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Mã số thuế:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span>{{data.tax_code}}</span>
                </v-flex>
              </v-layout>
              <v-layout row wrap class="hotel-info-item" align-center>
                <v-flex md3>
                  <span>Địa chỉ:</span>
                </v-flex>
                <v-flex md9 class="pl-2">
                  <span style="word-wrap: break-word;">{{data.address}}</span>
                </v-flex>
              </v-layout>
              <div>
                <v-chip color="red" class="white--text font-weight-bold">Ngưng hoạt động.</v-chip>
              </div>
            </v-flex>
          </v-layout>
        </v-flex>
      </v-layout>
      <v-layout row wrap class="pa-0 ma-0" justify-center align-center v-else>
        <v-flex md4 class="pa-2 ma-2">
          <v-img :aspect-ratio="1" src="/img/booking/load.gif" style="opacity:0.9">
            <v-layout fill-height align-center justify-center>
              <span class="pa-5 caption black--text font-weight-bold">đang tải...</span>
            </v-layout>
          </v-img>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-dialog
      v-model="bookingDialog.state"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
      scrollable
      persistent
    >
      <v-card tile light>
        <v-toolbar card flat dark>
          <v-btn icon dark v-on:click="bookingDialog.state =false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title class="text-uppercase">
            <span class="orange--text">h</span>otel booking
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-stepper v-model="bookingDialog.step" class="pa-2">
            <v-stepper-header>
              <v-stepper-step color="#EE5A24" :complete="bookingDialog.step > 1" step="1">Bước 1</v-stepper-step>
              <v-stepper-step color="#EE5A24" :complete="bookingDialog.step > 2" step="2">Bước 2</v-stepper-step>
              <v-stepper-step color="#EE5A24" step="3">Bước 3</v-stepper-step>
            </v-stepper-header>
          </v-stepper>
        </v-toolbar>
        <v-card-text>
          <v-layout row wrap justify-center class="pa-0 ma-0">
            <v-flex md3>
              <v-layout row wrap class="ml-1 pa-2 orange lighten-3">
                <v-flex md12>
                  <v-layout row wrap class="pa-0 ma-0">
                    <v-flex md12>
                      <div class="font-italic text-md-center">Thông Tin Phòng</div>
                      <v-divider></v-divider>
                    </v-flex>
                    <v-flex md2>
                      <v-img :aspect-ratio="1" :src="'/images/room/'+bookingDialog.room.image"></v-img>
                    </v-flex>
                    <v-flex md10 class="pa-3">
                      <div>{{data.name}} - {{data.hotel_type.name}}</div>
                    </v-flex>
                  </v-layout>
                  <v-divider></v-divider>
                </v-flex>
                <v-flex md6 class="service-item">
                  <div>
                    <span>Dịch Vụ</span>
                  </div>
                  <v-divider></v-divider>
                  <div v-for="(service,i) in bookingDialog.room.service" :key="i">
                    <i :class="'fas fa-'+service.icon"></i>&nbsp;
                    <span>{{service.name}}</span>
                  </div>
                </v-flex>
                <v-flex md6 class="feature-item">
                  <div>
                    <span>Nội Thất</span>
                  </div>
                  <v-divider></v-divider>
                  <div v-for="(feature,i) in bookingDialog.room.feature" :key="i">
                    <span>{{feature.name}}</span>
                  </div>
                </v-flex>
                <v-flex md12>
                  <v-divider></v-divider>
                </v-flex>
                <v-flex md4>
                  <span>Check-in:</span>
                </v-flex>
                <v-flex md8>
                  <span>{{checkInFormattedVal}}</span>
                </v-flex>
                <v-flex md4>
                  <span>Check-out:</span>
                </v-flex>
                <v-flex md8>
                  <span>{{checkOutFormattedVal}}</span>
                </v-flex>
                <v-flex md12>
                  <v-divider></v-divider>
                </v-flex>
                <v-flex md4>
                  <span>Loại Phòng:</span>
                </v-flex>
                <v-flex md8>
                  <span>{{bookingDialog.room.room_type.name}}</span>
                </v-flex>
                <v-flex md4>
                  <span>Giường:</span>
                </v-flex>
                <v-flex md8>
                  <div
                    v-for="(bed,i) in bookingDialog.room.room_bed_type"
                    :key="i"
                  >{{bed.amount}}x{{bed.bed_type.name}}</div>
                </v-flex>
                <v-flex md12>
                  <v-divider></v-divider>
                </v-flex>
                <v-flex md4>
                  <span>Số Lượng Đặt:</span>
                </v-flex>
                <v-flex md8>
                  <span>{{bookingDialog.room.bookingAmount}}</span>
                </v-flex>
              </v-layout>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex md9>
              <v-layout row wrap class="py-0 my-0 px-5 mx-5">
                <v-flex md12 v-show="!login.check">
                  <div>
                    <h2 class="headline">
                      <span class="orange--text">Đ</span>ăng Nhập
                    </h2>
                  </div>
                  <v-card flat tile dark color="#B53471">
                    <v-layout row wrap class="pa-0 ma-0">
                      <v-flex md2>
                        <v-img :aspect-ratio="1" src="/blog/img/slider/default.png"></v-img>
                      </v-flex>
                      <v-flex md10>
                        <v-card-title>
                          <div>
                            <div>Đăng nhập để nhận nhiều lợi ích khi là thành viên của chúng tôi!</div>
                            <div>
                              <span>
                                <a href="#" class="orange--text" @click="getLogin(1)">Đăng Nhập</a>
                              </span>&nbsp;||&nbsp;
                              <span>
                                <a href="#" class="orange--text" @click="getLogin(0)">Đăng Ký</a>
                              </span>
                            </div>
                          </div>
                        </v-card-title>
                      </v-flex>
                    </v-layout>
                  </v-card>
                </v-flex>
                <v-window v-model="bookingDialog.step">
                  <v-window-item :value="1">
                    <v-flex md12>
                      <div>
                        <h2 class="headline">
                          <span class="orange--text font-weight-black">T</span>hông Tin Của Bạn
                        </h2>
                      </div>
                      <v-form ref="form" data-vv-scope="form1">
                        <v-layout row wrap class="pa-0 pl-3 ma-0 booking-content-info-item">
                          <v-flex md12>
                            <div>
                              Tên liên hệ:
                              <span class="orange--text">*</span>
                            </div>
                            <v-text-field
                              :error-messages="errors.collect('form1.name')"
                              v-validate="'required'"
                              data-vv-name="name"
                              v-model="bookingDialog.booking.name"
                              class="ma-2"
                              color="teal"
                              outline
                              hint="*nhập tên theo chứng minh thư"
                              persistent-hint
                            ></v-text-field>
                          </v-flex>
                          <v-flex md6>
                            <div>
                              Số điện thoại liên hệ:
                              <span class="orange--text">*</span>
                            </div>
                            <v-text-field
                              :error-messages="errors.collect('form1.phone')"
                              v-validate="'required'"
                              data-vv-name="phone"
                              v-model="bookingDialog.booking.phone"
                              class="ma-2"
                              color="teal"
                              outline
                            ></v-text-field>
                          </v-flex>
                          <v-flex md6>
                            <div>
                              Địa chỉ email:
                              <span class="orange--text">*</span>
                            </div>
                            <v-text-field
                              :error-messages="errors.collect('form1.email')"
                              v-validate="'required|email'"
                              data-vv-name="email"
                              v-model="bookingDialog.booking.email"
                              class="ma-2"
                              color="teal"
                              outline
                              hint="*Ex: email@example.com"
                              persistent-hint
                            ></v-text-field>
                          </v-flex>
                          <v-flex md6>
                            <div>
                              Nơi cư trú:
                              <span class="orange--text">*</span>
                            </div>
                            <v-textarea
                              :error-messages="errors.collect('form1.address')"
                              v-validate="'required'"
                              data-vv-name="address"
                              v-model="bookingDialog.booking.address"
                              class="ma-2"
                              color="teal"
                              outline
                              auto-grow
                              rows="2"
                              hint="*Ex: phường Đa Kao, quận 1, thành phố Hồ Chí Minh"
                              persistent-hint
                            ></v-textarea>
                          </v-flex>
                          <v-flex md6>
                            <div>Yêu cầu đặc biệt:</div>
                            <v-textarea
                              v-model="bookingDialog.booking.request"
                              class="ma-2"
                              color="teal"
                              outline
                              auto-grow
                              rows="3"
                            ></v-textarea>
                          </v-flex>
                        </v-layout>
                      </v-form>
                      <div>
                        <v-divider dark></v-divider>
                        <h2 class="headline">
                          <span class="orange--text font-weight-black">C</span>hi Tiết Giá
                        </h2>
                      </div>
                      <v-layout row wrap class="grey lighten-2 pa-3 pl-5 ma-0" align-center>
                        <v-flex md12>
                          <h2 class="font-weight-bold">Phòng {{bookingDialog.room.room_name}}</h2>
                        </v-flex>
                        <v-flex md8>
                          <span>{{bookingDialog.room.room_type.name}}</span>
                        </v-flex>
                        <v-flex
                          md4
                        >{{bookingDialog.room.price.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</v-flex>
                        <v-flex md8>
                          <span>Số Lượng</span>
                        </v-flex>
                        <v-flex md4>{{bookingDialog.room.bookingAmount}}</v-flex>
                        <v-flex md8>
                          <span>Số Đêm Ở</span>
                        </v-flex>
                        <v-flex md4>{{bookingDialog.room.days}}</v-flex>
                        <v-flex md12>
                          <v-divider></v-divider>
                        </v-flex>
                        <v-flex md12>
                          <v-layout row wrap class="pa-0 ma-0" align-center>
                            <v-flex md8>
                              <v-text-field
                                label="nhập mã giảm giá tại đây..."
                                v-model="text"
                                class="mt-2 ma-0"
                                color="teal"
                                outline
                                clearable
                                @click:clear="text=''"
                              ></v-text-field>
                            </v-flex>
                            <v-flex md4>
                              <v-btn
                                round
                                depressed
                                :disabled="!bookingDialog.couponCode.check"
                                @click="applyCouponCode(bookingDialog.room.id,bookingDialog.room.couponCode)"
                              >Áp dụng</v-btn>
                            </v-flex>
                            <v-flex md12 v-if="bookingDialog.couponCode.mess.length >0">
                              <span class="red--text font-italic">{{bookingDialog.couponCode.mess}}</span>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                        <v-flex md12>
                          <v-divider></v-divider>
                        </v-flex>
                        <v-flex md8>
                          <span>Tổng Giá</span>
                        </v-flex>
                        <v-flex
                          md4
                          class="title font-weight-bold"
                        >{{bookingDialog.totalPrice.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</v-flex>
                      </v-layout>
                    </v-flex>
                  </v-window-item>
                  <v-window-item :value="2">
                    <v-flex md12>
                      <div>
                        <h2 class="headline">
                          <span class="orange--text font-weight-black">T</span>hanh Toán!
                        </h2>
                      </div>
                      <div>
                        <v-radio-group v-model="bookingDialog.payment" :mandatory="false">
                          <div v-for="(p,i) in paymentMethods" :key="i">
                            <v-radio :label="p.method.name" color="orange" :value="p.method.id"></v-radio>
                            <v-card
                              light
                              flat
                              tile
                              color="grey lighten-2"
                              v-show="bookingDialog.payment == p.method.id"
                            >
                              <v-card-title>
                                <div>{{p.method.name}}</div>
                                <div>{{p.content}}</div>
                              </v-card-title>
                            </v-card>
                          </div>
                        </v-radio-group>
                      </div>
                    </v-flex>
                  </v-window-item>
                  <v-window-item :value="3">
                    <v-flex md12>
                      <div>
                        <h2 class="headline">
                          <span class="orange--text font-weight-black">X</span>em Lại Thông Tin Đặt Phòng
                        </h2>
                      </div>
                      <div>
                        <v-divider dark></v-divider>
                        <span class="font-weight-black title">Thông Tin Của Bạn</span>
                      </div>
                      <v-layout row wrap class="pa-0 pl-3 ma-0 booking-content-info-item">
                        <v-flex md4>
                          <div class="mt-3">
                            <v-img
                              :aspect-ratio="1"
                              :src="'/images/room/'+bookingDialog.room.image"
                            ></v-img>
                          </div>
                        </v-flex>
                        <v-flex md8>
                          <v-layout row wrap class="pa-0 pl-4 ma-0">
                            <v-flex md12>
                              <div>
                                <h2>{{data.name}}</h2>
                              </div>
                              <div>{{data.hotel_type.name}}</div>
                            </v-flex>
                            <v-flex md12>
                              <v-divider class="pa-0 ma-0"></v-divider>
                            </v-flex>
                            <v-flex md12>
                              <v-layout row wrap class="pa-0 ma-0">
                                <v-flex md6 class="pa-2">
                                  <div>
                                    <div>
                                      <span class="font-weight-bold">Check-In:</span>
                                    </div>
                                    <div>
                                      <span>{{checkInFormattedVal}}</span>
                                    </div>
                                  </div>
                                </v-flex>
                                <v-flex md6 class="pa-2">
                                  <div>
                                    <div>
                                      <span class="font-weight-bold">Check-Out:</span>
                                    </div>
                                    <div>
                                      <span>{{checkOutFormattedVal}}</span>
                                    </div>
                                  </div>
                                </v-flex>
                                <v-flex md12 class="pl-2">
                                  <span class="font-weight-bold">Thông Tin Liên Hệ</span>
                                </v-flex>
                                <v-flex md12 class="pl-2">
                                  <v-layout row wrap class="pa-0 ma-0" align-center>
                                    <v-flex md5>Tên</v-flex>
                                    <v-flex md7>{{bookingDialog.booking.name}}</v-flex>
                                    <v-flex md5>Email</v-flex>
                                    <v-flex md7>{{bookingDialog.booking.email}}</v-flex>
                                    <v-flex md5>Số Điện Thoại</v-flex>
                                    <v-flex md7>{{bookingDialog.booking.phone}}</v-flex>
                                    <v-flex md5>Địa Chỉ</v-flex>
                                    <v-flex md7>{{bookingDialog.booking.address}}</v-flex>
                                  </v-layout>
                                </v-flex>
                                <v-spacer></v-spacer>
                              </v-layout>
                            </v-flex>
                            <v-flex md12>
                              <v-divider></v-divider>
                            </v-flex>
                            <v-flex md12>
                              <span class="font-weight-bold">Chi Tiết Phòng:</span>
                            </v-flex>
                            <v-flex md12>
                              <v-layout row wrap class="pa-0 ma-0" align-center>
                                <v-flex md5>Loại Phòng:</v-flex>
                                <v-flex md7>{{bookingDialog.room.room_type.name}}</v-flex>
                                <v-flex md5>Số Lượng Đặt:</v-flex>
                                <v-flex md7>{{bookingDialog.room.bookingAmount}}</v-flex>
                                <v-flex md5>Yêu Cầu Đặt Biệt:</v-flex>
                                <v-flex md7>{{bookingDialog.booking.request}}</v-flex>
                              </v-layout>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                      <div>
                        <v-divider dark></v-divider>
                        <span class="font-weight-black title">Chính Sách Hủy</span>
                      </div>
                      <v-layout row wrap class="pa-0 pl-3 ma-0 booking-content-info-item">
                        <span>{{bookingDialog.policy}}</span>
                      </v-layout>
                      <div>
                        <v-divider dark></v-divider>
                        <span class="font-weight-black title">Chi Tiết Giá</span>
                      </div>
                      <v-layout row wrap class="pa-0 pl-3 ma-0 booking-content-info-item">
                        <v-flex md12>
                          <v-divider></v-divider>
                        </v-flex>
                        <v-flex md8>
                          <span>{{bookingDialog.room.room_type.name}}</span>
                        </v-flex>
                        <v-flex
                          md4
                        >{{bookingDialog.room.price.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</v-flex>
                        <v-flex md8>
                          <span>Số Lượng Đặt</span>
                        </v-flex>
                        <v-flex md4>{{bookingDialog.room.bookingAmount}}</v-flex>
                        <v-flex md8>
                          <span>Số Đêm Ở</span>
                        </v-flex>
                        <v-flex md4>{{bookingDialog.room.days}}</v-flex>

                        <v-flex md12>
                          <div
                            class="font-italic font-weight-black"
                          >{{bookingDialog.couponCode.mess}}</div>
                        </v-flex>

                        <v-flex md12>
                          <v-divider></v-divider>
                        </v-flex>
                        <v-flex md8>
                          <span>Tổng Giá</span>
                        </v-flex>
                        <v-flex
                          md4
                          class="title font-weight-bold"
                        >{{bookingDialog.totalPrice.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</v-flex>
                      </v-layout>
                    </v-flex>
                  </v-window-item>
                </v-window>
                <v-flex md12>
                  <v-layout row wrap class="pa-5 ma-0" align-center>
                    <v-flex md12 class="ma-0 pa-0">
                      <span class="red--text">
                        Nhấn chọn thực hiện hành động sau, bạn đã trực tiếp đồng ý các điều khoản và chích sách liên quan.
                        <v-checkbox
                          v-show="bookingDialog.step === 3"
                          v-model="bookingDialog.accept"
                          label="Tôi đồng ý."
                          color="teal"
                        ></v-checkbox>
                      </span>
                    </v-flex>
                    <v-flex md6 class="text-md-left">
                      <div>
                        <v-btn
                          round
                          class="white--text"
                          color="#EE5A24"
                          large
                          depressed
                          :disabled="bookingDialog.step === 1"
                          @click="backBookingStep(bookingDialog.step)"
                        >
                          <i class="fa-lg fas fa-long-arrow-alt-left"></i>&nbsp;quay lại
                        </v-btn>
                      </div>
                    </v-flex>
                    <v-flex md6 class="text-md-right">
                      <div>
                        <v-btn
                          round
                          class="white--text"
                          color="#EE5A24"
                          large
                          depressed
                          v-show="bookingDialog.step !== 3"
                          @click="nextBookingStep(bookingDialog.step)"
                        >
                          tiếp theo&nbsp;
                          <i class="fa-lg fas fa-long-arrow-alt-right"></i>
                        </v-btn>
                        <v-btn
                          round
                          @click="getBooking"
                          :disabled="bookingDialog.accept === false"
                          class="booking-btn"
                          large
                          depressed
                          v-show="bookingDialog.step === 3"
                        >
                          <h1>thực hiện đặt</h1>
                        </v-btn>
                      </div>
                    </v-flex>
                  </v-layout>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog scrollable v-model="images.dialog" width="650px">
      <v-card flat tile light>
        <v-card-text class="pa-3 ma-0">
          <v-layout row wrap class="ma-0 pa-0">
            <v-flex md10>
              <v-img :aspect-ratio="1" :src="'/images/'+images.key+'/'+images.img.image_link"></v-img>
            </v-flex>
            <v-flex md2>
              <v-layout row wrap class="pa-0 ma-1">
                <v-flex md12 v-for="(img,i) in images.arr" :key="i" class="pa-1 border">
                  <v-avatar :tile="img.id !== images.img.id" size="100%">
                    <v-img
                      :aspect-ratio="1"
                      :src="'/images/'+images.key+'/'+img.image_link"
                      @click="images.img = img"
                    ></v-img>
                  </v-avatar>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog scrollable v-model="action.dialog" width="500px" persistent>
      <v-card flat tile light height="470px">
        <v-card-actions>
          <span class="ml-5 font-weight-bold title">Thực Hiện Đặt Câu Hỏi</span>
          <v-spacer></v-spacer>
          <div class="mr-3">
            <v-btn
              round
              color="teal"
              depressed
              class="white--text"
              @click="sendQuestion"
              :disabled="action.data.title.length===0 && action.data.content.length===0"
            >Gửi</v-btn>
            <v-btn
              round
              color="red"
              depressed
              class="white--text"
              @click="action.dialog = false"
            >Đóng</v-btn>
          </div>
        </v-card-actions>
        <v-divider class="pa-0 ma-0"></v-divider>
        <v-card-title class="pa-0 ma-0">
          <v-card flat width="100%" style="overflow:auto;" height="380px" class="pa-0 ma-0">
            <v-card-title>
              <v-form ref="form" data-vv-scope="form2">
                <v-layout row wrap class="pa-0 ma-0">
                  <v-flex md12>
                    <v-text-field
                      :error-messages="errors.collect('form2.title')"
                      data-vv-name="title"
                      v-validate="'required'"
                      v-model="action.data.title"
                      color="#B53471"
                      type="text"
                      outline
                      clearable
                      clear-icon="cancel"
                      label="Nhập tiêu đề tại đây..."
                    ></v-text-field>
                  </v-flex>
                  <v-flex md12>
                    <v-textarea
                      :error-messages="errors.collect('form2.content')"
                      data-vv-name="content"
                      v-validate="'required'"
                      v-model="action.data.content"
                      color="#B53471"
                      outline
                      clearable
                      clear-icon="cancel"
                      auto-grow
                      rows="4"
                      label="Nhập nội dung tại đây..."
                    ></v-textarea>
                  </v-flex>
                </v-layout>
              </v-form>
            </v-card-title>
            <v-card-text>
              <span
                class="caption font-italic grey--text"
              >Sẽ mất một khoảng thời gian để nhà cung cấp trả lời câu hỏi của bạn...</span>
            </v-card-text>
          </v-card>
        </v-card-title>
      </v-card>
    </v-dialog>
    <v-dialog persistent v-model="waiting" width="500px">
      <v-layout fill-height align-center justify-center class="blue lighten-2" style="opacity:0.9">
        <v-flex md5>
          <v-img :aspect-ratio="1" src="/img/booking/load.gif">
            <v-layout fill-height align-center justify-center>
              <span class="pa-5 caption white--text font-weight-bold">đang xử lý...</span>
            </v-layout>
          </v-img>
        </v-flex>
      </v-layout>
    </v-dialog>
  </v-layout>
</template>

<script>
import map from "@/js/frontend/components/map";
export default {
  components: {
    "component-map": map
  },
  $_veeValidate: {
    validator: "new"
  },
  props: {
    customer:{
      type: Object
    },
    login: {
      type: Object
    },
    loginCheck: {
      type: Boolean
    },
    place: {
      type: String
    },
    checkIn: {
      type: String
    },
    now: {
      type: String
    },
    checkOut: {
      type: String
    },
    checkInFormatted: {
      type: String
    },
    checkOutFormatted: {
      type: String
    }
  },
  data() {
    return {
      loadingRoom: true,
      softDelete: false,
      waiting: false,
      text: "",
      paymentMethods: [],
      couponCodes: [],
      action: {
        dialog: false,
        data: {
          title: "example",
          content: "examples- examples- examples"
        }
      },
      images: {
        key: "hotel",
        dialog: false,
        arr: [{ id: 0, image_link: "default.png" }],
        img: {
          id: 0,
          image_link: "default.png"
        }
      },
      userLogin: this.customer,
      bookingDialog: {
        couponCode: {
          check: false,
          id: 0,
          text: "",
          mess: ""
        },
        policy: "",
        payment: 1,
        accept: false,
        totalPrice: 0,
        step: 1,
        state: false,
        booking: {
          name: "",
          email: "",
          phone: "",
          address: "",
          request: ""
        },
        room: {
          id: "",
          bookingAmount: 1,
          price: 1,
          service: {},
          fearure: {},
          image: "default.png",
          room_bed_type: {},
          room_type: {
            name: ""
          },
          room_mode: {
            name: ""
          }
        },
        dictionary: {
          custom: {
            name: {
              required: () => "Tên liên hệ không được bỏ trống"
            },
            email: {
              required: () => "Email liên hệ không được bỏ trống"
            },
            phone: {
              required: () => "Số điện thoại không được bỏ trống"
            },
            address: {
              required: () => "Nơi cư trú không được bỏ trống"
            },
            title: {
              required: () => "Tiêu đề không được bỏ trống"
            },
            content: {
              required: () => "Nội dung không được bỏ trống"
            }
          }
        }
      },
      rooms: [],
      questionList: [],
      reviewList: [],
      id: this.$route.params.id,
      data: {
        followed: false,
        name: "",
        room: [],
        images: [],
        address: "",
        image: "default.png",
        review: [],
        question: [],
        policy: {
          content: ""
        },
        hotel_type: {
          name: ""
        }
      },
      mn: {
        menu1: false,
        menu2: false
      },
      page: 1,
      placeVal: "",
      checkInVal: "",
      checkInFormattedVal: "",
      checkOutVal: "",
      checkOutFormattedVal: "",
      count: 0,
      bounds: null,
      address: null,
      location: null,
      atm: null,
      restaurant: null,
      pub: null,
      supermarket: null,
      firstTime: true,
      active: false,
      map: null,
      flag: {
        detail: false,
        question: false,
        review: false
      },
      firstLoad: true,
    };
  },
  mounted() {
    this.$validator.localize("en", this.bookingDialog.dictionary);
    this.active = true;
  },
  created() {
    if (Object.keys(this.$route.query).length != 0) {
      this.placeVal = this.$route.query.place.replace(/\-/g, " ");
      this.checkInVal = this.$route.query.check_in;
      this.checkInFormattedVal = this.formatDate(this.$route.query.check_in);
      this.checkOutVal = this.$route.query.check_out;
      this.checkOutFormattedVal = this.formatDate(this.$route.query.check_out);
    }
      this.setSearchValue();
    this.getHotelDetail();
    this.getHotelRooms();
  },
  watch: {
    text: function(val) {
      this.bookingDialog.couponCode.check = false;
      this.bookingDialog.couponCode.id = 0;
      if (val == null || val.length == 0) {
        this.bookingDialog.couponCode.mess = "";
      } else {
        this.couponCodes.forEach(c => {
          if (c.code.localeCompare(val.trim()) == 0) {
            this.bookingDialog.couponCode.check = true;
            this.bookingDialog.couponCode.id = c.id;
            if (c.apply_amount - c.applied_amount <= 0)
              this.bookingDialog.couponCode.mess =
                "Rất tiếc, mã giảm giá đã hết.";
            else
              this.bookingDialog.couponCode.mess =
                "Bạn nhận được " + c.discount_value + "% giảm giá.";
          }
        });
        if (this.bookingDialog.couponCode.check == false) {
          this.bookingDialog.couponCode.mess =
            "Mã không chính xác hoặc không tồn tại! Thử lại?!";
        }
      }
    },
    "bookingDialog.payment": function() {
      this.paymentMethods.forEach(element => {
        if (element.method.id == this.bookingDialog.payment) {
          this.bookingDialog.policy = element.content;
        }
      });
    },
    loginCheck: function() {
      this.userLogin = this.customer;
      this.getHotelDetail();
      this.getHotelReviews();
    },
    placeVal: "loadSearchData",
    checkInVal: "loadSearchData",
    checkIn: "setSearchValue",
    checkOutVal: "loadSearchData",
    checkOut: "setSearchValue"
  },
  methods: {
    getHotelDetail: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/hotel/" + this.id,
        params: {
          id: this.id,
          userID: this.userLogin.id,
          check_in: this.checkInVal,
          check_out: this.checkOutVal
        }
      })
        .then(res => {
          if (res.data.status == true) {
            this.data = res.data.data;
            this.paymentMethods = res.data.data.paymentMethods;
            this.couponCodes = res.data.data.coupon_code;
            res.data.data.paymentMethods.forEach(p => {
              if (p.method.id == 1) {
                this.bookingDialog.policy = p.content;
              }
            });
          } else {
            if (res.data.data != null) {
              this.softDelete = true;
              this.data = res.data.data;
              this.paymentMethods = res.data.data.paymentMethods;
              this.couponCodes = res.data.data.coupon_code;
              res.data.data.paymentMethods.forEach(p => {
                if (p.method.id == 1) {
                  this.bookingDialog.policy = p.content;
                }
              });
            }
          }
          this.flag.detail = true;
        })
        .catch(error => {
          console.log(error);
          console.log(error.response);
        }).then(()=>{
          if(this.firstLoad == true && this.data.policy.cancelable >0){
            if(this.$moment(this.checkIn).diff(this.now,'days')>=0 && this.$moment(this.checkIn).diff(this.now,'days')<3)
              this.$emit("loadSnackbar","Bạn cần chú ý đến chính sách hủy phòng của "+this.data.name+", vì bạn đặt phòng quá cận ngày check-in. "+this.data.name+" chỉ chấp nhận hủy phòng trước "+this.data.policy.cancelable+" ngày.");
            this.firstLoad = false;
          }
        });
    },
    getHotelRooms: function() {
      this.page = 1;
      this.rooms = [];
      axios({
        method: "get",
        url: "http://localhost:8000/api/hotel-rooms",
        params: {
          hotel_id: this.id,
          check_in: this.checkInVal,
          check_out: this.checkOutVal
        }
      })
        .then(res => {
          if (res.data.room.data.length != 0) {
            this.rooms = res.data.room.data;
          }
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
        }).then(()=>{
          this.loadingRoom = false;
        });
    },
    getHotelReviews: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/hotel-review",
        params: {
          hotel_id: this.id,
          user_id: this.userLogin.id
        }
      })
        .then(res => {
          this.reviewList = res.data.reviews;
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
          this.reviews = [];
        }).then(()=>{
          this.flag.review = true;
        });
    },
    getHotelQuestion: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/hotel-question",
        params: {
          hotel_id: this.id
        }
      })
        .then(res => {
          this.questionList = res.data.questions;
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
          this.questionList = [];
        }).then(()=>{
          this.flag.question = true;
        });
    },
    loadRoom($state) {
      this.page = this.page + 1;
      axios({
        method: "get",
        url: "http://localhost:8000/api/hotel-rooms",
        params: {
          page: this.page,
          hotel_id: this.id,
          check_in: this.checkInVal,
          check_out: this.checkOutVal
        }
      })
        .then(res => {
          if (res.data.room.data.length != 0) {
            this.rooms = this.rooms.concat(res.data.room.data);
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
    loadSearchData: function() {
      this.page = 1;
      this.getHotelRooms();
      this.$emit("loadSearchData", {
        place: this.placeVal,
        checkIn: this.checkInVal,
        checkInFormatted: this.checkInFormattedVal,
        checkOut: this.checkOutVal,
        checkOutFormatted: this.checkOutFormattedVal
      });
    },
    setSearchValue: function() {
      this.placeVal = this.place;
      this.checkInVal = this.checkIn;
      this.checkOutVal = this.checkOut;
      this.checkInFormattedVal = this.checkInFormatted;
      this.checkOutFormattedVal = this.checkOutFormatted;
    },
    formatDate: function(date) {
      if (!date) return null;
      return this.$moment(date).format("DD-MM-YYYY");
    },
    openDialog: function(room) {
      this.bookingDialog.room = room;
      this.bookingDialog.room.image = room.image;
      this.bookingDialog.state = true;
      this.bookingDialog.accept = false;
      this.bookingDialog.totalPrice =
        room.bookingAmount * room.price * room.days;
      this.bookingDialog.step = 1;
      this.bookingDialog.couponCode.check = false;
      this.text = "";
      this.bookingDialog.couponCode.mess = "";
      this.bookingDialog.couponCode.id = 0;
      this.bookingDialog.payment = this.paymentMethods[0].method.id;
      if (this.login.check) {
        this.bookingDialog.booking.name = this.login.user.name;
        this.bookingDialog.booking.email = this.login.user.email;
        this.bookingDialog.booking.phone = this.login.user.phone_number;
        this.bookingDialog.booking.address = this.login.user.customer.address;
      } else {
        this.$validator.reset();
        this.bookingDialog.booking.name = "";
        this.bookingDialog.booking.email = "";
        this.bookingDialog.booking.phone = "";
        this.bookingDialog.booking.address = "";
        this.bookingDialog.booking.request = "";
      }
    },
    backBookingStep: function(val) {
      this.bookingDialog.step--;
    },
    nextBookingStep: function(val) {
      switch (val) {
        case 1:
          this.$validator.validateAll("form1").then(valid => {
            if (valid) {
              this.bookingDialog.step++;
            }
          });
          break;
        default:
          this.bookingDialog.step++;
          break;
      }
    },
    getLogin: function(val) {
      this.$emit("loadLoginDialog", true, val);
    },
    applyCouponCode: function(room_id, roomCouponCodes) {
      var temp = 0;
      roomCouponCodes.forEach((c, index) => {
        if (c.id == this.bookingDialog.couponCode.id)
          if (c.apply_amount - c.applied_amount > 0) {
            temp = c.discount_value;
          } else {
            this.bookingDialog.couponCode.mess =
              "Rất tiếc, mã giảm giá đã hết.";
          }
      });
      if (temp == 0) {
        this.bookingDialog.couponCode.mess =
          "Dường như mã ĐÃ HẾT hoặc " +
          this.text +
          " KHÔNG ĐƯỢC " +
          this.data.name +
          " ÁP DỤNG cho loại phòng " +
          this.bookingDialog.room.room_type.name +
          " .";
      }else{
        axios({
        method: "get",
        url: "http://localhost:8000/api/check-coupon-code",
        params: {
          code_id: this.bookingDialog.couponCode.id,
          room_id: room_id
        },
      })
        .then(res => {
          if(res.data.status == true){
            temp = res.data.value;
            this.bookingDialog.couponCode.mess = res.data.mess;
          }
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
        });
      }
      this.bookingDialog.totalPrice =
        this.bookingDialog.totalPrice * ((100 - temp) / 100);
    },
    getBooking: function() {
      this.waiting = true;
      axios({
        method: "post",
        url: "http://localhost:8000/api/booking",
        data: {
          booking: {
            payment: this.bookingDialog.payment,
            name: this.bookingDialog.booking.name,
            email: this.bookingDialog.booking.email,
            phone: this.bookingDialog.booking.phone,
            address: this.bookingDialog.booking.address,
            request: this.bookingDialog.booking.request,
            checkIn: this.checkIn,
            checkOut: this.checkOut,
            roomId: this.bookingDialog.room.id,
            roomAmount: this.bookingDialog.room.bookingAmount,
            coupon_code_id: this.bookingDialog.couponCode.id
          }
        },
        headers: {
          Authorization: "Bearer " + this.login.token
        }
      })
        .then(res => {
          if (res.data.status == true) {
            this.bookingDialog.state = false;
            this.$emit("loadLogin");
            this.$emit("loadBookingDetail", res.data.booking, 1);
            this.getHotelRooms();
            this.getHotelDetail();
          }
          this.$emit("loadSnackbar", res.data.mess);
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.getLogin(1);
          }
        }).then(()=>{
          this.waiting = false;
        });
    },
    openQuestionDialog() {
      if (this.loginCheck == false) {
        this.$emit("loadLoginDialog", true, 1);
        return;
      }
      this.action.data.title = "";
      this.action.data.content = "";
      this.$validator.reset();
      this.action.dialog = true;
    },
    sendQuestion: function() {
      this.$validator.validateAll("form2").then(valid => {
        if (valid) {
          var flag = true;
          var ques = {
            title: this.action.data.title,
            content: this.action.data.content,
            customer: this.login.user,
            created_at: new Date().toISOString().substr(0, 10)
          };
          this.questionList = [ques, ...this.questionList];
          axios({
            method: "get",
            url: "http://localhost:8000/api/question/create",
            params: {
              title: this.action.data.title,
              content: this.action.data.content,
              hotel_id: this.data.id
            },
            headers: {
              Authorization: "Bearer " + localStorage.getItem("login_token")
            }
          })
            .then(res => {
              if (res.data.status == true) {
                this.$validator.reset();
              } else {
                flag = false;
                this.$emit(
                  "loadSnackbar",
                  "Rất tiếc, thực hiện không thành công!"
                );
              }
            })
            .catch(error => {
              flag = false;
              if (error.response.status == 401) {
                localStorage.removeItem("login_token");
                this.$emit("loadLoginDialog", true, 1);
                this.action.dialog = false;
                return;
              }
              this.$emit(
                "loadSnackbar",
                "Rất tiếc, thực hiện không thành công!"
              );
              return;
            })
            .then(() => {
              if (!flag) {
                this.questionList.splice(0, 1);
              }
            });
          this.action.dialog = false;
        }
      });
    },
    followHotel: function(value, cmd) {
      if(this.loginCheck == false){
        this.$emit("loadLoginDialog", true, 1);
        return;
      }
      var flag = true;
      if (cmd == 1) {
        this.data.followed = true;
        axios({
          method: "get",
          url: "http://localhost:8000/api/following",
          params: {
            type: 1,
            id: this.userLogin.id,
            followed: value.id
          },
          headers: {
            Authorization: "Bearer " + localStorage.getItem("login_token")
          }
        })
          .then(res => {
            this.$emit("loadSnackbar", "Theo dõi " + this.data.name+" bạn chấp nhận nhận thông báo về cập nhật (chương trình khuyến mãi,...) của nhà cung cấp này.");
          })
          .catch(error => {
            console.log(error.response);
            console.log(error);
            flag = false;
            if (error.response.status == 401) {
              localStorage.removeItem("login_token");
              this.$emit("loadLoginDialog", true, 1);
            }
          }).then(()=>{
            if (!flag) this.data.followed = false;
          });
      } else {
        this.data.followed = false;
        axios({
          method: "get",
          url: "http://localhost:8000/api/un-following",
          params: {
            type: 1,
            id: this.userLogin.id,
            followed: value.id
          },
          headers: {
            Authorization: "Bearer " + localStorage.getItem("login_token")
          }
        })
          .then(res => {
            this.$emit("loadSnackbar", "Hủy theo dõi " + value.name);
          })
          .catch(error => {
            console.log(error.response);
            console.log(error);
            flag = false;
            if (error.response.status == 401) {
              localStorage.removeItem("login_token");
              this.$emit("loadLoginDialog", true, 1);
            }
          }).then(()=>{
            if (!flag) this.data.followed = true;
          });
      }
    },
    openImagesDialog: function(val, room) {
      if (val == 0) {
        this.images.key = "hotel";
        this.images.arr = this.data.images;
        this.images.img = this.data.images[0];
      } else {
        this.images.key = "room";
        this.images.arr = room.images;
        this.images.img = room.images[0];
      }
      this.images.dialog = true;
    },
    takeUseful: function(reviewID, index) {
      if(this.loginCheck == false){
        this.$emit("loadLoginDialog", true, 1);
        return;
      }
      if (this.reviewList[index].useful) this.reviewList[index].useful = false;
      else this.reviewList[index].useful = true;
      var flag = true;
      axios({
        method: "get",
        url: "http://localhost:8000/api/update-useful",
        params: {
          review_id: reviewID
        },
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          if (res.data.status == false) {
            flag = false;
            this.$emit("loadSnackbar", "Rất tiếc, thực hiện không thành công!");
          }
        })
        .catch(error => {
          flag = false;
          console.log(error.response);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.$emit("loadLoginDialog", true, 1);
          }
        })
        .then(() => {
          if (!flag) {
            if (this.reviewList[index].useful)
              this.reviewList[index].useful = false;
            else this.reviewList[index].useful = true;
          }
        });
    },
    getSearch: function() {
      this.$router.push({
        name: "searching",
        query: {
          place: this.place.replace(/\s/g, "-"),
          check_in: this.checkIn,
          check_out: this.checkOut
        }
      });
    },
    formatKey: function(string) {
      return string.replace(/\s/g, "+");
    },
    getAddress: function(address) {
      this.formatKey(address);
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/map",
        params: {
          address: this.formatKey(address)
        }
      })
        .then(response => {
          if (response.data.status == true) {
            this.location = response.data.location.data;
            this.atm = response.data.location.atm;
            this.restaurant = response.data.location.restaurant;
            this.pub = response.data.location.pub;
            this.supermarket = response.data.location.supermarket;
            var key = "11f82a7e50bc28";
            var ll = [
              response.data.location.data.lat,
              response.data.location.data.lon
            ];
            var streets = L.tileLayer(
              "https://{s}-tiles.locationiq.com/v2/obk/r/{z}/{x}/{y}.png?key=11f82a7e50bc28"
            );
            var map = L.map("map", {
              center: ll,
              zoom: 15,
              layers: [streets]
            });
            setTimeout(function() {
              map.invalidateSize();
            }, 0);
            L.control.scale().addTo(map);
            L.control
              .layers({
                Streets: streets
              })
              .addTo(map);
            var tooltip = L.tooltip({ direction: top }).setTooltipContent("hi");
            var redMarker = L.ExtraMarkers.icon({
              icon: "fa-home",
              markerColor: "red",
              shape: "circle",
              prefix: "fa"
            });
            L.marker(ll, { icon: redMarker }).addTo(map);
            this.addMarker(map, this.atm, "credit-card", "blue-dark");
            this.addMarker(map, this.restaurant, "utensils", "orange");
            this.addMarker(map, this.pub, "cocktail", "pink");
            this.addMarker(map, this.supermarket, "shopping-basket", "green");
          }
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
        });
    },
    addMarker: function(map, array, type, color) {
      array.forEach(element => {
        var icon = "fa-" + type;
        var marker = L.ExtraMarkers.icon({
          icon: icon,
          markerColor: color,
          shape: "circle",
          prefix: "fa"
        });
        L.marker([element.lat, element.lon], { icon: marker })
          .addTo(map)
          .bindTooltip(element.name);
      });
    }
  }
};
</script>

<style scoped>
.detail-container {
  margin-top: 35px !important;
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
.v-stepper__header {
  height: 30px;
}
.v-stepper__step {
  width: 150px;
  padding: 0;
}
.v-stepper,
.v-stepper__header {
  box-shadow: none;
}
.theme--dark.v-stepper {
  background: none;
}
.v-stepper {
  border: 1px solid #fff;
  margin-right: 100px;
}
div.comment tbody {
  height: 400px !important;
  overflow: auto !important;
}
</style>