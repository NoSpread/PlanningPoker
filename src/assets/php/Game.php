<?php

require_once "Database.php";
require_once "Utils.php";

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


    /**
     * returns the requested property
     * @access public
     * @param mixed $property
     * @throws Exception $property is not an element of game
     * @return Game $this->{$property}
     */
    public function __get($property) 
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        } else {
            throw new Exception("Property $property is not an element of game");
        }
    }
    
    /**
     * changes the value of a property
     * @access public
     * @param  mixed $property
     * @param  mixed $value
     * @throws Exception $property is not an element of game
     * @return Game $this
     */
    public function __set($property, $value) 
    {
        if (property_exists($this, $property)) {
            if ($property == 'topic') { // game-topic = goal whose effort is to be estimated with the game
               $this->{$property} = $this->db->escape(htmlspecialchars($value));
            } else {
                $this->{$property} = $value;
            }
            
        } else {
            throw new Exception("Property $property is not an element of game");
        }
        return $this;
    }

    
    /**
     * constructor of the game class
     * @access public
     * @param string $topic goal whose effort is to be estimated
     * @param array $players
     * @return void
     */
    public function __construct(string $topic = "", array $players = [])
    {
        $this->db = MysqliDb::getInstance();
        $this->__set("topic", $topic);
        $this->__set("players", $players);
    }

    /**
     * start game
     * @access public
     * @param string $topic goal whose effort is to be estimated
     * @param Account $startUser
     * @param array $players
     * @throws Exception Failed to start game
     * @return Game $this
     */
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

    /**
     * invite another player
     * @access public
     * @param Account $inviter
     * @param Account $invited_player
     * @param integer $gameID
     * @throws Exception Failed to invite player
     * @return bool invitation successful/failed
     */
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

    /**
     * ending a game
     * @access public
     * @param integer $gameID
     * @throws Exception Failed to stop game
     * @return bool ending successful
     */
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

    /**
     * delete game
     * @access public
     * @param integer $gameID
     * @throws Exception Failed to delete game
     * @return bool deletion successful
     */
    public function delete(int $gameID = null)
    {
        $this->setDefault(["id"], $gameID);
        $this->db
                ->where("id", $this->id)
                ->delete($this->table);
        
        if ($this->db->getLastErrno()) throw new Exception("Failed to delete game");
        return true;
    }

    /**
     * load game and player information from database
     * @access public
     * @param integer $gameID
     * @throws Exception Failed to load game information
     * @throws Exception Failed to load player information
     * @return Game $this
     */
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
        if ($this->db->getLastErrno()) throw new Exception("Failed to load game information");
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
        if ($this->db->getLastErrno()) throw new Exception("Failed to load player information");
        return $this;
    }

    /**
     * choosing card
     * @access public
     * @param Account $account
     * @param integer $cardID
     * @param integer $gameID
     * @throws Exception Failed to pick card
     * @return void
     */
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

    /**
     * already played cards
     * @access public
     * @param integer $gameID
     * @throws Exception Failed to find played cards
     * @return array $cards
     */
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

    /**
     * sets default values
     * @access private
     * @param array $type
     * @param mixed $vars
     * @return Game $this
     */
    private function setDefault(array $type, ...$vars) 
    {
        for ($i = 0; $i < sizeof($type); $i++) {
            if (($vars[$i] != "" || $vars[$i] != null) && !isset($this->{$type[$i]}) || empty($this->{$type[$i]})) {
                $this->__set($type[$i], $vars[$i]);
            }
        }
        return $this;
    }

    private function join(Account $account) 
    {
        $updateData = [
            "accepted" => 1
        ];
        $this->db
                ->where("Invited_UserID", $account->id)
                ->update($this->inv_table, $updateData);
                
        array_push($account->games, $this);
        array_push($this->players, $account);

        return $this;
    }

}

