<?php class Unban_char extends MX_Controller 
{
	private $characters;
	private $total;
	

	public function __construct()
	{
		parent::__construct();

		$this->user->userArea();
		
		$this->load->config('config');

        $this->init(); 

	}
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
	
	public function index()
	{
		requirePermission("view");
		

		
		//Set the title
		$this->template->setTitle("UnBan");
			

			$realms = $this->realms->getRealms();
				
				//Get characters, guilds, items for each realm
				foreach($realms as $realm)
				{
					// Assign the realm ID
					$i = $realm->getId();
				}
		//Load the content
		$content_data = array(
			"characters" => $this->characters,
			"url" => $this->template->page_url,
			"total" => $this->total,
			"dp" => $this->user->getDp(),
			"config" => $this->config,
			"char_unban"=>$this->character_banned($i)
		);
		
		$page_content = $this->template->loadPage("unban.tpl", $content_data);	
		
		//Load the page
		$page_data = array(
			"module" => "default", 
			"headline" => "Unbanned Character", 
			"content" => $page_content
		);
		
		$page = $this->template->loadPage("page.tpl", $page_data);
		
		$this->template->view($page, "modules/unban_char/css/unban.css", "modules/unban_char/js/unban.js");
	}
	
	public function submit()
	{
		//Get the post variables
	
		$characterGuid = $this->input->post('guid'); 
		$realmId = $this->input->post('realm'); 
		
		// Make sure the realm actually supports console commands
		if (!$this->realms->getRealm($realmId)->getEmulator()->hasConsole())
		{
				die(long("relamdoesnotsupport","unban_char"));
		}
		
		if ( $characterGuid && $realmId)
		{
			
				//The tool is valid
				$realmConnection = $this->realms->getRealm($realmId)->getCharacters();
				$realmConnection->connect();
				
				//Get the price
				$Price = $this->config->item("unban_price");
				
				// Make sure the character exists
				if (!$realmConnection->characterExists($characterGuid))
				{
						die(long("noselectedcharacter","unban_char"));
				}

			
				if (!$realmConnection->characterBelongsToAccount($characterGuid, $this->user->getId()))
				{
				die(long("notcharacteryouraccount","unban_char"));
				}
				
				//Get the character name
				$CharacterName = $realmConnection->getNameByGuid($characterGuid);
				
				//Make sure we've got the name
				if (!$CharacterName)
				{
					die(long("unablecharactername","unban_char"));
				}
				
				//Check if the user can afford the service
				if ($this->user->getDp() >= $Price || $Price == 0)
				{
					//Get the command for this emulator
					$command = $this->GetCommand( $realmId, $CharacterName);
					
					if (!$command)
					{
						die(long("cammandnoserver","unban_char"));
					}
					
					//Execute the command
					$this->realms->getRealm($realmId)->getEmulator()->sendCommand($command);
					
					//Update Donation Points
					if ($Price > 0)
					{
					    
						$this->user->setDp($this->user->getDp() - $Price);
							//Successful
				      	die("1");
					}
					
				
				}
				else 
				{
					die(long("notenough","unban_char"));
				}
		}
		else
		{
			die(long("Theinputisinvalid","unban_char"));
		}
	}
	
	private function GetCommand ($realmId, $CharacterName){

		 	return $this->GetUnbanCommand($realmId, $CharacterName);
	}
	
	private function GetUnbanCommand($realmId, $CharacterName)
	{
		switch ($this->getEmulatorString($realmId))
		{
			case "trinity":
			case "azerothcore":
			case "trinity_cata":
				
			    
				return ".unban character " . $CharacterName;
		}
		
		return false;
	}
	
	private function getEmulatorString($realmId)
	{
		return str_replace(array('_ra', '_soap', '_rbac'), '', $this->realms->getRealm($realmId)->getConfig('emulator'));
	}
	public function character_banned($realmId =1)
	{
		//Connect to the character database		
		$character_database = $this->realms->getRealm($realmId)->getCharacters();
		$character_database->connect();
	


		//Get the connection and run a query
		$query = $character_database->getConnection()->
		query("SELECT * FROM character_banned WHERE active=1");

		if($query->num_rows() > 0)
		{
		$row = $query->result_array();

			return $row;
		}
		else
		{
			return false;
		}
	  

	}
     
 }