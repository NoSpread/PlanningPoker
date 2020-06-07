<?php

require_once "Database.php";
require_once "Game.php";
require_once "Utils.php";

class Account
{

    private $db;
    private $table = 'accounts';
    private $token_table = 'auth_tokens';
    private $domain = "localhost";

    // account information
    private $id;
    private $username;
    private $email;
    private $password;
    private $last_ip;
    private $register_ip;
    private $last_login;
    private $created_at;

    private $gameInvites = [];
    private $games = [];


    /**
     * returns the requested property
     * @access public
     * @param  mixed $property
     * @throws Exception $property is not an element of account 
     * @return Account $this->{$property}
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        } else {
            throw new Exception("Property $property is not an element of account");
        }
    }

    /**
     * changes the value of a property
     * @access public
     * @param  mixed $property
     * @param  mixed $value
     * @throws Exception $property is not an element of account
     * @return Account $this
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            switch ($property) {
                case 'username':
                    $this->{$property} = $this->db->escape(htmlspecialchars($value));
                    break;
                case 'email':
                    $this->{$property} = $this->db->escape(htmlspecialchars($value));
                    break;
                case 'password':
                    $this->{$property} = password_hash($value, PASSWORD_ARGON2ID);
                    break;
                default:
                    $this->{$property} = $value;
            }
        } else {
            throw new Exception("Property $property is not an element of account");
        }
        return $this;
    }

    /**
     * constructor of the account class
     * @access public
     * @param  string $email
     * @param  string $username
     * @param  string $password
     * @return void
     */
    public function __construct(string $email = "", string $username = "", string $password = "")
    {
        $this->db = MysqliDb::getInstance();
        $this->__set("username", $username);
        $this->__set("email", $email);
        $this->__set("password", $password);
    }

    /**
     * creates an account and adds it to database
     * @access public
     * @param  string $username
     * @param  string $email
     * @param  string $password
     * @throws Exception account with the same name or email already exists
     * @throws Exception failed to insert new account into database
     * @return Account $this
     */
    public function create(string $username = "", string $email = "", string $password = "")
    {
        $this->setDefault(["username", "email", "password"], $username, $email, $password);

        if (!isset($this->username) && !empty($this->username) && !isset($this->email) && !empty($this->email) && !isset($this->password) && !empty($this->password)) throw new Exception("Missing username, email or password");

        $this->last_ip = Utils::get_ip();
        $this->register_ip = Utils::get_ip();
        $this->created_at = $this->db->now();

        if (!$this->check()) throw new Exception("account with the same name or email already exists");

        $data = [
            "username" => $this->username,
            "email" => $this->email,
            "password" => $this->password,
            "last_ip" => inet_pton($this->last_ip),
            "register_ip" => inet_pton($this->register_ip),
            "created_at" => $this->created_at
        ];

        $this->id = $this->db->insert($this->table, $data);

        if ($this->db->getLastErrno() === 0) {
            return $this;
        } else {
            throw new Exception("insert new account in database failed");
        }
    }

    /**
     * checks if username or email already exists
     * @access public
     * @param  string $username
     * @param  string $email
     * @return bool account exists/does not exist
     */
    public function check(string $username = "", string $email = "")
    {
        $this->setDefault(["username", "email"], $username, $email);

        $this->db
            ->where('username', $this->username)
            ->orWhere('email', $this->email);

        if ($this->db->has($this->table)) {
            // accounts already exists
            return false;
        } else {
            // no accounts with that name found
            return true;
        }
    }

