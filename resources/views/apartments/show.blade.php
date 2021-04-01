@extends('layouts.boolbnb')

@section('title')
  Dettagli appartamento
@endsection

@section('content')

<div class="row">

  <div class="container">

  <section class="apartment-details">

            <a href="/admin" class="go-back aptedit-back-link">
              Torna alla Dashboard
            </a>


            <div class="text-box">

                  <h2>{{$apartment->title}}</h2>

            </div>

    <div class="show-photo-container">

          <div id="hooper">

              <my-hooper id="firsthooper" :imgs="{{$apartment->imgs}}" :numofitems="1">  </my-hooper>

          </div>

          <div class="show-photo-collage">

                <div class="image-frame">

                      <img class="im-tl" src="{{asset($apartment->imgs[0]->path)}}" alt="">

                </div>

                <div class="image-frame">
                      <img class="im-tr" src="{{asset($apartment->imgs[1]->path)}}" alt="">
                </div>

                <div class="image-frame">
                      <img class="im-bl" src="{{asset($apartment->imgs[2]->path)}}" alt="">
                </div>

                <div class="image-frame">
                      <img class="im-br"src="{{asset($apartment->imgs[3]->path)}}" alt="">
                </div>

          </div>

    </div>


    <div class="my-apartment-info-container">

        <div class="services-list">

            <div class="service-list-upper-section">

                  <h4>Sistemazione</h4>

                  <ul class="apt-basics-list">

                        <li>

                          <i class="fas fa-bed fa-2x"></i>
                          <div><b>Letti:</b> {{$apartment->beds}} </div>

                        </li>

                        <li>

                          <i class="fas fa-door-open fa-2x"></i>
                          <div><b>Camere:</b> {{$apartment->rooms}}</div>

                        </li>

                        <li>

                          <i class="fas fa-bath fa-2x"></i>
                          <div><b>Bagni:</b> {{$apartment->bathrooms}}</div>

                        </li>
                  </ul>

            </div>

            <div class="line-separator"></div>

            <div class="service-list-mid-section">

              <h4>Servizi e altre opzioni</h4>

                  <ul class="advanced-list">

                    <?php
                        $array = array("fa-wifi", "fa-dog", "fa-car", "fa-swimmer", "fa-concierge-bell", "fa-hot-tub", "fa-water");
                        ?>

                  @foreach ($services as $service)
                            <li>

                                <i class="fas {{ $array[$service->id -1] }} fa-2x {{ $apartment->services->contains($service) ? 'colorblue' : 'colorgrey' }}"></i>
                                <div>{{$service->name}}</div>

                            </li>
                  @endforeach

                    </ul>

            </div>

            <div class="line-separator"></div>

            <div class="service-list-mid-low-section">

                  <h3>Descrizione</h3>
                  <p>{{$apartment->description}}</p>

            </div>

            <div class="line-separator"></div>

            <div class="service-list-low-section">

                  <div class="service-list-low-section-left">

                      <h3>Intero appartamento - Host: {{ $apartment->user->username }}</h3>
                      <div class="margin-2-helper">Dimensioni: {{$apartment->metri_quadrati}}&#13217; </div>

                  </div>

                  <div class="service-list-low-section-right">

                      <div id="modal">

                        <modal> </modal>

                      </div>

                  </div>

            </div>

      </div>

    </div>

    <div class="apt-position">

        <h3>Posizione</h3>

        <div class="apt-map-box" id="showMap">

              <div id="map-container">

                  <show-map :apartmentinfo= "{{$apartment}}" :position="{{$apartment->position}}"></show-map>

              </div>

        </div>

    </div>

  </section>

  </div>

</div>
@endsection
