<template>
  <v-card light flat tile>
    <v-layout row wrap class="hotel-tab">
      <v-flex md12>
        <div id="map"></div>
      </v-flex>
      <v-flex md3>
        <h3>ATM</h3>
        <ul>
          <li v-for="(item,i) in atm" :key="i">{{item.name}}</li>
        </ul>
      </v-flex>
      <v-flex md3>
        <h3>Nhà hàng</h3>
        <ul>
          <li v-for="(item,i) in restaurant" :key="i">{{item.name}}</li>
        </ul>
      </v-flex>
      <v-flex md3>
        <h3>Pub</h3>
        <ul>
          <li v-for="(item,i) in pub" :key="i">{{item.name}}</li>
        </ul>
      </v-flex>
      <v-flex md3>
        <h3>Siệu thị</h3>
        <ul>
          <li v-for="(item,i) in supermarket" :key="i">{{item.name}}</li>
        </ul>
      </v-flex>
    </v-layout>
  </v-card>
</template>
<script>
export default {
  props: {
    hotelAddress: {
      type: String
    }
  },
  data: function() {
    return {
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
      map: null
    };
  },
  created() {},
  watch: {
    hotelAddress: function() {
      this.address = this.hotelAddress;
    },
    address: function(newVal, oldVal) {
      
      if (newVal != oldVal) {
        // this.map = L.map("map", {
        //   center: [10, 10],
        //   zoom: 14
        // });
        var temp = newVal;
        this.getAddress(newVal);
      }
      // this.getAddress();
      // if (this.firstTime == true) {
      //   this.firstTime = false;
      //   alert("first");
      // } else {
      //   alert("second");
      //   this.getAddress();
      // }
    },
  },
  methods: {
    formatKey: function(string) {
      return  string.replace(/\s/g, "+");
      console.log(this.address);
    },
    getAddress: function(address) {
      this.formatKey(address);
      axios({
        method: "get",
        url: "http://localhost:8000/api/manager/map",
        params: {
          address: this.formatKey(address),
        }
      })
        .then(response => {
          console.log(response);
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
              zoom: 14,
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
            var marker = L.marker(ll)
              .addTo(map)
              .bindTooltip("hotel name")
              .openTooltip();
            this.addMarker(map, this.atm);
          }
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    addMarker: function(map, array) {
      array.forEach(element => {
        var marker = L.marker([element.lat, element.lon])
          .addTo(map)
          .bindTooltip(element.name);
      });
    }
  },
  mounted() {
    this.active = true;
    // this.map = L.map("map", {
    //   center: [10,10],
    //   zoom: 14,
    // });
  }
};
</script>


