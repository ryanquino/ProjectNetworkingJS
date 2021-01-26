<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'contactNumber' => ['required', 'string', 'max:11', 'unique:users'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();
        
        $referralId = DB::table('users')->where('accountNumber', '=', $input['referralId'])->value('id');
        if(empty($referralId))$referralId = null;

        if($referralId == null)$referralId = 1;
        $lastId = DB::table('users')->orderByDesc('id')->value('id');
        DB::table('referrals')->insert(['parent'=> $referralId, 'child' => $lastId + 1]);
        
        return User::create([
            'accountNumber' => strtoupper(uniqid()),
            'name' => $input['name'],
            'contactNumber' => $input['contactNumber'],
            'address' => $input['address'],
            'birthdate' => $input['birthdate'],
            'email' => $input['email'],
            'referralId' => $referralId,
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
