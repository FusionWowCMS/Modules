var Unban = {

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
		
		Unban.Character.initialize(name, guid, realm);
		
		$(".item_group", CharSection).each(function()
		{
			$(this).removeClass("item_group").addClass("select_character");
			$(this).find(".nice_active").removeClass("nice_active").html("Select");
		});
		
		$(button).parents(".select_character").removeClass("select_character").addClass("item_group");
		$(button).addClass("nice_active").html("Selected");
	},
	
	IsLoading: false,
	
	
	
	UNban: function(button){
		//Check if we're already executing a command
		if (Unban.IsLoading)
			return;
		
      

		//Check if we have selected character
		if (Unban.Character.guid == null){
			Swal.fire({
						icon:  'error',
						title: 'Unban',
						text:  'Please select a character first.',
					})
			
			
			return;
		}
			
	
			$.ajax({
			  	beforeSend: function(xhr)
				{
					Unban.IsLoading = true;
					$(button).parents(".select_tool").addClass("active_tool");
					$(button).html('Please Wait ...');

			  	}
			});
			
			// Execute the service
			$.post(Config.URL + "unban_char/submit", 
			{
				 
				guid: Unban.Character.guid, 
				realm: Unban.Character.realm, 
				csrf_token_name: Config.CSRF
			},
			function(data)
			{
				Unban.IsLoading = false;
				
				if (data == 1)
				{
					
					
					Swal.fire({
						icon:  'seccess',
						title: 'Unban',
						text:  'Character ' + Unban.Character.name + 'UnBanned successfully.',
					})
					

                          setTimeout ( "redirect('unban_char')", 5000 );	
				}
				else
				{
				Swal.fire({
						icon: 'error',
						title: 'Unban',
						text: data,
					})
				}
				
				$(".active_tool").find('.nice_button').html("Unban");
				$(".active_tool").removeClass("active_tool");
			});
		
	}
}

function redirect(url) 
{
	
	window.location=url; 
}