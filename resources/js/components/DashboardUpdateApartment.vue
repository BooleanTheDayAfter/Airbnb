<template >

  <section>
    <h1>UPDATE</h1>

    <form @submit.prevent="update" method="post" enctype="multipart/form-data">

          <!-- TITLE -->
          <div class="my-form">

              <label for="title">Title</label>
              <input type="text" class="my-form-input" name="title" id="title" v-model="title" required/>
              <div v-if="errors && errors.title" class="text-danger">{{ errors.title[0] }}</div>

          </div>

          <!-- PRICE -->
          <div class="my-form">

              <label for="price">Price</label>
              <input type="text" class="my-form-input" name="price" id="price" v-model="price" required/>
              <div v-if="errors && errors.price" class="text-danger">{{ errors.price[0] }}</div>

          </div>

          <!-- CITY -->
          <div class="my-form">

              <label for="city">City</label>
              <input type="text" class="my-form-input" name="city" id="city" v-model="city" required/>
              <div v-if="errors && errors.city" class="text-danger">{{ errors.city[0] }}</div>

          </div>

          <!-- ADDRESS -->
          <div class="my-form">

              <label for="address">Address</label>
              <input type="text" class="my-form-input" name="address" id="address" v-model="address" required/>
              <div v-if="errors && errors.address" class="text-danger">{{ errors.address[0] }}</div>

          </div>

          <!-- DESCRIPTION -->
          <div class="my-form">

              <label for="description">Description</label>
              <input type="text" class="my-form-input" name="description" id="description" v-model="description" required/>
              <div v-if="errors && errors.description" class="text-danger">{{ errors.description[0] }}</div>

          </div>

          <!-- BEDS -->
          <div class="my-form">

              <label for="beds">Beds</label>
              <input type="number" class="my-form-input" name="beds" id="beds" v-model="beds" required/>
              <div v-if="errors && errors.beds" class="text-danger">{{ errors.beds[0] }}</div>

          </div>

          <!-- ROOMS -->
          <div class="my-form">

              <label for="rooms">Rooms</label>
              <input type="number" class="my-form-input" name="rooms" id="rooms" v-model="rooms" required/>
              <div v-if="errors && errors.rooms" class="text-danger">{{ errors.rooms[0] }}</div>

          </div>

          <!-- BATHROOMS -->
          <div class="my-form">

              <label for="bathrooms">Bathrooms</label>
              <input type="number" class="my-form-input" name="bathrooms" id="bathrooms" v-model="bathrooms" required/>
              <div v-if="errors && errors.bathrooms" class="text-danger">{{ errors.bathrooms[0] }}</div>

          </div>

          <!-- METRI QUADRATI -->
          <div class="my-form">

              <label for="metri_quadrati">Metri_quadrati</label>
              <input type="number" class="my-form-input" name="metri_quadrati" id="metri_quadrati" v-model="metri_quadrati" required/>
              <div v-if="errors && errors.metri_quadrati" class="text-danger">{{ errors.metri_quadrati[0] }}</div>

          </div>

          <!-- SERVICES -->
          <div class="my-form">

                  <ul class="dashboardAddApartment-services-list">

                      <li v-for="(servizio, index) in servizi">

                        <label :for="servizio.name">{{servizio.name}}</label>
                        <input type="checkbox"  :name="servizio.name" class="my-form-input"  :id="servizio.name" :value="servizio.id" v-model="services">

                      </li>

                  </ul>

          </div>

          <!-- COVER IMG -->
          <div class="my-form">

              <label for="cover">Carica immagine di anteprima</label><br>
              <input type="file" class="my-form-input" name="cover" id="updateCover" ref="file">

          </div>

          <!-- MORE IMAGES -->
          <div class="my-form">

              <label for="images">Carica immagini </label><br>
              <input type="file" class="my-form-input" name="images[]" id="updateImages" multiple>

          </div>

          <!-- SUBMIT -->
          <div class="my-form">

              <button type="submit" name="submit" class="btn btn-primary">Update</button>

          </div>

    </form>

  </section>

</template>

<script>
export default{
  name: 'DashboardUpdateApartment',

  props:['userid', 'apartmentid'],

  data() {
    return {
      userId:this.userid,
      fields: {},
      errors: {},
      servizi: [],
      title:"",
      price:"",
      city:"",
      address:"",
      description:"",
      beds:0,
      rooms:0,
      bathrooms:0,
      metri_quadrati:0,
      services:[],
      currentApartmentId:this.apartmentid,
      currentApartment:{},
      // imageData:{},
      // moreImageData:[],

    }
  },
  methods: {

    getServices:function(){
      axios.get("api/user/apartments/getservices",
      ).then(response => {
        console.log(response.data.services);
        this.servizi = response.data.services;
    })
    },

    getApartment:function(){
      axios.post("api/user/apartments/getapartment",
        {
          'id' : this.apartmentid,
        }
      ).then(response=> {
        console.log(response),
        this.currentApartment = response.data.apartment
        console.log(this.currentApartment)
        this.title = this.currentApartment.title
        this.price = this.currentApartment.price
        //this.city:this.currentApartment.city
        this.address = response.data.address,
        this.description = this.currentApartment.description
        this.beds = this.currentApartment.beds
        this.rooms = this.currentApartment.rooms
        this.bathrooms = this.currentApartment.bathrooms
        this.metri_quadrati = this.currentApartment.metri_quadrati
        this.services = response.data.services.map(function(el){
          return el.id
        })

      })

    },

    update: function() {

      const json = JSON.stringify({
        'apartment_id': this.apartmentid,
        'user_id': this.userId,
        'title' : this.title,
        'price' : this.price,
        'city' : this.city,
        'address' : this.address,
        'description' : this.description,
        'beds' : this.beds,
        'rooms' : this.rooms,
        'bathrooms' : this.bathrooms,
        'metri_quadrati' : this.metri_quadrati,
        'services' : this.services,

      });

      let formData = new FormData();

      formData.append('cover', document.getElementById('updateCover').files[0]);
      formData.append('data', json);

      for( var i = 0; i < document.getElementById('updateImages').files.length; i++ ){
        let image = document.getElementById('updateImages').files[i];
        formData.append('images[' + i + ']', image);
      }

      let config = { headers: {'Content-Type': 'multipart/form-data'} }

      console.log(formData.values)

      this.errors = {};

      axios.post('http://localhost:8000/api/user/apartments/update',
              formData, config,

      ).then(response => {
        console.log(response);
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors || {};
        }
      });
    },

    // uploadImage:function(){
    //   this.imageData = document.getElementById('cover').files[0];
    //   console.log(this.imageData)
    //
    // },
    //
    // uploadMoreImages:function(){
    //   this.moreImageData = document.getElementById('images').files;
    //   console.log(this.moreImageData)
    //
    // },

    // processFile:function(event) {
    //   this.image = this.$refs.file.files[0];
    //   const formData = new FormData();
    //   formData.append("cover", this.image);
    //
    //   console.log(this.$refs.file.files[0]);
    //
    //   console.log(this.image);
    // }

  },

  watch:{
    'apartmentid':function(){
      this.getApartment();
    }
  },
  //
  // },

  created(){
    this.getServices();
  },

  mounted(){
    this.fields.user_id = this.userId;
  },

}

</script>

<style>

</style>
