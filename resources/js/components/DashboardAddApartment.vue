<template >

  <section>

    <form @submit.prevent="submit">

          <!-- TITLE -->
          <div class="my-form">

              <label for="title">Title</label>
              <input type="text" class="my-form-input" name="title" id="title" v-model="fields.title" />
              <div v-if="errors && errors.title" class="text-danger">{{ errors.title[0] }}</div>

          </div>

          <!-- PRICE -->
          <div class="my-form">

              <label for="price">Price</label>
              <input type="text" class="my-form-input" name="price" id="price" v-model="fields.price" />
              <div v-if="errors && errors.price" class="text-danger">{{ errors.price[0] }}</div>

          </div>

          <!-- CITY -->
          <div class="my-form">

              <label for="city">City</label>
              <input type="text" class="my-form-input" name="city" id="city" v-model="fields.city" />
              <div v-if="errors && errors.city" class="text-danger">{{ errors.city[0] }}</div>

          </div>

          <!-- ADDRESS -->
          <div class="my-form">

              <label for="address">Address</label>
              <input type="text" class="my-form-input" name="address" id="address" v-model="fields.address" />
              <div v-if="errors && errors.address" class="text-danger">{{ errors.address[0] }}</div>

          </div>

          <!-- DESCRIPTION -->
          <div class="my-form">

              <label for="description">Description</label>
              <input type="text" class="my-form-input" name="description" id="description" v-model="fields.description" />
              <div v-if="errors && errors.description" class="text-danger">{{ errors.description[0] }}</div>

          </div>

          <!-- BEDS -->
          <div class="my-form">

              <label for="beds">Beds</label>
              <input type="number" class="my-form-input" name="beds" id="beds" v-model="fields.beds" />
              <div v-if="errors && errors.beds" class="text-danger">{{ errors.beds[0] }}</div>

          </div>

          <!-- ROOMS -->
          <div class="my-form">

              <label for="rooms">Rooms</label>
              <input type="number" class="my-form-input" name="rooms" id="rooms" v-model="fields.rooms" />
              <div v-if="errors && errors.rooms" class="text-danger">{{ errors.rooms[0] }}</div>

          </div>

          <!-- BATHROOMS -->
          <div class="my-form">

              <label for="bathrooms">Bathrooms</label>
              <input type="number" class="my-form-input" name="bathrooms" id="bathrooms" v-model="fields.bathrooms" />
              <div v-if="errors && errors.bathrooms" class="text-danger">{{ errors.bathrooms[0] }}</div>

          </div>

          <!-- METRI QUADRATI -->
          <div class="my-form">

              <label for="metri_quadrati">Metri_quadrati</label>
              <input type="number" class="my-form-input" name="metri_quadrati" id="metri_quadrati" v-model="fields.metri_quadrati" />
              <div v-if="errors && errors.metri_quadrati" class="text-danger">{{ errors.metri_quadrati[0] }}</div>

          </div>

          <!-- SERVICES -->
          <div class="my-form">

                  <ul class="dashboardAddApartment-services-list">

                      <li v-for="(servizio, index) in servizi">

                        <label for="services[]">{{servizio.name}}</label>
                        <input type="checkbox" class="my-form-input" name="services[]" :value="servizio.id">

                      </li>

                  </ul>

          </div>

          <!-- COVER IMG -->
          <div class="my-form">

            <label for="cover">Carica immagina di anteprima</label><br>
            <input type="file" class="my-form-input" name="cover" id="cover" v-on:change="fields.cover_img" >

          </div>

          <button type="submit" class="btn btn-primary">Send message</button>

    </form>


  </section>

</template>

<script>
export default{
  name: 'DashboardAddApartment',

  data() {
    return {
      fields: {},
      errors: {},
      servizi: [],
    }
  },
  methods: {

    getServices:function(){
      axios.get("http://localhost:8000/api/user/apartments/getservices",
    ).then(response => {
        console.log(response.data.services);
        this.servizi = response.data.services;
    })
    },

    submit: function() {
      this.errors = {};
      axios.post('/submit', this.fields).then(response => {
        alert('Message sent!');
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors || {};
        }
      });
    },

  },

  created(){
    this.getServices();
  }
}

</script>

<style>

</style>
