<?php

namespace App\Http\Controllers\Api;
// use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\File;
use App\Position;
use App\Img;
use App\Apartment;
use App\Http\Resources\ServiceResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Sponsor;
use App\Service;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Http;

class ApiApartmentController extends Controller
{
    public function search(Request $request)
    {

        $response = file_get_contents('https://api.tomtom.com/search/2/geocode/' . $request->city . '.json?limit=1&key=' . env('TOMTOM_KEY'));


        $response = json_decode($response, true);
        $positionSearched = [
            'latit' => $response['results'][0]['position']['lat'],
            'longit' => $response['results'][0]['position']['lon'],
        ];

        $radius = 30;

        $filtered = Position::radius($positionSearched['latit'], $positionSearched['longit'], $radius);
        $arrayId = [];
        foreach ($filtered as $position) {
            array_push($arrayId, $position->apartment_id);
        }

        return response()->json(Apartment::with('services', 'position', 'imgs')
        ->orderBy("active", 'desc')
        ->find($arrayId)
        ->where('beds', '>=', $request->guests));
    }

    public function index(Request $request)
    {
        $user = User::find($request->id);
        $apartments = $user->apartments;
        $sponsors = Sponsor::active();
        $array_sponsored = [];
        foreach ($sponsors as $sponsor) {
            array_push($array_sponsored, $sponsor->apartment_id);
        }

        return response()->json(compact('apartments'));
    }

    public function destroy(Request $request)
    {

        //$data = $request->apartmentId;
        $elimina = Apartment::find($request->apartmentId);
        //
         $elimina->position()->delete();
         $elimina->messages()->delete();
         $elimina->sponsors()->delete();
         $elimina->imgs()->delete();
        //
         $serv = $elimina->services;
        foreach($serv as $services){
            $elimina->services()->detach($services->id);
        }

        $elimina->delete();

        //return redirect()->back(); //reindirizzo alla pagina precedente.
        return response()->json(compact('elimina'));
    }

    public function submit(Request $request)
    {

        $request['data'] = json_decode($request['data']);
        $data = $request['data'];

        $newApartment = new Apartment;
        $newApartment->title = $data->title;
        $newApartment->user_id = $data->user_id;
        $newApartment->description = $data->description;
        $newApartment->rooms = $data->rooms;
        $newApartment->beds = $data->beds;
        $newApartment->bathrooms = $data->bathrooms;
        $newApartment->metri_quadrati = $data->metri_quadrati;
        $newApartment->active = true;
        $newApartment->views_count = 0;
        $newApartment->price = $data->price;

        // COVER_IMG
        if($request->file('cover')){
            $name = Str::random(25);
            $imgEst = $request->file('cover')->extension();
            $path = $name . '.' . $imgEst;
            $ImgApartament = $request->file('cover')->move(public_path().'/images/', $path);
            $newApartment->cover_img = 'images/' . $path;
        }

        $newApartment->save();

        // SERVICES
        if(($data->services)){
            $newApartment->services()->attach($data->services);
        }

        // LOCATION
        $newApAddress = $data->city . ', ' . $data->address;

        $response = file_get_contents('https://api.tomtom.com/search/2/geocode/' . $newApAddress . '.json?limit=1&key=' . env('TOMTOM_KEY'));

        $response = json_decode($response, true);
        $latit = $response['results'][0]['position']['lat'];
        $longit = $response['results'][0]['position']['lon'];

        $newPosition = new Position;
        $newPosition->create([
            'apartment_id' => $newApartment->id,
            'latitude' => $latit,
            'longitude' => $longit,
            'address' => $newApAddress,
        ]);

        //IMAGES
        $arrayImgApartment = $request->file('images'); // prendo il file

         if($request->hasFile('images'))
         { //ciclo per salvarlo

             foreach ($arrayImgApartment as $file) {
                 $newImg = new Img; // collego la varibile alla tabella img
                 $name = Str::random(25); // creo nome random di 25 caratteri
                 $imgEst = $file->extension();
                 $path = $name . '.' . $imgEst;
                 $file->move(public_path().'/images/', $path);  //salva l'img nella cartella di destinazione
                 $newImg->path = 'images/' . $path;  //aggiungo path
                 $newImg->apartment_id = $newApartment->id; // aggangio all'appartamento tramite id
                 $newImg->save();             // salvo tutto
              }

         }

        return response()->json(compact('newApartment','data'));

    }

    public function returnServices()
    {

      $services = Service::all();

      return response()->json(compact('services'));

    }

    public function edit(Request $request)
    {
        $apartment = Apartment::find($request->id);
        $services = $apartment->services;
        $address = $apartment->position->address;

        return response()->json(compact('apartment','services', 'address'));
    }

    public function reupdate(Request $request)
    {
        // $validator = Apartment::make($request->all(), [
        //     'title' => 'required',
        //     'description' => 'required',
        //     'rooms' => 'required',
        //     'beds' =>  'required',
        //     'bathrooms' =>  'required',
        //     'metri_quadri'  => 'required',

        //     'price' => 'required',
        //     'cover_img' => '',
        //     'image'=> '',
        // ]);
        $request['data'] = json_decode($request['data']);
        $data = $request['data'];

        $apartment = Apartment::find($data->apartment_id);
        //$services = Service::all();
        //

        $apartment->title = $data->title;
        $apartment->description = $data->description;
        $apartment->rooms = $data->rooms;
        $apartment->beds = $data->beds;
        $apartment->bathrooms = $data->bathrooms;
        $apartment->metri_quadrati = $data->metri_quadrati;
        $apartment->active = true;
        $apartment->views_count = 0;
        $apartment->price = $data->price;

        // COVER_IMG
        if($request->file('cover')){
            $name = Str::random(25);
            $imgEst = $request->file('cover')->extension();
            $path = $name . '.' . $imgEst;
            $ImgApartament = $request->file('cover')->move(public_path().'/images/', $path);
            $apartment->cover_img = 'images/' . $path;
        }

        $apartment->save();

        // SERVICES
        $apartment->services()->sync($data->services);

        // LOCATION
        $newApAddress = $data->city . ', ' . $data->address;

        $response = file_get_contents('https://api.tomtom.com/search/2/geocode/' . $newApAddress . '.json?limit=1&key=' . env('TOMTOM_KEY'));

        $response = json_decode($response, true);
        $latit = $response['results'][0]['position']['lat'];
        $longit = $response['results'][0]['position']['lon'];

        $newPosition = $apartment->position();
        $newPosition->update([
            //'apartment_id' => $data->apartment_id,
            'latitude' => $latit,
            'longitude' => $longit,
            'address' => $newApAddress,
        ]);

        //IMAGES
        $arrayImgApartment = $request->file('images'); // prendo il file

         if($request->hasFile('images'))
         { //ciclo per salvarlo

             foreach ($arrayImgApartment as $file) {
                 $newImg = new Img; // collego la varibile alla tabella img
                 $name = Str::random(25); // creo nome random di 25 caratteri
                 $imgEst = $file->extension();
                 $path = $name . '.' . $imgEst;
                 $file->move(public_path().'/images/', $path);  //salva l'img nella cartella di destinazione
                 $newImg->path = 'images/' . $path;  //aggiungo path
                 $newImg->apartment_id = $data->apartment_id; // aggangio all'appartamento tramite id
                 $newImg->save();             // salvo tutto
              }

         }

        return response()->json(compact('data','apartment'));
    }


}
