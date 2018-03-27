<?php namespace App\Auth;

use App\Helpers\DmClient;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class DmUserProvider implements UserProvider
{

    private static $dmClient;
    protected $model;

    public function retrieveById($identifier) {

    }

    public function retrieveByToken($identifier, $token) {

    }

    public function updateRememberToken(Authenticatable $user, $token) {

    }

    /**
     * @param array $credentials
     * @return DmUser
     * @throws \Exception
     */
    public function retrieveByCredentials(array $credentials) {
        self::$dmClient = resolve(DmClient::class);

        $query = "
            SELECT 
              users.ID,
              users.username,
              users.password,
              (SELECT privilege FROM users_permissions WHERE username = users.username AND users_permissions.role = 'EdgeCreator') AS privilege
            FROM users
            WHERE users.username ='{$credentials['username']}' AND users.password = '{$credentials['password']}'";
        $resultat = self::$dmClient->getQueryResults($query, self::$dmClient->DB_DM);
        if (count($resultat) == 0) {
            throw new \Exception('Invalid credentials !');
        }

        return new DmUser($resultat[0]->ID, $resultat[0]->username, $resultat[0]->password, $resultat[0]->privilege);
    }

    public function validateCredentials(Authenticatable $user, array $credentials) {

    }

    /**
     * @param DmUser $user
     * @param string $password
     */
    public function createSession($user, $password) {
        session([
            'username' => $user->getAuthIdentifierName(),
            'pass' => $password,
            'privilege' => $user->getPrivilege(),
            'mode_expert' => false
        ]);
        session()->save();
    }

    public function invalidateSession() {
        session()->invalidate();
    }

}
