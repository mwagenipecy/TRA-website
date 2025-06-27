<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'phone'=>'required',
            'institution_id'=>'required',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

       $user= User::create([
            'name' => $input['first_name'] . ' '. $input['last_name'],
            'email' => $input['email'],
            'phone'=>$input['phone'],
            'email_verified_at' => now(),
            
            'institution_id'=>$input['institution_id'],
            'password' => Hash::make($input['password']),
        ]);


        $user->assignRole('student');

        return  $user;
    }
}
