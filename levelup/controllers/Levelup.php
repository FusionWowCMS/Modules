<?php

class Levelup extends MX_Controller 
{
	private $characters;
	private $total;
    

	

	public function __construct()
	{
		parent::__construct();

		$this->user->userArea();
		
		$this->load->config('character_tools');
        
        $this->language = $this->config->item('cta_language');

		//Init the variables
		$this->init();
	}

	/**
	 * Init every variable
	 */
	private function init()
	{
		$this->characters = $this->user->getCharacters($this->user->getId());


		foreach($this->characters as $realm_key => $realm)
		{
			if(is_array($realm['characters']))
			{
				foreach($realm['characters'] as $character_key => $character)
				{
					$this->characters[$realm_key]['characters'][$character_key]['avatar'] = $this->realms->formatAvatarPath($character);
				}
			}
		}

		$this->total = 0;

		foreach($this->characters as $realm)
		{
			if($realm['characters'])
			{
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
		
		clientLang("cant_afford", "levelup");
		clientLang("select", "levelup");
		clientLang("selected", "levelup");
		clientLang("p_w", "levelup");
		clientLang("levelup1", "levelup");
		
		//Set the title
		$this->template->setTitle($this->config->item("T_M"));
			
		//Load the content
		$content_data = array(
			"characters" => $this->characters,
			"url" => $this->template->page_url,
			"total" => $this->total,
			"dp" => $this->user->getDp(),
			"config" => $this->config,
            "Emulator" => $this,
		);
		
		$page_content = $this->template->loadPage("character_tools.tpl", $content_data);	
		
		//Load the page
		$page_data = array(
			"module" => "default", 
			"headline" => $this->config->item("T_M"), 
			"content" => $page_content
		);
		
		$page = $this->template->loadPage("page.tpl", $page_data);
		
		$this->template->view($page, "modules/levelup/css/character_tools.css", "modules/levelup/js/character_tools.js");
	}
	
	/**
	 * Submit method
	 */
	public function submit()
	{
	
		$characterGuid = $this->input->post('guid'); 
		$realmId = $this->input->post('realm'); 
		 

		// Make sure the realm actually supports console commands
		if (!$this->realms->getRealm($realmId)->getEmulator()->hasConsole())
		{
			die($this->language['realmdoesnotsupport']);
		}
		
		if ($characterGuid && $realmId)
		{
			
				//The tool is valid
				$realmConnection = $this->realms->getRealm($realmId)->getCharacters();
				$realmConnection->connect();
                
                
                if (!$this->checkcheckchar($characterGuid,$realmId))
                     
                     {

                     	die($this->language['characterisonline']);

                     }
                     
				
				//Get the price
				$Price = $this->config->item("lvlup_change");
				
				// Make sure the character exists
				if (!$realmConnection->characterExists($characterGuid))
				{
					die($this->language['characterdoesnotexist']);
				}

				// Make sure the character belongs to this account
				if (!$realmConnection->characterBelongsToAccount($characterGuid, $this->user->getId()))
				{
					die($this->language['youraccount']);
				}
				
				//Get the character name
				$CharacterName = $realmConnection->getNameByGuid($characterGuid);
				
				//Make sure we've got the name
				if (!$CharacterName)
				{
					die($this->language['resolveyourcharactersname']);
				}
				
					$command = $this->GetCommand($realmId, $CharacterName);
					
					if (!$command)
					{
						die($this->language['notsupporttheservice']);
					}
					
					

				//Check if the user can afford the service
				if ($this->user->getDp() >= $Price || $Price == 0)
				{
						if ($Price > 0)
					{
						//Execute the command
					    $this->realms->getRealm($realmId)->getEmulator()->sendCommand($command);

						$this->user->setDp($this->user->getDp() - $Price);
						die("1");
					}

		

					
				}
				else 
				{
					die(lang("no_cost", "levelup"));
				}
			
		}
		else
		{
			die($this->language['Somethingwentwrong']);
		}
	}
	
	private function GetCommand( $realmId, $CharacterName)
	{
			
				return $this->GetlevelupCommand($realmId, $CharacterName);
	}
	
	
	

	private function GetlevelupCommand($realmId, $CharacterName)
	{
		 $server_max_core= $this->config->item('server_max_core');

		switch ($this->getEmulatorString($realmId))
		{
			case "trinity":
                return ".char level " . $CharacterName." 80";
			case "trinity_cata":
                return ".char level " . $CharacterName." 85";
			case "skyfire":
			case "arkcore":
			case "mangos":
			case "mangosr2":
			//	return ".char level admin 85";//;.$server_max_core;
				return ".char level " . $CharacterName." ".$server_max_core;
		}
		
		return false;
	}
    
    
    public function getEmulator($realmId)
	{
		

		switch ($this->getEmulatorString($realmId))
		{
			case "trinity":
            case "skyfire":
                return 80;
			case "trinity_cata":
                return 85;  
			case "arkcore":
			case "mangos":
			case "mangosr2":
			return 70;
		}
		
	
	}
    
    
	
	private function getEmulatorString($realmId)
	{
		return str_replace(array('_ra', '_soap', '_rbac'), '', $this->realms->getRealm($realmId)->getConfig('emulator'));
	}
    
    public function checkcheckchar ($guid,$realmid)

	{

			$character_database = $this->realms->getRealm($realmid)->getCharacters();
	    	$character_database->connect();
			$query = $character_database->getConnection()->query("SELECT * FROM characters WHERE guid = ? and  online=0", array($guid));  

                 if($query->num_rows() > 0)
		                

                       return true;
	                  else
	                 	
		          return false;
	                 	 

        }
    
    
	
    

	
}
