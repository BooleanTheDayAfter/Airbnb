<template >

  <section>

            <div class="dashboardUpdateApartment-form-container">

                <h4>Modifica dati Utente</h4>
                <form @submit.prevent="updateUser" method="post" enctype="multipart/form-data">

                      <!-- USERNAME -->
                      <div class="my-form">

                          <label for="username">User Name</label>
                          <input type="text" class="my-form-input" name="username" id="username" v-model="username" required/>
                          <div v-if="errors && errors.username" class="text-danger">{{ errors.username[0] }}</div>

                      </div>

                      <!-- FULLNAME -->
                      <div class="my-form">

                          <label for="fullname">Fullname</label>
                          <input type="text" class="my-form-input" name="fullname" id="fullname" v-model="fullname" required/>
                          <div v-if="errors && errors.fullname" class="text-danger">{{ errors.fullname[0] }}</div>

                      </div>

                      <!-- ADDRESS -->
                      <div class="my-form">

                          <label for="address">Address</label>
                          <input type="text" class="my-form-input" name="address" id="address" v-model="address" required/>
                          <div v-if="errors && errors.address" class="text-danger">{{ errors.address[0] }}</div>

                      </div>

                      <!-- BIRTHDATE -->
                      <div class="my-form">

                          <label for="birthdate">Data di nascita</label>
                          <input type="date" class="my-form-input" name="birthdate" id="price" v-model="birthdate" required/>
                          <div v-if="errors && errors.birthdate" class="text-danger">{{ errors.birthdate[0] }}</div>

                      </div>

                      <!-- FISCAL CODE -->
                      <div class="my-form">

                          <label for="fiscal_code">Codice Fiscale</label>
                          <input type="text" class="my-form-input" name="fiscal_code" id="fiscal_code" v-model="fiscal_code" required/>
                          <div v-if="errors && errors.fiscal_code" class="text-danger">{{ errors.fiscal_code[0] }}</div>

                      </div>

                      <!-- P.IVA -->
                      <div class="my-form">

                          <label for="p_iva">P. iva</label>
                          <input type="text" class="my-form-input" name="p_iva" id="p_iva" v-model="p_iva" required/>
                          <div v-if="errors && errors.p_iva" class="text-danger">{{ errors.p_iva[0] }}</div>

                      </div>

                      <!-- PHONE NUMBER -->
                      <div class="my-form">

                          <label for="phone_number">Numero di telefono</label>
                          <input type="number" class="my-form-input" name="phone_number" id="phone_number" v-model="phone_number" required/>
                          <div v-if="errors && errors.phone_number" class="text-danger">{{ errors.phone_number[0] }}</div>

                      </div>

                      <!-- GENRE -->
                      <div class="my-form">

                          <label for="sex">Genere</label>
                          <input type="text" class="my-form-input" name="sex" id="sex" v-model="sex" required/>
                          <div v-if="errors && errors.sex" class="text-danger">{{ errors.sex[0] }}</div>

                      </div>

                      <!-- E-MAIL -->
                      <!-- <div class="my-form">

                          <label for="email">Email</label>
                          <input type="text" class="my-form-input" name="email" id="email" v-model="email" required/>
                          <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>

                      </div> -->

                      <!-- SUBMIT -->
                      <div class="my-form">

                          <button type="submit" name="submit" class="btn btn-primary">Modifica</button>

                      </div>

                </form>

            </div>

  </section>

</template>

<script>
export default{
  name: 'DashboardUpdateUser',

  props:['user','userinfo'],

  data() {
    return {
      userId:this.user.id,
      userInfoUp:{},
      userUp:{},
      //fields: {},
      errors: {},
      // servizi: [],
      username:"",
      fullname:"",
      birthdate:"",
      fiscal_code:"",
      address:"",
      p_iva:"",
      phone_number:0,
      sex:"",
      //email:"",
    }
  },
  methods: {

    sendOutIndexRefresh:function(){
      this.$emit('refresh')
    },

    updateUser: function() {

      const data = {
        'user_id' : this.userId,
        'username': this.username,
        'fullname' : this.fullname,
        'birthdate': this.birthdate,
        'fiscal_code': this.fiscal_code,
        'address':this.address,
        'p_iva': this.p_iva,
        'phone_number': this.phone_number,
        'sex':this.sex,
        //'email':this.email,

      };

      //const json = JSON.stringify({
        // 'apartment_id': this.apartmentid,
        // 'user_id': this.userId,
        // 'title' : this.title,
        // 'price' : this.price,
        // 'city' : this.city,
        // 'address' : this.address,
        // 'description' : this.description,
        // 'beds' : this.beds,
        // 'rooms' : this.rooms,
        // 'bathrooms' : this.bathrooms,
        // 'metri_quadrati' : this.metri_quadrati,
        // 'services' : this.services,

      //});

      // let formData = new FormData();
      //
      // formData.append('cover', document.getElementById('updateCover').files[0]);
      // formData.append('data', json);
      //
      // for( var i = 0; i < document.getElementById('updateImages').files.length; i++ ){
      //   let image = document.getElementById('updateImages').files[i];
      //   formData.append('images[' + i + ']', image);
      // }
      //
      // let config = { headers: {'Content-Type': 'multipart/form-data'} }
      //
      // console.log(formData.values)

      this.errors = {};

      axios.post('http://localhost:8000/api/user/updateuserinfo',
        data
      )
      .then(response => {
        console.log(response);
        //this.sendOutIndexRefresh();
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors || {};
        }
      });
    },

  },

  watch:{
    'userinfo': function(){
      this.userInfoUp = this.userinfo;
      this.userUp = this.user;
      console.log("UpdateUser")
      console.log(this.userUp)
      console.log(this.userInfoUp)
      this.username=this.user.username;
      this.fullname = this.userinfo.fullname;
      this.birthdate = this.userinfo.birthdate;
      this.sex = this.userinfo.sex;
      this.fiscal_code = this.userinfo.fiscal_code;
      this.p_iva = this.userinfo.p_iva;
      this.address = this.userinfo.address;
      this.phone_number = this.userinfo.phone_number;
      this.email = this.user.email;
    }
  },

}

</script>

<style scoped>

  .dashboardUpdateApartment-form-container{

    font-size: 12px;
    border:1px solid lightgrey;
    border-radius: 15px;
    padding:20px;

  }

  .dashboardUpdateApartment-form-container .my-form{
    display:flex;
    flex-direction: column;
    margin-top: 10px;
  }

  .dashboardUpdateApartment-form-container ul{
    list-style: none;
  }

  .dashboardUpdateApartment-form-container h5{
    font-size: 13px;
  }

  .dashboardUpdateApartment-form-container input{
    border-radius: 5px;
    border:1px solid lightgrey;
  }

</style>
