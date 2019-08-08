<template>
  <v-layout row wrap>
    <v-flex shrink md12>
      <v-img :aspect-ratio="16/4" src="/blog/img/slider/slider.png">
        <v-layout row wrap justify-center align-center fill-height>
          <v-flex md8 class="pa-1 black">
            <v-form ref="form">
              <v-layout row wrap class="search-form text-xs-center" align-center justify-center>
                <v-flex md4 class="ma-4 text-xs-left">
                  <span class="title">Tỉnh/thành phố</span>
                  <v-text-field
                    color="teal"
                    outline
                    type="text"
                    v-model="placeVal"
                    label="Bạn muốn đến đâu..."
                  ></v-text-field>
                </v-flex>
                <v-flex md2 class="ma-4 text-xs-left">
                  <span class="title">Check-in</span>
                  <v-menu
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
                        outline
                        color="teal"
                        type="text"
                        v-model="checkInFormattedVal"
                        hint="DD/MM/YYYY format"
                        persistent-hint
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker scrollable no-title v-model="checkInVal" :min="now"></v-date-picker>
                  </v-menu>
                </v-flex>
                <v-flex md2 class="ma-4 text-xs-left">
                  <span class="title">Check-out</span>
                  <v-menu
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
                        outline
                        color="teal"
                        type="text"
                        v-model="checkOutFormattedVal"
                        hint="DD/MM/YYYY format"
                        persistent-hint
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker scrollable no-title v-model="checkOutVal" :min="checkInVal+1"></v-date-picker>
                  </v-menu>
                </v-flex>
                <v-flex md1 class="ma-4 text-xs-left">
                  <v-btn
                    color="teal"
                    dark
                    icon
                    large
                    depressed
                    style="width:60px; height:60px;"
                    v-on:click="search"
                  >
                    <v-icon large>search</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-form>
          </v-flex>
        </v-layout>
      </v-img>
      <v-layout row wrap class="ma-4" justify-center align-center>
        <v-flex md12>
          <v-divider></v-divider>
        </v-flex>
        <v-flex md12>
      <v-layout row wrap class="pa-0 ma-4" align-center>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg green--text fas fa-broom"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg yellow--text fas fa-guitar"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg indigo--text fas fa-cookie-bite"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg red--text fas fa-palette"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg grey--text fas fa-umbrella"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg orange--text fas fa-tree"></i>
          </div>
        </v-flex>
      </v-layout>
    </v-flex>
        <v-flex md12>
          <v-divider></v-divider>
        </v-flex>
        <v-flex md6>
          <v-card light flat tile>
            <v-card-title>
              <span class="display-3 font-weight-bold">Welcome to Trvel</span>
              <v-card-text
                class="headline px-0"
              >Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi odio minima ea error iure esse enim deleniti voluptas tempora accusantium labore, molestiae, exercitationem consequatur quos culpa est laborum blanditiis a!</v-card-text>
            </v-card-title>
            <v-card-text>
              CEO:
              <a href="#">Nguyen Tran Hoang Thang</a>
            </v-card-text>
          </v-card>
        </v-flex>
        <v-flex md6>
          <v-layout row wrap>
            <v-flex md6 class="pa-1">
              <v-img :aspect-ratio="1/2" src="/blog/img/bg-img/15.jpg"></v-img>
            </v-flex>
            <v-flex md6 class="pa-1">
              <v-img class="mb-1" :aspect-ratio="1" src="/blog/img/bg-img/13.jpg"></v-img>
              <v-img :aspect-ratio="1" src="/blog/img/bg-img/5.jpg"></v-img>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex md12>
          <v-divider></v-divider>
        </v-flex>
        <v-flex md12>
          <v-divider></v-divider>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex shrink md12>
      <v-layout row wrap class="pa-0 ma-0 mx-4">
        <v-flex md4 class="pa-3">
          <div>
            <div>
              <span class="title">Join us</span>
            </div>
            <div>
              <span
                class="body-2"
              >Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus expedita itaque blanditiis quisquam, quis accusamus id voluptatem nulla excepturi praesentium aut impedit nobis, quas fugiat porro. Magnam minus incidunt laboriosam?</span>
            </div>
          </div>
        </v-flex>
        <v-flex md4 class="pa-3">
          <div>
            <div>
              <span class="title">Trvel is better?</span>
            </div>
            <div>
              <span
                class="body-2"
              >Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fuga inventore obcaecati, at dicta omnis voluptatem voluptas molestias pariatur enim dolor commodi temporibus velit distinctio vero natus asperiores repellat totam quam.</span>
            </div>
          </div>
        </v-flex>
        <v-flex md4 class="pa-3">
          <div>
            <div>
              <span class="title">We care about our members.</span>
            </div>
            <div>
              <span
                class="body-2"
              >Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas et distinctio quasi, eum repellat rem mollitia. Animi porro ducimus natus adipisci excepturi odit, qui accusamus minus voluptate sunt perferendis sapiente?</span>
            </div>
          </div>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex md12>
      <v-divider></v-divider>
    </v-flex>
    <v-flex shrink md12>
      <v-layout row wrap class="pa-0 ma-0 mx-4" align-center>
        <v-flex md8 class="py-3">
          <div class="my-3">
            <span class="display-1">Sowmewhere in your hometown</span>
          </div>
          <div class="pl-3 subheading">
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque culpa corrupti iure tenetur commodi, error obcaecati perspiciatis, quis eos veritatis est fuga, sint nobis nisi incidunt nihil aliquid laudantium totam.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas dolores deserunt officia reprehenderit commodi eos exercitationem, voluptates nihil, pariatur recusandae asperiores doloremque harum vel, atque sunt accusamus nemo modi. Aliquam!
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quo eum alias in hic culpa quas, impedit earum harum voluptate beatae adipisci explicabo sequi, incidunt distinctio architecto atque qui esse reprehenderit.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima sunt numquam ut suscipit alias dolorum reiciendis nostrum tempora error perferendis corrupti rerum quos, laborum aut omnis. Suscipit ratione inventore architecto.
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Et voluptatibus deserunt quidem nesciunt at accusamus debitis sequi nisi suscipit, molestias mollitia dignissimos, minus quas repudiandae magni repellat, odit beatae provident.
          </div>
        </v-flex>
        <v-flex md4 class="pa-3">
          <v-img :aspect-ratio="4/3" src="/blog/img/slider/travel.png"></v-img>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex md12>
      <v-divider></v-divider>
    </v-flex>
    <v-flex md12>
      <v-layout row wrap class="pa-0 ma-4" align-center>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg green--text fas fa-leaf"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg yellow--text fas fa-lemon"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg indigo--text fas fa-cloud-meatball"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg red--text fas fa-candy-cane"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg grey--text fas fa-puzzle-piece"></i>
          </div>
        </v-flex>
        <v-flex>
          <div class="text-md-center">
            <i class="fa-lg orange--text fas fa-carrot"></i>
          </div>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex md12>
      <v-divider></v-divider>
    </v-flex>
    <v-flex shrink md12>
      <v-layout row wrap class="pa-0 ma-0 mx-4" align-center>
        <v-flex md8 class="py-3">
          <div class="my-3">
            <span class="display-1">Go somewhere peace.</span>
          </div>
          <div class="pl-3 subheading">
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eaque culpa corrupti iure tenetur commodi, error obcaecati perspiciatis, quis eos veritatis est fuga, sint nobis nisi incidunt nihil aliquid laudantium totam.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas dolores deserunt officia reprehenderit commodi eos exercitationem, voluptates nihil, pariatur recusandae asperiores doloremque harum vel, atque sunt accusamus nemo modi. Aliquam!
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quo eum alias in hic culpa quas, impedit earum harum voluptate beatae adipisci explicabo sequi, incidunt distinctio architecto atque qui esse reprehenderit.
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima sunt numquam ut suscipit alias dolorum reiciendis nostrum tempora error perferendis corrupti rerum quos, laborum aut omnis. Suscipit ratione inventore architecto.
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Et voluptatibus deserunt quidem nesciunt at accusamus debitis sequi nisi suscipit, molestias mollitia dignissimos, minus quas repudiandae magni repellat, odit beatae provident.
          </div>
        </v-flex>
        <v-flex md4 class="pa-3">
          <v-img :aspect-ratio="4/3" src="/blog/img/slider/pool.png"></v-img>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex shrink md12>
      <v-layout row wrap class="pa-0 ma-4"></v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  props: {
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
      val: 25,
      placeVal: "",
      checkInVal: "",
      checkInFormattedVal: "",
      checkOutVal: "",
      checkOutFormattedVal: ""
    };
  },
  computed: {},
  watch: {
    placeVal: "loadSearchData",
    checkIn: "setSearchValue",
    checkInVal: "loadSearchData",
    checkIn: "setSearchValue",
    checkOutVal: "loadSearchData",
    checkOut: "setSearchValue"
  },
  created() {
    this.setSearchValue();
  },
  methods: {
    loadSearchData: function() {
      this.$emit("loadSearchData", {
        place: this.placeVal,
        checkIn: this.checkInVal,
        checkInFormatted: this.checkInFormattedVal,
        checkOut: this.checkOutVal,
        checkOutFormatted: this.checkOutFormattedVal
      });
    },
    search: function() {
      this.$router.push({
        path: "searching",
        query: {
          place: this.place.replace(/\s/g, "-"),
          check_in: this.checkIn,
          check_out: this.checkOut
        }
      });
    },
    setSearchValue: function() {
      this.placeVal = this.place;
      this.checkInVal = this.checkIn;
      this.checkOutVal = this.checkOut;
      this.checkInFormattedVal = this.checkInFormatted;
      this.checkOutFormattedVal = this.checkOutFormatted;
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
.search-form {
  opacity: 0.9;
  background-color: #fff;
}
</style>
