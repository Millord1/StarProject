<template>
  <MainPage ></MainPage>
</template>

<script lang="ts">
import Vue from 'vue'

import '~/assets/css/index.css'
import MainPage from "~/components/MainPage.vue";

export default Vue.extend({
  name: 'IndexPage',
  components: {MainPage}
})
</script>
