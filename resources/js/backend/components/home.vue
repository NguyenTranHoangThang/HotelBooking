<template>
  <div>
    <!-- {{booking}} -->
    <v-layout justify-space-around>
      <v-card flat width="23%" class="primary white--text" height="150px">
        <v-card-title>
          <v-layout align-center>
            <v-flex md2>
              <v-avatar color="teal" size="40px">
                <v-icon class="m-2" color="white">event_note</v-icon>
              </v-avatar>
            </v-flex>
            <v-flex md9 offset-md1>
              <div class="title ml-1">Tổng số đơn trong tháng</div>
            </v-flex>
          </v-layout>
        </v-card-title>
        <v-divider class="pa-0 ma-0 my-2"></v-divider>
        <v-card-text class="text-md-center">
          <span class="headline">{{numberOfAllBooking}}</span>
        </v-card-text>
      </v-card>
      <v-card flat width="23%" class="green accent-4 white--text" height="150px">
        <v-card-title>
          <!-- <v-icon class="m-2">airline_seat_individual_suite</v-icon> -->
          <!-- <h5>Số đơn mới</h5> -->
          <v-layout align-center>
            <v-flex md2>
              <v-avatar color="green darken-2" size="40px">
                <v-icon class="m-2" color="white">airline_seat_individual_suite</v-icon>
              </v-avatar>
            </v-flex>
            <v-flex md9 offset-md1>
              <div class="title ml-1">Số đơn mới</div>
            </v-flex>
          </v-layout>
        </v-card-title>
        <v-divider class="pa-0 ma-0 my-2"></v-divider>
        <v-card-text class="text-md-center">
          <span class="headline">{{numberOfNewBooking}}</span>
        </v-card-text>
      </v-card>
      <v-card flat width="23%" class="brown white--text" height="150px">
        <v-card-title>
          <!-- <v-icon class="m-2">loyalty</v-icon>
          <h5>Khuyến mãi đang diễn ra</h5>-->
          <v-layout align-center>
            <v-flex md2>
              <v-avatar color="brown darken-2" size="40px">
                <v-icon class="m-2" color="white">loyalty</v-icon>
              </v-avatar>
            </v-flex>
            <v-flex md9 offset-md1>
              <div class="title ml-1">Khuyến mãi đang diễn ra</div>
            </v-flex>
          </v-layout>
        </v-card-title>
        <v-divider class="pa-0 ma-0 my-2"></v-divider>
        <v-card-text class="text-md-center">
          <span class="headline">{{numberOfCouponCode}}</span>
        </v-card-text>
      </v-card>
      <v-card flat width="23%" class="purple white--text" height="150px">
        <v-card-title>
          <!-- <v-icon class="m-2">stars</v-icon>
          <h5>Câu hỏi mới</h5> -->
          <v-layout align-center>
            <v-flex md2>
              <v-avatar color="purple darken-2" size="40px">
                <v-icon class="m-2" color="white">loyalty</v-icon>
              </v-avatar>
            </v-flex>
            <v-flex md9 offset-md1>
              <div class="title ml-1">Câu hỏi mới</div>
            </v-flex>
          </v-layout>
        </v-card-title>
        <v-divider class="pa-0 ma-0 my-2"></v-divider>
        <v-card-text class="text-md-center">
          <span class="headline">{{numberOfNewQuestion}}</span>
        </v-card-text>
      </v-card>
    </v-layout>
    <v-alert v-show="errorMessage" type="error" :value="true">{{errorMessage}}</v-alert>
    <v-layout class="m-5">
      <v-spacer></v-spacer>
      <v-dialog fullscreen scrollable v-model="dialog">
        <v-layout row>
          <v-flex>
            <v-card width="100%" class="pa-0">
              <v-toolbar card flat color="primary">
                <v-toolbar-title class="text-uppercase">
                  <span class="text-md-center white--text">{{formTitle}}</span>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon dark v-on:click="dialog =false">
                  <v-icon>close</v-icon>
                </v-btn>
              </v-toolbar>
              <!-- <v-card-title class="text-xs-center px-5">
                <h1>{{formTitle}}</h1>
              </v-card-title>-->
              <v-card-text height="100px">
                <v-form ref="formNewHotel" class="px-5">
                  <h3 class="my-5">Thông tin chung</h3>
                  <v-layout row>
                    <v-text-field
                      v-validate="'required'"
                      :error-messages="errors.collect('hotelName')"
                      data-vv-name="hotelName"
                      class="pr-3"
                      label="Tên khách sạn"
                      v-model="newHotelData.name"
                      required
                      outline
                    ></v-text-field>
                    <v-select
                      v-validate="'required'"
                      :error-messages="errors.collect('hotelType')"
                      data-vv-name="hotelType"
                      class="pl-3"
                      item-text="name"
                      item-value="id"
                      :items="arrayHotelType"
                      label="Loại khách sạn"
                      v-model="defaultHotelType"
                      required
                      outline
                    ></v-select>
                  </v-layout>
                  <v-layout row align-center>
                    <b>Tiêu chuẩn khách sạn :</b>
                    <v-rating
                      v-model="newHotelData.rating"
                      hover
                      background-color="orange lighten-3"
                      color="orange"
                    ></v-rating>
                  </v-layout>
                  <v-layout row>
                    <v-textarea
                      v-validate="'required'"
                      :error-messages="errors.collect('description')"
                      data-vv-name="description"
                      label="Mô tả khách sạn"
                      v-model="newHotelData.description"
                      required
                      outline
                    ></v-textarea>
                  </v-layout>
                  <v-layout row>
                    <v-select
                      v-validate="'required'"
                      :error-messages="errors.collect('province')"
                      data-vv-name="province"
                      item-text="name"
                      :items="arrayProvince"
                      label="Tỉnh/Thành phố"
                      item-value="id"
                      v-model="defaultProvince"
                      @change="provinceSelected"
                      required
                      outline
                    ></v-select>
                    <v-select
                      v-validate="'required'"
                      :error-messages="errors.collect('district')"
                      data-vv-name="district"
                      class="px-5"
                      :items="arrayDistrict"
                      item-text="name"
                      item-value="id"
                      label="Quận/Huyện"
                      :disabled="districtDisabled"
                      v-model="defaultDistrict"
                      @change="districtSelected"
                      required
                      outline
                    ></v-select>
                    <v-select
                      v-validate="'required'"
                      :error-messages="errors.collect('ward')"
                      data-vv-name="ward"
                      :items="arrayWard"
                      item-text="name"
                      item-value="id"
                      label="Xã/Phường"
                      v-model="defaultWard"
                      :disabled="wardDisabled"
                      required
                      outline
                    ></v-select>
                  </v-layout>
                  <v-layout row>
                    <v-text-field
                      label="Địa chỉ"
                      v-validate="'required'"
                      class="pr-3"
                      :error-messages="errors.collect('address')"
                      data-vv-name="address"
                      v-model="newHotelData.address"
                      required
                      outline
                    ></v-text-field>
                    <v-text-field
                      label="Số thẻ tín dụng"
                      v-validate="'required'"
                      class="pl-3"
                      :error-messages="errors.collect('credit_card')"
                      data-vv-name="credit_card"
                      v-model="newHotelData.credit_card"
                      mask="credit-card"
                      required
                      outline
                    ></v-text-field>
                  </v-layout>
                  <v-layout row>
                    <v-text-field
                      label="Số điện thoại"
                      v-validate="'required'"
                      class="pr-3"
                      :error-messages="errors.collect('phone')"
                      data-vv-name="phone"
                      v-model="newHotelData.phone"
                      required
                      outline
                      mask="##########"
                    ></v-text-field>
                    <v-text-field
                      label="Chi nhánh ngân hàng"
                      v-validate="''"
                      class="pl-3"
                      :error-messages="errors.collect('bank')"
                      data-vv-name="bank"
                      v-model="newHotelData.bank"
                      outline
                    ></v-text-field>
                  </v-layout>
                  <v-layout row>
                    <v-text-field
                      label="Mã số thuế"
                      class="pr-3"
                      v-validate="'required'"
                      :error-messages="errors.collect('tax_code')"
                      data-vv-name="tax_code"
                      v-model="newHotelData.tax_code"
                      required
                      outline
                    ></v-text-field>
                    <v-text-field
                      label="Email"
                      class="pl-3"
                      v-validate="'required|email'"
                      :error-messages="errors.collect('email')"
                      data-vv-name="email"
                      v-model="newHotelData.email"
                      required
                      outline
                    ></v-text-field>
                  </v-layout>
                  <v-divider></v-divider>
                  <h3 class="my-5">Hình ảnh</h3>
                  <v-layout row wrap align-space-between justify-start>
                    <!-- <v-flex style="height : 200px;" v-if="images" md3> -->
                    <v-flex v-for="(image,i) in newHotelData.images" :key="i" md3>
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
                        <input type="file" id="file" multiple ref="images" @change="addImage" />
                      </div>
                    </v-flex>
                  </v-layout>
                  <v-divider></v-divider>
                  <h3 class="my-5">Chính sách</h3>
                  <v-layout row>
                    <v-flex offset-md1 md3>
                      <v-menu
                        ref="checkin"
                        v-model="menuCheckin"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="newHotelData.policy.checkin"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        max-width="290px"
                        min-width="290px"
                      >
                        <template v-slot:activator="{ on }">
                          <v-text-field
                            v-model="newHotelData.policy.checkin"
                            label="Check-in"
                            prepend-icon="access_time"
                            readonly
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-time-picker
                          v-if="menuCheckin"
                          v-model="newHotelData.policy.checkin"
                          full-width
                          value="24hr hh:mm"
                          format="24hr"
                          @click:minute="$refs.checkin.save(newHotelData.policy.checkin)"
                        ></v-time-picker>
                      </v-menu>
                    </v-flex>
                    <v-flex md3>
                      <v-menu
                        ref="checkout"
                        v-model="menuCheckout"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="newHotelData.policy.checkout"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        max-width="290px"
                        min-width="290px"
                      >
                        <template v-slot:activator="{ on }">
                          <v-text-field
                            v-model="newHotelData.policy.checkout"
                            label="Check-out"
                            prepend-icon="access_time"
                            readonly
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-time-picker
                          v-if="menuCheckout"
                          v-model="newHotelData.policy.checkout"
                          full-width
                          value="24hr hh:mm"
                          format="24hr"
                          @click:minute="$refs.checkout.save(newHotelData.policy.checkout)"
                        ></v-time-picker>
                      </v-menu>
                    </v-flex>
                  </v-layout>

                  <v-layout row>
                    <v-flex offset-md1 md3>
                      <v-checkbox
                        label="Có thể hủy phòng ?"
                        v-model="newHotelData.policy.cancelable"
                        color="primary"
                      ></v-checkbox>
                    </v-flex>
                  </v-layout>
                  <v-layout>
                    <v-flex md3 offset-md1>
                      <v-text-field
                        suffix="ngày"
                        v-if="newHotelData.policy.cancelable == true"
                        label="có thể hủy trước"
                        v-model="newHotelData.policy.cancel_day"
                        mask="###"
                      ></v-text-field>
                    </v-flex>
                    <v-flex md3 offset-md1>
                      <v-text-field
                        suffix="%"
                        v-if="newHotelData.policy.cancelable == true"
                        label="% hoàn trả"
                        v-model="newHotelData.policy.refundRate"
                        mask="##"
                      ></v-text-field>
                    </v-flex>
                    <v-flex md3 offset-md1>
                      <v-select
                        outline
                        v-validate="'required'"
                        :error-messages="errors.collect('child_age')"
                        data-vv-name="child_age"
                        label="Độ tuổi xem là trẻ em"
                        :items="arrayAge"
                        item-text="text"
                        item-value="value"
                        v-model="newHotelData.child_age"
                      ></v-select>
                    </v-flex>
                  </v-layout>
                  <v-layout row justify-center align-center>
                    <v-flex offset-md1 md3>
                      <h5>Các hình thức thanh toán chấp nhận</h5>
                    </v-flex>
                    <v-flex offset-md1 md3>
                      <v-checkbox v-model="newHotelData.policy.offline" label="Thanh toán offline"></v-checkbox>
                    </v-flex>
                    <v-flex md3>
                      <v-checkbox v-model="newHotelData.policy.online" label="Thanh toán online"></v-checkbox>
                    </v-flex>
                  </v-layout>
                  <v-layout row justify-center align-center>
                    <v-flex offset-md1 md11>
                      <v-textarea
                        label="Chính sách chi tiết"
                        v-model="newHotelData.policy.detailPolicy"
                        auto-grow
                        box
                      ></v-textarea>
                    </v-flex>
                  </v-layout>
                </v-form>
              </v-card-text>
              <v-card-actions class="mt-5">
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="updateHotel" type="submit">Sửa</v-btn>
                <v-btn color="blue darken-1" flat @click="reset">Reset</v-btn>
                <v-btn color="blue darken-1" flat @click="cancel">Hủy</v-btn>
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
    <v-dialog max-width="40%" v-model="confirmDialog">
      <v-card>
        <v-card-title>
          <h5>Xác nhận</h5>
        </v-card-title>
        <v-card-text v-if="bookingListCantDelete == null">{{confirmDialogText}}</v-card-text>
        <v-card-text v-else>
          Bạn không thể xóa khách sạn này vì :
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
          <v-btn color="red" flat round depressed @click.stop="deleteHotelConfirm()">Xác nhận</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-layout class="mt-5">
      <v-card width="100%">
        <v-card-title>
          <h1 class="ml-3">{{hotel.name}}</h1>
          <v-spacer></v-spacer>
          <v-btn class="warning" flat round depressed @click="editHotel(hotel.id)">Sửa</v-btn>
          <!-- <v-btn @click="editHotel(hotel.id)">Sửa</v-btn> -->
          <v-btn class="red white--text" flat round depressed @click="deleteHotel(hotel.id)">Xóa</v-btn>
        </v-card-title>
        <v-layout row wrap class="pa-2 ma-0">
          <v-flex md4 class="pa-1" v-for="(image,index) in hotel.images" :key="index">
            <v-img :aspect-ratio="1" style="height : auto" :src="image.image_link"></v-img>
          </v-flex>
        </v-layout>
        <v-card-text>
          <v-container>
            <h4>Thông tin chung</h4>
            <v-layout row>
              <v-flex xs3 offset-xs1>
                <h5>Loại khách sạn</h5>
              </v-flex>
              <v-flex xs8>{{hotel.hotel_type}}</v-flex>
            </v-layout>
            <v-layout row>
              <v-flex xs3 offset-xs1>
                <h5>Tiêu chẩn</h5>
              </v-flex>
              <v-flex xs8>
                <span v-for="i in hotel.stars_num" :key="i">
                  <v-icon color="yellow">star</v-icon>
                </span>
              </v-flex>
            </v-layout>
            <v-layout row>
              <v-flex xs3 offset-xs1>
                <h5>Địa chỉ</h5>
              </v-flex>
              <v-flex xs8>{{hotel.address}}</v-flex>
            </v-layout>
            <v-layout row>
              <v-flex xs3 offset-xs1>
                <h5>Mô tả</h5>
              </v-flex>
              <v-flex xs8>{{hotel.description}}</v-flex>
            </v-layout>
            <v-layout row>
              <v-flex xs3 offset-xs1>
                <h5>Mã số thuế</h5>
              </v-flex>
              <v-flex xs8>{{hotel.tax_code}}</v-flex>
            </v-layout>

            <v-layout row v-if="hotel.bank != null">
              <v-flex xs3 offset-xs1>
                <h5>Chi nhánh ngân hàng</h5>
              </v-flex>
              <v-flex xs8>{{hotel.bank}}</v-flex>
            </v-layout>
            <v-layout row>
              <v-flex xs3 offset-xs1>
                <h5>Email</h5>
              </v-flex>
              <v-flex xs8>{{hotel.email}}</v-flex>
            </v-layout>
            <v-layout row>
              <v-flex xs3 offset-xs1>
                <h5>Số điện thoại</h5>
              </v-flex>
              <v-flex xs8>{{hotel.phone}}</v-flex>
            </v-layout>
            <h4>Chính sách</h4>
            <v-layout row>
              <v-flex xs3 offset-xs1>
                <h5>Check-in</h5>
              </v-flex>
              <v-flex xs2>
                <h5>{{hotel.policy.checkin}}</h5>
              </v-flex>
              <v-flex xs3 offset-xs1>
                <h5>Check-out</h5>
              </v-flex>
              <v-flex xs2>
                <h5>{{hotel.policy.checkout}}</h5>
              </v-flex>
            </v-layout>
            <v-layout row wrap align-center>
              <v-flex xs3 offset-xs1>
                <h5>Hình thức thanh toán</h5>
              </v-flex>
              <v-flex xs2>
                <v-checkbox v-model="hotel.policy.offline" disabled readonly label="Offline"></v-checkbox>
              </v-flex>
              <v-flex xs2>
                <v-checkbox v-model="hotel.policy.online" disabled readonly label="Online"></v-checkbox>
              </v-flex>
            </v-layout>
            <v-layout row v-if="hotel.policy.cancelable == true">
              <v-flex xs3 offset-xs1>
                <h5>Có thể hủy phòng trước</h5>
              </v-flex>
              <v-flex xs8>
                <h5>{{hotel.policy.cancel_day}} ngày và hoàn trả {{hotel.policy.refundRate}} % giá trị đơn đặt phòng</h5>
              </v-flex>
            </v-layout>
            <v-layout row justify-center align-center>
              <v-flex xs3 offset-xs1>
                <h5>Chính sách Chi tiết</h5>
              </v-flex>
              <v-flex xs8>
                <v-textarea readonly flat v-model="hotel.policy.detailPolicy"></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-layout>
  </div>
