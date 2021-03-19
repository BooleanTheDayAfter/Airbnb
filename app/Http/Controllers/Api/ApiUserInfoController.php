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
}


?>
