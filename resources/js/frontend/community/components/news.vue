<template>
  <v-layout justify-center align-center row wrap>
    <v-flex shrink xs6>News1</v-flex>
    <v-flex shrink xs6>
        <div>Now:&nbsp;{{formatDate(now)}}</div>
        <div>Nex:&nbsp;{{formatDate(next)}}</div>
        <div>Yesterday:&nbsp;{{formatDate(yesterday)}}</div>
        <div>{{text}}</div>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  data() {
    return {
        now: new Date(),
        next: this.$moment(this.now).add(1,'days'),
        yesterday: this.$moment(this.now).add(-90,'days'),
        text:this.$moment("2019-03-21 11:00:00").fromNow(true),
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  created() {},
  methods: {
    formatDate: function(date) {
      if (!date) return null;
      return this.$moment(date).format("DD-MM-YYYY");
    },
  }
};
</script>
