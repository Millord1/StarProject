<template>
  <div class="d-flex flex-column align-items-center">
    <h1>Profile Browser</h1>

    <div v-for="star in this.stars" class="m-1 col-md-3">
      <b-btn block href="#" v-b-toggle="'accordion-' + star.first_name">
        {{ star.first_name }} {{ star.last_name }}
      </b-btn>
      <b-collapse :id="'accordion-' + star.first_name" accordion="my-accordion" role="tabpanel">

        <b-card
          :img-src="star.img"
          img-alt="Card image"
          img-height="30%"
          img-width="30%"
          img-top>

          <b-card-body :title="star.first_name+' '+star.last_name">

          <b-card-text>
            {{ star.description }}
          </b-card-text>

          </b-card-body>
        </b-card>

      </b-collapse>
    </div>


  </div>
</template>

<script>
export default {

  name: 'stars',
  props: {
    stars: []
  },

  methods: {
    async fetchData() {
      try {
        const response = await fetch('http://localhost:8000/api/stars');
        this.stars = await response.json();
      } catch (error) {
        console.error(error);
      }
    },

  },

  beforeMount() {
    this.fetchData()
  }
}
</script>
