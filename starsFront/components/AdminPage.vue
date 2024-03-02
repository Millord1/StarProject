<template>

  <div class="d-flex flex-column align-items-center">

    <b-card-group deck v-for="star in this.stars" class="m-1 col-6">

      <b-card no-body class="overflow-hidden" style="max-width: 540px;">
        <b-row no-gutters>
          <b-col md="6">
            <b-card-img :src="star.img" alt="Image" class="rounded-0"></b-card-img>
          </b-col>
          <b-col md="6">
            <b-card-body :title="star.first_name+' '+star.last_name">

              <b-card-text>
                {{ star.description }}
              </b-card-text>
              <b-button-group>
                <b-button class="mt-auto" href="#" variant="primary">Edit</b-button>
                <b-button class="mt-auto" href="#" variant="danger">Delete</b-button>
              </b-button-group>

            </b-card-body>
          </b-col>
        </b-row>
      </b-card>

    </b-card-group>
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
