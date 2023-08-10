<?php

class data_model extends CI_Model
{

    public function GetCountAccount($realmId = 1)
    {

        $character_database = $this->realms->getRealm($realmId)->getCharacters();
        $character_database->connect();

        $query = $character_database->getConnection()->query("SELECT COUNT(*) AS total FROM characters WHERE account= ?", array($this->user->getId()));

        if ($query && $query->num_rows() > 0) {
            $results = $query->result_array();

            return (int)$results[0]['total'];
        }

        return 0;
    }

    public function GetAccChar($realmId = 1)
    {
        //Connect to the character database
        $character_database = $this->realms->getRealm($realmId)->getCharacters();
        $character_database->connect();

        //Get the connection and run a query
        $query = $character_database->getConnection()->query("SELECT guid,race,gender, class, level,account,name FROM characters WHERE account= ?", array($this->user->getId()));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function Getavator($character)
    {
        $realms = $this->realms->getRealms();
        return $this->realms->formatAvatarPath($character);

    }


    public function getmoneychar($realmId, $guid)
    {
        //Connect to the character database
        $character_database = $this->realms->getRealm($realmId)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("SELECT * FROM  `characters` where guid = ?", array($guid));

        if ($query && $query->num_rows() > 0) {
            $results = $query->result_array();

            return $results[0]['money'];
        } else
            return 0;
    }

    public function Calculate_money($money)
    {
        $url = $this->template->page_url;
        if ($money != 0) {
            $result[] = 3;

            $copper = ($money % 100);
            $money = ($money - $copper) / 100;
            $silver = ($money % 100);
            $gold = ($money - $silver) / 100;
        } else {
            $copper = 0;
            $silver = 0;
            $gold = 0;
        }

        $result[0] = $copper;
        $result[1] = $silver;
        $result[2] = $gold;

        return $result[2];
    }


}