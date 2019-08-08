<template>
  <div>
    <v-container>
      {{test}}
      <v-card class="blue-grey darken-4 p-2">
        <v-expansion-panel v-model="panel" v-bind:popout="popout" expand class="elevation-0">
          <h1 class="white--text">Cơ sở vật chất</h1>
          <v-expansion-panel-content
            v-for="(feature,index) in features"
            :key="index"
            class
            hide-actions
          >
            <template v-slot:header>
              <v-layout row align-content-center>
                <v-flex xs11 align-self-center>
                  <i class="m-2 fas" :class="icon = 'fa-'+feature.feature.icon"></i>
                  <span class="title">{{feature.feature.name}}</span>
                </v-flex>
                <v-flex xs1 align-self-center>
                  <v-switch
                    color="primary"
                    v-model="panel[index]"
                    :value="panel[index]"
                    @click.native.stop
                    @change="chooseFeature(index,feature.feature.id)"
                  ></v-switch>
                  <!-- <v-btn @click="choose">asd</v-btn> -->
                </v-flex>
              </v-layout>
            </template>
            <v-container class="grey lighten-3">
              <v-layout justify-center row wrap>
                <v-radio-group v-model="feature.room.radio" row>
                  <v-radio
                    color="primary"
                    @change="chooseAll(index,feature.feature.id)"
                    label="Áp dụng cho tất cả phòng"
                    value="all"
                  ></v-radio>
                  <v-radio
                    color="primary"
                    label="Áp dụng cho 1 số phòng :"
                    value="some"
                    @change="chooseSome(index,feature.feature.id)"
                  ></v-radio>
                </v-radio-group>
                <v-select
                  dense
                  chips
                  multiple
                  outline
                  single-line
                  v-if="feature.room.radio =='some'"
                  v-model="feature.room.chosenRoom"
                  :items="arrayRoom"
                  item-text="full_name"
                  item-value="id"
                  label="Choose room(s)"
                  @change="chooseRoom(index,feature.feature.id)"
                >
                  <template v-slot:selection="{ item, index }">
                    <v-chip v-if="index === 0">
                      <span>{{ item.full_name }}</span>
                    </v-chip>
                    <span
                      v-if="index === 1"
                      class="grey--text caption"
                    >(+{{ feature.room.chosenRoom.length - 1 }} others)</span>
                  </template>
                </v-select>
              </v-layout>
              <v-layout wrap>
                <span xs-4 offset-xs4></span>
                <v-flex xs-4></v-flex>
              </v-layout>
            </v-container>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-card>
      <!-- <h1>features</h1> -->
      <!-- <v-layout row wrap v-for="(feature,index) in arrayfeature" :key="index">
        <v-flex xs12>{{feature.name}}</v-flex>
      </v-layout>-->
    </v-container>
  </div>
</template>
<script>
export default {
  props: {
    api_token: {
      type: String
    }
  },
  data: function() {
    return {
      popout: false,
      features: [],
      panel: [],
      chosenRoom: [],
      radioGroup: [],
      firsttime: false,
      hotelId: this.$route.query.hotelId,
      test: "",
      arrayRoom: []
    };
  },
  created() {
    this.$emit("chooseHotel", this.hotelId);
    this.initialize();
  },
  watch: {
    panel: function(newArray, oldArray) {
      if (this.firsttime == false) {
        for (var i = 0; i < newArray.length; i++) {
          if (newArray[i] != oldArray[i]) {
            var index = i;
            var featureId = this.features[i].feature.id;
          }
        }
        this.chooseFeature(index, featureId);
      }
      if (this.firsttime == true) {
        this.firsttime = false;
      }
    }
  },
  methods: {
    initialize: function() {
      console.log("start");
      this.firsttime = true;
      axios
        .get("http://localhost:8000/api/manager/feature", {
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
            this.features = response.data.data;
            this.panel = response.data.panel;
          }
        })
        .catch(function(error) {
          console.log(error.response);
        });
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
    chooseFeature: function(index, featureId) {
      if (this.panel[index] === null) {
        this.panel[index] = false;
      }
      if (this.panel[index] == true) {
        console.log(this.features[index].room.chosenRoom);
        axios({
          method: "post",
          url: "http://localhost:8000/api/manager/add-feature",
          data: {
            hotelId: this.hotelId,
            featureId: featureId,
            chosenRoom: this.features[index].room.chosenRoom,
            radio: this.features[index].room.radio
          },
          headers: {
            Authorization: "Bearer " + this.api_token
          }
        })
          .then(response => {
            console.log(response);
          })
          .catch(error => {
            console.log(error.response);
          });
      } else {
        this.chooseSome(index,featureId);
      }
    },
    chooseAll: function(index, featureId) {
      console.log(index);
      console.log(featureId);
      axios({
        method: "post",
        url: "http://localhost:8000/api/manager/add-all-feature",
        data: {
          hotelId: this.hotelId,
          featureId: featureId
        },
        headers: {
          Authorization: "Bearer " + this.api_token
        }
      })
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    chooseRoom: function(index, featureId) {
      axios({
        method: "post",
        url: "http://localhost:8000/api/manager/add-feature-room",
        data: {
          hotelId: this.hotelId,
          featureId: featureId,
          chosenRoom: this.features[index].room.chosenRoom
        },
        headers: {
          Authorization: "Bearer " + this.api_token
        }
      })
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    chooseSome: function(index,featureId) {
      axios({
        method: "delete",
        url: "http://localhost:8000/api/manager/remove-feature-room",
        data: {
          hotelId: this.hotelId,
          featureId: featureId
        },
        headers: {
          Authorization: "Bearer " + this.api_token
        }
      })
        .then(response => {
          console.log(response);
          this.features[index].room.chosenRoom = null;
        })
        .catch(error => {
          console.log(error.response);
        });
    }
  }
};
</script>

