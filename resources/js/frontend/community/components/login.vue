<template></template>

<script>
export default {
  props: {
    check:{
        type: Boolean
    }
  },
  created() {
      // if(this.check || localStorage.getItem('login_token') != null){
      //     this.$router.push({ name: "home" });
      // }
  }
};
</script>
