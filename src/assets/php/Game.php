<?php


/**
 * @todo change update on game and player so that it changes locally and pushes to remote, so we can save traffic, even if its localhost, maybe performance boost
 * @todo fix my shit
 */


require_once("Database.php");
require_once("Utils.php");

class Game
{

    private $db = MysqliDb::getInstance();
    private $table = "games";

    private $id;
    private $topic;
    private $player = [];
    private $start_date;
    private $end_date;


    public function __get($property) 
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            throw new Exception("Property $property is not an element of game");
        }
    }
    
    public function __set($property, $value) 
    {
        if (property_exists($this, $property)) {
            if ($property == 'topic') {
               $this->$property = $this->db->escape(htmlspecialchars($value));
            } else {
                $this->$property = $value;
            }
            
        } else {
            throw new Exception("Property $property is not an element of game");
        }
        return $this;
    }

    
    public function __construct(string $topic, array $player)
    {
        $this->topic = $topic;
        $this->player = $player;

        $gameData = [
            "topic" => $this->topic,
            "player" => $this->player
        ];

        $this->id = $this->db->insert($this->table, $gameData);
        if ($this->db->getLastErrno()) throw new Exception("Failed to update game status");
    }

    public function start(int $gameID = $this->id) 
    {
        $gameData = [
            "start_date" => $this->db->now()
        ];

        $this->db
                ->where('id', $gameID)
                ->update($this->table, $gameData);
        
        if ($this->db->getLastErrno()) {
            $this->load($gameID);
            return $this;
        } else {
            throw new Exception("Failed to update game status");
        }
    }

    public function invite(Account $player, int $gameID = $this->id)
    {
        array_push($this->player, $player->id);
    }

    public function stop(int $gameID = $this->id)
    {

    }

    public function delete(int $gameID = $this->id)
    {

    }

    public function load(int $gameID = $this->id)
    {
        $dbGame = $this->db
                        ->where('id', $gameID)
                        ->getOne($this->table);
        
        foreach($dbGame as $column => $data) {
            $this->$column = $data;
        }
        
        return $this;
    }

}