    /**
     * account login if userdata is correct
     * @access public
     * @param  string $password
     * @param  string $userOrEmail
     * @param  bool $remember_me
     * @throws Exception wrong credentials
     * @return bool login successful
     */
    public function login(string $password, bool $remember_me = false)
    {
        $this->setDefault(["password"], $password);
        $this->getAccountByName($this->username);

        if ($this->checkPassword($password)) {

            if ($remember_me) {
                $token = Utils::generateToken(32);
                $tokenHash = hash("sha256", $token);
                $expires = strtotime("+1 month");
                $timestamp = date("Y-m-d H:i:s", $expires);

                $oneinamillion = true;
                while ($oneinamillion) {
                    $selector = Utils::generateToken(6);
                    $this->db
                        ->where("selector", $selector)
                        ->get($this->token_table);
                    if ($this->db->count == 0) $oneinamillion = false;
                }

                $data = [
                    "selector" => $selector,
                    "hashedValidator" => $tokenHash,
                    "userid" => $this->id,
                    "expires" => $timestamp
                ];

                setcookie("remember", $selector . $token, $expires, "/", $this->domain);

                $this->db->insert($this->token_table, $data);

                if ($this->db->getLastErrno() !== 0) {
                    throw new Exception("Failed to set token");
                }
            }
            // verified login
            $updateData = [
                "last_login" => $this->db->now(),
                "last_ip" => Utils::get_ip()
            ];

            $this->db
                ->where('id', $this->id)
                ->update($this->table, $updateData);

            $this->getAccountByID($this->id);
            $_SESSION['USER'] = serialize($this);
            return true;
        } else {
            // wrong credentials
            //session_destroy();
            throw new Exception("Username or password wrong");
        }
    }

    /**
     * account logout
     * @access public
     * @return void
     */
    public static function logout()
    {
        session_destroy();
    }

    /**
     * update accountdata in database
     * @access public
     * @param array $changed
     * @param string $password
     * @param integer $userID
     * @throws Exception unser entry update failed
     * @return bool|Account $this
     */
    public function update(array $changed,  string $password, int $userID = null)
    {
        $this->setDefault(["id"], $userID);

        foreach ($changed as $column => $data) {
            if ($column == "password") {
                $changed[$column] =  password_hash($data, PASSWORD_ARGON2ID);
            } else {
                $changed[$column] = $this->db->escape(htmlspecialchars($data));
            }
        }

        $this->db
            ->where('id', $this->id)
            ->update($this->table, $changed);

        if ($this->db->getLastErrno() === 0) {
            return $this->getAccountByID($this->id);
        } else {
            throw new Exception("User update failed");
        }
    }

    /**
     * deletes account in database
     * @access public
     * @param string $password
     * @param integer $userID
     * @throws Exception password wrong
     * @return bool deletion successful
     */
    public function delete(string $password, int $userID = null)
    {
        $this->setDefault(["id"], $userID);

        $this->getAccountByID($userID);

        if ($this->checkPassword($password)) {
            $this->db
                ->where('id', $this->id)
                ->delete($this->table);
        } else {
            throw new Exception("Password is wrong");
        }
        return true;
    }

    /**
     * gets account by username or email
     * @access public
     * @param string $userOrEmail
     * @throws Exception name or email not found in database
     * @return Account $this
     */
    public function getAccountByName(string $userOrEmail = "")
    {
        $this->setDefault(["username"], $userOrEmail);

        $dbUser = $this->db
            ->where('username', $this->username)
            ->orWhere('email', $this->username)
            ->getOne($this->table);

        if ($this->db->count > 0) {
            foreach ($dbUser as $column => $data) {
                if ($column == "last_ip" || $column == "register_ip") {
                    $this->__set($column, inet_ntop($data));
                } else if ($column == "password") {
                    $this->password = $data;
                } else {
                    $this->__set($column, $data);
                }
            }
            return $this;
        }
        throw new Exception("Name or email does not exist");
    }

    /**
     * gets account by id
     * @access public
     * @param integer $userID 
     * @throws Exception ID not in database
     * @return Account $this
     */
    public function getAccountByID(int $userID = null)
    {
        $this->setDefault(["id"], $userID);

        $dbUser = $this->db
            ->where('id', $this->id)
            ->getOne($this->table);

        if ($this->db->count > 0) {
            foreach ($dbUser as $column => $data) {
                if ($column == "last_ip" || $column == "register_ip") {
                    $this->__set($column, inet_ntop($data));
                } else if ($column == "password") {
                    $this->password = $data;
                } else {
                    $this->__set($column, $data);
                }
            }
            return $this;
        }
        throw new Exception("ID not in database");
    }

    public function getAccountBySelector($selector, $validator)
    {
        $this->db->setTrace (true);
        $auth = $this->db
            ->rawQueryOne("SELECT * FROM " . $this->token_table . " WHERE expires > NOW() AND selector = ?", [$selector]);

        //DBG
        $hash = hash("sha256", $validator);
        //DBG
        $trace = $this->db->trace;
        if ($this->db->count == 0) return false;
        if (hash_equals($auth["hashedValidator"], hash("sha256", $validator))) return $this->getAccountByID($auth["userid"]);
        return false;
    }

