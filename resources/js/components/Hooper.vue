<template>


      <hooper group="group1" :settings="hooperSettings">

        <slide v-for="(image, indx) in images" :key="indx" :index="indx">

          <div class="img-container-hooper">

            <img :src="'../'.concat(image)" alt="">

          </div>

        </slide>



        <hooper-navigation slot="hooper-addons"></hooper-navigation>

        <hooper-pagination slot="hooper-addons"></hooper-pagination>

      </hooper>


</template>

<script>
  import { Hooper,
           Slide,
           Navigation as HooperNavigation,
           Pagination as HooperPagination } from 'hooper';
  import 'hooper/dist/hooper.css';



  export default {
    name: 'my-hooper',
    components: {
      Hooper,
      Slide,
      HooperPagination,
      HooperNavigation
    },

    props:['imgs','numofitems'],

    data() {
    return {
      hooperSettings: {
        itemsToShow: this.numofitems,
        centerMode: false,
        infiniteScroll:true,
        autoPlay: true,
        playSpeed: 3500,
      },

      images:this.imgs.map(el=>el.path),
    };
  },

  mounted(){
    console.log(this.imgs.map(el=>el.path))
    console.log(this.images)
  }
}
</script>

<style scoped>

.hooper{
  height:400px;;
  width:100%;
}

.hooper-slide{
  height:400px;
  width:100%;
}

img{
  object-fit: cover;
  object-position: 50% 50%;
  width:100%;
  height:400px;
  border-top-left-radius: 15px;
  border-bottom-left-radius: 15px;
}

@media screen and (max-width: 780px) {
  img{
    border-top-right-radius: 15px;
    border-bottom-right-radius: 15px;
  }
}

</style>
