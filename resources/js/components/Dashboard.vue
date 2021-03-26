<template>

<div class="dashboard-component-container row">

      <div class="column-1">

            <section class="dashboard-control-container">

        <h2>Dashboard</h2>

        <div class="dashboard-username">
          <!-- <b class="dashboard-font-helper">{{$user->username}}</b> · {{$user->email}} -->
        </div>

        <ul class="button-list-container d-flex">

            <li class="dashboard-card">

                <!-- <button href="{{route('user.edit')}}">

                        <i class="far fa-id-card"></i>
                        <span class="">Modifica informazioni personali</span>

                </button> -->

                <button v-on:click="getUserInfo()">

                      <i class="fas fa-house-user"></i>
                      <span class="">Info Personali</span>

                </button>

            </li>

            <li class="dashboard-card">

                <button v-on:click="getIndex(); toggleApartmentList()">

                      <i class="fas fa-house-user"></i>
                      <span class="">I miei appartamenti</span>

                </button>

            </li>

            <li class="dashboard-card">

                <button v-on:click="toggleAddApartment()">

                      <i class="fas fa-plus-circle"></i>
                      <span class="">Aggiungi appartamento</span>

                </button>

            </li>

            <li class="dashboard-card">

                <button v-on:click="getSponsorTypes()">

                        <i class="fas fa-plus-circle"></i>
                        <span class="">Statistiche</span>

                </button>

            </li>

        </ul>
      </section>

      </div>

      <div class="column-2">

            <section class="dashboardList-container" v-bind:class="{ active: apartmentListIsActive}">

              <DashboardListContainer />

              <DashboardListItem v-for="(data, index) in apartmentList "

                          :class="data.id == currentApartmentId ? 'selected' : 'unselected'"

                          
                          @refresh="getIndex"
                          @displayUpdate = "toggleUpdateApartment"
                          @displaySponsor = "toggleSponsor"
                          @clicked = "getIdForUpdate"

                          :apartmentid="data.id"
                          :activestar="data.active"
                          :coverimg="'../'.concat(data.cover_img)"
                          :description="data.description"
                          :link="'window.location=`apartments/'.concat(data.id).concat('`;')"
                          :title = "data.title"
                          :rooms = "data.rooms"
                          :beds = "data.beds"
                          :bathrooms = "data.bathrooms"
                          :metri_quadrati = "data.metri_quadrati"
                          :price ="data.price"
                          :cover_img ="'../'.concat(data.cover_img)"
                          :key = "index"
                          slot = "items"
                          />

      </section>

      </div>

      <div class="column-3">

            <section v-if= "addApartmentisActive" class="DashboardAddApartment-container" >

                  <DashboardAddApartment @refresh="getIndex(); toggleAddApartment()" :userid="userId"/>

            </section>

            <section class="dashboardAddApartment-container" v-bind:class="{ active: updateApartmentisActive }">

                  <DashboardUpdateApartment @refresh="getIndex(); toggleUpdateApartment()" :userid="userId" :apartmentid="currentApartmentId"/>

            </section>

            <section class="hide" v-bind:class="{ active: sponsorFormIsActive }">

                    <Payments @refresh="getIndex()" :appartamento ="currentApartmentId"/>

            </section>

      </div>

</div>

</template>

<script>
import DashboardListItem from './DashboardListItem'
import DashboardListContainer from './DashboardListContainer'
import DashboardAddApartment from './DashboardAddApartment'
import DashboardUpdateApartment from './DashboardUpdateApartment'
import Payments from './Payments'

export default{
  name: "Dashboard",

  props:['user'],

  components:{
    DashboardAddApartment,
    DashboardUpdateApartment,
    DashboardListItem,
    DashboardListContainer,
    Payments,
  },

  data(){
          return{

            addApartmentisActive:false,
            updateApartmentisActive:false,
            apartmentListIsActive:false,
            sponsorFormIsActive:false,
            apartmentList:[],
            currentApartmentId:0,
            currentApartment:{},
            userId:this.user.id,
            userInfo:this.user,
          }
  },

  methods:{

    getIndex: function(){
      axios.post("http://localhost:8000/api/user/apartments",
      { "id":this.userId },
      // {
      //   headers: {
      //   "Content-type": "application/json; charset=UTF-8",
      //   }
      // },
    )
      .then(response => {
          console.log(response)
          this.apartmentList = response.data.apartments
          console.log(this.apartmentList)
      })
    },

    getUserInfo: function(){
      axios.post("http://localhost:8000/api/user/info",
      {"id":this.userId},
      // {
      //   headers: {
      //   "Content-type": "application/json; charset=UTF-8",
      //   }
      // },
      )
      .then(response => {
          console.log(response)
      })
      .catch((e) => {
          console.log("Caught", e);
      })
    },

    getServices:function(){
      axios.get("http://localhost:8000/api/user/apartments/getservices",
      ).then(response => {
        console.log(response.data.services);
      })
    },

    getIdForUpdate:function(apartmentId){
      console.log(apartmentId)
      this.currentApartmentId = apartmentId;
    },

    toggleAddApartment:function(){
      this.addApartmentisActive = !this.addApartmentisActive;
      this.toggleOffUpdateApartment();
      this.toggleOffSponsor();
    },

    toggleUpdateApartment: function(){
      this.updateApartmentisActive = !this.updateApartmentisActive;
      this.toggleOffAddApartment();
      this.toggleOffSponsor();
    },

    toggleApartmentList:function(){
      this.apartmentListIsActive = !this.apartmentListIsActive;
    },

    toggleSponsor:function(){
      this.sponsorFormIsActive = !this.sponsorFormIsActive;
      this.toggleOffAddApartment();
      this.toggleOffUpdateApartment()
    },

    toggleOffAddApartment: function(){
      this.addApartmentisActive = false;
    },

    toggleOffUpdateApartment: function(){
      this.updateApartmentisActive = false;
    },

    toggleOffSponsor: function(){
      this.sponsorFormIsActive = false;
    },

  },

  watch:{
    'currentApartmentId': function(){
      console.log(this.currentApartmentId)
    },

    'isSomethingSelected': function(){
        this.shouldVisible = !this.isSomethingSelected
    }

  },

  created() {
    this.getServices();

    console.log(this.userId)
    console.log(this.userInfo)

  },
}


</script>

<style scoped>

/* .special-column{
  padding-left:20px;
  padding-right:20px;
} */

.column-1, .column-3{
  /* border: 2px solid red; */
  width:30%;
  /* float:left; */
}

.column-2{
  /* border: 2px solid red; */
  padding-left:20px;
  padding-right:20px;
  width:40%;
  /* float:left; */
}

.dashboardList-container{
  display: none;
}

.dashboard-component-container{
  margin-top: 150px;
  display:flex;
  width:100%;
  height:80vh;
}

.dashboard-control-container{
  /* border:2px solid red; */
  width:200px;
}

.button-list-container{
  flex-direction: column;
  justify-content: space-between;
  list-style: none;
}

.button-list-container li{
    display:flex;
    height:40px;
    margin-top:15px;
}

.button-list-container li  button{
      border-radius: 5px;
      background-color: white;
      color:grey;
      border:2px solid lightgrey;
      width:250px;
      cursor:pointer;
}

.button-list-container li  button:hover{
  background-color: #e1f4f5;
}

.dashboardAddApartment-container{
  /* height:100%; */
  display: none;
}

.hide{
  display: none;
}

.active{
  display: block;
}

.selected{
  border:2px solid #d70466;
}

.unselected {
  opacity: 0.6;
}

</style>
