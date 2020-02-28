<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(Role::ADMIN)) {
            return redirect('/');
        }
        
        $fail = $this->specialValidation($request);

        if ($fail) {
            return back()->with(['error' => $fail]);
        }

        $user = User::find((int) $request->user_id);

        $user->roles()->sync($request->role);

        return redirect('/role-user/' . $user->id . '/edit')
             ->with('success', 'Rol(es) ajustados correctamente');
    }

    /**
     * [edit description]
     * 
     * @param  User   $role_user [description]
     * 
     * @return [type]            [description]
     */
    public function edit(User $role_user)
    {
        $roles = Role::all(['id', 'role']);

        return view('users.role-user', ['user' => $role_user, 'roles' => $roles]);
    }

    /**
     * Make validation if it's editing to the admin of the system
     * 
     * @param  $request
     * 
     * @return string|null
     */
    public function specialValidation($request)
    {
        $has_not_admin_role = $request->role ? !in_array(1, $request->role) : false;

        if (( $request->role == null && $request->user_id == 1) ||
            ($has_not_admin_role && $request->user_id == 1)) {

            return 'No se puede dejar al Administrador sin el Rol de Admin';
        
        }
        return null;
    }
}
