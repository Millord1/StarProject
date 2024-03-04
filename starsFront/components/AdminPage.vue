<template>

  <div class="d-flex flex-column align-items-center">

    <b-button
      class="mt-4 mb-4 col-3"
      @click="redirectToForm(null)"
      variant="success">
      Create a new star
    </b-button>

    <b-card-group deck v-for="star in this.stars" class="m-1 col-4">

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
                <b-button
                  @click="redirectToForm(star.id)"
                  class="mt-auto"
                  variant="primary">
                  Edit
                </b-button>
                <b-button
                  class="mt-auto"
                  v-b-modal.modal-delete-star
                  @click="actionDelete(star.id)"
                  variant="danger">
                  Delete
                </b-button>
              </b-button-group>

            </b-card-body>
          </b-col>
        </b-row>
      </b-card>

    </b-card-group>

<!--  I wanted to add a modal here but I had issue to send data from the button and get it inside the modal  -->

<!--    <b-modal-->
<!--       v-if="this.starToDelete"-->
<!--       scrollable-->
<!--       id="modal-delete-star"-->
<!--       centered-->
<!--       @ok="actionDelete(this.starToDelete.id)"-->
<!--       class="d-block text-center"-->
<!--       title="Confirm Delete Star">-->
<!--      <p>-->
<!--        Confirm that you are about to delete Star-->
<!--        {{ starToDelete.first_name }} {{ starToDelete.last_name }}-->
<!--      </p>-->
<!--    </b-modal>-->

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
      // get data from API to have all existing stars
      try {
        const response = await fetch('http://localhost:8000/api/stars')
        this.stars = await response.json();
      } catch (error) {
        console.error(error);
      }
    },

    async actionDelete(id) {
      // basic delete a star
      alert('You are about to delete a star !')
      const resp = await fetch('http://localhost:8000/api/stars/'+id, { method: 'DELETE' })
      if(resp.status !== 200) {
        console.error(resp.status)
      }
      window.location.reload()
    },

    async redirectToForm(id) {
      // redirect on form page
      await this.$router.push({ name:'form', params:{id:id} })
    },
  },

  beforeMount() {
    this.fetchData()
  }
}
</script>
