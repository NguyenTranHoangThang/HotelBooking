<template>
  <v-layout row wrap>
    <v-flex shrink md12>
      <v-img :aspect-ratio="16/2" src="/blog/img/slider/slider.png" class="pt-5">
        <v-layout row wrap justify-center>
          <v-flex md10 class="text-md-center">
            <span class="title text-uppercase font-weight-black">Về Chúng Tôi</span>
          </v-flex>
          <v-flex md10 class="text-md-center">
            <router-link :to="{name:'home'}">Trang Chủ</router-link>&nbsp;/&nbsp;Về Chúng Tôi
          </v-flex>
        </v-layout>
      </v-img>
      <v-layout row wrap justify-center align-center class="my-4">
        <v-flex md3>
          <v-img :aspect-ratio="1/1" src="/blog/img/hotel/30.png"></v-img>
        </v-flex>
        <v-flex md8>
          <v-card light flat tile>
            <v-card-title>
              <span class="display-3 font-weight-bold">Welcome to Trvel- Hotel Club</span>
              <v-card-text
                class="headline px-0"
              >Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi odio minima ea error iure esse enim deleniti voluptas tempora accusantium labore, molestiae, exercitationem consequatur quos culpa est laborum blanditiis a!</v-card-text>
            </v-card-title>
          </v-card>
        </v-flex>
      </v-layout>
      <v-divider></v-divider>
      <v-layout row wrap justify-center align-center class="my-4">
        <v-flex md3>
          <v-img :aspect-ratio="1/1" src="/blog/img/hotel/27.png"></v-img>
        </v-flex>
        <v-flex md8>
          <v-card light flat tile>
            <v-card-title>
              <span class="display-3 font-weight-bold">What's Trvel?</span>
              <v-card-text
                class="headline px-0"
              >Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi odio minima ea error iure esse enim deleniti voluptas tempora accusantium labore, molestiae, exercitationem consequatur quos culpa est laborum blanditiis a!</v-card-text>
            </v-card-title>
          </v-card>
        </v-flex>
      </v-layout>
      <v-divider></v-divider>
      <v-layout row wrap justify-center align-center class="my-4">
        <v-flex md3>
          <v-img :aspect-ratio="1/1" src="/blog/img/hotel/16.png"></v-img>
        </v-flex>
        <v-flex md8>
          <v-card light flat tile>
            <v-card-title>
              <span class="display-3 font-weight-bold">What's do we do?</span>
              <v-card-text class="headline px-0">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi odio minima ea error iure esse enim deleniti voluptas tempora accusantium labore, molestiae, exercitationem consequatur quos culpa est laborum blanditiis a!
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde recusandae dolore libero totam optio sint iste itaque tempora aperiam possimus ea vero, reiciendis quisquam, quo neque. Reiciendis molestias obcaecati quia.
              </v-card-text>
            </v-card-title>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  mounted() {
  },
  data() {
    return {};
  },
  created() {}
};
</script>