    /**
     * checks if password is correct
     * @access public
     * @param string $password
     * @param string $dbpassword
     * @throws Exception account not loaded in class
     * @return bool matching password and hash
     */
    public function checkPassword(string $password, string $dbpassword = "")
    {
        $this->setDefault(["password"], $dbpassword);
        
        if (!isset($this->password)) throw new Exception("Account not loaded in class");
        return password_verify($password, $this->password);
    }

    /**
     * sets default values
     * @access private
     * @param array $type
     * @param mixed $vars
     * @return Account $this
     */
    private function setDefault(array $type, ...$vars)
    {
        for ($i = 0; $i < sizeof($type); $i++) {
            if (($vars[$i] != "" || $vars[$i] != null) && (!isset($this->{$type[$i]}) || empty($this->{$type[$i]}))) {
                $this->__set($type[$i], $vars[$i]);
                //$this->{$type[$i]} = $vars[$i];
            }
        }
        return $this;
    }

    public static function checkPasswordRequirements($pwd, &$errors)
    {
        if (strlen($pwd) < 8) {
            $errors[] = "Password must be at least 8 characters!";
        }

        if (!preg_match("@[^\d]@", $pwd)) {
            $errors[] = "Password must contain at least 1 digit!";
        }

        if (!preg_match("@[a-zA-Z]@", $pwd)) {
            $errors[] = "Password must contain at least 1 letter!";
        }

        if (!preg_match("@[^\w]@", $pwd)) {
            $errors[] = "Password must contain at least 1 special character!";
        }
    }

    public static function checkUsernameRequirements($username, &$errors)
    {
        if (strlen($username) > 20) {
            $errors[] = "Username is too long!";
        }
        if (strlen($username) < 3) {
            $errors[] = "Username is too short!";
        }
        if (!preg_match("@[a-zA-Z0-9\u00c4-\u00df\u00e4-\u00fc]@", $username)) {
            $errors[] = "Username has invalid characters!";
        }
    }

    public function fetchOwnGames(int $userID = null)
    {
        $this->setDefault(["id"], $userID);

        $games = $this->db
            ->join("games g", "g.id = i.Invited_GameID", "INNER")
            ->where("Inviter_UserID", $this->id)
            ->where("end_date", NULL, "IS")
            ->groupBy("Invited_GameID")
            ->get("invites i", null, "Invited_GameID");
        foreach ($games as $gameID) {
            //$game = new Game();
            array_push($this->games, $gameID["Invited_GameID"]);//$game->load($gameID["Invited_GameID"]));
        }
        return $this;
    }

    public function fetchOpenInvites(int $userID = null)
    {
        $this->setDefault(["id"], $userID);

        $games = $this->db
            ->join("games", "id = Invited_GameID", "INNER")
            ->where("accepted", 0)
            ->where("Invited_UserID", $this->id)
            ->where("end_date", NULL, "IS")
            ->groupBy("Invited_GameID")
            ->get("invites", null, "Invited_GameID");

        foreach ($games as $gameID) {
            //$game = new Game();
            array_push($this->gameInvites, $gameID["Invited_GameID"]);//$game->load($gameID["Invited_GameID"]));
        }
        return $this;
    }

    public function partOfGame(int $gameID, int $userID = null) 
    {
        $this->setDefault(["id"], $userID);

        $games = $this->db
                        ->join("games", "id = Invited_GameID", "INNER")
                        ->where("accepted", 1)
                        ->where("end_date", NULL, "IS")
                        ->where("Invited_GameID", $gameID)
                        ->where("Invited_UserID", $this->id)
                        ->orWhere("Inviter_UserID", $this->id)
                        ->groupBy("Invited_GameID")
                        ->get("invites", null, "Invited_GameID");
        
        if ($this->db->count > 0) {
            return true;
        }
        return false;
    }

    public function clearSession(int $userID = null) {
        $this->setDefault(["id"], $userID);

        $this->db
                ->where("userid", $this->id)
                ->delete($this->token_table);
    }
}
