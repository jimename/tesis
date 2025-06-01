<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(){
        return Permission::all();
    }
    public function attach(Request $request)
    {
        $validated = request()->validate([
            'user_id' => 'required',
        ]);
        $user = User::find($validated['user_id']);
        if ($request->permission==[]) {
            $user->syncPermissions();
        } else {
            $user->syncPermissions($request->permission);
        }
        return $user->permissions;
    }
}
