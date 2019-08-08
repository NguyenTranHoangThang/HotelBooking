<template>
  <v-layout row wrap>
    <v-flex shrink md12>
      <v-img :aspect-ratio="16/2" src="/blog/img/slider/slider.png" class="pt-5">
        <v-layout row wrap justify-center>
          <v-flex md10 class="text-md-center">
            <span class="title text-uppercase font-weight-black">Liên Hệ</span>
          </v-flex>
          <v-flex md10 class="text-md-center">
            <router-link :to="{name:'home'}">Trang Chủ</router-link>&nbsp;/&nbsp;Liên Hệ
          </v-flex>
        </v-layout>
      </v-img>
      <v-layout row wrap justify-center align-center class="my-1">
        <v-flex md4 class="pl-5">
          <v-list>
            <v-list-tile>
              <v-list-tile-avatar>
                <v-avatar size="40px" color="black">
                  <v-icon medium color="white">phone</v-icon>
                </v-avatar>
              </v-list-tile-avatar>
              <v-list-tile-title>1234567890</v-list-tile-title>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-avatar>
                <v-avatar size="40px" color="black">
                  <v-icon medium color="white">mail</v-icon>
                </v-avatar>
              </v-list-tile-avatar>
              <v-list-tile-title>xxx@gmail.com</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-flex>
        <v-flex md8 class="text-md-center pa-3">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.9551334403727!2d106.67572371411624!3d10.7379414628408!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f62a90e5dbd%3A0x674d5126513db295!2zxJDhuqFpIEjhu41jIEPDtG5nIE5naOG7hyBTw6BpIEfDsm4!5e0!3m2!1svi!2s!4v1560999432503!5m2!1svi!2s"
            width="100%"
            height="500"
            frameborder="0"
            style="border:0"
            allowfullscreen
          ></iframe>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  mounted() {
  }
};
</script>
