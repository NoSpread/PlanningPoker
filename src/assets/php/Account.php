<?php

require_once("Database.php");
require_once("Utils.php");

class Account
{

    private $db;
    private $table = 'accounts';

    private $id;
    private $username;
    private $email;
    private $password;
    private $last_ip;
    private $register_ip;
    private $last_login;
    private $created_at;
    
    private $gameInvites = [];
    

    public function __get($property) 
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        } else {
            throw new Exception("Property $property is not an element of account");
        }
    }
    
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

    public function __construct(string $email = "", string $username = "", string $password = "")
    {
        $this->db = MysqliDb::getInstance();
        $this->__set("username", $username);
        $this->__set("email", $email);
        $this->__set("password", $password);
    }

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

    public function login(string $password, string $userOrEmail = "") 
    {
        $this->setDefault(["username", "password"], $userOrEmail, $password);
        $this->getAccountByName($this->username);

        if ($this->checkPassword($password)) {
            // verified login
            $updateData = [
                "last_login" => $this->db->now(),
                "last_ip" => Utils::get_ip()
            ];

            $this->db
                    ->where('id', $this->id)
                    ->update($this->table, $updateData);
            
            $this->getAccountByID($this->id);
            $_SESSION['USER'] = $this;
            return true;
        } else {
            // wrong credentials
            session_destroy();
            throw new Exception("wrong credentials");
        }
    }

    public function logout() {
        session_destroy();
    }

    public function update(array $changed,  string $password, int $userID = null) 
    {
        $this->setDefault(["id"], $userID);

        if (!isset($id)) return false;

        foreach($changed as $column => $data) {
            $changed[$column] = $this->db->escape(htmlspecialchars($data));
        }
        
        $user = $this->getAccountByID($userID);
        if (password_verify($password, $user->password)) {
            $this->db
                    ->where('id', $userID)
                    ->update($this->table, $changed);
        }

        if($this->db->getLastErrno() === 0) {
            return $this;
        } else {
            throw new Exception("user entry update failed");
        }
    }

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

    public function getAccountByName(string $userOrEmail = "") 
    {
        $this->setDefault(["username"], $userOrEmail);

        $dbUser = $this->db
                        ->where('username', $this->username)
                        ->orWhere('email', $this->email)
                        ->getOne($this->table);

        if ($this->db->count > 0) {
            foreach($dbUser as $column => $data) {
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

    public function getAccountByID(int $userID = null) 
    {
        $this->setDefault(["id"], $userID);

        $userID = $this->db->escape(htmlspecialchars($userID));
        $dbUser = $this->db
                        ->where('id', $this->id)
                        ->getOne($this->table);

        if ($this->db->count > 0) {
            foreach($dbUser as $column => $data) {
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

    public function checkPassword(string $password, string $dbpassword = "") 
    {
        $this->setDefault(["password"], $dbpassword);

        if (!isset($this->password)) throw new Exception("account not loaded in class");
        return password_verify($password, $this->password);
    }

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
}