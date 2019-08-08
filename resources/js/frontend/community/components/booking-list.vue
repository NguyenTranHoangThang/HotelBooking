<template>
  <v-layout row wrap class="mx-3">
    <v-flex md7 v-if="flag.state">
      <v-layout class="pa-0 ma-0" v-if="bookingList.length >0">
        <v-flex md12>
          <template>
            <v-card flat>
              <v-card-title
                v-if="search !=0 && item.form == false && item.review == false && item.detail == false"
              >Kết quả tìm kiếm...</v-card-title>
              <v-card-title v-if="search !=0 && item.detail == true">Chi tiết đơn...</v-card-title>
              <v-card-title v-if="search !=0 && item.form ==true">Thực hiện đánh giá...</v-card-title>
              <v-card-title v-if="search !=0 && item.review ==true">Chi tiết đánh giá của bạn...</v-card-title>
              <v-data-table
                :search="search"
                :rows-per-page-items='[10,15,{"text":"$vuetify.dataIterator.rowsPerPageAll","value":-1}]'
                :headers="tblHeaders"
                :items="bookingList"
                class="elevation-0 border"
              >
                <template v-slot:items="b">
                  <tr class="pa-0 ma-0">
                    <td>
                      <a href="#">
                        <span style="word-wrap: break-word;" class="caption">{{ b.item.id }}</span>
                      </a>
                    </td>
                    <td class="border-left">
                      <a href="#">
                        <span
                          style="word-wrap: break-word;"
                          class="caption"
                        >{{ b.item.total.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</span>
                      </a>
                    </td>
                    <td class="border-left">
                      <div>
                        <a href="#">
                          <span
                            style="word-wrap: break-word;"
                            class="caption"
                          >{{ b.item.status.name}}</span>
                        </a>
                      </div>
                      <div>
                        <div v-if="b.item.review!=null">
                          <span style="word-wrap: break-word;" class="caption">Bạn đã đánh giá.</span>
                          <a
                            href="#"
                            class="blue--text"
                            v-if="!b.expanded || item.detail"
                            @click="b.expanded = true; getReview(b.item)"
                          >Xem đánh giá...</a>
                          <a
                            v-else
                            href="#"
                            class="orange--text"
                            @click="getDetail(b.item)"
                          >Chi tiết đơn...</a>
                        </div>
                        <div v-if="b.item.can_review == true">
                          <span
                            style="word-wrap: break-word;"
                            class="caption"
                          >Bây giờ, bạn đã có thể</span>
                          <a
                            href="#"
                            class="purple--text"
                            @click="b.expanded = true; formReview(b.item)"
                          >đánh giá</a>
                          <span class="caption">nhà cung cấp này.</span>
                        </div>
                      </div>
                    </td>
                    <td>
                      <v-layout row wrap class="pa-0 ma-0" align-center>
                        <v-flex>
                          <v-btn
                            small
                            v-if="!b.expanded"
                            fab
                            depressed
                            color="teal"
                            @click="b.expanded = true; getDetail(b.item)"
                          >
                            <v-icon small color="white">keyboard_arrow_down</v-icon>
                          </v-btn>
                          <v-btn
                            small
                            v-else
                            fab
                            depressed
                            color="grey"
                            @click="b.expanded = false; reset();search= radioSelected"
                          >
                            <v-icon color="white">keyboard_arrow_up</v-icon>
                          </v-btn>
                        </v-flex>
                        <v-flex>
                          <v-btn
                            small
                            :disabled="!b.item.cancel_status"
                            @click="confirmAction(b.item)"
                            depressed
                            color="orange"
                            round
                            class="white--text pa-1 ma-1"
                          >Hủy</v-btn>
                        </v-flex>
                      </v-layout>
                    </td>
                  </tr>
                </template>
                <template v-slot:expand="b">
                  <v-card flat tile light>
                    <v-layout class="pa-4 ma-1 border grey lighten-2" row wrap>
                      <v-flex md12 v-if="item.review == true">
                        <v-layout row wrap class="pa-0 ma-0" justify-center align-center>
                          <v-flex md2>
                            <v-avatar size="102px" color="black">
                              <v-avatar size="100px" color="blue">
                                <v-layout fill-height align-center justify-center>
                                  <v-flex
                                    class="white--text font-weight-black headline"
                                  >{{review.point}}/10</v-flex>
                                </v-layout>
                              </v-avatar>
                            </v-avatar>
                          </v-flex>
                          <v-flex md10>
                            <div class="body-1">
                              <div>
                                <span>{{review.title}}</span>
                              </div>
                              <v-divider></v-divider>
                              <div>
                                <span class="font-italic">"{{review.content}}"</span>
                              </div>
                            </div>
                          </v-flex>
                          <v-flex>
                            <span
                              class="caption grey--text pl-4"
                            >- {{formatDate(review.created_at)}}</span>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                      <v-flex md12 v-else>
                        <v-layout row wrap class="pa-0 pl-3 ma-0">
                          <v-flex md4>
                            <div class="mt-3">
                              <v-img :aspect-ratio="1" :src="'/blog/img/room/'+booking.room.image"></v-img>
                            </div>
                          </v-flex>
                          <v-flex md8>
                            <v-layout row wrap class="pa-0 pl-3 ma-0 ml-3 border-left border-light">
                              <v-flex md12>
                                <router-link
                                  :to="{name:'hotel',params:{id:booking.room.hotel.id}}"
                                  target="_blank"
                                  class="headline font-weight-black"
                                >{{booking.hotel_name}}</router-link>
                              </v-flex>
                              <v-flex md12>
                                <div>
                                  <span>{{booking.room.hotel.type}}</span>
                                </div>
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
                                        <span>{{formatDate(booking.check_in)}}</span>
                                      </div>
                                    </div>
                                  </v-flex>
                                  <v-flex md6 class="pa-2">
                                    <div>
                                      <div>
                                        <span class="font-weight-bold">Check-Out:</span>
                                      </div>
                                      <div>
                                        <span>{{formatDate(booking.check_out)}}</span>
                                      </div>
                                    </div>
                                  </v-flex>
                                  <v-flex md12 class="pl-2">
                                    <span class="font-weight-bold">Thông Tin Liên Hệ</span>
                                  </v-flex>
                                  <v-flex md12 class="pl-2">
                                    <v-layout row wrap class="pa-0 ma-0">
                                      <v-flex md5>Họ Tên</v-flex>
                                      <v-flex md7>{{booking.contact_name}}</v-flex>
                                      <v-flex md5>Email</v-flex>
                                      <v-flex md7>{{booking.contact_email}}</v-flex>
                                      <v-flex md5>Số Điện Thoại</v-flex>
                                      <v-flex md7>{{booking.contact_phone}}</v-flex>
                                      <v-flex md5>Địa Chỉ</v-flex>
                                      <v-flex md7>{{booking.contact_address}}</v-flex>
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
                                <v-layout row wrap class="pa-0 ma-0">
                                  <v-flex md5>Tên Phòng:</v-flex>
                                  <v-flex md7>{{booking.room.room_name}}</v-flex>
                                  <v-flex md5>Loại Phòng:</v-flex>
                                  <v-flex md7>{{booking.room.room_type.name}}</v-flex>
                                  <v-flex md5>Số Lượng:</v-flex>
                                  <v-flex md7>{{booking.room_amount}}</v-flex>
                                  <v-flex md5>Yêu Cầu Đặc Biệt:</v-flex>
                                  <v-flex md7>{{booking.special_request}}</v-flex>
                                </v-layout>
                              </v-flex>
                            </v-layout>
                          </v-flex>
                        </v-layout>
                        <div v-if="item.detail == true">
                          <div>
                            <v-divider dark></v-divider>
                            <span class="font-weight-black title">Tình Trạng Đơn</span>
                          </div>
                          <v-layout row wrap class="pa-0 pl-3 ma-0">
                            <div>
                              <div>
                                <span
                                  class="font-weight-black red--text font-italic subheading"
                                >{{booking.status.name}}</span>
                              </div>
                              <div>
                                <span class="font-italic">{{booking.payment_method.content}}</span>
                              </div>
                            </div>
                          </v-layout>
                          <div>
                            <v-divider dark></v-divider>
                            <span class="font-weight-black title">Chi Tiết Giá</span>
                          </div>
                          <v-layout row wrap class="pa-0 pl-3 ma-0">
                            <v-flex md12>
                              <v-divider></v-divider>
                            </v-flex>
                            <v-flex md8>
                              <span>{{booking.room.room_type.name}}</span>
                            </v-flex>
                            <v-flex
                              md4
                            >{{booking.room_price.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</v-flex>
                            <v-flex md8>
                              <span>Số Lượng</span>
                            </v-flex>
                            <v-flex md4>{{booking.room_amount}}</v-flex>
                            <v-flex md8>
                              <span>Số Đêm Ở</span>
                            </v-flex>
                            <v-flex md4>{{booking.days}}</v-flex>
                            <v-flex md12 v-if="booking.discount_value!=0">
                              <v-layout row wrap class="pa-0 ma-0">
                                <v-flex md8>
                                  <span>Mã Giảm Giá</span>
                                </v-flex>
                                <v-flex md4>
                                  <span>{{booking.coupon_code}}</span>
                                </v-flex>
                                <v-flex md12>
                                  <div class="font-italic font-weight-black">
                                    Bạn nhận được
                                    <span class="red--text">{{booking.discount_value}}%</span> giảm giá!
                                  </div>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                            <v-flex md8>
                              <span>Thanh Toán</span>
                            </v-flex>
                            <v-flex md4>{{booking.payment_method.name}}</v-flex>
                            <v-flex md12>
                              <v-divider></v-divider>
                            </v-flex>
                            <v-flex md8>
                              <span class="headline orange--text">Tổng Giá</span>
                            </v-flex>
                            <v-flex md4>
                              <span
                                class="headline orange--text"
                              >{{booking.total.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</span>
                            </v-flex>
                          </v-layout>
                        </div>
                        <div v-else-if="item.form == true">
                          <v-divider></v-divider>
                          <v-card flat tile light>
                            <v-card-actions>
                              <div class="mr-3">
                                <v-btn
                                  color="teal"
                                  depressed
                                  class="white--text"
                                  @click="sendReview"
                                >gửi</v-btn>
                              </div>
                            </v-card-actions>
                            <v-divider class="pa-0 ma-0"></v-divider>
                            <v-card-title class="pa-0 ma-3">
                              <v-form ref="form" data-vv-scope="formReview">
                                <v-layout row wrap class="pa-0 ma-0">
                                  <v-flex md12>
                                    <v-layout row wrap class="pa-0 ma-0" align-center>
                                      <v-flex md8>
                                        <v-rating
                                          small
                                          v-model="reviewData.point"
                                          color="indigo darken-3"
                                          background-color="grey darken-1"
                                          empty-icon="$vuetify.icons.ratingFull"
                                          hover
                                          :length="10"
                                        ></v-rating>
                                      </v-flex>
                                      <v-flex md2>({{reviewData.point}}/10)</v-flex>
                                      <v-flex md6>
                                        <v-checkbox
                                          v-model="reviewData.can_comment"
                                          label="Bật bình luận"
                                          color="#B53471"
                                        ></v-checkbox>
                                      </v-flex>
                                      <v-flex md6>
                                        <v-checkbox
                                          :disabled="!reviewData.can_comment"
                                          v-model="reviewData.notification"
                                          label="Nhận thông báo"
                                          color="#B53471"
                                        ></v-checkbox>
                                      </v-flex>
                                    </v-layout>
                                  </v-flex>
                                  <v-flex md5 class="pr-3">
                                    <v-textarea
                                      :error-messages="errors.collect('formReview.title')"
                                      data-vv-name="title"
                                      v-validate="'required'"
                                      v-model="reviewData.title"
                                      color="#B53471"
                                      type="text"
                                      outline
                                      clearable
                                      auto-grow
                                      rows="4"
                                      clear-icon="cancel"
                                      label="Nhập tiêu đề tại đây..."
                                    ></v-textarea>
                                  </v-flex>
                                  <v-flex md7>
                                    <v-textarea
                                      :error-messages="errors.collect('formReview.content')"
                                      data-vv-name="content"
                                      v-validate="'required'"
                                      v-model="reviewData.content"
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
                          </v-card>
                        </div>
                      </v-flex>
                    </v-layout>
                  </v-card>
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
          </template>
        </v-flex>
      </v-layout>
      <v-layout class="pa-0 ma-0" justify-center align-center v-else>
        <div>
          <span>{{flag.text}}</span>
        </div>
      </v-layout>
    </v-flex>
    <v-flex md7 v-else>
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
    <v-flex md4 offset-md1>
      <v-card light flat tile>
        <v-card-title>
          <div class="title my-3 text-uppercase font-weight-black">
            <v-icon color="black">import_export</v-icon>&nbsp;Lựa chọn cho bạn
          </div>
          <v-btn round depressed color="grey lighten-2" @click.stop="reset">
            <v-icon small>replay</v-icon>&nbsp;xem lại
          </v-btn>
          <v-layout row wrap class="pa-0 ma-2">
            <v-flex md12>
              <v-radio-group v-model="search">
                <v-layout row wrap class="pa-0 ma-0">
                  <v-flex md12>
                    <span class="body-1 orange--text">Tình Trạng Đơn</span>
                  </v-flex>
                  <v-flex md6 v-for="(s,i) in status" :key="i">
                    <v-layout row wrap class="pa-0 ma-0">
                      <v-flex md2>
                        <v-radio :value="s.name" @click="search = s.name"></v-radio>
                      </v-flex>
                      <v-flex md10 class="caption">{{s.name}}</v-flex>
                    </v-layout>
                  </v-flex>
                </v-layout>
              </v-radio-group>
            </v-flex>
            <v-flex md12>
              <div>
                <div class="mb-1">
                  <span class="body-1 orange--text">Tìm theo ngày</span>
                </div>
                <div>
                  <input type="text" v-model="code" class="pa-2 border" />
                </div>
                <div class="mt-1">
                  <span class="grey--text cption font-italic">*nhập ngày theo chuẩn: DD-MM-YYYY</span>
                </div>
              </div>
              <div>
                <div class="mb-1">
                  <span class="body-1 orange--text">Tìm theo mã</span>
                </div>
                <div>
                  <input type="text" v-model="bookingCode" class="pa-2 border" />
                </div>
                <div class="mt-1">
                  <span class="grey--text cption font-italic">*nhập mã đơn</span>
                </div>
              </div>
            </v-flex>
          </v-layout>
        </v-card-title>
      </v-card>
    </v-flex>
    <v-dialog persistent v-model="confirm.dialog" max-width="500">
      <v-card>
        <v-card-title>
          <v-layout class="pa-0 ma-0" align-center>
            <v-flex md5>
              <v-img :aspect-ratio="1" src="/img/booking/octo.gif"></v-img>
            </v-flex>
            <v-flex md7>
              <div class="title">{{confirm.title}}</div>
              <div class="ml-3">
                <v-chip
                  class="pa-1 ma-2 white--text font-weight-bold"
                  color="red"
                  @click="confirm.dialog = false"
                >Hủy</v-chip>
                <v-chip
                  class="pa-1 ma-2 white--text font-weight-bold"
                  color="green darken-1"
                  @click="cancelBooking()"
                >Đồng ý</v-chip>
              </div>
            </v-flex>
          </v-layout>
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
  mounted() {
    console.log("Component mounted.");
    this.$validator.localize("en", this.dictionary);
  },
  data() {
    return {
      item: {
        form: false,
        detail: false,
        review: false
      },
      dictionary: {
        custom: {
          title: {
            required: () => "Title can not be empty"
          },
          content: {
            required: () => "Content can not be empty"
          }
        }
      },
      reviewData: {
        title: "",
        content: "",
        point: 0,
        can_comment: true,
        notification: true
      },
      review: {
        title: "",
        content: "",
        created_at: "",
        point: ""
      },
      search: "",
      code: "",
      confirm: {
        dialog: false,
        index: 0,
        title:
          "Xóa? Đồng ý thực hiện hành động này, bạn đã cam kết với chúng tôi rằng bạn đã đọc và hiểu rõ mọi điều khoản liên quan."
      },
      flag: {
        state: false,
        text: ""
      },
      bookingCode: "",
      status: [],
      bookingList: [],
      pureBookingList: [],
      sortedBookingList: [],
      booking: {
        total: 0,
        status: {},
        room_price: 0,
        room: {
          room_mode: {},
          hotel: {},
          room_type: {},
          image: "default.png"
        },
        payment_method: {},
        review: {
          title: "",
          content: "",
          created_at: ""
        }
      },
      radioSelected: "",
      tblHeaders: [
        {
          text: "Mã",
          value: "slug",
          align: "",
          class: "orange--text",
          sortable: false,
          width: "6%"
        },
        {
          text: "Tổng Giá",
          value: 0,
          align: "",
          class: "orange--text border-left",
          sortable: false
        },
        {
          text: "Tình Trạng Đơn",
          value: "status.name",
          align: "",
          class: "orange--text border-left",
          sortable: false
        },
        {
          text: "",
          value: "check_in",
          align: "center",
          class: "orange--text",
          sortable: false,
          width: "30%"
        }
      ]
    };
  },
  created() {
    if (Object.keys(this.$route.query).length != 0) {
      this.search = this.$route.query.code;
    }
    this.load();
    this.loadBookingStatus();
  },
  watch: {
    code: function(val) {
      if (val.length == 10) {
        let [day, month, year] = val.trim().split("-");
        val = year + "-" + month + "-" + day;
        this.search = this.$moment(val).format("YYYY-MM-DD");
      }
    },
    bookingCode: function(val) {
      this.search = "bookingcode" + val;
    },
    "reviewData.can_comment": function() {
      if (this.reviewData.can_comment == 0) {
        this.reviewData.notification = 0;
      }
    }
  },
  methods: {
    getDetail: function(booking) {
      if (this.search.substr(0, 11).localeCompare("bookingcode") != 0)
        this.radioSelected = this.search;
      this.search = "bookingcode" + booking.id;
      this.item.detail = true;
      (this.item.form = false), (this.item.review = false);
      this.booking = booking;
    },
    reset: function() {
      this.item.form = false;
      this.item.review = false;
      this.item.detail = false;
      this.search = "";
      this.code = "";
    },
    getReview: function(booking) {
      if (this.search.substr(0, 11).localeCompare("bookingcode") != 0)
        this.radioSelected = this.search;
      this.search = "bookingcode" + booking.id;
      this.item.review = true;
      this.item.detail = false;
      this.item.form = false;
      this.booking = booking;
      if (this.booking.review != null) this.review = booking.review;
    },
    formReview: function(booking) {
      if (this.search.substr(0, 11).localeCompare("bookingcode") != 0)
        this.radioSelected = this.search;
      this.search = "bookingcode" + booking.id;
      this.reviewData.title = "";
      this.reviewData.content = "";
      this.reviewData.point = 0;
      this.reviewData.can_comment = true;
      this.reviewData.notification = true;
      this.item.form = true;
      this.item.review = false;
      this.item.detail = false;
      this.booking = booking;
    },
    sendReview: function() {
      var index = this.bookingList.indexOf(this.booking);
      this.$validator.validateAll("formReview").then(valid => {
        if (valid) {
          axios({
            method: "get",
            url: "http://localhost:8000/api/review/create",
            params: {
              title: this.reviewData.title,
              content: this.reviewData.content,
              star: this.reviewData.point,
              hotel_id: this.booking.room.hotel.id,
              booking_id: this.booking.id,
              can_comment: this.reviewData.can_comment,
              notification: this.reviewData.notification
            },
            headers: {
              Authorization: "Bearer " + localStorage.getItem("login_token")
            }
          })
            .then(res => {
              if (res.data.status == true) {
                this.bookingList[index].review = res.data.review;
                this.bookingList[index].can_review = false;
                this.getReview(this.bookingList[index]);
                this.item.form = false;
                this.item.detail = false;
                this.item.review = true;
                this.$validator.reset();
              } else {
                this.$emit(
                  "loadSnackbar",
                  "Rất tiếc. thực hiện chưa hoàn thành. Thử lại?"
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
            });
        }
      });
    },
    load: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/booking",
        params: {},
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          this.flag.state = true;
          this.bookingList = res.data.data;
          this.pureBookingList = res.data.data;
          if (this.pureBookingList == 0)
            this.flag.text = "Bạn chưa có đơn nào!";
        })
        .catch(error => {
          this.flag.state = false;
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            localStorage.removeItem("login_token");
            this.$router.push({ name: "login" });
          }
        });
    },
    confirmAction: function(booking) {
      this.confirm.dialog = true;
      this.confirm.index = this.bookingList.indexOf(booking);
      this.booking = booking;
      this.confirm.title =
        "Xóa? Đồng ý thực hiện hành động này, bạn đã cam kết với chúng tôi rằng bạn đã đọc và hiểu rõ mọi điều khoản liên quan.";
    },
    loadBookingStatus: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/booking-status",
        params: {}
      })
        .then(res => {
          this.status = res.data.data;
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
        });
    },
    cancelBooking: function() {
      var index = this.confirm.index;
      var del = false;
      switch (this.booking.status.id) {
        case 1:
          del = true;
          this.bookingList.splice(index, 1);
          break;
        default:
          this.bookingList[index].status.id = 5;
          this.bookingList[index].status.name = "Cancel by user!";
          this.bookingList[index].cancel_status = false;
          this.bookingList[index].payment_method = this.booking.payment_method;
          break;
      }
      var flag = true;
      if (this.confirm.dialog === true) this.confirm.dialog = false;
      axios({
        method: "put",
        url: "http://localhost:8000/api/booking/" + this.booking.id,
        params: {
          action: "cancel"
        },
        headers: {
          Authorization: "Bearer " + localStorage.getItem("login_token")
        }
      })
        .then(res => {
          if (res.data.status == false) {
            flag = false;
            this.$emit(
              "loadSnackbar",
              "Rất tiếc, thao tác chưa hoàn thành. Thử lại!"
            );
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
        }).then(()=>{
          if (!flag) {
            if (del) this.bookingList.push(this.booking);
            else this.bookingList[index] = this.booking;
          }
        });
      this.booking = {
        status: {},
        room_price: 0,
        room: {
          room_mode: {},
          hotel: {},
          room_type: {},
          image: "default.png"
        },
        payment_method: {},
        review: null
      };
    },
    formatDate: function(date) {
      if (!date) return null;
      return this.$moment(date).format("DD-MM-YYYY");
    },
    sortData: function() {
      this.sortedBookingList.length = 0;
      this.pureBookingList.forEach(element => {
        if (element.status.id == 4) this.sortedBookingList.push(element);
      });
      this.bookingList = this.sortedBookingList;
    }
  }
};
</script>
