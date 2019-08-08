<template>
  <div>
    <v-toolbar flat color="white">
      <v-toolbar-title>My hotels</v-toolbar-title>
      <v-divider class="mx-2" inset vertical></v-divider>
      <v-spacer></v-spacer>

      <v-dialog v-model="dialog" max-width="75%" scrollable>
        <template v-slot:activator="{ on }">
          <v-btn color="primary" dark class="mb-2" v-on="on" @click="getHotelTypes">New Item</v-btn>
        </template>
        <!-- <v-card>
          <v-card-title>
            <span class="headline">{{ formTitle }}</span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedItem.name" label="Dessert name"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedItem.calories" label="Calories"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedItem.fat" label="Fat (g)"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedItem.carbs" label="Carbs (g)"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedItem.protein" label="Protein (g)"></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click="close">Cancel</v-btn>
            <v-btn color="blue darken-1" flat @click="save">Save</v-btn>
          </v-card-actions>
        </v-card>-->
        <v-layout row>
          <v-flex>
            <v-card width="100%" class="p-5">
              <v-card-title>
                <h1>{{formTitle}}</h1>
              </v-card-title>
              <v-card-text height="100px">
                <v-form>
                  <v-layout>
                    <v-text-field class="pr-3" label="Hotel name"></v-text-field>
                    <v-select
                      class="pl-3"
                      item-text="text"
                      :items="arrayHotelType"
                      label="Hotel type"
                    ></v-select>
                  </v-layout>
                  <v-layout>
                    <v-select item-text="text" :items="arrayHotelType" label="Province"></v-select>
                    <v-select
                      class="px-5"
                      item-text="text"
                      :items="arrayHotelType"
                      label="District"
                    ></v-select>
                    <v-select item-text="text" :items="arrayHotelType" label="Ward"></v-select>
                  </v-layout>
                  <v-text-field label="Address"></v-text-field>
                  <v-layout>
                    <v-text-field label="Phone number"></v-text-field>
                    <v-text-field label="Fax number"></v-text-field>
                  </v-layout>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="save">Create</v-btn>
                <v-btn color="blue darken-1" flat>Reset</v-btn>
                <v-btn color="blue darken-1" flat>Cancel</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-dialog>
    </v-toolbar>
    <v-data-table :headers="headers" :items="arrayHotel" class="elevation-1">
      <template v-slot:items="props">
        <td>{{ props.item[1].name }}</td>
        <td class>{{ props.item[1].hotel_type }}</td>
        <td class>{{ props.item[1].review_point }}</td>
        <td class>{{ props.item[1].credit_card }}</td>
        <td class="justify-center layout px-0">
          <v-icon small class="mr-2" @click="editItem(props.item)">edit</v-icon>
          <v-icon small @click="deleteItem(props.item)">delete</v-icon>
        </td>
      </template>
      <template v-slot:no-data>
        <v-alert :value="true" color="error" icon="warning">Sorry, nothing to display here :(</v-alert>
        <!-- <v-btn color="primary" @click="initialize">Reset</v-btn> -->
      </template>
      <template v-slot:no-results>
        <v-alert
          :value="true"
          color="error"
          icon="warning"
        >Your search for "{{ search }}" found no results.</v-alert>
      </template>
    </v-data-table>
  </div>
</template>
<script>
export default {
  props: {
    foo: {
      type: String
    }
  },
  data: () => ({
    search: "",
    dialog: false,
    headers: [
      {
        text: "Hotel name",
        align: "center",
        sortable: true,
        value: "name"
      },
      { text: "hotel_type", value: "hotel_type", align: "center" },
      { text: "review_point", value: "review_point", align: "center" },
      { text: "credit_card", value: "credit_card", align: "center" },
      { text: "Actions", value: "action", sortable: false, align: "center" }
    ],
    desserts: [],
    editedIndex: -1,
    editedItem: {
      name: "",
      hotel_type: 0,
      review_point: 0,
      credit_card: 0
    },
    defaultItem: {
      name: "",
      hotel_type: 0,
      review_point: 0,
      credit_card: 0
    },
    arrayHotel: [],
    arrayHotelType: []
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Hotel" : "Edit Item";
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  created() {
    // _this = this;
    this.initialize();
  },

  methods: {
    initialize() {
      this.desserts = [
        {
          name: "Frozen Yogurt",
          calories: 159,
          fat: 6.0,
          carbs: 24,
          protein: 4.0
        },
        {
          name: "Ice cream sandwich",
          calories: 237,
          fat: 9.0,
          carbs: 37,
          protein: 4.3
        },
        {
          name: "Eclair",
          calories: 262,
          fat: 16.0,
          carbs: 23,
          protein: 6.0
        },
        {
          name: "Cupcake",
          calories: 305,
          fat: 3.7,
          carbs: 67,
          protein: 4.3
        },
        {
          name: "Gingerbread",
          calories: 356,
          fat: 16.0,
          carbs: 49,
          protein: 3.9
        },
        {
          name: "Jelly bean",
          calories: 375,
          fat: 0.0,
          carbs: 94,
          protein: 0.0
        },
        {
          name: "Lollipop",
          calories: 392,
          fat: 0.2,
          carbs: 98,
          protein: 0
        },
        {
          name: "Honeycomb",
          calories: 408,
          fat: 3.2,
          carbs: 87,
          protein: 6.5
        },
        {
          name: "Donut",
          calories: 452,
          fat: 25.0,
          carbs: 51,
          protein: 4.9
        },
        {
          name: "KitKat",
          calories: 518,
          fat: 26.0,
          carbs: 65,
          protein: 7
        }
      ];
      var _this = this;
      axios
        .get("http://localhost:8000/api/manager/hotel", {
          params: {
            api_token: 123
          }
        })
        .then(response => {
          var arrayHotel = response.data.data;
          _this.arrayHotel = Object.entries(arrayHotel);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.desserts.indexOf(item);
      confirm("Are you sure you want to delete this item?") &&
        this.desserts.splice(index, 1);
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
        console.log("hi");
      } else {
        console.log("yeah");
        this.desserts.push(this.editedItem);
      }
      this.close();
    },
    getHotelTypes: function() {
      var _this = this;
      axios
        .get("http://localhost:8000/api/manager/hotel-type", {
          params: {
            api_token: 123
          }
        })
        .then(response => {
          console.log(response.data.data);
          var arrayHotelType = response.data.data;
          _this.arrayHotelType = arrayHotelType;
        })
        .catch(function(error) {
          console.log(error);
        });
      console.log("array" + _this.arrayHotelType);
    }
  }
};
</script>