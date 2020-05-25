<?php

require_once("Database.php");
require_once("Utils.php");

class Game
{
    private $db;
    private $table = "games";
    private $inv_table = "invites";
    private $played_cards_table = "played_cards";
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

    public function start(string $topic, Account $startUser, array $players) 
    {
        $this->setDefault(["topic", "players"], $topic, $players);

        $gameData = [
            "topic" => $this->topic,
            "start_date" => $this->db->now()
        ];
        $this->id = $this->db->insert($this->table, $gameData);

        $inviteData = [
            [
                "Inviter_UserID" => $startUser->id,
                "Invited_GameID" => $this->id,
                "Invited_UserID" => $startUser->id,
                "accepted" => 1
            ]
        ];

        foreach($players as $player) {
            array_push($inviteData, [
                "Inviter_UserID" => $startUser->id,
                "Invited_GameID" => $this->id,
                "Invited_UserID" => $player->id
            ]);
        }
        $this->db->insertMulti($this->inv_table, $inviteData);

        if ($this->db->getLastErrno() === 0) {
            $this->load();
            return $this;
        } else {
            throw new Exception("Failed to start game");
        }
    }

    public function invite(Account $inviter, Account $invited_player, int $gameID = null)
    {
        $this->setDefault(["id"], $gameID);
        
        if (sizeof($this->players) < 4) {
            $inviteData = [
                "Inviter_UserID" => $inviter->id,
                "Invited_GameID" => $gameID,
                "Invited_UserID" => $invited_player->id
            ];

            $this->db->insert($this->inv_table, $inviteData);
    
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
                ->where("end_date", NULL, "IS")
                ->update($this->table, $gameData);

        if ($this->db->getLastErrno()) throw new Exception("Failed to stop game");
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
        
        foreach($dbGame as $column => $data) {
            if ($data == null) continue;
            $this->__set($column, $data);
        }
        if ($this->db->getLastErrno()) throw new Exception("Failed to load game infos");
        $this->players = [];
        $dbInvites = $this->db
                            ->where("Invited_GameID", $this->id)
                            ->where("accepted", 1)
                            ->get($this->inv_table);
        foreach($dbInvites as $invite) {
            $player = new Account();
            $player->getAccountByID($invite["Invited_UserID"]);
            array_push($this->players, $player);
        }
        if ($this->db->getLastErrno()) throw new Exception("Failed to load player infos");
        return $this;
    }

    public function pickCard(Account $account, int $cardID, int $gameID = null) 
    {
        $this->setDefault(["id"], $gameID);
        
        $playedCardsData = [
            "GameID" => $this->id,
            "UserID" => $account->id,
            "CardID" => $cardID
        ];
        $this->db->insert($this->played_cards_table, $playedCardsData);
        if ($this->db->getLastErrno()) throw new Exception("Failed to pick card");
    }

    public function playedCards($gameID = null) {
        $this->setDefault(["id"], $gameID);

        $playedCards = $this->db
                                ->where("GameID", $this->id)
                                ->get($this->played_cards_table);
        if ($this->db->getLastErrno()) throw new Exception("Failed to find played cards");

        $cards = [
            "game" => $this->load($playedCards[0]["GameID"]),
            "user" => []
        ];
        $p_cards = [];
        $sum = 0;
        foreach($playedCards as $entry) {
            $acc = new Account;
            array_push($cards["user"], [
                "account" => $acc->getAccountByID($entry["UserID"]),
                "card" => $entry["CardID"]
            ]);
            array_push($p_cards, $entry["CardID"]);
            $sum += $entry["CardID"];
        }

        $cards["stats"] = [
            "mean" => $sum / sizeof($cards["user"]),
            "max" => max($p_cards),
            "min" => min($p_cards)
        ];

        return $cards;        
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

