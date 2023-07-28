var CharacterTools = {

	User: {

		dp: null,

		initialize: function(dp)
		{
			this.dp = dp;
		}
	},

	Character: {

		name: null,
		guid: null,
		realm: null,

		initialize: function(name, guid, realm)
		{
			this.name = name;
			this.guid = guid;
			this.realm = realm;
		}
	},

	selectCharacter: function(button, realm, guid, name)
	{
		var CharSection = $("#select_character");
		
		CharacterTools.Character.initialize(name, guid, realm);
		
		$(".item_group").each(function()
		{
			$(this).removeClass("item_group").addClass("character-select");
			$(this).find(".nice_active").removeClass("nice_active").html(lang("select", "character_tools"));
		});

		$(button).parents("tr").removeClass("character-select").addClass("item_group");
		$(button).addClass("nice_active").html(lang("selected", "character_tools"));
	},
	
	IsLoading: false,
	
	GetToolName: function(ToolId)
	{
		switch (ToolId)
		{
			case 1:	return "Name Change";
			case 2: return "Race Change";
			case 3: return "Faction Change";
			case 4: return "Appearance Change";
			case 5: return "Revive";
			case 6: return "Level Up";
		}
		
		return "Unknown";
	},
	
	buy: function(button, ToolId, Price)
	{
		//Check if we're already executing a command
		if (CharacterTools.IsLoading)
			return;
			
		//Check if we have selected character
		if (CharacterTools.Character.guid == null)
		{
			Swal.fire("Please select a character first.");
			return;
		}
			
		var CanAfford = false;

		if (Price == 0)
		{
			CanAfford = true;
		}
		else
		{
			if (CharacterTools.User.dp < Price)
			{
				Swal.fire(lang("cant_afford", "character_tools"));
			}
			else
			{
				CanAfford = true;
			}
		}

		if (CanAfford)
		{
			$.ajax({
			  	beforeSend: function(xhr)
				{
					CharacterTools.IsLoading = true;
					$(button).parents(".select_tool").addClass("active_tool");
					$(button).html('Loading...');
			  	}
			});
			
			// Execute the service
			$.post(Config.URL + "character_tools/submit", 
			{
				id: ToolId, 
				guid: CharacterTools.Character.guid, 
				realm: CharacterTools.Character.realm, 
				csrf_token_name: Config.CSRF
			},
			function(data)
			{
				if (data == 1)
				{
					Swal.fire(CharacterTools.GetToolName(ToolId) + " was successfully purchased for " + CharacterTools.Character.name);
				}
				else
				{
					Swal.fire(data);
				}
				
				CharacterTools.IsLoading = false;
				$(".active_tool").find('.nice_button').html("Purchase");
				$(".active_tool").removeClass("active_tool");
			});
		}
	}
}