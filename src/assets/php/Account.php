<?php

require_once("Database.php");
require_once("Utils.php");

class Account
{

    private $db = MysqliDb::getInstance();
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
            return $this->$property;
        } else {
            throw new Exception("Property $property is not an element of account");
        }
    }
    
    public function __set($property, $value) 
    {
        if (property_exists($this, $property)) {
            switch ($property) {
                case 'username':
                    $this->property = $this->db->escape(htmlspecialchars($value));
                    break;
                case 'email':
                    $this->property = $this->db->escape(htmlspecialchars($value));
                    break;
                default:
                    $this->$property = $value;
            }
        } else {
            throw new Exception("Property $property is not an element of account");
        }
        return $this;
    }

    public function __construct(string $email = "", string $username = "", string $password = "")
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function create(string $username = $this->username, string $email = $this->email, string $password = $this->password) 
    {
        
        if (!isset($username) && !empty($username) && !isset($email) && !empty($email) && !isset($password) && !empty($password)) throw new Exception("Missing username, email or password");

        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_ARGON2ID);
        $this->last_ip = Utils::get_ip();
        $this->register_ip = Utils::get_ip();
        $this->created_at = $this->db->now();
        
        if (!$this->check()) throw new Exception("Account with the same name or Email already exists");

        $data = [
            "username" => $this->username,
            "email" => $this->email,
            "password" => $this->password,
            "last_ip" => $this->last_ip,
            "register_ip" => $this->register_ip,
            "created_at" => $this->created_at
        ];

        $this->id = $this->db->insert($this->table, $data);
        
        if ($this->db->getLastErrno() === 0) {
            return $this;
        } else {
            throw new Exception("Insert new account in database failed");
        }
    }

    public function check(string $username = $this->username, string $email = $this->email) 
    {
        $this->db
                ->where('username', $username)
                ->orWhere('email', $email);

        if ($this->db->has($this->table)) {
            // accounts already exists
            return false;
        } else {
            // no accounts with that name found
            return true;       
        }
    }

    public function login(string $userOrEmail, string $password = $this->password) 
    {
        $userOrEmail = $this->db->escape(htmlspecialchars($userOrEmail));
        
        $this->getAccountByName($userOrEmail);
        

        if ($this->db->count > 0 && $this->checkPassword($password)) {
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

        } else {
            // wrong credentials
            session_destroy();
            throw new Exception("Wrong credentials");
        }
    }

    public function logout() {
        session_destroy();
    }

    public function update(array $changed,  string $password, string $userID = $this->id) 
    {
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

        if($this->db->getLastErrno()) {
            return $this;
        } else {
            throw new Exception("User entry update failed");
        }
    }

    public function delete(string $password, int $userID = $this->id) 
    {
        $this->getAccountByID($userID);
        
        if ($this->checkPassword($password)) {
            $this->db
                    ->where('id', $this->id)
                    ->delete($this->table);
        } else {
            throw new Exception("Password wrong");
        }
    }

    public function getAccountByName(string $userOrEmail = $this->username) 
    {
        $userOrEmail = $this->db->escape(htmlspecialchars($userOrEmail));
        $dbUser = $this->db
                        ->where('username', $userOrEmail)
                        ->orWhere('email', $userOrEmail);

        if ($this->db->count > 0) {
            foreach($dbUser as $column => $data) {
                $this->$column = $data;
            }
            return $this;
        }
        throw new Exception("Name or Email not found in database");
    }

    public function getAccountByID(int $userID = $this->id) 
    {
        $userID = $this->db->escape(htmlspecialchars($userID));
        $dbUser = $this->db
                        ->where('id', $userID)
                        ->getOne($this->table);

        if ($this->db->count > 0) {
            foreach($dbUser as $column => $data) {
                $this->$column = $data;
            }
            return $this;
        }
        throw new Exception("ID not in database");
    }

    public function checkPassword(string $password, string $dbpassword = $this->password) 
    {
        if (!isset($this->password)) throw new Exception("Account not loaded in class");
        return password_verify($password, $dbpassword);
    }

}