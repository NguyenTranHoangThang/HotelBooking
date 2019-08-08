<template>
  <v-layout row wrap>
    <v-flex shrink md8 v-if="flag.state">
      <v-layout class="pa-0 ma-0" v-if="data.length>0">
        <v-flex md12 v-if="!createForm">
          <template>
            <v-card flat>
              <v-data-table
                :rows-per-page-items='[5,10,15,{"text":"$vuetify.dataIterator.rowsPerPageAll","value":-1}]'
                :headers="tblHeaders"
                :search="search"
                :items="data"
                class="elevation-0 border"
              >
                <template v-slot:items="c">
                  <tr>
                    <td>
                      <v-layout row wrap class="pa-0 ma-0" align-center>
                        <v-flex md2>
                          <div
                            class="indigo--text text-md-center font-weight-black display-1"
                          >{{c.item.discount_value}}%</div>
                        </v-flex>
                        <v-flex md5 class="border-right pa-2">
                          <v-layout row wrap class="pa-0 ma-0">
                            <div>
                              <div>
                                <span class="font-weight-black">{{c.item.title}}</span>
                              </div>
                              <div>
                                <span class="font-italic caption">"{{c.item.content}}"</span>
                              </div>
                              <div>
                                Mã:&nbsp;<span class="font-weight-black title brown--text">{{c.item.code}}</span>
                              </div>
                              <div v-if="c.item.days">
                                Đã diễn ra được&nbsp;
                                <span
                                  class="font-weight-black"
                                >{{c.item.days}}&nbsp;</span>ngày...
                              </div>
                            </div>
                          </v-layout>
                        </v-flex>
                        <v-flex md5 class="pa-2">
                          <v-layout row wrap class="pa-0 ma-0">
                            <v-flex md4>
                              <span class="caption">Bắt Đầu:</span>
                            </v-flex>
                            <v-flex md8>
                              <span class="font-weight-black">{{formatDate(c.item.start_at)}}</span>
                            </v-flex>
                            <v-flex md4>
                              <span class="caption">Kết Thúc:</span>
                            </v-flex>
                            <v-flex md8>
                              <span
                                class="font-weight-black"
                                v-if="c.item.end_at != null"
                              >{{formatDate(c.item.end_at)}}</span>
                              <a
                                class="teal--text caption"
                                v-else
                                @click="openDatePicker(c.item)"
                              >Thêm...</a>
                            </v-flex>
                            <v-flex md4>
                              <span class="caption">Số lượng:</span>
                            </v-flex>
                            <v-flex md8>
                              <span class="font-weight-black">{{c.item.apply_amount}}</span>
                            </v-flex>
                            <v-flex md4>
                              <span class="caption">Đã dùng:</span>
                            </v-flex>
                            <v-flex md8>
                              <span class="font-weight-black">{{c.item.applied_amount}}</span>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </td>
                    <td>
                      <div v-if="radio ==1">
                        <v-chip color="red" class="white--text caption" @click="openSureConfirm(c.item.id)">Xóa</v-chip>
                      </div>
                      <div v-if="radio ==0">
                        <v-chip color="indigo" class="white--text caption" @click="openSureConfirm(c.item.id)">Dừng</v-chip>
                      </div>
                    </td>
                  </tr>
                </template>
                <template v-slot:expand="c">
                  <v-card flat tile light color="brown lighten-2">
                    <v-card-text></v-card-text>
                    <v-layout align-center class="pa-0 ma-1 border grey lighten-2" row wrap></v-layout>
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
        <v-flex md12 v-else>
          <v-form ref="form" data-vv-scope="form1" class="pa-1 brown lighten-2">
            <v-layout row wrap class="pa-4 ma-0 white caption" align-center>
              <v-flex md12>
                <div
                  class="ma-3 font-weight-black headline text-md-center text-uppercase brown--text"
                >tạo mã coupon</div>
              </v-flex>
              <v-flex md12>
                <v-divider class="brown"></v-divider>
              </v-flex>
              <v-flex md5>
                <span>Ngày bắt đầu:&nbsp;</span>
                <v-menu
                  ref="start"
                  v-model="mn.menu1"
                  :return-value.sync="code.start_at"
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
                    <v-text-field
                      :error-messages="errors.collect('form1.start_at')"
                      data-vv-name="start_at"
                      v-validate="'required'"
                      type="text"
                      outline
                      color="brown"
                      v-model="startFormatted"
                      readonly
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker light no-title scrollable v-model="code.start_at" :min="now">
                    <v-spacer></v-spacer>
                    <v-btn flat v-on:click="mn.menu1 = false">Hủy</v-btn>
                    <v-btn flat v-on:click="$refs.start.save(code.start_at)">OK</v-btn>
                  </v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex md6 offset-md1>
                <span>Ngày kết thúc:&nbsp;</span>
                <v-menu
                  :disabled="code.start_at.length ===0"
                  ref="end"
                  v-model="mn.menu2"
                  :return-value.sync="code.end_at"
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
                    <v-text-field
                      type="text"
                      outline
                      color="brown"
                      v-model="endFormatted"
                      readonly
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    light
                    no-title
                    scrollable
                    v-model="code.end_at"
                    :min="code.start_at+1"
                  >
                    <v-spacer></v-spacer>
                    <v-btn flat @click="mn.menu2 = false">Hủy</v-btn>
                    <v-btn flat @click="$refs.end.save(code.end_at)">OK</v-btn>
                  </v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex md5>
                <v-text-field
                  :error-messages="errors.collect('form1.code')"
                  data-vv-name="code"
                  v-validate="'required'"
                  type="text"
                  outline
                  color="brown"
                  v-model="code.code"
                  label="Nhập mã coupon tại đây..."
                ></v-text-field>
              </v-flex>
              <v-flex md6 offset-md1>
                <v-textarea
                  :error-messages="errors.collect('form1.title')"
                  data-vv-name="title"
                  v-validate="'required'"
                  type="text"
                  outline
                  auto-grown
                  rows="2"
                  color="brown"
                  v-model="code.title"
                  label="Nhập tiêu đề khuyến mãi tại đây..."
                ></v-textarea>
              </v-flex>
              <v-flex md12 class="mb-3 py-3 border-top border-bottom">
                <span>Áp dụng cho:&nbsp;</span>
                <v-layout align-center class="pa-2 mx-2">
                  <v-flex md4>
                    <v-radio-group v-model="roomTypeRadio" :mandatory="false">
                      <v-layout class="caption mb-2" align-center>
                        <v-flex md1>
                          <v-radio :value="0" color="brown"></v-radio>
                        </v-flex>
                        <v-flex offset-md1>
                          Tất cả loại phòng.
                        </v-flex>
                      </v-layout>                      
                      <v-layout class="caption" align-center>
                        <v-flex md1>
                          <v-radio :value="1" color="orange"></v-radio>
                        </v-flex>
                        <v-flex offset-md1>
                          Một số loại...
                        </v-flex>
                      </v-layout>                      
                  </v-radio-group> 
                  </v-flex>
                  <v-flex md7 offset-md1>
                    <div v-if="roomTypeRadio ==0" class="body-2 font-weight-bold">
                      Mã sẽ áp dụng cho tất cả các loại phòng.
                    </div>
                    <div v-else>
                      <v-layout row wrap class="pa-0 ma-0 caption" align-center>
                        <v-select
                          label="Chọn loại phòng"
                          :items="roomTypes"
                          v-model="selectedRoomTypes"
                          chips
                          multiple
                          outline
                          color="brown"
                          :item-text="'name'"
                          :item-value="'id'">
                        </v-select>
                      </v-layout>
                    </div>
                  </v-flex>
                </v-layout>                  
              </v-flex>
              <v-flex md5>
                <span class="subheading">Mức giảm (%):&nbsp;</span>
                <input
                  type="number"
                  min="1"
                  max="100"
                  v-model="code.discount"
                  class="text-md-center pa-2 border"
                  style="width:100px"
                />
              </v-flex>
              <v-flex md6 offset-md1>
                <span class="subheading">Số lượng:&nbsp;</span>
                <input
                  type="number"
                  min="1"
                  v-model="code.amount"
                  class="text-md-center pa-2 border"
                  style="width:100px"
                />
              </v-flex>
              <v-flex m12 class="mt-3">
                <v-textarea
                  outline
                  auto-grow
                  rows="6"
                  color="brown"
                  v-model="code.content"
                  label="Nhập nội dung chương trình tại đây..."
                ></v-textarea>
              </v-flex>
            </v-layout>
          </v-form>
        </v-flex>
      </v-layout>
      <v-layout class="pa-0 ma-0" justify-center align-center v-else>
        <div v-if="!createForm">
          <span>{{flag.text}}</span>
        </div>
        <v-flex md12 v-else>
          <v-form ref="form" data-vv-scope="form1" class="pa-1 brown lighten-2">
            <v-layout row wrap class="pa-4 ma-0 white caption" align-center>
              <v-flex md12>
                <div
                  class="ma-3 font-weight-black headline text-md-center text-uppercase brown--text"
                >tạo mã coupon</div>
              </v-flex>
              <v-flex md12>
                <v-divider class="brown"></v-divider>
              </v-flex>
              <v-flex md5>
                <span>Ngày bắt đầu:&nbsp;</span>
                <v-menu
                  ref="start"
                  v-model="mn.menu1"
                  :return-value.sync="code.start_at"
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
                    <v-text-field
                      :error-messages="errors.collect('form1.start_at')"
                      data-vv-name="start_at"
                      v-validate="'required'"
                      type="text"
                      outline
                      color="brown"
                      v-model="startFormatted"
                      readonly
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker light no-title scrollable v-model="code.start_at" :min="now">
                    <v-spacer></v-spacer>
                    <v-btn flat v-on:click="mn.menu1 = false">Hủy</v-btn>
                    <v-btn flat v-on:click="$refs.start.save(code.start_at)">OK</v-btn>
                  </v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex md6 offset-md1>
                <span>Ngày kết thúc:&nbsp;</span>
                <v-menu
                  :disabled="code.start_at.length ===0"
                  ref="end"
                  v-model="mn.menu2"
                  :return-value.sync="code.end_at"
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
                    <v-text-field
                      type="text"
                      outline
                      color="brown"
                      v-model="endFormatted"
                      readonly
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    light
                    no-title
                    scrollable
                    v-model="code.end_at"
                    :min="code.start_at+1"
                  >
                    <v-spacer></v-spacer>
                    <v-btn flat @click="mn.menu2 = false">Hủy</v-btn>
                    <v-btn flat @click="$refs.end.save(code.end_at)">OK</v-btn>
                  </v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex md5>
                <v-text-field
                  :error-messages="errors.collect('form1.code')"
                  data-vv-name="code"
                  v-validate="'required'"
                  type="text"
                  outline
                  color="brown"
                  v-model="code.code"
                  label="Nhập mã coupon tại đây..."
                ></v-text-field>
              </v-flex>
              <v-flex md6 offset-md1>
                <v-textarea
                  :error-messages="errors.collect('form1.title')"
                  data-vv-name="title"
                  v-validate="'required'"
                  type="text"
                  outline
                  auto-grown
                  rows="2"
                  color="brown"
                  v-model="code.title"
                  label="Nhập tiêu đề khuyến mãi tại đây..."
                ></v-textarea>
              </v-flex>
              <v-flex md12 class="mb-3 py-3 border-top border-bottom">
                <span>Áp dụng cho:&nbsp;</span>
                <v-layout align-center class="pa-2 mx-2">
                  <v-flex md4>
                    <v-radio-group v-model="roomTypeRadio" :mandatory="false">
                      <v-layout class="caption mb-2" align-center>
                        <v-flex md1>
                          <v-radio :value="0" color="brown"></v-radio>
                        </v-flex>
                        <v-flex offset-md1>
                          Tất cả loại phòng.
                        </v-flex>
                      </v-layout>                      
                      <v-layout class="caption" align-center>
                        <v-flex md1>
                          <v-radio :value="1" color="orange"></v-radio>
                        </v-flex>
                        <v-flex offset-md1>
                          Một số loại...
                        </v-flex>
                      </v-layout>                      
                  </v-radio-group> 
                  </v-flex>
                  <v-flex md7 offset-md1>
                    <div v-if="roomTypeRadio ==0" class="body-2 font-weight-bold">
                      Mã sẽ áp dụng cho tất cả các loại phòng.
                    </div>
                    <div v-else>
                      <v-layout row wrap class="pa-0 ma-0 caption" align-center>
                        <v-select
                          label="Chọn loại phòng"
                          :items="roomTypes"
                          v-model="selectedRoomTypes"
                          chips
                          multiple
                          outline
                          color="brown"
                          :item-text="'name'"
                          :item-value="'id'">
                        </v-select>
                      </v-layout>
                    </div>
                  </v-flex>
                </v-layout>                  
              </v-flex>
              <v-flex md5>
                <span class="subheading">Mức giảm (%):&nbsp;</span>
                <input
                  type="number"
                  min="1"
                  max="100"
                  v-model="code.discount"
                  class="text-md-center pa-2 border"
                  style="width:100px"
                />
              </v-flex>
              <v-flex md6 offset-md1>
                <span class="subheading">Số lượng:&nbsp;</span>
                <input
                  type="number"
                  min="1"
                  v-model="code.amount"
                  class="text-md-center pa-2 border"
                  style="width:100px"
                />
              </v-flex>
              <v-flex m12 class="mt-3">
                <v-textarea
                  outline
                  auto-grow
                  rows="6"
                  color="brown"
                  v-model="code.content"
                  label="Nhập nội dung chương trình tại đây..."
                ></v-textarea>
              </v-flex>
            </v-layout>
          </v-form>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex md8 v-else>
      <v-layout row wrap class="pa-0 ma-0" justify-center align-start>
        <v-flex md5 class="pa-2 ma-2">
          <v-img :aspect-ratio="1" src="/img/booking/load.gif" style="opacity:0.9">
            <v-layout fill-height align-center justify-center>
              <span class="pa-5 caption brown--text font-weight-bold">đang tải...</span>
            </v-layout>
          </v-img>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex md3 offset-md1>
      <v-card light flat tile>
        <v-card-title>
          <v-layout row wrap class="pa-0 ma-2">
            <v-flex md12>
              <v-btn
                round
                depressed
                color="brown lighten-2"
                @click.stop="createForm = true; resetForm()"
                class="white--text"
                v-if="createForm === false"
              >
                <v-icon small>add</v-icon>&nbsp;Tạo một mã
              </v-btn>
              <v-btn
                round
                depressed
                color="brown lighten-2"
                @click.stop="createForm = false"
                class="white--text"
                v-else
              >
                <v-icon small>close</v-icon>&nbsp;Hủy
              </v-btn>
            </v-flex>
            <v-flex md12>
              <v-divider class="brown"></v-divider>
            </v-flex>
            <v-flex md12 v-if="!createForm">
              <v-radio-group v-model="radio">
                <v-layout row wrap class="pa-0 ma-0">
                  <v-flex md12>
                    <span class="body-1 brown--text">Chọn danh sách:</span>
                  </v-flex>
                  <v-flex md12>
                    <v-layout row wrap class="pa-0 ma-0" align-center>
                      <v-flex md2>
                        <v-radio :value="0" @click="radio = 0; search = ''" color="brown"></v-radio>
                      </v-flex>
                      <v-flex md8 class="caption">
                        Đang diễn ra&nbsp;
                        <span class="font-weight-black">({{couponCode.length}})</span>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                  <v-flex md12>
                    <v-layout row wrap class="pa-0 ma-0" align-center>
                      <v-flex md2>
                        <v-radio :value="1" @click="radio = 1; search = ''" color="orange"></v-radio>
                      </v-flex>
                      <v-flex md8 class="caption">
                        Chưa kích hoạt&nbsp;
                        <span
                          class="font-weight-black"
                        >({{waitingCouponCode.length}})</span>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                  <v-flex md12>
                    <v-layout row wrap class="pa-0 ma-0" align-center>
                      <v-flex md2>
                        <v-radio :value="2" @click="radio = 2;search = ''" color="black"></v-radio>
                      </v-flex>
                      <v-flex md8 class="caption">
                        Hết hạn&nbsp;
                        <span class="font-weight-black">({{expiredCouponCode.length}})</span>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                  <v-flex md12>
                    <v-divider dark></v-divider>
                  </v-flex>
                  <v-flex md12>
                    <div>
                      <div>
                        <span class="body-1 brown--text">Tìm mã...</span>
                      </div>
                      <div>
                        <input type="text" v-model="search" class="pa-2 border" />
                      </div>
                      <div>
                        <span class="grey--text caption font-italic">*nhập coupon-code...</span>
                      </div>
                    </div>
                  </v-flex>
                </v-layout>
              </v-radio-group>
            </v-flex>
            <v-flex v-else>
              <v-chip color="orange" text-color="white" @click="resetForm">Hoàn Tác</v-chip>
              <v-chip color="teal" text-color="white" @click="openConfirm">Tạo</v-chip>
            </v-flex>
          </v-layout>
        </v-card-title>
      </v-card>
    </v-flex>
    <v-dialog persistent v-model="confirm.couponCode" max-width="500">
      <v-card flat title class="pa-2">
        <v-card-text>
          <div>
            <span class="headline font-weight-bold mb-3">Xem lại chi tiết dữ liệu vừa tạo</span>
          </div>
          <v-layout row wrap class="pa-2 ma-0 border" align-center>
            <v-flex md3>
              <div
                class="indigo--text text-md-center font-weight-black display-2"
              >{{code.discount}}%</div>
            </v-flex>
            <v-flex md8 offset-md1>
              <v-layout row wrap class="border-left pa-0 ma-0 px-3" align-center>
                <v-flex md4>
                  <span class="caption">Mã Coupon:</span>
                </v-flex>
                <v-flex md6 offset-md1>
                  <span class="font-weight-black brown--text title">"{{code.code}}"</span>
                </v-flex>
                <v-flex md12>
                  <span class="caption">Nội Dung:</span>
                  <div class="mx-2 pl-1">
                    <div>
                      <span class="font-weight-black body-1">{{code.title}}</span>
                    </div>
                    <div v-if="code.content && code.content.length >0">
                      <span class="font-italic caption">"{{code.content}}"</span>
                    </div>
                  </div>
                </v-flex>
                <v-flex md4>
                  <span class="caption">Ngày Bắt Đầu:</span>
                </v-flex>
                <v-flex md6 offset-md1>
                  <span class="font-weight-black caption">{{formatDate(code.start_at)}}</span>
                </v-flex>
                <v-flex md4>
                  <span class="caption">Ngày Kết Thúc:</span>
                </v-flex>
                <v-flex md6 offset-md1>
                  <span class="font-weight-black caption">{{formatDate(code.end_at)}}</span>
                </v-flex>
                <v-flex md4>
                  <span class="caption">Số Lượng:</span>
                </v-flex>
                <v-flex md6 offset-md1>
                  <span class="font-weight-black caption">{{code.amount}}</span>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-card-text>
        <v-card-actions class="mr-2 mt-0">
          <v-spacer></v-spacer>
          <v-btn round class="white--text font-weight-text mx-2"
            depressed color="red" @click="confirm.couponCode = false"
          >Hủy</v-btn>
          <v-btn round class="white--text font-weight-text mx-2"
            depressed color="green darken-1" @click="submitForm"
          >Đồng Ý</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog persistent v-model="datePicker.state" max-width="450">
      <v-card flat title class="pa-2">
        <v-card-title>
          <input
            type="text"
            v-model="datePicker.endFormatted"
            readonly
            style="width:100%"
            class="pa-3 text-md-center border"
          />
        </v-card-title>
        <v-card-text>
          <v-date-picker
            width="400"
            dark
            no-title
            scrollable
            v-model="datePicker.end_at"
            :min="datePicker.start_at+1"
            class="elevation-0"
          >
            <v-spacer></v-spacer>
            <v-btn round flat @click="datePicker.state = false" color="red">Hủy</v-btn>
            <v-btn round flat @click="addEndDate()" color="teal">OK</v-btn>
          </v-date-picker>
        </v-card-text>
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
                  @click="deleteCode(confirm.id)"
                  v-if="radio !=0"
                >Đồng ý</v-chip>
                <v-chip
                  class="pa-1 ma-2 white--text font-weight-bold"
                  color="green darken-1"
                  @click="endCode(confirm.id)"
                  v-else
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
  data() {
    return {
      roomTypeRadio: 0,
      selectedRoomTypes:[],
      roomTypes:[],
      waiting: false,
      confirm: {
        couponCode:false,
        dialog: false,
        id: 0,
        title: "Bạn có chắc chắn?",
      },
      search: "",
      radio: 0,
      createForm: false,
      tblHeaders: [
        {
          text: "Danh sách khuyến mãi...",
          value: "code",
          align: "",
          class: "brown--text",
          sortable: false,
          width: ""
        },
        {
          text: "",
          value: "",
          align: "",
          class: "orange--text",
          sortable: false,
          width: "10%"
        }
      ],
      flag: {
        state: false,
        text: "Dữ liệu không tìm thấy! Không có chương trình khuyến mãi nào."
      },
      mn: {
        menu1: false,
        menu2: false
      },
      hotelId: this.$route.query.hotelId,
      data: [],
      couponCode: [],
      expiredCouponCode: [],
      waitingCouponCode: [],
      code: {
        code: "",
        discount: 1,
        title: "",
        content: "",
        start_at: "",
        end_at: "",
        amount: 1,
        applied: 0,
      },
      now: this.$moment(new Date()).format("YYYY-MM-DD"),
      startFormatted: "",
      endFormatted: "",
      dictionary: {
        custom: {
          code: {
            required: () => "Vẫn chưa nhập mã cần tạo"
          },
          title: {
            required: () => "Vẫn chưa nhập tiêu đề khuyến mãi cần tạo"
          },
          start_at: {
            required: () => "Chọn một ngày"
          },
        }
      },
      datePicker: {
        state: false,
        id: 0,
        end_at: "",
        start_at: "",
        endFormatted: this.formatDate(this.now)
      }
    };
  },
  created() {
    this.$emit("chooseHotel", this.hotelId);
    console.log(localStorage.getItem('api_token'));
    this.load();
  },
  mounted() {
    console.log(this);
    this.$validator.localize("en", this.dictionary);
  },
  watch: {
    "roomTypeRadio":function(){
      if(this.roomTypeRadio == 0){
        let temp = [];
        this.roomTypes.forEach(element=>{
          temp.push(element.id);
        });
        this.selectedRoomTypes = temp;
      } 
      else{
        this.selectedRoomTypes =[];
      }
    },
    "selectedRoomTypes": function(){
      if(this.selectedRoomTypes.length == this.roomTypes.length){
        this.roomTypeRadio =0;
      }
    },
    "confirm.dialog":function(){
      if(this.confirm.dialog == false) {
        this.confirm.id = 0;
        this.confirm.content = "";
      }
    },
    "datePicker.end_at": function() {
      this.datePicker.endFormatted = this.formatDate(this.datePicker.end_at);
    },
    "code.start_at": function() {
      this.startFormatted = this.formatDate(this.code.start_at);
    },
    "code.end_at": function() {
      this.endFormatted = this.formatDate(this.code.end_at);
    },
    radio: function() {
      switch (this.radio) {
        case 0:
          this.data = this.couponCode;
          break;
        case 1:
          this.data = this.waitingCouponCode;
          break;
        case 2:
          this.data = this.expiredCouponCode;
          break;
      }
    }
  },
  methods: {
    load: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/coupon-code",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("api_token")
        },
        params: {
          hotelId: this.hotelId
        }
      })
        .then(res => {
          this.flag.state = true;
          if (res.data.status == true) {
            this.couponCode = res.data.couponCode;
            this.expiredCouponCode = res.data.expiredCouponCode;
            this.waitingCouponCode = res.data.waitingCouponCode;
            if (this.couponCode.length == 0) {
              if (this.waitingCouponCode.length != 0) {
                this.data = this.waitingCouponCode;
                this.radio = 1;
              } else {
                this.data = this.expiredCouponCode;
                this.radio = 2;
              }
            } else {
              this.radio = 0;
              this.data = this.couponCode;
            }
          } else {
            this.$router.push({ name: "home" });
          }
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            this.router.push({ name: "login" });
          }
        });
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/room-type/"+this.hotelId,
        headers: {
          Authorization: "Bearer " + localStorage.getItem("api_token")
        },
        params: {
          hotelId: this.hotelId
        }
      })
        .then(res => {
          if (res.data.status == true) {
            this.roomTypes = res.data.data;
          }
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            this.router.push({ name: "login" });
          }
        });
    },
    formatDate: function(date) {
      if (!date) return null;
      return this.$moment(date).format("DD-MM-YYYY");
    },
    resetForm: function() {
      this.startFormatted = "";
      this.code.start_at = "";
      this.code.title = "";
      this.code.code = "";
      this.code.content = "";
      this.code.end_at = "";
      this.code.discount = 1;
      this.code.amount = 1;
      this.endFormatted = "";
      this.roomTypeRadio = 0;
      let temp = [];
        this.roomTypes.forEach(element=>{
          temp.push(element.id);
        });
      this.selectedRoomTypes = temp;
      this.$validator.reset();
    },
    openConfirm: function() {
      this.$validator.validateAll("form1").then(valid => {
        if (valid) {
          if(this.roomTypeRadio !=0 && this.selectedRoomTypes.length ==0){
            this.$emit("loadSnackbar", "Dường như bạn bỏ sót bước chọn loại phòng áp dụng!");
          }else{
          this.confirm.couponCode = true;
          }
        }
      });
    },
    openSureConfirm: function(id){
      this.confirm.id = id;
      let index = this.getIndex(id);
      let code = null;
      switch(this.radio){
        case 0:
          code = this.couponCode[index].code;
          this.confirm.title = "Bạn có chắc sẽ dừng áp dụng mã "+code+" ?";
          break;
        case 1:
          code = this.waitingCouponCode[index].code;
          this.confirm.title = "Bạn có chắc sẽ xóa mã "+code+" ?";
          break;        
      }
      this.confirm.dialog = true;
    },
    submitForm: function() {
      this.waiting = true;
      this.$validator.validateAll("form1").then(valid => {
        if (valid) {
          if(this.roomTypeRadio !=0 && this.selectedRoomTypes.length ==0){
            this.$emit("loadSnackbar", "Dường như bạn bỏ sót bước chọn loại phòng áp dụng!");
          }else{
            axios({
              method: "post",
              url: "http://localhost:8000/api/manager/coupon-code",
              headers: {
                Authorization: "Bearer " + localStorage.getItem("api_token")
              },
              data: {
                hotelId: this.hotelId,
                code: this.code,
                radio: this.roomTypeRadio,
                roomTypes: this.selectedRoomTypes,
              }
            })
              .then(res => {
                if (res.data.status == true) {
                  this.resetForm();
                  this.createForm = false;
                  this.confirm.couponCode = false;
                  var newCoupon = res.data.data;
                  if (newCoupon.waiting) {
                    this.waitingCouponCode.push(newCoupon);
                    this.radio = 1;
                  } else {
                    this.couponCode.push(newCoupon);
                    this.radio = 0;
                  }
                  this.search = newCoupon.code;
                } else {
                  if(res.data.message){
                    this.$emit('loadSnackbar',res.data.message);
                  }
                  this.$router.push({ name: "home" });
                }
              })
              .catch(error => {
                console.log(error.response);
                console.log(error);
                if (error.response.status == 401) {
                  this.router.push({ name: "login" });
                }
              }).then(()=>{
                this.waiting = false;
              });
          }
        }
      });
    },
    getIndex(id) {
      var index = 0;
      var arr = [];
      switch (this.radio) {
        case 0:
          arr = this.couponCode;
          break;
        case 1:
          arr = this.waitingCouponCode;
          break;
      }
      if (arr.length != 0) {
        arr.forEach((element, i) => {
          if (element.id == id) index = i;
        });
      }
      return index;
    },
    remove (item) {
        this.selectedRoomTypes.splice(this.selectedRoomTypes.indexOf(item), 1)
        this.selectedRoomTypes = [...this.selectedRoomTypes]
      },
    endCode: function(id) {
      this.confirm.dialog = false;
      this.waiting = true;
      axios({
        method: "put",
        url: "http://localhost:8000/api/manager/coupon-code/" + id,
        headers: {
          Authorization: "Bearer " + localStorage.getItem("api_token")
        },
        params: {
          id: id,
          flag: 0
        }
      })
        .then(res => {
          if (res.data.status == true) {
            var index = this.getIndex(id);
            this.expiredCouponCode.push(res.data.couponCode);
            this.couponCode.splice(index, 1);
            this.radio = 2;
            this.search = res.data.couponCode.code;
          }
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            this.router.push({ name: "login" });
          }
        }).then(()=>{
            this.waiting = false;
        });
    },
    deleteCode(id){
      this.confirm.dialog = false;
      this.waiting = true;
      axios({
        method: "delete",
        url: "http://localhost:8000/api/manager/coupon-code/" + id,
        headers: {
          Authorization: "Bearer " + localStorage.getItem("api_token")
        },
        params: {
          id: id,
        }
      })
        .then(res => {
          if (res.data.status == true) {
            var index = this.getIndex(id);
            this.waitingCouponCode.splice(index,1);
          }
        })
        .catch(error => {
          console.log(error.response);
          console.log(error);
          if (error.response.status == 401) {
            this.router.push({ name: "login" });
          }
        }).then(()=>{
            this.waiting = false;
        })
    },
    openDatePicker: function(item) {
      this.datePicker.state = true;
      this.datePicker.id = item.id;
      if (item.start_at > this.now) this.datePicker.start_at = item.start_at;
      else this.datePicker.start_at = this.now;
      this.datePicker.end_at = "";
      this.datePicker.endFormatted = "";
    },
    addEndDate: function() {
      this.waiting = true;
      axios({
        method: "put",
        url:
          "http://localhost:8000/api/manager/coupon-code/" + this.datePicker.id,
        headers: {
          Authorization: "Bearer " + localStorage.getItem("api_token")
        },
        params: {
          id: this.datePicker.id,
          flag: 1,
          end_at: this.datePicker.end_at
        }
      })
        .then(res => {
          if (res.data.status == true) {
            this.datePicker.state = false;
            var index = this.getIndex(this.datePicker.id);
            switch (this.radio) {
              case 0:
                this.couponCode[index].end_at = this.datePicker.end_at;
                this.search = this.couponCode[index].code;
                break;
              case 1:
                this.waitingCouponCode[index].end_at = this.datePicker.end_at;
                this.search = this.waitingCouponCode[index].code;
                break;
            }
          }
        })
        .catch(error => {
          console.log(error);
          console.log(error.response);
          if (error.response.status == 401) {
            this.router.push({ name: "login" });
          }
        }).then(()=>{
            this.waiting = false;
        });
    }
  }
};
</script>

<style scoped>
</style>