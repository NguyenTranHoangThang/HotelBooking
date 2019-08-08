<template>
  <div>
    <template>
      <v-dialog max-width="40%" v-model="dialogFilter">
        <v-card width="100%">
          <span v-if="filterId == true">
            <v-card-title>
              <h5>Lọc theo Id :</h5>
            </v-card-title>
            <v-card-text>
              <v-form ref="formFilterById">
                <v-text-field
                  v-model="arrayIdtoFilter"
                  hint="Type in order id to find.(ex: 1,2,3)"
                  v-validate="{ required: true, regex: '^[0-9,;]+$'}"
                  :error-messages="errors.collect('idList')"
                  data-vv-name="idList"
                  outline
                ></v-text-field>
              </v-form>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn round depressed color="primary" @click="filterById">Lọc</v-btn>
            </v-card-actions>
          </span>
          <span v-else-if="filterDate == true">
            <v-card-title>
              <h5>Lọc theo ngày :</h5>
            </v-card-title>

            <v-card-text>
              <v-form ref="formFilterByDate">
                <v-layout row>
                  <v-flex>
                    <v-menu
                      ref="menuCheckin"
                      v-model="menuCheckin"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="checkin"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="checkin"
                          label="Từ"
                          prepend-icon="event"
                          required
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="checkin" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="menuCheckin = false">Hủy</v-btn>
                        <v-btn
                          flat
                          color="primary"
                          @click="$refs.menuCheckin.save(checkin);minDate = checkin;"
                        >Xác nhận</v-btn>
                      </v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex>
                    <v-menu
                      ref="menuCheckout"
                      v-model="menuCheckout"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="checkout"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="checkout"
                          label="Đến"
                          prepend-icon="event"
                          readonly
                          required
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="checkout" :min="minDate" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="menuCheckout = false">Hủy</v-btn>
                        <v-btn
                          flat
                          color="primary"
                          @click="$refs.menuCheckout.save(checkout)"
                        >Xác nhận</v-btn>
                      </v-date-picker>
                    </v-menu>
                  </v-flex>
                </v-layout>
              </v-form>
              <v-layout row>
                <v-spacer></v-spacer>
                <v-btn round depressed color="primary" @click="filterByDate">Lọc</v-btn>
              </v-layout>
            </v-card-text>
          </span>
        </v-card>
        <!-- <v-card > -->

        <!-- <v-card> -->
      </v-dialog>
      <v-dialog v-model="bookingDialog" max-width="40%">
        <v-card>
          <v-card-title>
            <h3>Thêm booking</h3>
          </v-card-title>
          <v-card-text>
            <div v-show="ask">
              <h5>Người dùng có tải khoản trên hệ thống hay không</h5>
              <v-btn class="orange" row flat @click="withUser = true;ask = false">Có</v-btn>
              <v-btn class="orange" row flat @click="withoutUser = true;ask = false">Không</v-btn>
            </div>
            <v-alert v-show="emailError != null" type="error">{{emailError}}</v-alert>
            <v-form class="px-5">
              <v-layout row wrap align-center justify-center v-show="ask == false">
                <v-flex md6>
                  <v-menu
                    ref="menuCheckin2"
                    v-model="menuCheckin2"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="checkin2"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="checkin2"
                        label="Từ"
                        prepend-icon="event"
                        required
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="checkin2" no-title scrollable>
                      <v-spacer></v-spacer>
                      <v-btn flat color="primary" @click="menuCheckin2 = false">Hủy</v-btn>
                      <v-btn
                        flat
                        color="primary"
                        @click="$refs.menuCheckin2.save(checkin2);minDate2 = checkin2;"
                      >Xác nhận</v-btn>
                    </v-date-picker>
                  </v-menu>
                </v-flex>
                <v-flex md6>
                  <v-menu
                    ref="menuCheckout2"
                    v-model="menuCheckout2"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="checkout2"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="checkout2"
                        label="Đến"
                        prepend-icon="event"
                        readonly
                        required
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="checkout2" :min="minDate2" no-title scrollable>
                      <v-spacer></v-spacer>
                      <v-btn flat color="primary" @click="menuCheckout2 = false">Hủy</v-btn>
                      <v-btn
                        flat
                        color="primary"
                        @click="$refs.menuCheckout2.save(checkout2)"
                      >Xác nhận</v-btn>
                    </v-date-picker>
                  </v-menu>
                </v-flex>
                <v-flex md12 v-if="withUser == true" class="px-3">
                  <v-layout align-center justify-center row wrap>
                    <v-flex md6>Email</v-flex>
                    <v-flex md6>
                      <v-text-field
                        v-model="email"
                        v-validate="'required|email'"
                        :error-messages="errors.collect('email')"
                        data-vv-name="email"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                </v-flex>
                <v-flex md12 v-if="withoutUser == true" class="px-3">
                  <v-layout align-center justify-center row wrap>
                    <v-flex md6>Tên</v-flex>
                    <v-flex md6>
                      <v-text-field
                        v-model="name"
                        v-validate="'required'"
                        :error-messages="errors.collect('name')"
                        data-vv-name="name"
                      ></v-text-field>
                    </v-flex>
                    <v-flex md6>Email</v-flex>
                    <v-flex md6>
                      <v-text-field
                        v-model="email"
                        v-validate="'required|email'"
                        :error-messages="errors.collect('email')"
                        data-vv-name="email"
                      ></v-text-field>
                    </v-flex>
                    <v-flex md6>Số điện thoại</v-flex>
                    <v-flex md6>
                      <v-text-field
                        v-model="phone"
                        v-validate="'required'"
                        :error-messages="errors.collect('phone')"
                        data-vv-name="phone"
                      ></v-text-field>
                    </v-flex>
                    <v-flex md6>Địa chỉ</v-flex>
                    <v-flex md6>
                      <v-text-field
                        v-model="address"
                        v-validate="'required'"
                        :error-messages="errors.collect('address')"
                        data-vv-name="address"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                </v-flex>
                <v-flex md6>Loại phòng</v-flex>
                <v-flex md6>
                  <v-select
                    v-model="roomChosen"
                    :items="arrayRoom"
                    item-text="full_name"
                    item-value="id"
                    required
                  ></v-select>
                </v-flex>
                <v-flex md6>Số lượng phòng</v-flex>
                <v-flex md6>
                  <v-text-field
                    v-model="roomAmount"
                    type="number"
                    min="1"
                    v-validate="'required|numeric'"
                    :error-messages="errors.collect('amount')"
                    data-vv-name="amount"
                  ></v-text-field>
                </v-flex>
              </v-layout>
            </v-form>
          </v-card-text>
          <v-card-actions v-show="ask == false">
            <v-spacer></v-spacer>
            <v-btn round depressed color="red" @click="bookingDialog = false" class="white--text">Hủy</v-btn>
            <v-btn round depressed color="primary" @click="addBooking">Thêm</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            class="elevation-0"
            @click="openAddBookingDialog"
            round
            dark
          >Thêm booking</v-btn>
          <v-menu v-if="filterDate == false && filterId == false" offset-y>
            <template v-slot:activator="{ on }">
              <v-btn color="primary" class="elevation-0" round dark v-on="on">Tùy chọn</v-btn>
            </template>
            <v-list>
              <v-list-tile>
                <v-list-tile-title @click="openFilterById">Lọc theo Id</v-list-tile-title>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-title @click="openFilterByDate">Lọc theo ngày</v-list-tile-title>
              </v-list-tile>
            </v-list>
            <!-- <v-text-field v-model="search" hint="insert Id"></v-text-field> -->
          </v-menu>
          <v-btn
            v-else
            color="primary"
            class="elevation-0"
            round
            dark
            @click="resetBookingList"
          >Reset</v-btn>
        </v-card-title>
        <v-data-table :search="search" :headers="headers" :items="bookingList" class="elevation-1">
          <template v-slot:items="booking" class="setHeight">
            <td class="text-xs-center">{{booking.item.id}}</td>
            <td class="text-xs-center">{{booking.item.room_name}}</td>
            <td class="text-xs-center">{{booking.item.check_in}}</td>
            <td class="text-xs-center">{{booking.item.check_out}}</td>
            <td
              class="text-xs-center"
            >{{(booking.item.total_price).toLocaleString('vi', {style: 'currency',currency: 'VND'})}}</td>
            <td class="text-xs-center">
              <!-- <v-btn round color="warning" :ripple="false" depressed small></v-btn> -->
              <span v-if="booking.item.status_id == 1">
                <v-chip disabled color="warning" text-color="white">{{booking.item.status}}</v-chip>
              </span>
              <span v-else-if="booking.item.status_id == 2">
                <v-chip disabled color="blue" text-color="white">{{booking.item.status}}</v-chip>
              </span>
              <span v-else-if="booking.item.status_id == 3">
                <v-chip disabled color="red" text-color="white">{{booking.item.status}}</v-chip>
              </span>
              <span v-else-if="booking.item.status_id == 4">
                <v-chip disabled color="success" text-color="white">{{booking.item.status}}</v-chip>
              </span>
              <span v-else-if="booking.item.status_id == 5">
                <v-chip disabled color="red" text-color="white">{{booking.item.status}}</v-chip>
              </span>
              <span v-else-if="booking.item.status_id == 6">
                <v-chip disabled color="red" text-color="white">{{booking.item.status}}</v-chip>
              </span>
              <span v-else-if="booking.item.status_id == 7">
                <v-chip disabled color="grey" text-color="white">{{booking.item.status}}</v-chip>
              </span>
              <span v-else-if="booking.item.status_id == 8">
                <v-chip disabled color="blue" text-color="white">{{booking.item.status}}</v-chip>
              </span>
            </td>
            <td class="text-xs-center action-width">
              <!-- {{booking.item.status_id}} -->
              <div style="width:100%">
                <v-layout row wrap justify-center>
                  <v-flex md12>
                    <div @click="ShowDetailBooking(booking.item)" class="mb-0 chip info">Chi tiết</div>
                  </v-flex>
                  <v-flex md12>
                    <div v-if="booking.item.status_id == 1">
                      <div class="chip success" @click="acceptBooking(booking.item)">Chấp nhận</div>
                      <div class="chip red" @click="declineBooking(booking.item)">Từ chối</div>
                    </div>
                    <div v-if="booking.item.status_id == 2">
                      <div>
                        <div class="chip blue" @click="confirmBooking(booking.item)">Xác nhận</div>

                        <div class="chip red" @click="showConfirmDialog(booking.item)">Hủy đơn</div>
                      </div>
                    </div>
                    <div v-if="booking.item.status_id == 4">
                      <div>
                        <div class="chip red" @click="showConfirmDialog(booking.item)">Hủy đơn</div>
                      </div>
                    </div>
                    <!-- <div v-if="booking.item.status_id == 4">
                      <div>c</div>
                    </div>-->
                  </v-flex>
                  <!-- <span v-if="booking.item.status_id == 1">
                    <v-flex md12>
                      <v-chip
                        round
                        depressed
                        small
                        class="white--text"
                        color="success"
                        @click="acceptBooking(booking.item)"
                      >Chấp nhận</v-chip>
                    </v-flex>
                    <v-flex md12>
                      <v-chip
                        round
                        depressed
                        small
                        class="white--text"
                        color="error"
                        @click="declineBooking(booking.item)"
                      >Từ chối</v-chip>
                    </v-flex>
                  </span>
                  <span  v-else-if="booking.item.status_id == 2">
                    <v-flex md12>
                      <v-chip
                        round
                        depressed
                        small
                        color="blue"
                        class="white--text"
                        @click="confirmBooking(booking.item)"
                      >Xác nhận</v-chip>
                    </v-flex>
                    <v-flex md12>
                      <v-chip
                        round
                        depressed
                        small
                        color="red"
                        class="white--text"
                        @click="showConfirmDialog(booking.item)"
                      >Hủy đơn</v-chip>
                    </v-flex>
                  </span>
                  <span v-else-if="booking.item.status_id == 4">
                    <v-flex md12  >
                      <v-chip
                        round
                        depressed
                        small
                        color="red"
                        class="white--text"
                        @click="showConfirmDialog(booking.item)"
                      >Hủy đơn</v-chip>
                    </v-flex>
                  </span>-->
                </v-layout>
              </div>
            </td>
          </template>
        </v-data-table>
      </v-card>
      <v-dialog v-model="dialog" max-width="80%">
        <template v-slot:activator="{ on }"></template>
        <v-card>
          <v-card-text>
            <v-layout>
              <v-flex md19 class="m-3">
                <h3>Đơn #{{detailBooking.id}}</h3>
              </v-flex>
              <v-flex md3>
                <b>Trạng thái :</b>
                <span v-if="detailBooking.status_id == 1">
                  <v-chip outline disabled text-color="warning">
                    <b>{{detailBooking.status}}</b>
                  </v-chip>
                </span>
                <span v-else-if="detailBooking.status_id == 2">
                  <v-chip outline disabled text-color="blue">
                    <b>{{detailBooking.status}}</b>
                  </v-chip>
                </span>
                <span v-else-if="detailBooking.status_id == 3">
                  <v-chip outline disabled text-color="red">
                    <b>{{detailBooking.status}}</b>
                  </v-chip>
                </span>
                <span v-else-if="detailBooking.status_id == 4">
                  <v-chip outline disabled text-color="success">
                    <b>{{detailBooking.status}}</b>
                  </v-chip>
                </span>
                <span v-else-if="detailBooking.status_id == 5">
                  <v-chip outline disabled text-color="red">
                    <b>{{detailBooking.status}}</b>
                  </v-chip>
                </span>
                <span v-else-if="detailBooking.status_id == 6">
                  <v-chip outline disabled text-color="red">
                    <b>{{detailBooking.status}}</b>
                  </v-chip>
                </span>
                <span v-else-if="detailBooking.status_id == 7">
                  <v-chip outline disabled text-color="grey">
                    <b>{{detailBooking.status}}</b>
                  </v-chip>
                </span>
                <span v-else-if="detailBooking.status_id == 8">
                  <v-chip outline disabled text-color="blue">
                    <b>{{detailBooking.status}}</b>
                  </v-chip>
                </span>
              </v-flex>
            </v-layout>

            <h5>Thông tin khách hàng</h5>
            <v-layout row wrap>
              <v-flex md2 offset-md1>
                <b>Tên</b>
              </v-flex>
              <v-flex md9>: {{detailBooking.contact_name}}</v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex md2 offset-md1>
                <b>Email</b>
              </v-flex>
              <v-flex md9>: {{detailBooking.contact_email}}</v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex md2 offset-md1>
                <b>Số điện thoại</b>
              </v-flex>
              <v-flex md9>: {{detailBooking.contact_phone}}</v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex md2 offset-md1>
                <b>địa chỉ</b>
              </v-flex>
              <v-flex md9>: {{detailBooking.contact_address}}</v-flex>
            </v-layout>
            <v-layout v-if="detailBooking.special_request != null" row wrap>
              <v-flex md2 offset-md1>
                <b>Yêu cầu đặc biệt</b>
              </v-flex>
              <v-flex md9>: {{detailBooking.special_request}}</v-flex>
            </v-layout>
            <v-divider></v-divider>
            <h5>Thông tin Đơn</h5>
            <v-layout>
              <v-flex md4 class>
                <span v-for="(image,index) in detailBooking.room_images" :key="index">
                  <v-img
                    height="200px"
                    v-if="image.is_primary == 1"
                    dark
                    :src="'http://localhost:8000/images/room/'+image.image_link"
                  ></v-img>
                </span>
              </v-flex>
              <v-flex md8 class="mt-3">
                <v-layout row wrap class="mb-3">
                  <v-flex md2 offset-md1>
                    <b>Check in</b>
                  </v-flex>
                  <v-flex md3>
                    :
                    <span class="title ml-3">{{detailBooking.check_in}}</span>
                  </v-flex>
                  <v-flex md2 offset-md1>
                    <b>Check out</b>
                  </v-flex>
                  <v-flex md3>
                    :
                    <span class="title ml-3">{{detailBooking.check_out}}</span>
                  </v-flex>
                </v-layout>
                <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>Tên phòng</b>
                  </v-flex>
                  <v-flex md9>: {{detailBooking.room_name}}</v-flex>
                </v-layout>
                <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>Giá phòng</b>
                  </v-flex>
                  <v-flex
                    md9
                  >: {{(detailBooking.room_price).toLocaleString('vi', {style: 'currency',currency: 'VND'})}}</v-flex>
                </v-layout>
                <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>Số lượng phòng</b>
                  </v-flex>
                  <v-flex md9>: {{detailBooking.room_amount}}</v-flex>
                </v-layout>
                 <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>Số đêm ở</b>
                  </v-flex>
                  <v-flex md9>: {{detailBooking.stay_days}}</v-flex>
                </v-layout>
                <!-- <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>Mã phòng</b>
                  </v-flex>
                  <v-flex md9>: {{detailBooking.room_id}}</v-flex>
                </v-layout> -->
                <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>phòng</b>
                  </v-flex>
                  <v-flex md9>: <b>{{detailBooking.room.full_name}}</b></v-flex>
                </v-layout>
                <!-- <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>Kiểu phòng</b>
                  </v-flex>
                  <v-flex md9>: {{detailBooking.room}}</v-flex>
                </v-layout> -->
                <!-- <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>Loại phòng</b>
                  </v-flex>
                  <v-flex md9>: {{detailBooking.room.room_mode}}</v-flex>
                </v-layout> -->
                <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>Phương thức thanh toán</b>
                  </v-flex>
                  <v-flex md9>: {{detailBooking.payment_method}}</v-flex>
                </v-layout>
                
                <v-layout row wrap>
                  <v-flex md2 offset-md1>
                    <b>Tổng giá</b>
                  </v-flex>
                  <v-flex md9>: {{(detailBooking.total_price).toLocaleString('vi', {style: 'currency',currency: 'VND'})}}</v-flex>
                </v-layout>
                <!-- {{(booking.item.room_price * booking.item.room_amount).toLocaleString('vi', {style: 'currency',currency: 'VND'})}} -->
              </v-flex>
            </v-layout>
            <v-divider></v-divider>
          </v-card-text>
        </v-card>
      </v-dialog>
    </template>

    <v-dialog max-width="30%" v-model="confirmDialog">
      <v-card>
        <v-card-title>
          <h4>Xác nhận</h4>
        </v-card-title>
        <v-card-text>
          {{confirmDialogText}}
          <br />
          <ul>
            <li>Thứ tự xuất hiện của khách sạn sẽ bị giảm</li>
            <li>chuyển {{transferAmount}} xu ({{transferPercent}}% của đơn hàng này) đến khách hàng</li>
          </ul>
          <span
            v-if="cancelReminder == true"
          >Bạn sẽ phải hoàn tiền 100% giá trị đơn hàng này cho khách hàng.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn round depressed small class="grey white--text" flat @click="cancel">Hủy</v-btn>
          <v-btn
            round
            depressed
            small
            class="red white--text"
            flat
            @click.stop="cancelBooking()"
          >Xác nhận</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  $_veeValidate: {
    validator: "new"
  },
  props: {
    api_token: {
      type: String
    }
  },
  data: function() {
    return {
      dialog: false,
      menuCheckin: false,
      menuCheckout: false,
      menuCheckin2: false,
      menuCheckout2: false,
      checkin: null,
      checkout: null,
      checkin2: null,
      checkout2: null,
      detailBooking: {
        room_price: 0,
        room : {
          full_name : ""
        },
        stay_days : 0,
        total_price : 0,
      },
      bookingList: [],
      search: "",
      headers: [
        {
          text: "Id",
          value: "id",
          sortable: false,
          align: "center",
          width: "10%"
        },
        { text: "Tên phòng", value: 0, sortable: false, align: "center" },
        { text: "Check in", value: 0, sortable: false, align: "center" },
        { text: "Check out", value: 0, sortable: false, align: "center" },
        { text: "Tổng giá", value: 0, sortable: false, align: "center" },
        { text: "Trạng thái", value: 0, sortable: false, align: "center" },
        {
          text: "Action",
          value: 0,
          sortable: false,
          align: "center",
          width: "10%"
        }
      ],
      hotelId: this.$route.query.hotelId,
      confirmDialog: false,
      confirmDialogText: "",
      transferPercent: 0,
      transferAmount: 0,
      cancelReminder: false,
      dialogFilter: false,
      arrayIdtoFilter: null,
      filterId: false,
      filterDate: false,
      dictionary: {
        custom: {
          idList: {
            regex: "Vd : 1,2,3"
          },
          name: {
            required: "Tên không được để trống"
          },
          email: {
            email: "Email phải là kiểu email.Vd : abc@gmail.com",
            required: "Email không được để trống"
          },
          phone: {
            required: "Số điện thoại không được để trống"
          },
          address: {
            required: "Địa chỉ không được để trống"
          },
          address: {
            required: "Số lượng phòng không được để trống",
            numeric: "Số lượng phòng phải là số nguyên không âm"
          }
        }
      },
      temp: null,
      flagFilterId: false,
      flagFilterDate: false,
      today: null,
      minDate: null,
      minDate2: null,
      bookingDialog: false,
      withUser: false,
      withoutUser: false,
      ask: true,
      arrayRoom: [],
      roomChosen: null,
      roomAmount: 1,
      name: "",
      email: "",
      phone: "",
      address: "",
      emailError: null
    };
  },
  mounted() {
    this.$validator.localize("en", this.dictionary);
    window.Echo.channel("manager").listen(".new-booking", e => {
      this.bookingList.push(e.booking);
    });
  },
  created() {
    this.$emit("chooseHotel", this.hotelId);
    this.initialize();
    if (this.$route.query.orderId != null) {
      this.search = this.$route.query.orderId;
    }
    // var today = new Date();
    // var dd = String(today.getDate()).padStart(2, "0");
    // var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
    // var yyyy = today.getFullYear();

    // this.today = yyyy + "-" + mm + "-" + dd;
    // console.log(this.today);
  },
  watch: {
    dialogFilter: function() {
      if (this.dialogFilter == false) {
        if (this.flagFilterId == false) {
          this.filterId = false;
        } else {
          this.filterId = true;
        }
        if (this.flagFilterDate == false) {
          this.filterDate = false;
        } else {
          this.filterDate = true;
        }
      }
    },
    $route: function() {
      if (this.$route.query.orderId != null) {
        this.search = this.$route.query.orderId;
      }
    }
  },
  methods: {
    initialize: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/all-booking",
        headers: {
          Authorization: "Bearer " + this.api_token
        },
        params: {
          hotelId: this.hotelId
        }
      })
        .then(response => {
          console.log(response.data);
          if (response.status == 401) {
            this.logout;
          }
          if (response.data.status == true) {
            this.bookingList = response.data.booking;
            this.temp = response.data.booking;
          }
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    ShowDetailBooking: function(item) {
      this.dialog = true;
      this.detailBooking = item;
      console.log(this.detailBooking)
    },
    acceptBooking: function(booking) {
      var index = this.bookingList.indexOf(booking);
      axios({
        method: "post",
        url: "http://localhost:8000/api/manager/accept",
        headers: {
          Authorization: "Bearer " + this.api_token
        },
        data: {
          bookingId: booking.id
        }
      })
        .then(response => {
          if (response.status == 401) {
            this.logout;
          }
          console.log(response);
          // booking = response.data.data;
          // this.bookingList[index] = response.data.data;
          this.bookingList = response.data.data;
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    declineBooking: function(booking) {
      axios({
        method: "post",
        url: "http://localhost:8000/api/manager/decline",
        headers: {
          Authorization: "Bearer " + this.api_token
        },
        data: {
          bookingId: booking.id
        }
      })
        .then(response => {
          if (response.status == 401) {
            this.logout;
          }
          // console.log(response);
          // booking = response.data.data;
          this.bookingList = response.data.data;
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    confirmBooking: function(booking) {
      axios({
        method: "post",
        url: "http://localhost:8000/api/manager/confirm",
        headers: {
          Authorization: "Bearer " + this.api_token
        },
        data: {
          bookingId: booking.id
        }
      })
        .then(response => {
          if (response.status == 401) {
            this.logout;
          }
          // console.log(response);
          // booking = response.data.data;
          this.bookingList = response.data.data;
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    showConfirmDialog: function(booking) {
      console.log(booking);
      if (booking.status_id == 2) {
        this.transferPercent = 20;
        this.cancelReminder = false;
      }
      if (booking.status_id == 4) {
        this.transferPercent = 40;
        this.cancelReminder = true;
      }
      this.transferAmount = Math.ceil(
        (this.transferPercent * booking.room_price * booking.room_amount) / 100
      );
      this.confirmDialogText =
        "Bạn có muốn hủy đơn đặt phòng này? Nếu bạn hủy đơn này hệ thống sẽ:";
      this.confirmDialog = true;
      this.detailBooking = booking;
    },
    cancel: function() {
      this.confirmDialog = false;
    },
    cancelBooking: function() {
      axios({
        method: "post",
        url: "http://localhost:8000/api/manager/cancel",
        headers: {
          Authorization: "Bearer " + this.api_token
        },
        data: {
          bookingId: this.detailBooking.id
        }
      })
        .then(response => {
          if (response.status == 401) {
            this.logout;
          }
          console.log(response);
          // booking = response.data.data;
          this.bookingList = response.data.data;
          this.confirmDialog = false;
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    openFilterById: function() {
      this.filterId = true;
      this.flagFilter = false;
      this.dialogFilter = true;
    },
    openFilterByDate: function() {
      // this.filterId = false;
      this.filterDate = true;
      this.flagFilter = false;
      this.dialogFilter = true;
    },
    filterById: function() {
      console.log(this.arrayIdtoFilter);
      this.$validator
        .validate()
        .then(valid => {
          if (!valid) {
          } else {
            axios({
              method: "get",
              url: "http://localhost:8000/api/manager/booking-filter-id",
              headers: {
                Authorization: "Bearer " + this.api_token
              },
              params: {
                bookingIds: this.arrayIdtoFilter,
                hotelId: this.hotelId
              }
            })
              .then(response => {
                console.log(response.data);
                if (response.status == 401) {
                  this.logout;
                }
                if (response.data.status == true) {
                  this.bookingList = response.data.data;
                  this.flagFilterId = true;
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
    filterByDate: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/booking-filter-date",
        headers: {
          Authorization: "Bearer " + this.api_token
        },
        params: {
          checkin: this.checkin,
          checkout: this.checkout,
          hotelId: this.hotelId
        }
      })
        .then(response => {
          console.log(response.data);
          if (response.status == 401) {
            this.logout;
          }
          if (response.data.status == true) {
            this.bookingList = response.data.data;
            this.flagFilterDate = true;
          }
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    resetBookingList: function() {
      this.filterId = false;
      this.filterDate = false;
      this.bookingList = this.temp;
    },
    openAddBookingDialog: function() {
      this.bookingDialog = true;
      this.roomChosen = null;
      this.ask = true;
      this.emailError = null;
      this.withUser = false;
      this.withoutUser = false;
      axios
        .get("http://localhost:8000/api/manager/all-room", {
          params: {
            hotelId: this.hotelId
          },
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
        .then(response => {
          console.log(response);
          this.arrayRoom = response.data.data;
        })
        .catch(function(error) {
          console.log(error.response);
        });
    },
    addBooking: function() {
      axios({
        method: "post",
        url: "http://localhost:8000/api/manager/booking",
        headers: {
          Authorization: "Bearer " + this.api_token
        },
        data: {
          withUser: this.withUser,
          hotelId: this.hotelId,
          checkin: this.checkin2,
          checkout: this.checkout2,
          name: this.name,
          email: this.email,
          name: this.name,
          phone: this.phone,
          address: this.address,
          roomChosen: this.roomChosen,
          roomAmount: this.roomAmount
        }
      })
        .then(response => {
          if (response.status == 401) {
            this.logout;
          }
          console.log(response);
          if (response.data.status == false) {
            this.emailError = response.data.message;
            console.log("sai");
            console.log(this.emailError);
          } else {
            this.bookingList.push(response.data.booking);
            this.bookingDialog = false;
            console.log("dung");
          }
        })
        .catch(error => {
          console.log(error.response);
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
          if (response.status == 401) {
            this.logout;
          }
          console.log(response);
        })
        .catch(error => {
          console.log(error.response);
        });
      localStorage.removeItem("api_token");
      this.api_token = null;
      location.href = "login";
    }
  }
};
</script>
<style scoped>
.action-width {
  width: 100px;
}
.chip {
  background-color: blue;
  padding: 2px 12px;
  text-align: center;
  border-radius: 28px;
  color: white;
  width: 100%;
  margin: 5px 0px;
  white-space: nowrap;
}
</style>


