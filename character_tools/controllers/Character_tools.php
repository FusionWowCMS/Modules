<?php

use MX\MX_Controller;

class Character_tools extends MX_Controller
{
    private $characters;
    private $total;

    private $tools = [
        1 => "name_change",
        2 => "race_change",
        3 => "faction_change",
        4 => "appearance_change",
    ];

    public function __construct()
    {
        parent::__construct();

        $this->user->userArea();

        //Load the Config
        $this->load->config('character_tools');

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

        clientLang("cant_afford", "character_tools");
        clientLang("select", "character_tools");
        clientLang("selected", "character_tools");
        clientLang("dp", "character_tools");
        clientLang("free", "character_tools");

        //Set the title
        $this->template->setTitle("Character Tools");

        //Load the content
        $content_data = array(
            "characters" => $this->characters,
            "url" => $this->template->page_url,
            "total" => $this->total,
            "dp" => $this->user->getDp(),
            "config" => $this->config
        );

        $page_content = $this->template->loadPage("character_tools.tpl", $content_data);

        //Load the page
        $page_data = [
            "module" => "default",
            "headline" => "Character Tools",
            "content" => $page_content
        ];

        $page = $this->template->loadPage("page.tpl", $page_data);

        $this->template->view($page, "modules/character_tools/css/character_tools.css", "modules/character_tools/js/character_tools.js");
    }

    /**
     * Submit method
     */
    public function submit()
    {
        //Get the post variables
        $ToolId = $this->input->post('id');
        $characterGuid = $this->input->post('guid');
        $realmId = $this->input->post('realm');

        // Make sure the realm actually supports console commands
        if (!$this->realms->getRealm($realmId)->getEmulator()->hasConsole()) {
            die("The realm does not support that service.");
        }

        if ($ToolId && $characterGuid && $realmId) {
            //Validate the tool id
            if (isset($this->tools[$ToolId])) {
                //The tool is valid
                $realmConnection = $this->realms->getRealm($realmId)->getCharacters();
                $realmConnection->connect();

                //Get the price
                $Price = $this->config->item($this->tools[$ToolId] . "_price");

                // Make sure the character exists
                if (!$realmConnection->characterExists($characterGuid)) {
                    die("The selected character does not exist.");
                }

                // Make sure the character belongs to this account
                if (!$realmConnection->characterBelongsToAccount($characterGuid, $this->user->getId())) {
                    die("The selected character does not belong to your account.");
                }

                //Get the character name
                $CharacterName = $realmConnection->getNameByGuid($characterGuid);

                //Make sure we've got the name
                if (!$CharacterName) {
                    die("The website was unable to resolve your character's name.");
                }

                //Check if the user can afford the service
                if ($this->user->getDp() >= $Price || $Price == 0) {
                    //Get the command for this emulator
                    $command = $this->GetCommand($ToolId, $CharacterName);

                    if (!$command) {
                        die("The realm does not support that service.");
                    }

                    //Execute the command
                    $this->realms->getRealm($realmId)->getEmulator()->sendCommand($command);

                    //Update Donation Points
                    if ($Price > 0) {
                        $this->user->setDp($this->user->getDp() - $Price);
                    }

                    //Successful
                    die("1");
                } else {
                    die("You dont have enough Donation Points.");
                }
            } else {
                die("The selected service is invalid.");
            }
        } else {
            die("Something went wrong, please try again.");
        }
    }

    private function GetCommand($ToolId, $CharacterName)
    {
        //Start by switching the tool id
        switch ($this->tools[$ToolId]) {
            case 'name_change':
                return '.character rename ' . $CharacterName;
            case 'race_change':
                return '.character changerace ' . $CharacterName;
            case 'faction_change':
                return '.character changefaction ' . $CharacterName;
            case 'appearance_change':
                return '.character customize ' . $CharacterName;
        }

        return false;
    }
}
