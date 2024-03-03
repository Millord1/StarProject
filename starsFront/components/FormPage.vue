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
<!--    <b-card class="mt-3" header="Form Data Result">-->
<!--      <pre class="m-0">{{ form }}</pre>-->
<!--    </b-card>-->
  </div>
</template>

<script>
export default {
  data() {
    return {
      star: {},
      id: null
    }
  },
  methods: {
    onSubmit(event) {
      event.preventDefault()
      alert(JSON.stringify(this.star))
    },

    getDefaultValue(prop, defaultPlaceHolder){
      if(Object.keys(this.star).length > 0){
        return this.star[prop]
      }
      return defaultPlaceHolder
    },

    async getStarData() {
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
    if (this.id !== null){
      await this.getStarData()
    }
  }
}
</script>
