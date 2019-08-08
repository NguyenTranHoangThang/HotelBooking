<template></template>

<script>
export default {
  created() {
    console.log(this.$route.params.id);
    if (this.$route.params.id == null || this.$route.params.id <=0) {
      this.$router.push({ name: "home" });
    }
  }
};
</script>
