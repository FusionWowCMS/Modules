var CharacterClass = {

             MODULE_NAME: 'goldtodp',
         LANG_SELECT_CHAR: 'Gold conversion below 1000 is not allowed',
              LANG_SELECT_NoT_NUmbber: 'Please use the allowed number',
             
             
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


selectCharacter: function(button, realm, guid, name,goldchar)
	{
		var CharSection = $("#select_character");
        var slider = document.getElementById("goldbar");
		
		CharacterClass.Character.initialize(name, guid, realm);
		
		$(".item_group", CharSection).each(function()
		{
            slider.min=0 ;
            slider.max = 0 ;
         ///  slider.value = 0;
            
			$(this).removeClass("item_group").addClass("select_character");
			$(this).find(".nice_active").removeClass("nice_active").html('select');
		});
		
		$(button).parents(".select_character").removeClass("select_character").addClass("item_group");
		$(button).addClass("nice_active").html('selected');
        
        if(goldchar => 1000)
            {
                slider.min=1000 ;
            }
        else
            {
              slider.max = 0 ; 
            }
        
        slider.max = goldchar ;
     //   slider.value = 0;
        
        slider.step='1000';
        
        
        
	},
	
	

	IsLoading: false,
    
	  conversion:function(button)

       {
        
        var CanAfford = false;
        var GoldBarCharacter = document.getElementById("goldbar").value;

		if (CharacterClass.Character.guid == null && CharacterClass.Character.realm == null )
		{
			
			Swal.fire({
						icon:  'error',
						title: 'Gold to DP',
						text:  'Please select a Character',
					})
			
			return;
		}
        
        if ( GoldBarCharacter <= 1000   )
		{
			UI.alert(CharacterClass.LANG_SELECT_CHAR);
			
			return;
		}
        else if (isNaN(GoldBarCharacter))
	
		{
		
			Swal.fire({
						icon:  'error',
						title: 'Gold to DP',
						text:  CharacterClass.LANG_SELECT_NoT_NUmbber,
					})
			
			return;
		}
		else
		{
			CanAfford=true;

		}
       
       if (CanAfford)
		{ 
        
        $.ajax({
			  	beforeSend: function(xhr)
				{
					CharacterClass.IsLoading = true;
					$(button).parents(".select_tool").addClass("active_tool");
				   
			  	}
			});
             
             
             	$.post(Config.URL + CharacterClass.MODULE_NAME +"/process", 
			{
			
				guid: CharacterClass.Character.guid, 
				realm: CharacterClass.Character.realm,
                cost:GoldBarCharacter,
				csrf_token_name: Config.CSRF
			},
			function(data)
			{
                       
			if (data == 1)
			 
             {
				 	Swal.fire({
						icon:  'seccess',
						title: 'Gold to DP',
						text:  'The conversion operation was successful',
					})
                      
				        setTimeout ( "redirect('goldtodp')", 1000 );	
                
             }
             
         
				else
               {
                 Swal.fire({
						icon:  'error',
						title: 'Gold to DP',
						text:  data,
					})
                 
                 CharacterClass.IsLoading = false;
                 $(button).parents(".select_tool").addClass("active_tool");
		         $(button).html('Gold conversion');
                 
               }	
       });
       
     }     
    
     
}


}

function redirect(url) 
{
	
	window.location=url; 
}