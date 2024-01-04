<?php

class Levelup extends MX_Controller 
{
	private $characters;

	public function __construct()
	{
		parent::__construct();

		$this->user->userArea();
		
		$this->load->config('config');
        
		$this->init();
	}

	private function init(){
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
	public function index(){
		
		requirePermission("view");
		
		clientLang("cant_afford", "levelup");
		clientLang("select", "levelup");
		clientLang("selected", "levelup");
		clientLang("p_w", "levelup");
		clientLang("levelup1", "levelup");
		clientLang("seccess", "levelup");
		
		//Set the title
		$this->template->setTitle(lang('LevelUP',"levelup"));
			
		//Load the content
		$content_data = array(
			"characters" => $this->characters,
			"url" => $this->template->page_url,
			"total" => $this->total,
			"dp" => $this->user->getDp(),
			"config" => $this->config,
            "Emulator" => $this,
		);
		
		$page_content = $this->template->loadPage("levelup.tpl", $content_data);	
		
		//Load the page
		$page_data = array(
			"module" => "default", 
			"headline" => lang("LevelUP","levelup"), 
			"content" => $page_content
		);
		
		$page = $this->template->loadPage("page.tpl", $page_data);
		
		$this->template->view($page, "modules/levelup/css/levelup.css", "modules/levelup/js/levelup.js");
	}
	
	public function submit(){
	
		$characterGuid = $this->input->post('guid'); 
		$realmId = $this->input->post('realm'); 
		 

		
		
		// Make sure the realm actually supports console commands
		if (!$this->realms->getRealm($realmId)->getEmulator()->hasConsole())
		{
			die(lang('realmdoesnotsupport', "levelup"));
		}
		 
		if ($characterGuid && $realmId){
			
				//The tool is valid
				$realmConnection = $this->realms->getRealm($realmId)->getCharacters();
				$realmConnection->connect();
                
                
                if (!$this->checkcheckchar($characterGuid,$realmId)){

					die(lang('realmdoesnotsupport', "levelup"));

                     }
			
			
				//Get the price
				$Price = $this->config->item("Server_fee");
				
				// Make sure the character exists
				if (!$realmConnection->characterExists($characterGuid)){
					
					die(lang('characterdoesnotexist', "levelup"));
				}

				// Make sure the character belongs to this account
				if (!$realmConnection->characterBelongsToAccount($characterGuid, $this->user->getId())){
				
					die(lang('youraccount', "levelup"));
				}
				
				//Get the character name
				$CharacterName = $realmConnection->getNameByGuid($characterGuid);
				
				//Make sure we've got the name
				if (!$CharacterName){
					
					
						die(lang('resolveyourcharactersname', "levelup"));
				}
			
				
					$command = $this->GetCommand($realmId, $CharacterName);
					
					if (!$command){
					
						
						die(lang('notsupporttheservice', "levelup"));
					}
					
					// die($command);

				//Check if the user can afford the service
				if ($this->user->getDp() >= $Price || $Price == 0){
						if ($Price > 0){
						//Execute the command
					  $this->realms->getRealm($realmId)->getEmulator()->send($command);

						$this->user->setDp($this->user->getDp() - $Price);
						 die("1");
					}					
				}else {
					die(lang("no_cost", "levelup"));
				}
			
		}else{
			
			
			die(lang('Somethingwentwrong', "levelup"));
		}
	}
	
	private function GetCommand( $realmId, $CharacterName){
			
				return $this->GetlevelupCommand($realmId, $CharacterName);
	}
	private function GetlevelupCommand($realmId, $CharacterName){
		
		 $server_max_core= $this->config->item('server_max_core');

		switch ($this->getEmulatorString($realmId)){
			case 'trinity':
            case 'trinity_df':
            case 'trinity_wotlkclassic':
            case 'trinity_sl':
            case 'trinity_legion':
            case 'trinity_cata':
            case 'azerothcore':
            case 'skyfire':
		
				
                   return ".char level " . $CharacterName." ".$server_max_core;
			
			case "cmangos":
		
				
                   return ".char level " . $CharacterName." ".$server_max_core;
		}
		
		return false;
	}
    	
	private function getEmulatorString($realmId){
		
		    $replace = [
            '_sph',
            '_soap',
            '_rbac'
        ];
 
		return str_replace($replace, '', $this->realms->getRealm($realmId)->getConfig('emulator'));
	}
    
    public function checkcheckchar ($guid,$realmid){

			$character_database = $this->realms->getRealm($realmid)->getCharacters();
	    	$character_database->connect();
			$query = $character_database->getConnection()->query("SELECT * FROM characters WHERE guid = ? and  online=0", array($guid));  

                 if($query->num_rows() > 0)
		                

                       return true;
	                  else
	                 	
		          return false;
	                 	 

        }	
}
