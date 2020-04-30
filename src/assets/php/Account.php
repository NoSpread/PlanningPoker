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
    
    public $error_msg;

    public function __get($property) 
    {
        if (property_exists($this, $property)) {
            return $this->$property;
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
            }
            if ($property == 'username' || $property == 'email') {
               
            }
            $this->$property = $value;
        }
        return $this;
    }

    public function __construct(string $email = "", string $username = "", string $password = "")
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        return $this;
    }

    public function create(string $username = $this->username, string $email = $this->email, string $password = $this->password) 
    {
        $this->error_msg = "Missing username, email or password.";
        if (!isset($username) && !empty($username) && !isset($email) && !empty($email) && !isset($password) && !empty($password)) return false;

        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_ARGON2ID);
        $this->last_ip = Utils::get_ip();
        $this->register_ip = Utils::get_ip();
        $this->created_at = $this->db->now();
        
        $this->error_msg = "Account already exists.";
        if (!$this->check()) return false;

        $data = [
            "username" => $this->username,
            "email" => $this->email,
            "password" => $this->password,
            "last_ip" => $this->last_ip,
            "register_ip" => $this->register_ip,
            "created_at" => $this->created_at
        ];

        $this->db->insert($this->table, $data);
        
        if ($this->db->getLastErrno() === 0) {
            $this->error_msg = "";
            return $this;
        } else {
            $this->error_msg = "Insert in DB failed.";
            return false;
        }
    }

    public function check(string $username = $this->username, string $email = $this->email) 
    {
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

    public function login(string $userOrEmail, string $password = $this->password) 
    {
        $userOrEmail = $this->db->escape(htmlspecialchars($userOrEmail));
        $this->password = $password;
        $this->db
                ->where('username', $userOrEmail)
                ->orWhere('email', $userOrEmail);
        $dbUser = $this->db->getOne($this->table);

        if ($this->db->count > 0 && password_verify($this->password, $dbUser['password'])) {
            // verified login
            $updateData = [
                "last_login" => $this->db->now(),
                "last_ip" => Utils::get_ip()
            ];

            $this->db->update($this->table, $updateData);
            
            $dbUser = $this->db
                            ->where('username', $dbUser['username'])
                            ->where('email', $dbUser['email'])
                            ->getOne($this->table);

            foreach ($dbUser as $column => $data) {
                $this->__set($column, $data);
            }

            unset($this->password);
            $_SESSION['USER'] = $this;
        } else {
            // wrong credentials
            session_destroy();
            return false;
        }
    }

    /**
     * @todo change $changed to var, only 1 change at the time accepted!
     */
    public function update(array $changed,  string $password, string $username = $this->username, string $email = $this->email) 
    {
        $this->username = $username;
        $this->email = $email;

        foreach($changed as $column => $data) {
            $changed[$column] = $this->db->escape(htmlspecialchars($data));
        }

        if (!isset($changed['username'])) $changed['username'] = $this->username;
        if (!isset($changed['email'])) $changed['email'] = $this->email;
        
        $dbUser = $this->getAccountByName();
        if (password_verify($password, $dbUser['password'])) {
            $this->db->update($this->table, $changed);
        }

        if($this->db->getLastErrno()) {
            return true;
        } else {
            $this->error_msg = "Failed updating";
            return false;
        }
    }

    public function delete(string $password) 
    {
        $dbUser = $this->db
                        ->where('username', $this->username)
                        ->where('email', $this->email)
                        ->getOne($this->table);
        
        if (password_verify($password, $dbUser['password'])) {
            $this->db
                    ->where('username', $this->username)
                    ->where('email', $this->email)
                    ->delete($this->table);
        } else {
            $this->error_msg = "Wrong password. Did not delete account";
        }
    }

    public function getAccountByName(string $userOrEmail = $this->username) 
    {
        $userOrEmail = $this->db->escape(htmlspecialchars($userOrEmail));
        $dbUser = $this->db
                        ->where('username', $userOrEmail)
                        ->orWhere('email', $userOrEmail);
        if ($this->db->count > 0) return $dbUser;
        return false;
    }

    public function getAccountByID(int $userID = $this->id) 
    {
        $userID = $this->db->escape(htmlspecialchars($userID));
        $dbUser = $this->db
                        ->where('id', $userID)
                        ->getOne($this->table);
        if ($this->db->count > 0) return $dbUser;
        return false;
    }

}