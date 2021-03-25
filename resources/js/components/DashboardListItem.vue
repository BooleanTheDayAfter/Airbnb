<template >

              <li class="dashboard-component-apartments-card">

                  <div :onclick="link" class="search-component-apartments-card">

                        <div class="search-component-apartment-img-box">

                            <img :src="coverimg" alt="img">

                        </div>

                        <div class="search-component-apartment-features">

                              <i  v-if="activestar" class="fas fa-star aria-hidden='true' search-component-sponsored-star"> </i>

                              <div class="font-helper-1"> <h3>{{ title }}</h3></div>

                              <ul class="tinies-container" >

                                    <li class="font-helper-1 tiny"> Stanze: {{ rooms }}</li>
                                    <li class="font-helper-1 tiny"> Letti: {{ beds }}</li>
                                    <li class="font-helper-1 tiny"> Bagni: {{ bathrooms }}</li>
                                    <li class="font-helper-1 tiny"> Mq: {{ metri_quadrati }}</li>

                              </ul>

                              <div class="price-tag-search-component"> <span><b> {{ price / 100}} Euro </b> / notte </span></div>

                        </div>

                  </div>

                  <div class="dashboard-list-buttons-container">

                        <ul>
                              <li><button :onclick="link" type="button" name="button">Mostra Annuncio </button></li>
                              <li><button v-on:click="currentApartmentId = $event.target.value; sendOutDisplayUpdate(); sendOutApartmentId(); " type="button" name="button" :value="apartmentid">Modifica</button> </li>
                              <li><button v-on:click="currentApartmentId = $event.target.value; sendOutDisplaySponsor(); sendOutApartmentId(); " type="button" name="button" :value="apartmentid">Sponsorizza </button></li>
                              <li><button v-on:click="currentApartmentId = $event.target.value; deleteApartment()" type="button" name="button" :value="apartmentid" >Elimina </button></li>

                        </ul>

                  </div>

              </li>

</template>


<script>

          export default{
            name: "DashboardListItem",

            props:['title' ,
                   'description',
                   'rooms',
                   'beds',
                   'bathrooms',
                   'metri_quadrati',
                   'price',
                   'link',
                   'coverimg',
                   'description',
                   'activestar',
                   'apartmentid',
                   ],

                   data(){
                           return{

                             currentApartmentId:0,
                             // isDisplayed:true,

                           }
                   },

                   methods:{

                     sendOutApartmentId:function(event){
                       this.$emit('clicked', this.currentApartmentId)
                     },

                     sendOutDisplayUpdate:function(event){
                       this.$emit('displayUpdate',this.isDisplayed)// ,this.isDisplayed
                     },

                     sendOutDisplaySponsor:function(event){
                       this.$emit('displaySponsor',this.isDisplayed)// ,this.isDisplayed
                     },

                     sendOutIndexRefresh:function(){
                       this.$emit('refresh')
                     },

                     deleteApartment: function(){
                       axios.post("http://localhost:8000/api/user/apartments/delete",
                       {
                         'apartmentId':this.currentApartmentId,

                       },
                       {
                         headers: {
                         "Content-type": "application/json; charset=UTF-8",
                         }
                       },
                     )

                     .then(response => {
                         console.log(response)
                         this.sendOutIndexRefresh();
                     },
                     (err) => {
                       console.log("Err", err);
                     })
                     .catch((e) => {
                       console.log("Caught", e);
                      }
                    )


                     }

                   }

            };

</script>

<style scoped>
.dashboard-component-apartments-card{
  border:1px solid lightgrey;
  border-radius: 10px;
  margin-bottom:10px;
  display: flex;
  flex-direction: column;
  width:100%;
  height:170px;
  padding:10px;
  /* border-bottom:1px solid lightgrey; */

}

.dashboard-list-buttons-container ul{
  /* border:1px solid red; */
  /* border-top:1px solid pink; */
  height:20px;
  list-style: none;
  display: flex;
  justify-content: space-between;
  align-items: center;

}

.dashboard-list-buttons-container ul li button:hover{
  cursor:pointer;
}

.search-component-apartments-card{
  /* border:1px solid blue; */
  display: flex;
  width:100%;
  height:130px;
  padding:10px;
  border-bottom:1px solid lightgrey;
  position:relative;
}

.search-component-apartments-card:hover{
  background-color: #e1f4f5;
  cursor:pointer;
}

.search-component-apartment-img-box{
  /* border:1px solid brown; */
  position: relative;
  min-width:130px;
  max-width:130px;
  height:110px;
  border-radius:10px;
  overflow:hidden;
}

.search-component-apartment-img-box img{
  height:100%;
  position:absolute;
  top:50%;
  left:50%;
  transform:translate(-50%, -50%)
}

.search-component-apartment-features{
  width:100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding:5px;
  margin-left: 20px;
}

.tinies-container{
  width:100%;
  display: flex;
  justify-content: space-between;
  list-style: none;
}


.apartment-description-search-component{
  font-size: 15px;
  height:75px;
  width:100%;
  overflow:hidden;
  text-overflow:ellipsis;
}

.price-tag-search-component{
  height:30px;
}

.price-tag-search-component{
  display:flex;
  justify-content: flex-end;
}

.search-component-sponsored-star{
  position:absolute;
  color:#FF385C;
  right:15px;
  top:10px;
}

/* .dActive{
  display: block;
} */

</style>
