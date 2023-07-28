var Playtime = {


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



		initialize: function(name, guid, realm){

			this.name = name;

			this.guid = guid;

			this.realm = realm;

		}

	},

	selectCharacter: function(button, realm, guid, name){

		var CharSection = $("#select_character");

		Playtime.Character.initialize(name, guid, realm);

		$(".item_group", CharSection).each(function(){

			$(this).removeClass("item_group").addClass("select_character");

			$(this).find(".nice_active").removeClass("nice_active").html("Selected");

		});

		
		$(button).parents(".select_character").removeClass("select_character").addClass("item_group");

		$(button).addClass("nice_active").html("Selected");

	},


	IsLoading: false,

	Convert: function(button)

	{
         var CanAfford = false;
	
		if (Playtime.IsLoading)

			return;

		if (Playtime.Character.guid == null){
			
					Swal.fire({
						icon:  'error',
						title: 'Playtime',
						text:  'Please select a character first',
					});

			return;

		}


		CanAfford = true;

		if (CanAfford){

			$.ajax({

			  	beforeSend: function(xhr){

					Playtime.IsLoading = true;

					$(button).parents(".select_tool").addClass("active_tool");

					$(button).html('Please wait... ');



			  	}

			});

			$.post(Config.URL + "playtime/submit", {


				guid: Playtime.Character.guid, 

				realm: Playtime.Character.realm, 

				csrf_token_name: Config.CSRF

			},

			function(data){
				
                Playtime.IsLoading = false;
				
				if (data == 1){
					
					Swal.fire({
						icon:  'seccess',
						title: 'Playtime',
						text:  'Item sent successfully.',
					});
					

					 setTimeout ( "redirect('Playtime')", 1000 );	

				}

				else{

					Swal.fire({
						icon:  'error',
						title: 'Playtime',
						text:  data,
					});
					
					 setTimeout ( "redirect('Playtime')", 1000 );	

				}

			});

		}

	}

}

