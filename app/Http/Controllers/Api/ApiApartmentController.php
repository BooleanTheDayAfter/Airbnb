<?php

namespace App\Http\Controllers\Api;
// use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Position;
use App\Apartment;
use App\Http\Resources\ServiceResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Sponsor;
use App\Service;

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

    public function store(Request $request)
    {
        // $validation = $request->validate([
        // 'title' => 'required | min:3 | string | max:100',
        // 'description' => 'required | string | min:3 |max:250',
        // 'rooms' => 'required | numeric | min:1',
        // 'beds' =>  'required| min:1 | numeric',
        // 'bathrooms' =>  'required | numeric | min:1',
        // 'metri_quadri'  => 'required | numeric | min:10',
        // 'price' => 'required | numeric | min:5',
        // 'cover' => 'file',
        // 'image' => 'file',
        // ]);
        $data = $request->all();
        $user = Auth::user();
        $newApartment = new Apartment;

        $newApartment->title = $data['title'];
        $newApartment->user_id = $user->id;
        $newApartment->description = $data['description'];
        $newApartment->rooms = $data['rooms'];
        $newApartment->beds = $data['beds'];
        $newApartment->bathrooms = $data['bathrooms'];
        $newApartment->metri_quadrati = $data['metri_quadrati'];
        $newApartment->active = true;
        $newApartment->views_count = 0;
        $newApartment->price = $data['price'];

        // cover_img
        if($request->file('cover')){
            $name = Str::random(25);
            $imgEst = $request->file('cover')->extension();
            $path = $name . '.' . $imgEst;
            $ImgApartament = $request->file('cover')->move(public_path().'/images/', $path);
            $newApartment->cover_img = 'images/' . $path;
        }
        //$request->file('cover')->store('covers');

        $newApartment->save(); //salva
        // services

        if(isset($data['services'])){
            $newApartment->services()->attach($data['services']);
        }

        // locations

        $newApAddress = $request->city . ', ' . $request->address;

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

         $files = $newApartment->imgs; //salvo il file in variabile
         $arrayImgApartment = $request->file('image'); // prendo il file

         if($request->hasFile('image'))
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

        return redirect()->route('apartments.index');

    }

    public function returnServices()
    {

      $services = Service::all();

      return response()->json(compact('services'));

    }


}