</template>
<script>
import { constants } from 'crypto';
export default {
  $_veeValidate: {
    validator: "new"
  },
  props: {
    arrayProvince: {
      type: Array
    },
    arrayHotel: {
      type: Array
    },
    arrayService: {
      type: Array
    },
    api_token: {
      type: String
    },
    errorMessage: {
      type: String
    }
  },
  data: function() {
    return {
      newHotelData: {
        name: "",
        email: "",
        description: "",
        rating: 3,
        district: "",
        address: "",
        credit_card: "",
        phone: "",
        tax_code: "",
        bank: "",
        images: [],
        policy: {
          checkin: "",
          checkout: "",
          cancelable: false,
          cancel_day: 0,
          refundRate: 0,
          detailPolicy: "",
          offline: false,
          online: false
        },
        child_age: ""
      },
      hotel: {
        // policy: {
        //   checkin: null,
        //   checkout: null,
        //   cancel_day: 0,
        //   refundRate: 0,
        //   detailPolicy: "",
        //   cancelable: false,
        //   online: false,
        //   offline: false
        // }
        name: "",
        email: "",
        description: "",
        rating: 3,
        district: "",
        address: "",
        credit_card: "",
        phone: "",
        tax_code: "",
        bank: "",
        images: [],
        policy: {
          checkin: "",
          checkout: "",
          cancelable: false,
          cancel_day: 0,
          refundRate: 0,
          detailPolicy: ""
        },
        child_age: ""
      },
      menuCheckin: false,
      menuCheckout: false,
      defaultHotelType: 1,
      defaultProvince: 1,
      defaultDistrict: 1,
      defaultWard: 1,
      userID: 1,
      arrayDistrict: [],
      arrayHotelType: [],
      arrayWard: [],
      formTitle: "",
      districtDisabled: true,
      wardDisabled: true,
      snackbar: false,
      snackbarText: "hihi",
      snackbarTimeout: 5000,
      dialog: false,
      hotel_stars_num: 2,
      confirmDialog: false,
      selectedId: 0,
      confirmDialogText: "",
      firstTime: true,
      primaryImage: 0,
      arrayAge: [
        { text: "<1", value: 0 },
        { text: "1", value: 1 },
        { text: "2", value: 2 },
        { text: "3", value: 3 },
        { text: "4", value: 4 },
        { text: "5", value: 5 },
        { text: "6", value: 6 },
        { text: "7", value: 7 },
        { text: "8", value: 8 },
        { text: "9", value: 9 },
        { text: "10", value: 10 },
        { text: "11", value: 11 },
        { text: "12", value: 12 },
        { text: "13", value: 13 },
        { text: "14", value: 14 },
        { text: "15", value: 15 },
        { text: "16", value: 16 },
        { text: "17", value: 17 }
      ],
      bookingListCantDelete: null,
      dictionary: {
        custom: {
          hotelName: {
            required: () => "tên khách sạn không được để trống"
          },
          hotelType: {
            required: () => "Vui lòng chọn loại khách sạn"
          },
          description: {
            required: () => "Mô tả khách sạn"
          },
          province: {
            required: () => "Tỉnh/Thành phố không được để trống"
          },
          district: {
            required: () => "Quận/Huyện không được để trống"
          },
          ward: {
            required: () => "Xã/Phường không được để trống"
          },
          address: {
            required: () => "Địa chỉ không được để trống"
          },
          credit_card: {
            required: () => "Số thẻ tín dụng không được để trống"
          },
          phone: {
            required: () => "Số điện thoại không được để trống"
          },
          bank: {
            required: () => "Chi nhánh ngân hàng không được để trống"
          },
          tax_code: {
            required: () => "Mã số thuế không được để trống"
          },
          email: {
            required: () => "Email không được bỏ trống"
          },
          child_age: {
            required: () => "Vui lòng cho biết độ tuổi được xem là trẻ em"
          }
        }
      },
      hotelId: this.$route.query.hotelId,
      numberOfAllBooking: 0,
      numberOfNewBooking: 0,
      numberOfCouponCode: 0,
      numberOfNewQuestion: 0,
    };
  },
  mounted() {
    this.$validator.localize("en", this.dictionary);
    this.getAllBooking();
    this.getAllCouponCode();
    this.getAllQuestion();
  },
  created() {
    // this.getHotelList();
    this.getHotelInfo();
    this.getArrayHotelType();
    this.$emit("chooseHotel", this.hotelId);
  },
  watch: {
    confirmDialog: function() {
      if (this.confirmDialog == false) {
        this.bookingListCantDelete = null;
      }
    }
  },
  methods: {
    getAllCouponCode: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/coupon-code",
        headers: {
          Authorization: "Bearer " + this.api_token
        },
        params: {
          hotelId: this.hotelId
        }
      })
        .then(res => {
          console.log(res.data);
          this.numberOfCouponCode = res.data.couponCode.length;
        })
        .catch(error => {
          console.log(error.response);
          if (error.response.status == 401) {
            this.router.push({ name: "login" });
          }
        });
    },
    getAllQuestion: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/question",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("api_token")
        },
        params: {
          hotelId: this.hotelId
        }
      })
        .then(res => {
          if (res.data.status == true) {
            this.numberOfNewQuestion = res.data.newQuestions.length;
          } else {
            this.$router.push({ name: "home" });
          }
        })
        .catch(error => {
          console.log(error.response);
          if (error.response.status == 401) {
            this.router.push({ name: "login" });
          }
        });
    },
    getHotelInfo() {
      axios
        .get("http://localhost:8000/api/manager/hotel/" + this.hotelId, {
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          console.log(response);
          this.hotel = response.data.data;
          if (response.status == 401) {
            this.logout;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getArrayHotelType() {
      axios
        .get("http://localhost:8000/api/manager/hotel-type", {
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          var arrayHotelType = response.data.data;
          this.arrayHotelType = arrayHotelType;
        })
        .catch(function(error) {
          if (error.response.status == 401) {
            _this.logout();
          }
        });
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
        if (_this.newHotelData.images.length == 0) {
          temp.is_primary = 1;
          // this.primaryImage = 1;
        } else {
          temp.is_primary = 0;
        }
        temp.id = -1;
        _this.newHotelData.images.push(temp);
      };
      reader.readAsDataURL(file);
      // console.log(this.primaryImage);
    },
    choosePrimary(index) {
      for (var i = 0; i < this.newHotelData.images.length; i++) {
        this.newHotelData.images[i].is_primary = 0;
        if (i == index) {
          this.newHotelData.images[i].is_primary = 1;
        }
      }
      console.log(index);
      console.log(this.primaryImage);
    },
    cancel() {
      this.errors.clear();
      this.dialog = false;
      this.confirmDialog = false;
    },
    reset() {
      this.newHotelData.district = "";
      this.newHotelData.ward = "";
      this.districtDisabled = true;
      this.wardDisabled = true;
      this.errors.clear();
    },
    provinceSelected: function() {
      var _this = this;
      axios
        .get("http://localhost:8000/api/manager/district", {
          params: {
            provinceId: _this.defaultProvince
          },
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          if (response.status == 401) {
            this.logout;
          }
          _this.arrayDistrict = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });
      this.districtDisabled = false;
      this.wardDisabled = true;
      this.newHotelData.district = "";
      this.newHotelData.ward = "";
    },
    districtSelected: function() {
      var _this = this;
      axios
        .get("http://localhost:8000/api/manager/ward", {
          params: {
            districtId: _this.defaultDistrict
          },
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          if (response.status == 401) {
            this.logout;
          }
          _this.arrayWard = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });
      this.wardDisabled = false;
    },
    updateHotel: function() {
      var _this = this;
      var payment_method = null;
      if (this.newHotelData.policy.online == true) {
        if (this.newHotelData.policy.offline == true) {
          payment_method = 2;
        } else if(this.newHotelData.policy.offline == false){
          payment_method = 1;
        }
      } else if(this.newHotelData.policy.online == false){
        payment_method = 0;
      }
      console.log(this.newHotelData.images);
      this.$validator.validate().then(valid => {
        if (!valid) {
        } else {
          axios({
            method: "put",
            url: "http://localhost:8000/api/manager/hotel/" + this.selectedId,
            data: {
              name: this.newHotelData.name,
              email: this.newHotelData.email,
              hotelType: this.defaultHotelType,
              hotel_manager_id: this.userID,
              stars_num: this.newHotelData.rating,
              description: this.newHotelData.description,
              province: this.defaultProvince,
              district: this.defaultDistrict,
              ward: this.defaultWard,
              address: this.newHotelData.address,
              credit_card: this.newHotelData.credit_card,
              phone: this.newHotelData.phone,
              bank: this.newHotelData.bank,
              tax_code: this.newHotelData.tax_code,
              child_age: this.newHotelData.child_age,
              images: this.newHotelData.images,
              primaryId: this.primaryImage,
              checkin: this.newHotelData.policy.checkin,
              checkout: this.newHotelData.policy.checkout,
              cancelable: this.newHotelData.policy.cancelable,
              cancel_day: this.newHotelData.policy.cancel_day,
              refundRate: this.newHotelData.policy.refundRate,
              detailPolicy: this.newHotelData.policy.detailPolicy,
              payment_method: payment_method
            },
            headers: {
              Authorization: "Bearer " + this.api_token
            }
          })
            .then(response => {
              console.log(response)
              if (response.status == 401) {
                this.logout;
              }
              if (response.data.status == false) {
                alert("can't update new hotel with this info");
                console.log("cant");
              } else {
                this.snackbarText = response.data.message;
                this.snackbar = true;
                this.hotel = response.data.hotel;
                this.dialog = false;
                // this.hotel
                
              }
              this.$emit("changeArrayHotel", _this.arrayHotel);
            })
            .catch(error => {
              console.log(error.response);
            });
        }
      });
    },
    save: function() {
      console.log(this.newHotelData);
    },
    editHotel: function(hotelId) {
      this.districtDisabled = false;
      this.wardDisabled = false;
      this.dialog = true;
      this.selectedId = hotelId;
      this.formTitle = "Sửa thông tin khách sạn";
      var _this = this;
      var address = {};
      var hotel = this.hotel;
      console.log(hotel);
      this.newHotelData.name = hotel.name;
      this.defaultHotelType = hotel.hotel_type_id;
      this.newHotelData.rating = hotel.stars_num;
      this.newHotelData.description = hotel.description;
      this.newHotelData.child_age = hotel.child_age;
      this.newHotelData.email = hotel.email;
      if (hotel.policy.cancel_day > 0 && hotel.policy.cancel_day != null) {
        this.newHotelData.policy.cancelable = true;
      } else {
        this.newHotelData.policy.cancelable = false;
      }
      this.newHotelData.policy.checkin = hotel.policy.checkin;
      this.newHotelData.policy.checkout = hotel.policy.checkout;
      this.newHotelData.policy.cancel_day = hotel.policy.cancel_day;
      this.newHotelData.policy.online = hotel.policy.online;
      this.newHotelData.policy.offline = hotel.policy.offline;
      this.newHotelData.policy.detailPolicy = hotel.policy.detailPolicy;
      this.newHotelData.policy.refundRate = hotel.policy.refundRate;
      this.newHotelData.images = hotel.images;
      axios
        .get("http://localhost:8000/api/manager/hotel-address", {
          params: {
            hotel_id: hotel.id
          },
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          if (response.status == 401) {
            this.logout;
          }
          address = response.data.data;
          for (var p = 0; p < _this.arrayProvince.length; p++) {
            if (response.data.data.province_id == _this.arrayProvince[p].id) {
              _this.defaultProvince = _this.arrayProvince[p].id;
              return axios
                .get("http://localhost:8000/api/manager/district", {
                  params: {
                    provinceId: _this.defaultProvince
                  },
                  headers: {
                    Authorization: "Bearer " + this.api_token
                  }
                })
                .then(response => {
                  if (response.status == 401) {
                    this.logout;
                  }
                  _this.arrayDistrict = response.data.data;
                  for (var d = 0; d < _this.arrayDistrict.length; d++) {
                    if (address.district_id == _this.arrayDistrict[d].id) {
                      _this.defaultDistrict = _this.arrayDistrict[d].id;
                      return axios
                        .get("http://localhost:8000/api/manager/ward", {
                          params: {
                            districtId: _this.defaultDistrict
                          },
                          headers: {
                            Authorization: "Bearer " + this.api_token
                          }
                        })
                        .then(response => {
                          if (response.status == 401) {
                            this.logout;
                          }
                          _this.arrayWard = response.data.data;
                          for (var d = 0; d < _this.arrayWard.length; d++) {
                            if (address.ward_id == _this.arrayWard[d].id) {
                              _this.defaultWard = _this.arrayWard[d].id;
                            }
                          }
                        })
                        .catch(function(error) {
                          console.log(error.response);
                        });
                    }
                  }
                })
                .catch(function(error) {
                  console.log(error.response);
                });
            }
          }
        })
        .catch(function(error) {
          console.log(error);
        });
      this.newHotelData.address = hotel.address;
      this.newHotelData.email = hotel.email;
      this.newHotelData.credit_card = hotel.credit_card;
      this.newHotelData.phone = hotel.phone;
      this.newHotelData.bank = hotel.bank;
      this.newHotelData.tax_code = hotel.tax_code;
      console.log(this.newHotelData);
      console.log(this.hotel);
    },
    deleteHotel: function(hotelId) {
      this.confirmDialog = true;
      this.selectedId = hotelId;
      this.confirmDialogText = "Bạn có muốn xóa khách sạn này " + "?";
    },
    deleteHotelConfirm: function() {
      var _this = this;
      axios
        .delete("http://localhost:8000/api/manager/hotel/" + this.selectedId, {
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          if (response.status == 401) {
            this.logout;
          }
          console.log(response);
          if (response.data.status == false) {
            if (response.data.booking_list != null) {
              this.confirmDialog = true;
              this.bookingListCantDelete = response.data.booking_list;
              console.log(response.data.booking_list);
            }
          } else {
            this.hotel = {};
            this.confirmDialog = false;
            document.location.href = "login";
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    logout: function() {
      axios({
        method: "post",
        url: "http://localhost:8000/api/manager/logout",
        headers: {
          Authorization: "Bearer " + this.api_token
        }
      })
        .then(response => {
          // if (response.status == 401) {
          //   this.logout;
          // }
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
      localStorage.removeItem("api_token");
      this.api_token = null;
      location.href = "login";
    },
    deleteImage: function(i) {
      this.newHotelData.images.splice(i, 1);
    },
    detailOrder: function(id) {
      this.$router.push({
        name: "order",
        query: { hotelId: this.selectedId, orderId: id }
      });
    },
    getAllBooking: function() {
      var date = new Date(),
        y = date.getFullYear(),
        m = date.getMonth();
      var firstDay = new Date(y, m, 1);
      var lastDay = new Date(y, m + 1, 0);
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/booking-filter-date",
        headers: {
          Authorization: "Bearer " + this.api_token
        },
        params: {
          checkin: firstDay,
          checkout: lastDay,
          hotelId: this.hotelId
        }
      })
        .then(response => {
          console.log(response.data);
          if (response.status == 401) {
            this.logout;
          }
          if (response.data.status == true) {
            // this.booking = response.data.data;
            this.numberOfAllBooking = response.data.data.length;
            response.data.data.forEach(element => {
              if (element.status_id == 1) {
                this.numberOfNewBooking += 1;
              }
            });
          }
        })
        .catch(error => {
          console.log(error.response);
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

