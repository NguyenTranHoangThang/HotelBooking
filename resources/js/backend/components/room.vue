<template>
  <div>
    <v-layout class="mx-5">
      <v-spacer></v-spacer>
      <v-dialog fullscreen scrollable v-model="dialog">
        <template v-slot:activator="{ on }">
          <v-btn
            class="primary"
            round
            depressed
            flat
            v-on="on"
            @click="addNewTitle();"
          >Tạo Phòng mới</v-btn>
        </template>
        <v-layout>
          <v-flex>
            <v-card width="100%" class="pa-0">
              <v-toolbar card flat dark color="primary">
                <v-toolbar-title class="text-uppercase">
                  <span class="text-md-center white--text">{{formTitle}}</span>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon dark v-on:click="dialog =false">
                  <v-icon>close</v-icon>
                </v-btn>
              </v-toolbar>

              <v-card-text height="100px">
                <v-form ref="formNewRoom" class="px-5">
                  <h3>Thông tin chung</h3>
                  <v-layout row>
                    <v-text-field
                      v-validate="'required'"
                      :error-messages="errors.collect('roomName')"
                      data-vv-name="roomName"
                      outline
                      class="pr-3"
                      label="Tên Phòng"
                      v-model="room.room_name"
                    ></v-text-field>
                    <v-text-field
                      v-validate="'numeric|required'"
                      :error-messages="errors.collect('numberOfRoom')"
                      data-vv-name="numberOfRoom"
                      outline
                      type="number"
                      class="pl-3"
                      min="1"
                      label="Số lượng phòng"
                      v-model="room.amount"
                    ></v-text-field>
                  </v-layout>
                  <v-layout row>
                    <v-textarea
                      outline
                      v-validate="'required'"
                      :error-messages="errors.collect('description')"
                      data-vv-name="description"
                      label="Mô tả phòng"
                      v-model="room.description"
                    ></v-textarea>
                  </v-layout>
                  <v-layout row v-if="formTitle == 'New Room'">
                    <v-select
                      v-validate="'required'"
                      :error-messages="errors.collect('roomMode')"
                      data-vv-name="roomMode"
                      outline
                      class="pr-3"
                      label="Kiểu phòng"
                      v-model="defaultRoomMode"
                      :items="arrayRoomMode"
                      item-text="name"
                      item-value="id"
                      @change="chooseRoomMode"
                    ></v-select>
                    <v-select
                      v-validate="'required'"
                      :error-messages="errors.collect('roomType')"
                      data-vv-name="roomType"
                      outline
                      class="pl-3"
                      label="Loại phòng"
                      v-model="defaultRoomType"
                      :items="arrayRoomType"
                      item-text="name"
                      item-value="id"
                      :disabled="roomTypeDisable"
                    ></v-select>
                  </v-layout>
                  <v-layout row wrap v-show="showAmount">
                    <v-flex md3>
                      <!-- v-validate="'required'"
                        :error-messages="errors.collect('maxAmountAdult')"
                      data-vv-name="maxAmountAdult"-->
                      <v-text-field
                        outline
                        v-validate="''"
                        :error-messages="errors.collect('max_adult_amount')"
                        data-vv-name="max_adult_amount"
                        type="number"
                        min="1"
                        class="pr-3"
                        label="Số lượng người lớn tối đa"
                        v-model="room.max_adult_amount"
                      ></v-text-field>
                    </v-flex>
                    <v-flex md3 offset-md1>
                      <v-text-field
                        outline
                        type="number"
                        v-validate="''"
                        :error-messages="errors.collect('max_child_amount')"
                        data-vv-name="max_child_amount"
                        min="1"
                        class="pl-3"
                        label="Số lượng trẻ em tối đa"
                        v-model="room.max_child_amount"
                        @change="chooseMaxChildAmount"
                      ></v-text-field>
                    </v-flex>
                    <v-flex md3 offset-md1 v-show="showFreeAmount">
                      <v-text-field
                        outline
                        type="number"
                        v-validate="'max_value:'+room.max_child_amount"
                        :error-messages="errors.collect('free_child_amount')"
                        data-vv-name="free_child_amount"
                        min="1"
                        :max="room.max_child_amount"
                        class="pl-3"
                        label="Số lượng trẻ em được miễn phí"
                        v-model="room.free_child_amount"
                      ></v-text-field>
                    </v-flex>
                    <v-flex v-show="showFreeAmount" md1>
                      <v-tooltip close-delay="800" top light>
                        <template v-slot:activator="{ on }">
                          <v-btn flat icon v-on="on">
                            <v-icon>info</v-icon>
                          </v-btn>
                        </template>
                        <span>
                          Số lượng trẻ em được miễn phí
                          <br />
                          cho phòng này nếu dưới {{room.child_age}} tuổi
                        </span>
                      </v-tooltip>
                    </v-flex>
                  </v-layout>
                  <v-divider></v-divider>
                  <h3>Hình ảnh</h3>
                  <v-layout row wrap align-space-between justify-start>
                    <!-- <v-flex style="height : 200px;" v-if="images" md3> -->
                    <v-flex v-for="(image,i) in images" :key="i" md3>
                      <div class="img-box" v-if="image.is_primary == 1" id="primaryBox">
                        <div class="ribbon text-center">
                          <h5>PRIMARY</h5>
                        </div>
                        <div class="deleteImage">
                          <i
                            @click="deleteImage(i)"
                            class="fas fa-minus-square red--text white fa-lg"
                          ></i>
                        </div>
                        <img
                          @click="choosePrimary(i)"
                          class="primaryImage"
                          width="200px"
                          :src="image.image_link"
                          height="200px"
                        />
                      </div>
                      <div class="img-box" v-else>
                        <div class="deleteImage">
                          <i
                            @click="deleteImage(i)"
                            class="fas fa-minus-square red--text white fa-lg"
                          ></i>
                        </div>
                        <img
                          @click="choosePrimary(i)"
                          class="img"
                          width="200px"
                          :src="image.image_link"
                          height="200px"
                        />
                      </div>
                    </v-flex>
                    <v-flex md3 class="justify-center">
                      <div class="add-image">
                        <label for="file" class="fileLabel" v-ripple="{ class: `primary--text` }">
                          <v-icon color="primary">add</v-icon>
                        </label>
                        <input
                          type="file"
                          v-validate="'required|mimes:image/jpeg,image/png'"
                          :error-messages="errors.collect('image')"
                          data-vv-name="image"
                          id="file"
                          multiple
                          ref="images"
                          @change="addImage"
                        />
                        <span>{{ errors.first('image') }}</span>
                      </div>
                    </v-flex>
                  </v-layout>
                  <v-divider></v-divider>
                  <h3>Thông tin giường</h3>
                  <!-- <v-layout row>
                    <v-text-field
                      class="pr-3"
                      label="ammout"
                      type="number"
                      min="1"
                      max="10"
                      v-model="amount"
                      @change="test"
                    ></v-text-field>
                    <v-select
                      class="pl-3"
                      label="Bed Type"
                      v-model="defaultBedType"
                      :items="arrayBedType"
                      item-text="name"
                      item-value="id"
                    ></v-select>
                  </v-layout>-->
                  <v-layout row wrap v-for="(b,i) in bed" :key="i" justify-start>
                    <v-flex md-6>
                      <!-- <v-flex md-2> -->
                      <v-text-field
                        outline
                        label="Số lượng giường"
                        type="number"
                        min="1"
                        max="10"
                        v-model="b.amount"
                        @change="test"
                      ></v-text-field>
                    </v-flex>
                    <!-- <v-flex md-11 class="pl-5"> -->
                    <v-flex md-6 class="pl-5">
                      <v-select
                        outline
                        label="Loại giường"
                        v-model="b.bedTypeId"
                        :items="arrayBedType"
                        item-text="name"
                        item-value="id"
                      ></v-select>
                    </v-flex>
                    <v-flex md1>
                      <v-btn fab flat depressed v-if="i != 0" @click="deleteBedType(i)">
                        <v-icon medium color="red">delete</v-icon>
                      </v-btn>
                    </v-flex>
                  </v-layout>
                  <v-layout>
                    <v-btn round flat class="primary" @click="addBed">
                      <v-icon class="m-2">add_circle</v-icon>Thêm loại giường
                    </v-btn>
                  </v-layout>
                  <v-divider></v-divider>
                  <h3>Chi tiết</h3>
                  <v-layout row>
                    <v-flex class="no-spinners">
                      <v-text-field
                        v-validate="'decimal|min_value:1'"
                        :error-messages="errors.collect('roomSize')"
                        data-vv-name="roomSize"
                        class="pr-3"
                        type="number"
                        outline
                        suffix="m²"
                        label="Diện tích phòng"
                        v-model="room.room_size"
                      ></v-text-field>
                    </v-flex>
                    <v-flex>
                      <v-text-field
                        v-validate="'decimal|min_value:1'"
                        :error-messages="errors.collect('price')"
                        data-vv-name="price"
                        outline
                        suffix="VND"
                        class="pl-3"
                        label="Giá phòng"
                        v-model="room.price"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <!-- <v-divider></v-divider>
                  <h3>Service</h3>
                  <v-layout wrap row>
                    <v-flex xs4 v-for="(service,i) in arrayService" :key="i">
                      <v-checkbox
                        class="pl-3"
                        :label="service.name"
                        :value="service.id"
                        v-model="arrayServiceChoose"
                      ></v-checkbox>
                    </v-flex>
                  </v-layout>-->
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  v-if="formTitle != 'Sửa thông tin phòng'"
                  color="blue darken-1"
                  flat
                  round
                  depressed
                  type="submit"
                  @click="addNewRoom"
                >Create</v-btn>
                <v-btn
                  v-else
                  color="blue darken-1"
                  flat
                  round
                  depressed
                  type="submit"
                  @click="editRoom(roomId)"
                >Cập nhật</v-btn>
                <v-btn
                  color="blue darken-1"
                  flat
                  round
                  depressed
                  @click="$refs.formNewRoom.reset()"
                >Reset</v-btn>
                <v-btn color="blue darken-1" flat round depressed @click="cancel">Hủy</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-dialog>
      <v-snackbar
        v-model="snackbar"
        color="primary white--text"
        top
        right
        :timeout="snackbarTimeout"
        multi-line
      >
        <h4>{{snackbarText}}</h4>
      </v-snackbar>
    </v-layout>
    <v-layout>
      <v-container>
        <v-card class="blue-grey darken-4 p-2">
          <!-- <v-img max-height="300px" max-width="300px" src="http://localhost:8000/images/room_no_data.png"></v-img>
          <h1>Let's create your first room !!!</h1>-->

          <v-expansion-panel v-model="panelIndex" v-bind:popout="popout" expand class="elevation-0">
            <h1 class="white--text">Phòng</h1>
            <v-expansion-panel-content v-for="(el,index) in arrayRoom" :key="index" class="primary">
              <template v-slot:header>
                <div>
                  <h3>{{el.room_mode.name}}</h3>
                </div>
              </template>
              <!-- <v-card>
              <v-card-text class="grey lighten-3 black--text">-->
              <v-tabs grow slider-color="#0cbf7b">
                <v-tab v-for="(r,i) in el.room" :key="i">{{r.room_type}}</v-tab>
                <v-tabs-items>
                  <v-tab-item v-for="(r,i) in el.room" :key="i">
                    <v-container class="grey lighten-3">
                      <v-layout row>
                        <v-spacer></v-spacer>

                        <v-btn
                          class="warning"
                          flat
                          round
                          depressed
                          @click="showEdit(el.room_mode.name,r.id)"
                        >Sửa</v-btn>
                        <v-btn
                          class="red white--text"
                          flat
                          round
                          depressed
                          @click="deleteConfirm(el.room_mode.name,r.id)"
                        >Xóa</v-btn>
                      </v-layout>
                      <v-layout>
                        <v-flex md4>
                          <span height="200px" v-for="(image,index) in r.images" :key="index">
                            <v-img v-if="image.is_primary == 1" dark :src="image.image_link"></v-img>
                          </span>
                        </v-flex>
                        <v-flex md8 class="m-5 mt-2">
                          <v-layout row wrap>
                            <v-flex md12>
                              <h3>Thông tin chung</h3>
                            </v-flex>
                            <v-flex md12>
                              <v-container>
                                <v-layout row wrap>
                                  <v-flex md5>
                                    <h5>Tên phòng :</h5>
                                  </v-flex>
                                  <v-flex md7>
                                    <h4>{{r.room_name}}</h4>
                                  </v-flex>
                                  <v-flex md5>
                                    <h5>Giá phòng :</h5>
                                  </v-flex>
                                  <v-flex md7>
                                    <span
                                      class="body-2 orange--text"
                                    >{{(r.price).toLocaleString('vi', {style: 'currency',currency: 'VND',})}}</span>
                                  </v-flex>
                                  <v-flex md5>
                                    <h5>Mô tả phòng :</h5>
                                  </v-flex>
                                  <v-flex md7>{{r.description}}</v-flex>

                                  <v-flex md5>
                                    <h5>Diện tích phòng :</h5>
                                  </v-flex>
                                  <v-flex md7>{{r.room_size}} m²</v-flex>
                                  <v-flex md5>
                                    <h5>Sức chứa :</h5>
                                  </v-flex>
                                  <v-flex md7 align-items-center>
                                    {{r.max_adult_amount}} x
                                    <i class="fas fa-male fa-2x"></i>
                                    <span v-if="r.max_child_amount!=0 && r.child_age!=0">
                                    {{r.max_child_amount}} x
                                    <i
                                      class="fas fa-child"
                                    ></i>
                                    
                                      ({{r.free_child_amount}} trẻ em dưới {{r.child_age}} tuổi sẽ được ở
                                      <span
                                        class="green--text"
                                      >Miễn phí !!!</span>)
                                    </span>
                                  </v-flex>
                                  <v-flex md5>
                                    <h5>Số lượng phòng</h5>
                                  </v-flex>
                                  <v-flex md7>{{r.amount}}</v-flex>
                                  <v-flex md5></v-flex>
                                </v-layout>
                              </v-container>
                              <!-- <h5>{{r.room_name}}</h5></v-flex> -->
                            </v-flex>
                          </v-layout>
                          <v-layout row wrap>
                            <v-flex md12>
                              <h3>Dịch vụ</h3>
                            </v-flex>
                            <v-flex md12>
                              <v-container class="pa-0">
                                <!-- {{r.service}} -->
                                <v-layout row wrap>
                                  <v-flex md4 v-for="(s,index) in r.service" :key="index">
                                    <i class="mx-3 fas" :class="icon = 'fa-'+s.icon"></i>
                                    <b>{{s.name}}</b>
                                  </v-flex>
                                </v-layout>
                              </v-container>
                            </v-flex>
                          </v-layout>
                          <v-layout row wrap>
                            <v-flex md12>
                              <h3>Cơ sở vật chất</h3>
                            </v-flex>
                            <v-flex md12>
                              <v-container class="pa-0">
                                <!-- {{r.feature}} -->
                                <v-layout row wrap>
                                  <v-flex md4 v-for="(f,index) in r.feature" :key="index">
                                    <!-- <i class="mx-3 fas" :class="icon = 'fa-'+s.icon"></i> -->
                                    <b class="mx-3">{{f.name}}</b>
                                  </v-flex>
                                </v-layout>
                              </v-container>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-tab-item>
                </v-tabs-items>
              </v-tabs>
            </v-expansion-panel-content>
          </v-expansion-panel>
          <v-dialog max-width="40%" v-model="confirmDialog">
            <v-card>
              <v-card-title>
                <h5>Xác nhận</h5>
              </v-card-title>
              <v-card-text v-if="bookingListCantDelete == null">{{confirmDialogText}}</v-card-text>
              <v-card-text v-else>
                Bạn không thể xóa phòng này vì :
                <ul>
                  <li v-for="(booking,i) in bookingListCantDelete.sau" :key="i">
                    {{booking.reason}} -- Đơn # {{booking.id}}
                    <v-btn small depressed round @click="detailOrder(booking.id)">Chi tiết</v-btn>
                  </li>
                  <li v-for="(booking,i) in bookingListCantDelete.giua" :key="i">
                    {{booking.reason}} -- Đơn # {{booking.id}}
                    <v-btn small depressed round @click="detailOrder(booking.id)">Chi tiết</v-btn>
                  </li>
                </ul>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="grey" flat round depressed @click="cancel">Hủy</v-btn>
                <v-btn color="red" flat round depressed @click.stop="deleteRoom()">Xác nhận</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-card>
      </v-container>
    </v-layout>
  </div>
