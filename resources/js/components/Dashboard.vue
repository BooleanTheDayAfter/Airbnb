<template>

<div class="dashboard-component-container row">

      <!-- <h2>Dashboard</h2> -->

      <div class="column-1">

        <section class="dashboard-control-container">

        <!-- <div class="dashboard-username"> -->
          <!-- <b class="dashboard-font-helper">{{$user->username}}</b> · {{$user->email}} -->
        <!-- </div> -->

        <ul class="button-list-container d-flex">

            <li class="dashboard-card">

                <button v-on:click="getUserInfo(); toggleUpdateUser();">

                      <i class="fas fa-house-user"></i>
                      <span class="">Info Personali</span>

                </button>

            </li>

            <li id="sp-button" class="dashboard-card">

              <button v-on:click="getIndex(); toggleDisplay();">

                    <i class="fas fa-house-user"></i>
                    <span class="">Mostra tutti i miei appartamenti</span>

              </button>

            </li>

            <li class="dashboard-card">

                <button v-on:click="getIndex(); toggleApartmentList()">

                      <i class="fas fa-house-user"></i>
                      <span class="">Mostra tutti i miei appartamenti</span>

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

                          :class="(data.id == currentApartmentId || display) ? 'selected' : 'unselected'"

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

            <section class="hide" v-bind:class="{ active: updateUserisActive }">

                      <DashboardUpdateUser :user = "user" :userinfo = "userInfo"/>

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
import DashboardUpdateUser from './DashboardUpdateUser'
import Payments from './Payments'

export default{
  name: "Dashboard",

  props:['user'],

  components:{
    DashboardAddApartment,
    DashboardUpdateApartment,
    DashboardUpdateUser,
    DashboardListItem,
    DashboardListContainer,
    Payments,
  },

  data(){
          return{

            addApartmentisActive:false,
            updateApartmentisActive:false,
            updateUserisActive:false,
            apartmentListIsActive:false,
            sponsorFormIsActive:false,
            apartmentList:[],
            currentApartmentId:0,
            currentApartment:{},
            userId:this.user.id,
            userInfo:{},
            display:false,

          }
  },

  methods:{

    getIndex: function(){
      axios.post("http://localhost:8000/api/user/apartments",
        { "id":this.userId },
      )
      .then(response => {
          this.apartmentList = response.data.apartments
      })
    },

    getUserInfo: function(){
      axios.post("http://localhost:8000/api/user/info",
        { "id":this.userId },
      )
      .then(response => {
          console.log(response)
          this.userInfo = response.data.userInfo[0]
          console.log(this.userInfo)
      })
      .catch((e) => {
          console.log("Caught", e);
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

    toggleUpdateUser: function(){
      this.updateUserisActive =!this.updateUserisActive;
      this.toggleOffApartmentList();

    },

    toggleApartmentList:function(){
      this.apartmentListIsActive = !this.apartmentListIsActive;
      this.toggleOffUpdateUser();
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

    toggleOffApartmentList: function(){
      this.apartmentListIsActive = false;
    },

    toggleOffUpdateUser: function(){
      this.updateUserisActive = false;
    },

    resetCurrentId: function(){
      this.currentApartmentId = 0;
    },

    toggleDisplay:function(){
      this.apartmentListIsActive = true;
      this.display = !this.display;
    }

  },

  watch:{
    'currentApartmentId':function(){
      this.display = false;
    }
  },

}


</script>

<style scoped>
.column-1{
  width:150px;
}

.column-2{
  padding-left:20px;
  padding-right:20px;
  width:60%;
}

.column-3{
  /* border: 2px solid red; */
  width:40%;
  /* float:left; */
}

.dashboardList-container{
  display: none;
  /* margin-top: 10px; */
}

.dashboard-component-container{
  margin-top: 150px;
  display:flex;
  width:100%;
  height:80vh;
}

.dashboard-control-container{
  /* border:2px solid red; */
  width:100%;
}

.button-list-container{
  flex-direction: column;
  justify-content: space-between;
  list-style: none;
}

.button-list-container li{
    display:flex;
    height:40px;
    margin-bottom:15px;
}

.button-list-container li  button{
      border-radius: 5px;
      background-color: white;
      color:grey;
      border:2px solid lightgrey;
      width:100%;
      cursor:pointer;
}

.button-list-container li  button:hover{
  background-color: #e1f4f5;
}

.dashboardAddApartment-container{
  /* height:100%; */
  display: none;
}

.dashboard-card button{
  padding:5px;
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
  opacity: 0.7;
}

.sm-screen-button{
  display:none;
}

.button-list-container li:nth-child(2){
  display: none;
}

/* MEDIA QUERIES */
@media screen and (max-width: 960px) {

  .column-1 , .column-2, .column-3{
    width:100%;
  }

  .column-2{
    padding:0;
  }

  .button-list-container li:nth-child(2){
    display: flex;
  }

  .button-list-container li:nth-child(3){
    display: none;
  }

  .unselected{
    display:none;
  }

  .dashboard-component-container{
    display: flex;
    flex-direction: column;
  }

  .button-list-container{
    width:100%;
    flex-direction: row;
  }

  .sm-screen-button{
    display:block;
  }
}

@media screen and (max-width: 768px) {

}

</style>
