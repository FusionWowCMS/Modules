<?php

class Playtime extends MX_Controller
{
    private $characters;

    public function __construct()
    {
        parent::__construct();

        $this->user->userArea();

        $this->load->config('config');

        //Init the variables
        $this->init();
    }

    /**
     * Init every variable
     */
    private function init()
    {
        $this->characters = $this->user->getCharacters($this->user->getId());

        foreach ($this->characters as $realm_key => $realm) {
            if (is_array($realm['characters'])) {
                foreach ($realm['characters'] as $character_key => $character) {
                    $this->characters[$realm_key]['characters'][$character_key]['avatar'] = $this->realms->formatAvatarPath($character);
                }
            }
        }

        $this->total = 0;

        foreach ($this->characters as $realm) {
            if ($realm['characters']) {
                $this->total += count($realm['characters']);
            }
        }
    }

    /**
     * Load the page
     */
    public function index()
    {
        requirePermission("view");

        $realmId = $this->config->item("realmId");
        $itemid = $this->config->item('itemid');
        $item_count = $this->config->item('item_count');

        //Load the content
        $content_data = array(
            "characters" => $this->characters,
            "url" => $this->template->page_url,
            "total" => $this->total,
            "dp" => $this->user->getDp(),
            "config" => $this->config,
            'IconName' => $this->geticon($realmId, $itemid),
            'QualityID' => $this->GetQualityID($realmId, $itemid, 'Quality'),
            'Name' => $this->GetQualityID($realmId, $itemid, 'name'),
            "this" => $this
        );

        $page_content = $this->template->loadPage("playtime.tpl", $content_data);

        $this->template->setTitle(long("TITLE", "playtime"));
        //Load the page
        $page_data = array(
            "module" => "default",
            "headline" => long("TITLE", "playtime"),
            "content" => $page_content
        );

        $page = $this->template->loadPage("page.tpl", $page_data);

        $this->template->view($page, "modules/playtime/css/playtime.css", "modules/playtime/js/playtime.js");
    }


    public function submit()
    {
        $characterGuid = $this->input->post('guid');
        $realmId = $this->input->post('realm');

        // Make sure the realm actually supports console commands
        if (!$this->realms->getRealm($realmId)->getEmulator()->hasConsole()) {
            die(long("relamdoesnotsupport", "playtime"));
        }

        if ($characterGuid && $realmId) {


            $realmConnection = $this->realms->getRealm($realmId)->getCharacters();
            $realmConnection->connect();


            if (!$this->checkcheckchar($characterGuid, $realmId)) {
                die(long("LongCharacterOnline", "playtime"));
            }

            //Get the price
            $convert_Price = $this->config->item("reward_amount");
            $PriceCurrency = $this->config->item('price_currency');

            // Make sure the character exists
            if (!$realmConnection->characterExists($characterGuid)) {
                die(long("noselectedcharacter", "playtime"));
            }

            // Make sure the character belongs to this account
            if (!$realmConnection->characterBelongsToAccount($characterGuid, $this->user->getId())) {
                die(long("notcharacteryouraccount", "playtime"));
            }

            //Get the character name
            $CharacterName = $realmConnection->getNameByGuid($characterGuid);

            //Make sure we've got the name
            if (!$CharacterName) {
                die(long("unablecharactername", "playtime"));
            }
            if ($this->checktotaltime($CharacterName, $realmId)) {
                if ($PriceCurrency == 'DP') {

                    $this->user->setDp($this->user->getDp() + $convert_Price);
                }
                $this->Senditems($realmId, $characterGuid);

                //Successful
                die("1");
            } else {
                die(long("notenoughplaytimecharacter", "playtime"));

            }

        } else {
            die(long("Theinputisinvalid", "playtime"));
        }
    }

    public function Senditems($realmId, $guid)
    {
        $title = $this->config->item('senditemtitle');
        $body = $this->config->item('senditembody');

        $itemid = $this->config->item('itemid');
        $item_count = $this->config->item('item_count');

        if ($item_count)
            $items = array(array('id' => $itemid . ":" . $item_count));
        else
            $items = array(array('id' => $itemid));

        $realm = $this->realms->getRealm($realmId);

        $CharacterName = $realm->getCharacters()->getNameByGuid($guid);
        //Send the email

        $Sent = $this->realms->getRealm($realmId)->getEmulator()->sendItems($CharacterName, $title, $body, $items);

        if (strpos($Sent, 'Something went wrong'))

            return false;
        else
            return true;
    }


    public function checktotaltime($name, $realmid)
    {
        $character_database = $this->realms->getRealm($realmid)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("SELECT * FROM characters WHERE name = '$name' and  online=0");

        if ($query->num_rows() > 0) {
            $row = $query->result_array();

            if ($row[0]["totaltime"] >= $this->config->item('convert_min_time')) {

                if ($this->changetotoaltime($name, $realmid))
                    return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }


    public function Gototaltime($realmid, $guid)
    {
        $character_database = $this->realms->getRealm($realmid)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("SELECT totaltime FROM characters WHERE guid = ? and  online=0", array($guid));

        if ($query->num_rows() > 0) {
            $row = $query->result_array();

            return $this->GeConvert($row[0]["totaltime"]);
        } else {
            return false;
        }
    }

    public function changetotoaltime($name, $realmid)
    {
        $convert = $this->config->item('convert_min_time');

        $character_database = $this->realms->getRealm($realmid)->getCharacters();
        $character_database->connect();
        $character_database->getConnection()->query("UPDATE characters SET totaltime = totaltime - '$convert' WHERE name = '$name' and online=0");

        return true;
    }

    public function checkcheckchar($guid, $realmid)
    {
        $character_database = $this->realms->getRealm($realmid)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("SELECT * FROM characters WHERE guid = ? and  online=0", array($guid));

        if ($query->num_rows() > 0) {

            return true;
        } else {
            return false;
        }
    }

    public function GeConvert($totaltime)
    {
        if ($totaltime > 86400) {
            $uptime = round(($totaltime / 24 / 60 / 60), 0) . " Days";
        } elseif ($totaltime > 3600) {
            $uptime = round(($totaltime / 60 / 60), 0) . " Hours";
        } else {
            $uptime = round(($totaltime / 60), 0) . " Minutes";
        }

        return $uptime;
    }

    public function geticon($realm, $itemid)
    {
        $displayid = $this->getDisplayId($itemid, $realm);

        return $this->getIconName($displayid);
    }

    private function getDisplayId($item, $realm)
    {
        $realmObj = $this->realms->getRealm($realm);
        $item = $realmObj->getWorld()->getItem($item);

        return $item['displayid'];
    }

    private function getIconName($id)
    {
        // Get the item ID
        $query = $this->db->query("SELECT * FROM data_wotlk_itemdisplayinfo WHERE id=? LIMIT 1", array($id));

        // Check for results
        if ($query->num_rows() > 0) {
            $row = $query->result_array();

            return strtolower($row[0]['iconname']);
        } else {
            return 'inv_misc_questionmark';
        }
    }


    public function GetQualityID($realmId, $entry, $data)
    {
        $WorldConnection = $this->realms->getRealm($realmId)->getWorld();
        $WorldConnection->connect();

        $query = $WorldConnection->getConnection()->query("SELECT * FROM `item_template` WHERE `entry` = ?  ", array($entry));


        if ($query && $query->num_rows() > 0) {

            $results = $query->result_array();


            return $results[0][$data];
        }

        return false;
    }
}
