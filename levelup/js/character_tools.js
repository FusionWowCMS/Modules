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
		
		$(".item_group", CharSection).each(function()
		{
			$(this).removeClass("item_group").addClass("select_character");
			$(this).find(".nice_active").removeClass("nice_active").html(lang("select", "levelup"));
		});
		
		$(button).parents(".select_character").removeClass("select_character").addClass("item_group");
		$(button).addClass("nice_active").html(lang("selected", "levelup"));
	},
	
	IsLoading: false,
	

	
	Purchase: function(button)
	{
		//Check if we're already executing a command
		if (CharacterTools.IsLoading)
			return;
		
   
        var CanAfford = false;
		//Check if we have selected character
		if (CharacterTools.Character.guid == null)
		{
		
			Swal.fire({
						icon: 'error',
						title: 'Levelup',
						text: lang("no_select", "levelup"),
					})
			
	
			return;
		}
			
		
		else
		{
			
				CanAfford = true;
		}

		if (CanAfford)
		{
			$.ajax({
			  	beforeSend: function(xhr)
				{
					CharacterTools.IsLoading = true;
					$(button).parents(".select_tool").addClass("active_tool");
			$(button).html(lang("p_w", "levelup"));
			  	}
			});
			
            
          //  UI.alert(lang("p_w", "levelup")) ;
			
			Swal.fire({
						icon: 'error',
						title: 'Levelup',
						text: lang("p_w", "levelup"),
					})
			
			
			// Execute the service
			$.post(Config.URL + "levelup/submit", 
			{
				
				guid: CharacterTools.Character.guid, 
				realm: CharacterTools.Character.realm, 
				csrf_token_name: Config.CSRF
			},
			function(data)
			{
				if (data == 1)
				{
					
					Swal.fire({
						icon: 'seccess',
						title: 'Levelup',
						text: lang("seccess", "levelup"),
					})
					
	        // UI.alert(lang("seccess", "levelup")) ;

             window.location = "/levelup";	
				}
				else
				{
					Swal.fire({
						icon: 'error',
						title: 'Levelup',
						text: data,
					})
					
					
				}
				
				CharacterTools.IsLoading = false;
				$(".active_tool").find('.nice_button').html(lang("levelup1", "levelup"));
				$(".active_tool").removeClass("active_tool");
			});
		}
	}
}
