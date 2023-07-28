<?php

class Character_tools_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function characterExists($guid, $realmConnection)
    {
        $query = $realmConnection->query("SELECT * FROM " . table("characters") . " WHERE " . column("characters", "guid") . " = ? AND " . column("characters", "online") . " = 0 AND " . column("characters", "account") . " = ?", array($guid, $this->user->getId()));

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0];
        } else {
            return false;
        }
    }

    public function setLocation($x, $y, $z, $o, $mapId, $characterGuid, $realmConnection)
    {
        $realmConnection->query("UPDATE " . table("characters") . " SET " . column("characters", "position_x") . " = ?, " . column("characters", "position_y") . " = ?, " . column("characters", "position_z") . " = ?, " . column("characters", "orientation") . " = ?, " . column("characters", "map") . " = ? WHERE " . column("characters", "guid") . " = ?", array($x, $y, $z, $o, $mapId, $characterGuid));
    }
}
