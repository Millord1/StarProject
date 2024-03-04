<template>
  <div class="d-flex flex-column align-items-center">
    <b-form @submit="onSubmit">
      <b-form-group
        id="input-group-1"
        label="First name"
        label-for="input-1"
      >
        <b-form-input
          id="input-1"
          v-model="star.first_name"
          type="text"
          :placeholder="getDefaultValue('first_name', 'Enter first name')"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-2" label="Last name:" label-for="input-2">
        <b-form-input
          id="input-2"
          v-model="star.last_name"
          :placeholder="getDefaultValue('last_name', 'Enter last name')"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-3" label="Image" label-for="input-3">
        <b-form-input
          id="input-3"
          v-model="star.img"
          :placeholder="getDefaultValue('img', 'Enter an image name')"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-4" label="Description" label-for="input-4">
        <b-form-textarea
          id="input-4"
          v-model="star.description"
          :placeholder="getDefaultValue('description', 'Enter an description')"
        ></b-form-textarea>
      </b-form-group>

      <b-button type="submit" variant="primary">Submit</b-button>
    </b-form>
  </div>
</template>

<script>

export default {
  data() {
    return {
      star: {
        'first_name': "",
        'last_name': "",
        'img': "",
        'description': ""
      },
      id: null
    }
  },
  methods: {
    async onSubmit(event) {
      event.preventDefault()

      try {
        // define if we have to create or update from the same form
        const send = typeof this.star.id == 'undefined' ? await this.createStar() : await this.updateStar()
        if(send.status !== 200){
          console.error("Error while sending update: "+send.status)
        }

      }catch (err){
        console.error(err)
      }
    },

    async createStar(){
      // POST method to create a star, actually I have an issue with cors policy
      // apparently this method should be send by a proxy
      await fetch('http://localhost:8000/api/', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(this.star),
      })
    },

    async updateStar(){
      // update call with the form data
      return await fetch('http://localhost:8000/api/stars/'+this.star.id, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.star),
      })
    },

    getDefaultValue(prop, defaultPlaceHolder){
      // return the actual values for a star
      if(Object.keys(this.star).length > 0){
        return this.star[prop]
      }
      return defaultPlaceHolder
    },

    async getStarData() {
      // if the user wants to modify, we show the actual data of the star
      try {
        const response = await fetch('http://localhost:8000/api/stars/' + this.id);
        this.star = await response.json()
      }catch (err){
        console.error(err)
      }
    }
  },

  async mounted() {
    this.id = this.$route.params.id
    if (typeof this.id !== 'undefined'){
      await this.getStarData()
    }
  }
}
</script>
