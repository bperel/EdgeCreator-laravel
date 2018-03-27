<?php namespace App\Auth;

use App\Helpers\DmClient;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class DmUser implements Authenticatable
{
    private $id;
    private $username;
    private $password;
    private $privilege;

    /**
     * DmUser constructor.
     * @param integer $id
     * @param string  $username
     * @param string  $password
     * @param string  $privilege
     */
    public function __construct($id, $username, $password, $privilege) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->privilege = $privilege;
    }


    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName() {
        return $this->username;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken() {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value) {
        return;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName() {
        return null;
    }

    public function getPrivilege() {
        return $this->privilege;
    }
}
