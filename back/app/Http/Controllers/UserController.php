<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserController extends Controller{
    public function allPermissions()
    {
        return Permission::all();
    }
    public function attachPermissions(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission' => 'required|array'
        ]);

        $user = User::findOrFail($request->user_id);

        // Opcionalmente, validamos si los permisos existen
        $allPermissions = Permission::whereIn('name', $request->permission)->pluck('name')->toArray();

        // Sync reemplaza todos los permisos previos con los nuevos
        $user->syncPermissions($allPermissions);

        return response()->json(['message' => 'Permisos actualizados']);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('username', $request->email)->with('permissions')->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return [
                    "user" => $user,
                    "token" => $user->createToken('token')->plainTextToken
                ];
            } else {
                return response()->json([
                    'message' => 'Contraseña incorrecta'
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }
    }
    public function me(Request $request)
    {
        $user = User::where('id', $request->user()->id)->with('permissions')->first();
        return $user;
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }
    public function updatePassword(Request $request,User $user)
    {
        $request->validate([
            'password' => 'required'
        ]);
        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
    }

    public function index(){
        return User::with('permissions')->get();
    }
    public function show(User $user){ return $user; }
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $request['password'] = Hash::make($request->password);
        return User::create($request->all());
    }
    public function update(Request $request, $id){
        return User::find($id)->update($request->all());
    }
    public function destroy($id){
        $user=User::find($id);
        $user->delete();
        return $user;
    }
}
