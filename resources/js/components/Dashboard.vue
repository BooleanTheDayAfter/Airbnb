<template>

<div class="dashboard-component-container">

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

            <!-- <button href="{{route('apartments.index')}}">

                  <i class="fas fa-house-user"></i>
                  <span class="">I miei appartamenti</span>

            </button> -->

            <button v-on:click="getIndex()">

                  <i class="fas fa-house-user"></i>
                  <span class="">I miei appartamenti</span>

            </button>



        </li>

        <li class="dashboard-card">

            <!-- <button href="{{route('apartments.create')}}">

                    <i class="fas fa-plus-circle"></i>
                    <span class="">Inserisci un nuovo appartamento</span>

            </button> -->
            <button v-on:click="createApartment()">

                  <i class="fas fa-plus-circle"></i>
                  <span class="">Aggiungi appartamento</span>

            </button>

        </li>

        <li class="dashboard-card">

            <button href="">

                    <i class="fas fa-plus-circle"></i>
                    <span class="">Statistiche</span>

            </button>

        </li>

    </ul>
  </section>

  <section>

    <DashboardListContainer />

    <DashboardListItem  v-for="(data, index) in apartmentList "

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

          </ul>

  </section>

</div>
</template>

<script>
import DashboardListItem from './DashboardListItem'
import DashboardListContainer from './DashboardListContainer'

export default{
  name: "Dashboard",

  props:['user'],

  components:{
    DashboardListItem,
    DashboardListContainer,
  },

  data(){
          return{

            apartmentList:[],
            userId:this.user.id,
            userInfo:this.user,

          }
  },

  methods:{
    getIndex: function(){
      axios.post("http://localhost:8000/api/user/apartments",
      {
        "id":this.userId,

      },
      {
        headers: {
        "Content-type": "application/json; charset=UTF-8",
        }
      },
    )
      .then(response => {
          console.log(response)
          this.apartmentList = response.data.apartments
          console.log(this.apartmentList)
      })
    },

    getUserInfo: function(){
      axios.post("http://localhost:8000/api/user/info",
      {
        "id":this.userId,

      },
      {
        headers: {
        "Content-type": "application/json; charset=UTF-8",
        }
      },
    )
    .then(response => {
        console.log(response)
    })

    .catch((e) => {
        console.log("Caught", e);
    })

    }

  },

  created() {

    console.log(this.userId)
    console.log(this.userInfo)

  },
}


</script>

<style>
.dashboard-component-container{
  display:flex;
}

.dashboard-control-container{
  border:2px solid red;
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
      color:$bnbpink;
      border:2px solid lightgrey;
      width:250px;
}


</style>
