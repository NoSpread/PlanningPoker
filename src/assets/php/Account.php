<?php

require_once "Database.php";
require_once "Game.php";
require_once "Utils.php";

class Account
{

    private $db;
    private $table = 'accounts';

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
    public function login(string $password, string $userOrEmail = "", bool $remember_me = false)
    {
        $this->setDefault(["username", "password"], $userOrEmail, $password);
        $this->getAccountByName($this->username);

        if ($this->checkPassword($password)) {

            if ($remember_me) {
                // do remember me
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
            session_destroy();
            throw new Exception("wrong credentials");
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

        if (!isset($id)) return false;

        foreach ($changed as $column => $data) {
            $changed[$column] = $this->db->escape(htmlspecialchars($data));
        }

        $user = $this->getAccountByID($userID);
        if (password_verify($password, $user->password)) {
            $this->db
                ->where('id', $userID)
                ->update($this->table, $changed);
        }

        if ($this->db->getLastErrno() === 0) {
            return $this;
        } else {
            throw new Exception("user entry update failed");
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
            throw new Exception("password wrong");
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
        throw new Exception("name or email not found in database");
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

        if (!isset($this->password)) throw new Exception("account not loaded in class");
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

        if (!preg_match("@[`!@§$#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?~]@", $pwd)) {
            $errors[] = "Password must contain at least 1 special character!";
        }
    }

    public function fetchOwnGames(int $userID = null)
    {
        $this->setDefault(["id"], $userID);

        $games = $this->db
            ->join("games g", "g.id = i.Invited_GameID", "INNER")
            ->where("Inviter_UserID", $this->id)
            ->groupBy("Invited_GameID")
            ->get("invites i", null, "Invited_GameID");
        foreach ($games as $gameID) {
            $game = new Game();
            array_push($this->games, $game->load($gameID["Invited_GameID"]));
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
            $game = new Game();
            array_push($this->gameInvites, $game->load($gameID["Invited_GameID"]));
        }
        return $this;
    }
}
