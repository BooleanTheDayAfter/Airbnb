<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\UserInfo;

class ApiUserInfoController extends Controller
{
  public function show(Request $request)
  {
    $user = User::find($request->id);
    $userInfo = UserInfo::find($user);
    return response()->json(compact('userInfo' , 'user'));
  }

  public function updateuser(Request $request)
  {

      $data = $request->all();
      $user = User::find($request->user_id);

      $user->update([
          'username' => $request['username'],
      ]);

      $info = UserInfo::find($user->info->id);
      $info->update([
         'fullname' => $request['fullname'],
         'sex' => $request['sex'],
         'p_iva' => $request['p_iva'],
         'birthdate' => $request['birthdate'],
         'phone_number' => $request['phone_number'],
         'address' => $request['address'],
         'fiscal_code' => $request['fiscal_code'],
      ]);

      return response()->json(compact('data','user','info'));

  }


}


?>
