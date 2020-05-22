<?php

namespace App;

class Auth extends User {
    protected static $columns = ['id', 'firstName', 'lastName', 'username', 'password'];

    public $password;

    /**
     * Verify user login
     * 
     * @param String $username
     * @param String $password
     */
    public static function verify(String $username, String $password) {
        // Create a new Auth object
        $auth = new Auth;

        // Check if a user with $username exists; else return false
        if (count($user = $auth->where('username', '=', $username)->get()) < 1) {
            return false;
        }

        // Check if the $password matches the users password; else return false
        if (!password_verify($password, $user[0]->password)) {
            return false;
        }

        // Set session 'id' to the user id
        $_SESSION['id'] = $user[0]->id;
        return true;
    }

}