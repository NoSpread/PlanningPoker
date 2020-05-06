<?php

require_once("Database.php");
require_once("Utils.php");

class Game
{
    private $db;
    private $table = "games";
    private $id;
    private $topic;
    private $players = [];
    private $start_date;
    private $end_date;


    public function __get($property) 
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        } else {
            throw new Exception("Property $property is not an element of game");
        }
    }
    
    public function __set($property, $value) 
    {
        if (property_exists($this, $property)) {
            if ($property == 'topic') {
               $this->{$property} = $this->db->escape(htmlspecialchars($value));
            } else {
                $this->{$property} = $value;
            }
            
        } else {
            throw new Exception("Property $property is not an element of game");
        }
        return $this;
    }

    
    public function __construct(string $topic = "", array $players = [])
    {
        $this->db = MysqliDb::getInstance();
        $this->__set("topic", $topic);
        $this->__set("players", $players);
    }

    public function start(string $topic, array $players, int $gameID = null) 
    {
        $this->setDefault(["topic", "players", "id"], $topic, $players, $gameID);

        $gameData = [
            "topic" => $this->topic,
            "start_date" => $this->db->now()
        ];

        foreach($this->players as $index => $player) {
            $gameData["player_" . $index] = $player;
        }

        $this->id = $this->db->insert($this->table, $gameData);

        if ($this->db->getLastErrno() === 0) {
            $this->load();
            return $this;
        } else {
            throw new Exception("Failed to start game");
        }
    }

    public function invite(Account $player, int $gameID = null)
    {
        $this->setDefault(["id"], $gameID);
        
        if (sizeof($this->players) < 4) {
            array_push($this->players, $player->id);
            
            $gameData = [];
            foreach($this->players as $index => $player) {
                $gameData["player_" . $index] = $player;
            }
    
            $this->db
                    ->where("id", $this->id)
                    ->update($this->table, $gameData);
    
            if ($this->db->getLastErrno()) throw new Exception("Failed to invite player");
            return true;
        }
        return false;
    }

    public function stop(int $gameID = null)
    {
        $this->setDefault(["id"], $gameID);

        $gameData = [
            "end_date" => $this->db->now()
        ];

        $this->db
                ->where("id", $this->id)
                ->update($this->table, $gameData);
        
        if ($this->db->getLastErrno()) throw new Exception("Failed to invite player");
        return true;

    }

    public function delete(int $gameID = null)
    {
        $this->setDefault(["id"], $gameID);
        $this->db
                ->where("id", $this->id)
                ->delete($this->table);
        
        if ($this->db->getLastErrno()) throw new Exception("Failed to delete game");
        return true;
    }

    public function load(int $gameID = null)
    {
        $this->setDefault(["id"], $gameID);

        $dbGame = $this->db
                        ->where('id', $this->id)
                        ->getOne($this->table);
        
        $this->players = [];
        foreach($dbGame as $column => $data) {
            if ($data == null) continue;
            if (substr($column, 0, 6) === "player") {
                array_push($this->players, $data);
            } else {
                $this->__set($column, $data);
            }
        }
        return $this;
    }

    private function setDefault(array $type, ...$vars) 
    {
        for ($i = 0; $i < sizeof($type); $i++) {
            if (($vars[$i] != "" || $vars[$i] != null) && !isset($this->{$type[$i]}) || empty($this->{$type[$i]})) {
                $this->__set($type[$i], $vars[$i]);
            }
        }
        return $this;
    }

}