</template>
<script>
export default {
  props: {
    // hotelId: {
    //   type: Number
    // },
    arrayHotel: {
      type: Array
    },
    arrayService: {
      type: Array
    },
    api_token: {
      type: String
    }
  },
  watch: {
    panelIndex: function() {
      // console.log(this.panelIndex);
    }
    // hotelId: function() {
    //   this.hotelId = localStorage.getItem("hotelId");
    // }
  },
  data: function() {
    return {
      popout: false,
      panelIndex: -1,
      dialog: false,
      formTitle: "",
      showAmount: false,
      showFreeAmount: false,
      room: {
        room_name: "",
        amount: 1,
        description: "",
        max_adult_amount: 1,
        max_child_amount: 0,
        free_child_amount: 0,
        child_age: 13,
        room_size: 1,
        price: 1
      },
      bed: [
        {
          amount: 1,
          bedTypeId: 1
        }
      ],
      arrayRoom: [],
      arrayRoomMode: [],
      defaultRoomMode: 1,
      arrayRoomType: [],
      defaultRoomType: 1,
      arrayServiceChoose: [],
      roomTypeDisable: true,
      arrayBedType: [],
      defaultBedType: 1,
      amount: 1,
      total: 1,
      confirmDialog: false,
      confirmDialogText: "",
      bookingListCantDelete: null,
      dictionary: {
        custom: {
          roomName: {
            required: "Tên phòng không được để trống"
          },
          numberOfRoom: {
            numeric: "Số phòng không được bé hơn 1"
          },
          description: {
            required: "Mô tả phòng không được để trống"
          },
          roomSize: {
            decimal: "Kích thước phòng ko hợp lệ"
          },
          price: {
            decimal: "Giá phòng ko hợp lệ"
          },
          free_child_amount: {
            max_value:
              "số trẻ em được miễn phí phải bé hơn hoặc bằng số trẻ em tối đa"
          }
        }
      },
      confirmDialog: false,
      roomIndex: 0,
      roomId: 0,
      roomType: 0,
      hotelId: this.$route.query.hotelId,
      images: [],
      primaryImage: 0,
      snackbar: false,
      snackbarText: "hihi",
      snackbarTimeout: 5000,
      roomModeWorkingOn: ""
    };
  },
  created() {
    // if (this.$route.query.hotelId == 0) {
    //   this.$router.push({ name: "home" });
    //   this.$emit("onErrorMessage", "vui long chon khach san");
    // } else {
    //   this.$emit("chooseHotel", this.hotelId);
    //   this.initialize();
    // }
    this.initialize();
  },
  watch: {
    room: function() {
      if (this.room.free_child_amount > this.room.max_child_amount) {
        this.room.free_child_amount = this.room.max_child_amount;
      }
    }
  },
  mounted() {
    this.$validator.localize("en", this.dictionary);
  },
  methods: {
    chooseRoomMode: function() {
      this.roomTypeDisable = false;
      for (var i = 0; i < this.arrayRoomMode.length; i++) {
        if (this.defaultRoomMode == this.arrayRoomMode[i].id) {
          this.roomModeWorkingOn = this.arrayRoomMode[i].name;
        }
      }
      axios
        .get("http://localhost:8000/api/manager/room-type", {
          params: {
            hotelId: this.hotelId,
            roomModeId: this.defaultRoomMode
          },
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          this.arrayRoomType = response.data.data;
          console.log(response);
        })
        .catch(function(error) {
          console.log(error.response);
        });
      this.showAmount = false;
      if (this.defaultRoomMode == 1) {
        this.room.max_adult_amount = 1;
      } else if (this.defaultRoomMode == 2) {
        this.room.max_adult_amount = 2;
      } else {
        this.room.max_adult_amount = 2;
        this.showAmount = true;
      }
    },
    addImage(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[files.length - 1]);
    },
    createImage(file) {
      let reader = new FileReader();
      var _this = this;
      reader.onload = e => {
        var temp = {};
        temp.image_link = e.target.result;
        // console.log(_this.images.length);
        if (_this.images.length == 0) {
          temp.is_primary = 1;
          this.primaryImage = 1;
        } else {
          temp.is_primary = 0;
        }
        temp.id = -1;
        _this.images.push(temp);
      };
      reader.readAsDataURL(file);
      // console.log(this.primaryImage);
    },
    choosePrimary(index) {
      for (var i = 0; i < this.images.length; i++) {
        this.images[i].is_primary = 0;
        if (i == index) {
          this.images[i].is_primary = 1;
        }
      }
      console.log(index);
      console.log(this.images);
    },
    deleteImage: function(i) {
      this.images.splice(i, 1);
    },
    chooseMaxChildAmount: function() {
      this.showFreeAmount = false;
      this.room.free_child_amount = this.room.max_child_amount;
      if (this.room.max_child_amount != 0) {
        this.showFreeAmount = true;
      }
    },
    test: function() {
      console.log(this.amount);
    },
    addNewTitle: function() {
      this.formTitle = "New Room";
      this.$refs.formNewRoom.reset();
      this.images = [];
      this.showAmount = false;
      if (this.defaultRoomMode == 1) {
        this.room.max_adult_amount = 1;
      } else if (this.defaultRoomMode == 2) {
        this.room.max_adult_amount = 2;
      } else {
        this.room.max_adult_amount = 2;
        this.showAmount = true;
      }
    },
    initialize: function() {
      this.chooseRoomMode();
      axios
        .get("http://localhost:8000/api/manager/room", {
          params: {
            hotelId: this.hotelId
          },
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          console.log(response.data);
          if (response.data.status == true) {
            this.arrayRoom = response.data.data;
          }
        })
        .catch(function(error) {
          console.log(error.response);
        });
      axios
        .get("http://localhost:8000/api/manager/room-mode", {
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          this.arrayRoomMode = response.data;
          console.log(this.arrayRoomMode);
        })
        .catch(function(error) {
          console.log(error.response);
        });

      axios
        .get("http://localhost:8000/api/manager/bed-type", {
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          this.arrayBedType = response.data;
        })
        .catch(function(error) {
          console.log(error.response);
        });
    },
    addBed: function() {
      var newBed = {
        amount: 1,
        bedTypeId: 1
      };
      this.bed.push(newBed);
    },
    deleteBedType: function(index) {
      this.bed.splice(index, 1);
    },
    addNewRoom: function() {
      console.log(this.room);
      var _this = this;
      this.$validator
        .validate()
        .then(valid => {
          if (!valid) {
          } else {
            axios({
              method: "post",
              url: "http://localhost:8000/api/manager/room",
              data: {
                hotel_id: this.hotelId,
                room_name: this.room.room_name,
                description: this.room.description,
                price: this.room.price,
                amount: this.room.amount,
                max_adult_amount: this.room.max_adult_amount,
                max_child_amount: this.room.max_child_amount,
                free_child_amount: this.room.free_child_amount,
                child_age: this.room.child_age,
                room_size: this.room.room_size,
                room_mode_id: this.defaultRoomMode,
                room_type_id: this.defaultRoomType,
                bed: this.bed,
                images: this.images
              },
              headers: {
                Authorization: "Bearer " + this.api_token
              }
            })
              .then(response => {
                console.log(response.data);
                if (response.data.status == true) {
                  this.dialog = false;
                  this.snackbarText = response.data.message;
                  this.snackbar = true;
                  var exist = false;
                  var room = response.data.room;
                  console.log(room);
                  this.arrayRoom.forEach(element => {
                    if (element.room_mode.name == room.room_mode) {
                      exist = true;
                    }
                  });
                  if (exist == false) {
                    var object = {
                      room_mode: {
                        id: room.room_mode_id,
                        name: room.room_mode
                      },
                      room: []
                    };
                    this.arrayRoom.push(object);
                  }
                  this.arrayRoom.forEach(element => {
                    console.log(element);
                    if (element.room_mode.name == room.room_mode) {
                      element.room.push(room);
                    }
                  });
                }
              })
              .catch(error => {
                console.log(error.response);
              });
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    deleteConfirm: function(roomMode, roomId) {
      this.confirmDialog = true;
      this.roomId = roomId;
      this.roomModeWorkingOn = roomMode;
      this.confirmDialogText = "Bạn có muốn xóa phòng này " + "?";
    },
    cancel: function() {
      this.dialog = false;
      this.confirmDialog = false;
    },
    deleteRoom: function() {
      // console.log("xoa" + this.roomId);
      axios({
        method: "delete",
        url: "http://localhost:8000/api/manager/room/" + this.roomId,
        data: {
          hotelId: this.hotelId
        },
        headers: {
          Authorization: "Bearer " + this.api_token
        }
      })
        .then(response => {
          console.log(response.data)
          if (response.data.status == false) {
            if (response.data.booking_list != null) {
              this.confirmDialog = true;
              this.bookingListCantDelete = response.data.booking_list;
              console.log(response.data.booking_list);
            }
          } else {
            this.arrayRoom.forEach(element => {
              for (var i = 0; i < element.room.length; i++) {
                if (element.room[i].id == this.roomId) {
                  element.room.splice(i, 1);
                }
              }
            });
            for (var i = 0; i < this.arrayRoom.length; i++) {
              console.log(this.arrayRoom[i].room.length);
              if (this.arrayRoom[i].room.length == 0) {
                this.arrayRoom.splice(i, 1);
              }
            }
            this.confirmDialog = false;
          }
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    showEdit: function(roomMode, roomId) {
      console.log(this.arrayRoom);
      this.roomModeWorkingOn = roomMode;
      this.roomId = roomId;
      axios
        .get("http://localhost:8000/api/manager/room/" + roomId, {
          params: {
            hotelId: this.hotelId
          },
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          this.dialog = true;
          this.formTitle = "Sửa thông tin phòng";
          if (response.data.status == true) {
            var room = response.data.data;
            this.room.room_name = room.room_name;
            this.room.amount = room.amount;
            this.room.description = room.description;
            this.room.max_adult_amount = room.max_adult_amount;
            this.room.max_child_amount = room.max_child_amount;
            this.room.child_age = room.child_age;
            this.room.free_child_amount = room.free_child_amount;
            if (this.room.max_child_amount != 0) {
              this.showAmount = true;
              this.showFreeAmount = true;
            } else {
              this.showAmount = false;
              this.showFreeAmount = false;
            }

            this.room.room_size = room.room_size;
            this.room.price = room.price;
            this.defaultRoomMode = room.room_mode_id;
            this.defaultRoomType = room.room_type_id;
            this.images = room.images;
            console.log(this.images);
            // if (response.data.bed.length > 0) {
            //   this.bed = response.data.bed;
            // }
            this.bed = response.data.bed;
          }
        })
        .catch(function(error) {
          console.log(error.response);
          this.dialog = false;
          this.snackbarText = response.data.message;
          this.snackbar = true;
        });
    },
    editRoom: function(roomId) {
      console.log(this.images);
      axios({
        method: "put",
        url: "http://localhost:8000/api/manager/room/" + roomId,
        data: {
          hotel_id: this.hotelId,
          room_name: this.room.room_name,
          description: this.room.description,
          price: this.room.price,
          amount: this.room.amount,
          max_adult_amount: this.room.max_adult_amount,
          max_child_amount: this.room.max_child_amount,
          free_child_amount: this.room.free_child_amount,
          room_size: this.room.room_size,
          room_mode_id: this.defaultRoomMode,
          room_type_id: this.defaultRoomType,
          bed: this.bed,
          images: this.images
        },
        headers: {
          Authorization: "Bearer " + this.api_token
        }
      })
        .then(response => {
          console.log(response);
          if (response.data.status == true) {
            this.dialog = false;
            this.snackbarText = response.data.message;
            this.snackbar = true;
            this.arrayRoom.forEach(element => {
              for (let i = 0; i < element.room.length; i++) {
                if (element.room[i].id == response.data.room.id) {
                  var room = response.data.room;
                  element.room[i] = room;
                  this.images = room.images;
                }
              }
            });
          }
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    detailOrder: function(id) {
      this.$router.push({
        name: "order",
        query: { hotelId: this.hotelId, orderId: id }
      });
    }
  }
};
</script>
<style scoped>
.primaryImage {
  border: 4px solid teal;
  padding: 2px;
}
#primaryBox {
  box-sizing: border-box;
  overflow: hidden;
}
.img-box {
  position: relative;
  height: 200px;
  margin-bottom: 10px;
}
.img-box > .img {
  border: 1px solid black;
}
.ribbon {
  position: absolute;
  background-color: teal;
  height: 20px;
  padding: auto;
  width: 200px;
  top: 6px;
  left: 0px;
  color: white;
}
.deleteImage {
  position: absolute;
  padding: auto;
  top: 6px;
  left: 180px;
  color: white;
}
#file {
  display: none;
}
.fileLabel {
  padding-top: 2px;
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  margin-top: 75px;
  margin-left: 40%;
  border-radius: 50px;
}
.add-image {
  height: 200px;
  width: 200px;
  border: 4px dashed teal;
}
.img {
  height: 200px;
  width: 200px;
  margin-bottom: 20px;
}
</style>



