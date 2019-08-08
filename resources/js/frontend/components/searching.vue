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
          v-on:click="reSearch()"
        >
          <v-icon small>search</v-icon>
        </v-btn>
      </v-layout>
    </v-flex>
    <v-flex md4>
      <div class="right-search">
        <v-card dark flat width="100%">
          <v-card-text>
            <v-card flat>
              <v-card-title class="pa-0 ma-0">
                <h5>Tiêu chuẩn sao</h5>
                {{starsSeleted}}
              </v-card-title>
              <v-card-text class="pa-0 ma-0">
                <v-layout row v-for="i in number" :key="i" justify-center align-center>
                  <v-flex md1 offset-md1 class="pt-3">
                    <v-checkbox
                      class="custom-checkbox"
                      type="checkbox"
                      v-model="starsSeleted"
                      :value="number+1-i"
                      height="1"
                    ></v-checkbox>
                  </v-flex>
                  <v-flex md10>
                    <div @click="chooseStar(i)">
                      <span>
                        <v-icon
                          class="pb-1 ml-2"
                          color="yellow"
                          v-for="(index) in number+1-i"
                          :key="index"
                        >star</v-icon>
                      </span>
                    </div>
                  </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
            <v-divider></v-divider>

            <v-card flat>
              <v-card-title class="pa-0 ma-0">
                <h5>Dịch vụ</h5>
                {{serviceSeleted}}
              </v-card-title>
              <v-card-text class="pa-0 ma-0">
                <v-layout
                  row
                  v-for="(service,i) in arrayService"
                  :key="i"
                  justify-center
                  align-center
                >
                  <v-flex md1 offset-md1 class="pt-3">
                    <v-checkbox
                      class="custom-checkbox"
                      type="checkbox"
                      v-model="serviceSeleted"
                      :value="service.id"
                      height="1"
                    ></v-checkbox>
                  </v-flex>
                  <v-flex md10>
                    <div @click="chooseService(service.id)">
                      <span>
                        <i class="m-2 fas" :class="icon = 'fa-'+service.icon"></i>
                        {{service.name}}
                      </span>
                    </div>
                  </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
            <v-divider></v-divider>
            <v-card flat>
              <v-card-title class="pa-0 ma-0">
                <h5>Mức giá</h5>
                <!-- <span>({{price[0].toLocaleString('vi', {style: 'currency',currency: 'VND',})}}&nbsp;-&nbsp;{{price[1].toLocaleString('vi', {style: 'currency',currency: 'VND',})}})</span> -->
              </v-card-title>
              <v-card-text class="pa-0 ma-0 mt-3">
                <v-layout row justify-center align-center>
                  <v-flex md10>
                    <v-range-slider
                      thumb-label="always"
                      always-dirty
                      v-model="price"
                      :max="priceMax"
                      :min="priceMin"
                      :step="10"
                      @end="getData"
                    ></v-range-slider>
                  </v-flex>
                  <!-- <v-flex md3 offset-md1>Min : {{priceMin}} -- Max : {{priceMax}}</v-flex> -->
                </v-layout>
              </v-card-text>
            </v-card>
            <v-divider></v-divider>
            <v-card v-if="noData == false" flat>
              <v-card-title>
                <h5>Vị trí</h5>
                {{districtSeleted}}
              </v-card-title>
              <v-card-text class="pa-0 ma-0">
                <v-layout
                  row
                  v-for="(district,i) in arrayDistrict"
                  :key="i"
                  justify-center
                  align-center
                >
                  <v-flex md1 offset-md1 class="pt-3">
                    <v-checkbox
                      class="custom-checkbox"
                      type="checkbox"
                      v-model="districtSeleted"
                      :value="district.id"
                      height="1"
                    ></v-checkbox>
                  </v-flex>
                  <v-flex md10>
                    <div @click="chooseDistrict(district.id)">
                      <span>{{district.name}}</span>
                    </div>
                  </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
            <v-divider v-if="noData == false"></v-divider>
            <v-card flat>
              <v-card-title class="pa-0 ma-0">
                <h5>Loại hình chỗ ở</h5>
                {{hotelTypeSeleted}}
              </v-card-title>
              <v-card-text class="pa-0 ma-0">
                <v-layout
                  row
                  v-for="(hotelType,i) in arrayHotelType"
                  :key="i"
                  justify-center
                  align-center
                >
                  <v-flex md1 offset-md1 class="pt-3">
                    <v-checkbox
                      class="custom-checkbox"
                      type="checkbox"
                      v-model="hotelTypeSeleted"
                      :value="hotelType.id"
                      height="1"
                    ></v-checkbox>
                  </v-flex>
                  <v-flex md10>
                    <div @click="chooseDistrict(district.id)">
                      <span>{{hotelType.name}}</span>
                    </div>
                  </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
            <v-divider></v-divider>
            <v-card flat>
              <v-card-title class="pa-0 ma-0">
                <h5>Loại phòng</h5>
                {{RoomTypeSeleted}}
              </v-card-title>
              <v-card-text class="pa-0 ma-0">
                <v-layout
                  row
                  v-for="(roomType,i) in arrayRoomType"
                  :key="i"
                  justify-center
                  align-center
                >
                  <v-flex md1 offset-md1 class="pt-3">
                    <v-checkbox
                      class="custom-checkbox"
                      type="checkbox"
                      v-model="RoomTypeSeleted"
                      :value="roomType.id"
                      height="1"
                    ></v-checkbox>
                  </v-flex>
                  <v-flex md10>
                    <div @click="(roomType.id)">
                      <span>{{roomType.name}}</span>
                    </div>
                  </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
          </v-card-text>
        </v-card>
      </div>
    </v-flex>
    <v-flex md8>
      <div class="container-search">
        <div v-if="loading" class="text-center">
          <v-progress-circular :size="70" :width="7" color="blue" indeterminate></v-progress-circular>
        </div>
        <div v-else>
          <v-card v-if="noData == true" light flat tile>Không tìm thấy dữ liệu phù hợp :'(</v-card>
          <v-card v-else light flat tile>
            <v-layout class="search-item" v-for="(hotel,index) in data" :key="index" align-center>
              <v-flex xs2>
                <router-link
                  tag="a"
                  :to="{path: 'hotel/'+hotel.id, query: { place: place.replace(/\s/g,'-'), check_in : checkIn, check_out:checkOut }}"
                  target="_blank"
                >
                  <v-img :aspect-ratio="4/3" :src="hotel.image"></v-img>
                </router-link>
              </v-flex>
              <v-flex xs10>
                <router-link
                  class="pointer"
                  tag="a"
                  :to="{path: 'hotel/'+hotel.id, query: { place: place.replace(/\s/g,'-'), check_in : checkIn, check_out:checkOut }}"
                  target="_blank"
                >
                  <v-card-title>
                    <v-layout row wrap class="pa-0 ma-0">
                      <v-flex md12 class="mb-2 pb-2 border-bottom border-light">
                        <div>
                        <span class="headline">{{hotel.name}}</span>
                        <v-tooltip right>
                          <template v-slot:activator="{ on }">
                            <i
                              class="blue--text fas fa-check-circle"
                              v-on="on"
                              v-show="hotel.verified!=0"
                            ></i>
                          </template>
                          <span>đã kiểm chứng</span>
                        </v-tooltip>
                        </div>
                        <div><v-icon small color="pink">room</v-icon><span>{{hotel.short_address}}</span></div>
                      </v-flex>
                      <v-flex md5 class="caption">
                        <div>
                          <span>
                            Loại hình:&nbsp;
                            <span
                              class="body-2 font-weight-bold"
                            >{{hotel.hotel_type}}</span>
                          </span>
                        </div>
                        <div v-if="hotel.stars_num >0">
                          <v-icon class="mx-2" color="orange" small v-for="i in hotel.stars_num" :key="i">star</v-icon>
                        </div>
                        <div>Độ tuổi của trẻ em để nhận ưu đãi là dưới&nbsp;{{hotel.child_age}}&nbsp;tuổi.</div>
                        <div>E-mail:&nbsp;{{hotel.email}}</div>
                        <div>
                          Phòng giá thấp nhất&nbsp;
                          <span
                            class="body-2 font-weight-bold"
                          >{{hotel.minPrice.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</span>
                        </div>
                        <div>
                          Phòng giá cao nhất&nbsp;
                          <span
                            class="body-2 font-weight-bold"
                          >{{hotel.maxPrice.toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</span>
                        </div>
                        <div v-if="hotel.coupon_code>0">
                          <v-chip color="red" class="white--text font-weight-bold body-2 pa-1">Còn mã giảm giá</v-chip>
                        </div>
                      </v-flex>
                      <v-flex md6 class="caption border-left border-light pl-4 ml-3">
                        <div>Chính sách đặt/hủy phòng:</div>
                        <div class="pl-2">-&nbsp;{{hotel.policy.detailPolicy}}</div>
                        <div class="pl-2">- Thời gian Check-In:&nbsp;{{hotel.policy.checkin}}</div>
                        <div class="pl-2">- Thời gian Check-Out:&nbsp;{{hotel.policy.checkout}}</div>
                        <div class="pl-2" v-if="hotel.policy.refundRate >0">
                          - Hoàn tiền:&nbsp;
                          <span
                            class="body-2 font-weight-bold"
                          >{{hotel.policy.refundRate}}%</span>
                        </div>
                        <div class="pl-2" v-else>- Khách hủy đặt phòng sẽ không được hoàn tiền.</div>
                      </v-flex>
                      <v-flex class="mt-2 border-top border-light pt-2">
                        <div v-if="hotel.review_point > 0">
                          Đánh giá:&nbsp;
                          <v-chip dark color="grey"><span
                            class="body-2 font-weight-black"
                          >{{hotel.review_point}}/10</span></v-chip>
                        </div>
                        <div v-else>Chưa có đánh giá.</div>
                      </v-flex>
                    </v-layout>
                  </v-card-title>
                </router-link>
              </v-flex>
            </v-layout>
            <infinite-loading spinner="waveDots" @distance="1" @infinite="infiniteHandler">
            <!-- <infinite-loading :identifier="infiniteId" spinner="waveDots" @distance="1" @infinite="infiniteHandler"> -->

              <div slot="no-more">Bạn đã đến cuối trang...</div>
              <div slot="no-results">Không thể tìm khách sạn nào :(</div>
            </infinite-loading>
          </v-card>
        </div>
      </div>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  props: {
    login: {
      type: Object
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
      expand: [true, true],
      mn: {
        menu1: false,
        menu2: false
      },
      data: [],
      placeVal: "",
      checkInVal: "",
      checkInFormattedVal: "",
      checkOutVal: "",
      checkOutFormattedVal: "",
      arrayDistrict: [],
      arrayService: [],
      arrayHotelType: [],
      arrayRoomType: [],
      districtSeleted: [],
      serviceSeleted: [],
      hotelTypeSeleted: [],
      RoomTypeSeleted: [],
      starsSeleted: [],
      price: [10, 100000000],
      priceMin: 10,
      priceMax: 100000000,
      number: 5,
      stars: {
        1: false,
        2: false,
        3: false,
        4: false,
        5: false
      },
      arrayHotel: [],
      loading: false,
      noData: false,
      firstTime: true,
      firstLoad: true,
      page: 1,
    };
  },
  created() {
    this.placeVal = this.$route.query.place.replace(/\-/g, " ");
    this.checkInVal = this.$route.query.check_in;
    this.checkInFormattedVal = this.formatDate(this.$route.query.check_in);
    this.checkOutVal = this.$route.query.check_out;
    this.checkOutFormattedVal = this.formatDate(this.$route.query.check_out);
    this.setSearchValue();
    this.addFilter();
  },
  watch: {
    $route: "addFilter",
    placeVal: "loadSearchData",
    checkInVal: "loadSearchData",
    checkIn: "setSearchValue",
    checkOutVal: "loadSearchData",
    checkOut: "setSearchValue",
    starsSeleted: "addFilter",
    "districtSeleted": function(){
      if(this.districtSeleted == null) this.districtSeleted = [];
      this.addFilter();
    },
    serviceSeleted: "addFilter",
    hotelTypeSeleted: "addFilter",
    RoomTypeSeleted: "addFilter",
    firstTime: function(newValue, oldValue) {
      if (oldValue != newValue) {
        this.initialize();
      }
    }
  },
  methods: {
    addFilter() {
      this.page = 1;
      this.data = [];
      this.getData();
    },
    infiniteHandler: function($state) {
              this.page += 1;
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/searching",
        params: {
          place: this.placeVal,
          checkIn: this.checkIn,
          checkOut: this.checkOut,
          stars: this.starsSeleted,
          service: this.serviceSeleted,
          district: this.districtSeleted,
          hotelType: this.hotelTypeSeleted,
          price: this.price,
          roomType: this.RoomTypeSeleted,
          page: this.page
        }
      })
        .then(response => {
          this.firstTime = false;
          if (response.data.status == false) {
            this.loading = false;
            this.noData = true;
          } else {
            if (response.data.data.data.length) {
              response.data.data.data.forEach(element => {
                this.data.push(element);
              });
              $state.loaded();
            } else {
              $state.complete();
            }
          }
        })
        .catch(error => {
          this.loading = false;
          console.log(error.response);
          console.log(error);
        });
    },
    chooseStar: function(i) {
      this.stars[i] = !this.stars[i];
    },
    chooseDistrict: function(i) {
      this.arrayDistrict[i] = !this.arrayDistrict[i];
    },
    chooseService: function(i) {
      this.arrayService[i] = !this.arrayService[i];
    },
    loadSearchData: function() {
      this.$emit("loadSearchData", {
        place: this.placeVal,
        checkIn: this.checkInVal,
        checkOut: this.checkOutVal,
      });
    },
    setSearchValue: function() {
      this.placeVal = this.place;
      this.checkInVal = this.checkIn;
      this.checkOutVal = this.checkOut;
      this.checkInFormattedVal = this.formatDate(this.checkIn);
      this.checkOutFormattedVal = this.formatDate(this.checkOut);
    },
    formatDate: function(date) {
      if (!date) return null;
      return this.$moment(date).format("DD-MM-YYYY");
    },
    reSearch: function() {
      this.districtSeleted = [];
      this.arrayDistrict = [];
      this.$router.push({
        path: "searching",
        query: {
          place: this.place.replace(/\s/g, "-"),
          check_in: this.checkIn,
          check_out: this.checkOut
        }
      });
    },
    getData: function() {
      this.loading = true;
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/searching",
        params: {
          place: this.placeVal,
          checkIn: this.checkIn,
          checkOut: this.checkOut,
          stars: this.starsSeleted,
          service: this.serviceSeleted,
          district: this.districtSeleted,
          hotelType: this.hotelTypeSeleted,
          price: this.price,
          roomType: this.RoomTypeSeleted,
          page: this.page
        }
      })
        .then(response => {
          this.firstTime = false;
          if (response.data.status == false) {
            this.loading = false;
            this.noData = true;
          } else {
            this.loading = false;
            this.noData = false;
            this.data = response.data.data.data;
            this.getDisctrict();
          }
        })
        .catch(error => {
          this.loading = false;
        }).then(()=>{
          if(this.firstLoad == true){
            if(this.$moment(this.checkIn).diff(this.now,'days')>=0 && this.$moment(this.checkIn).diff(this.now,'days')<3)
              this.$emit("loadSnackbar","Bạn cần chú ý đến chính sách hủy phòng của nhà cung cấp, vì bạn đặt phòng quá cận ngày check-in. Thông thường ít nhất hơn 2 hoặc 3 ngày!");
            this.firstLoad = false;
          }
        });
    },
    initialize: function() {
      this.getService();
      this.getPrice();
      this.getHotelType();
      this.getRoomType();
    },
    getDisctrict: function() {
      axios
        .get("http://localhost:8000/api/district", {
          params: {
            province: this.placeVal
          }
        })
        .then(response => {
          if (response.data.status == false) {
            this.showDistrict = false;
          } else {
            this.arrayDistrict = response.data.data;
            this.showDistrict = true;
          }
        })
        .catch(function(error) {
          console.log(error);
          console.log(error.response);
        });
    },
    getService: function() {
      axios
        .get("http://localhost:8000/api/service", {
          params: {
            province: this.placeVal
          }
        })
        .then(response => {
          this.arrayService = response.data.data;
        })
        .catch(function(error) {
        });
    },
    getPrice: function() {},
    getHotelType: function() {
      axios
        .get("http://localhost:8000/api/hotel-type", {
          params: {
            province: this.placeVal
          }
        })
        .then(response => {
          this.arrayHotelType = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
          console.log(error.response);
        });
    },
    getRoomType: function() {
      axios
        .get("http://localhost:8000/api/room-type", {})
        .then(response => {
          this.arrayRoomType = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
          console.log(error.response);
        });
    }
  }
};
</script>

<style scoped>
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
.pointer {
  cursor: pointer;
}
.v-expansion-panel__header {
  padding: 0 !important;
  padding-top: 0 !important;
  padding-bottom: 0 !important;
  padding-right: 0 !important;
  padding-left: 0 !important;
}
.custom-checkbox {
  margin-top: 0px;
  padding-top: 0px;
}
</style>