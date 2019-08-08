<template>
  <v-container grid-list-md text-xs-center>
    <v-layout row wrap>
      <v-flex xs3>
        <v-avatar :tile="tile" :size="avatarSize" color="grey lighten-4">
          <img src="https://vuetifyjs.com/apple-touch-icon-180x180.png" alt="avatar">
        </v-avatar>
        <v-card dark color="#222f3e">
          <v-card-text class="px-0">{{user.name}}</v-card-text>
          <v-btn large color="#00b894" v-if="active"><router-link :to="{name:'home'}">Home</router-link></v-btn>
          <v-card-text class="text-sm-left">
            User following: {{userFollowing}}
            <br>
            Hotel following: {{hotelFollowing}}
            <br>
            Followers: {{followers}}
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs9>
        
        <v-card class="mx-auto m-5" color="#26c6da" dark max-width="700">
          <v-card-title>
            <v-icon large left>chat</v-icon>
            <v-card-text class="text-sm-left headline font-weight-bold">Lorem ipsum dolor sit amet consectetur adipisicing elit.</v-card-text>
          </v-card-title>
          <v-card-text
            class="text-sm-left"
          >"Lorem ipsum dolor sit amet consectetur adipisicing elit. Id velit, inventore pariatur reiciendis sapiente dignissimos voluptate. Voluptatem id nulla iure quo quidem, vitae libero laboriosam. Adipisci nostrum nobis pariatur temporibus."</v-card-text>
        </v-card>
        <v-spacer></v-spacer>
        <v-card class="mx-auto m-5" color="#26c6da" dark max-width="700">
          <v-card-title>
            <v-icon large left>chat</v-icon>
            <v-card-text class="text-sm-left headline font-weight-bold">Lorem ipsum dolor sit amet consectetur adipisicing elit.</v-card-text>
          </v-card-title>
          <v-card-text
            class="text-sm-left"
          >"Lorem ipsum dolor sit amet consectetur adipisicing elit. Id velit, inventore pariatur reiciendis sapiente dignissimos voluptate. Voluptatem id nulla iure quo quidem, vitae libero laboriosam. Adipisci nostrum nobis pariatur temporibus."</v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      active: true,
      tile: false,
      avatarSize: "100px",
      id: 3,
      user: [],
      customer: [],
      userFollowing: 0,
      hotelFollowing: 0,
      followers: 0
    };
  },
  created() {
    this.getData();
  },
  methods: {
    getData: function() {
      axios({
        method: "get",
        url: "http://localhost:8000/api/user/" + this.id,
        params: {
          id: this.id
        }
      }).then(res => {
        console.log(res.data.user.name);
        if (!res.data.status) {
          this.$router.push({ name: "login" });
          return;
        }
        this.user = res.data.user;
        this.customer = res.data.customer;
        this.userFollowing = res.data.userFollowing;
        this.hotelFollowing = res.data.hotelFollowing;
        this.followers = res.data.followers;
        return;
      });
    }
  }
};
</script>
