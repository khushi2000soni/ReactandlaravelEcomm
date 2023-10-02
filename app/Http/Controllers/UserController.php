<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        
        if (isset($_SERVER['HTTP_CONNECTION']) && $_SERVER['HTTP_CONNECTION'] == 'keep-alive') {
            // If online, send the data to the server for immediate storage
            $userData = json_decode($request->getContent());
           // dd($userData);
            if ($userData) {
                $user = new User();
                $user->name = $userData->name;
                $user->email = $userData->email;
                $user->password = bcrypt($userData->password); // Hash the password
                $user->save();
    
                // Return a response indicating success
                return response()->json(['success' => true, 'message' => 'Data stored in the database.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Invalid data received.']);
            }
        } else {
            // If offline, you can handle it differently or return a response
            return response()->json(['success' => false, 'message' => 'Data will be synchronized when online.']);
        }
    }

    //
    function register(Request $request){
        $data= $request->all();
        $user= User::create($data);
        return $user;
    }
}
