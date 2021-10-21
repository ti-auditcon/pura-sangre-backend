<?php

namespace App\Models\Users;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    /**
     *  Name of the table in the database
     *
     *  @var  string
     */
    protected $table = 'password_resets';

    /**
     *  Mass assignable
     *
     *  @var  array
     */
    protected $fillable = ['email', 'token', 'expired'];


    /**
     *  Remove olds tokens and return a new one
     *
     *  @param   string  $email
     *
     *  @return  string
     */
    public static function getNewToken(string $email)
    {
        self::expireOldsTokens($email);

        return self::generateNewToken($email);
    }

    /**
     *  Crate a new token for an specific email
     *
     *  @param   string  $email  [$email description]
     *
     *  @return  string  token
     */
    public static function generateNewToken(string $email): string
    {
        $token = Str::random(150);

        return self::create([
            'email' => $email, 'token' => $token
        ])->token;
    }

    /**
     *  Expires all old tokens of an specific user by email
     *
     *  @param   string  $email
     *
     *  @return  void
     */
    public static function expireOldsTokens(string $email): void
    {
        self::where('email', $email)->update(['expired' => true]);
    }

    /**
     *  [tokenDoesntExists description]
     *
     *  @param   string  $token  [$token description]
     *
     *  @return  bool            [return description]
     */
    public static function tokenDoesntExists(string $token): bool
    {
        return !static::tokenExists($token);
    }
    
    /**
     *  [tokenExists description]
     *
     *  @param   [type]  $token  [$token description]
     *
     *  @return  bool            [return description]
     */
    public static function tokenExists($token): bool
    {
        return self::where('token', $token)->where('expired', false)->exists('id');
    }

    /**
     *  Expire an specific token
     *
     *  @param   string  $token
     *
     *  @return  void
     */
    public static function spendToken(string $token): void
    {
        self::where('token', $token)->update(['expired' => true]);
    }
}
