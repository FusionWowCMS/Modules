<section id="character_tools">
	<section id="select_character">
		{foreach from=$realms item=realm}
		 
		<div style="text-align: center;padding: 8px 35px 8px 14px;margin-bottom: 20px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);background-color: #120e12;border: 1px solid #262425;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 44px;">
			
			Charactar Select
		</div>
		
		
		
			{if $this->data_model->GetCountAccount($realm->getId())}
		    	<center>
                     <div style="padding: 8px 35px 8px 14px; margin-bottom: 20px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5); background-color: #593C58; border: 1px solid #FF006A; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
                           <div style="text-align: right;"><span style="color: blue;"><span style="font-family: tahoma,geneva,sans-serif;"><span style="font-size: 12px;"></span></span></span></div>
                                <div style="text-align: right;"></div>
	
                                    <div>  {lang("Free", "goldtodp")}  
										
	                                     {if $Server_fee != 0}  
	                                        	<img src="{$url}application/images/icons/coins_add.png" align="absmiddle" height="12" width="12"/>	
		                                           {lang("DP", "goldtodp")} {$Server_fee}  
                                         {else}	
	                                 	            Free  
                                         {/if}  
		                                   /  {lang("realm_name", "goldtodp")} {$realm->getName()}
	                                   </div>
					</div>			
                </center>

                         {foreach from=$this->data_model->GetAccChar($realm->getId()) item=character}
            
				  <div class="select_character" >
					 <div class="character store_item">
					
					
					
						<section class="character_buttons">
                              {if   $this->realms->getRealm($realm->getId())->getCharacters()->isOnline($character.guid)}  
                       
                                     <a href="javascript:void(0)" class="nice_button"> {lang("ShortCharacterOnline", "goldtodp")} </a>

                         
                        
                               {else}
							
								     <a href="javascript:void(0)" class="nice_button" onClick="CharacterClass.selectCharacter(this, {$realm->getId()},'{$character.guid}','{$character.name}','{$this->data_model->Calculate_money({$this->data_model->getmoneychar($realm->getId(),{$character.guid})})}')">
								     
                  	                 Select	 	 
							
							        </a>
                              {/if}
					             
					   	</section>	
	
								  <img class="item_icon" width="45" height="45" src="{$url}application/images/avatars/{$this->data_model->Getavator($character)}.gif" data-tip=" Go Profile <br/><img src='{$url}application/images/stats/{$character.class}.gif' align='absbottom'/> {$character.name}(Lv{$character.level})  " align="absmiddle">
              
                                    <a class="character_name"  href="{$url}character/{$realm->getId()}/{$character.guid}"   data-tip=" Go Profile <br/><img src='{$url}application/images/stats/{$character.class}.gif' align='absbottom'/> {$character.name}(Lv{$character.level}) ">{$character.name}</a> 

						<div class="clear"></div>
					
						
					</div>
				</div>
                
					{/foreach}
			{else}
				<center style="padding-top:10px;"><b>{lang("no_chars", "goldtodp")}</b></center>
			{/if}
		{/foreach}
	
	
 <section id="select_tool">
 
	 	<div style="text-align: center;padding: 8px 35px 8px 14px;margin-bottom: 20px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);background-color: #120e12;border: 1px solid #262425;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 44px;">
			
			{lang("GoldBar", "goldtodp")} & {lang("DP", "goldtodp")}
		</div>
        
   <div class="select_tools">
        <div class="select_tool">
             <div class="tool store_item">
				 
                 <tr>
                      <td> {lang("GoldBar", "goldtodp")} </td>
					 
                      <td>
                           <input type="range" min="0" max="0"   class="slider" id="goldbar" />

			                <div id="demo"> 
			  
			                 <img src="{$url}application/modules/goldtodp/img/money-gold.gif" align="absmiddle" height="12" width="12"/>
								
			                </div> 
                     </td>
	   
                     <div id="cost2">
			          <img src="{$url}application/images/icons/coins_add.png" align="absmiddle" height="12" width="12"/> 
						 {lang("DP", "goldtodp")} 0 
	                 </div>  

                  </tr>

                    <div class="clear"></div>
             </div>
	   </div>
 </div>

  </section>
    
	<section id="select_tool">
		
		<div class="select_tools">
			<center>
				<div class="select_tool">
					<div class="tool store_item">
						<tr>
							<section class="tool_buttons">
                            <center>
                                
								<a href="javascript:void(0)" class="nice_button" onClick="CharacterClass.conversion(this)"> {lang("convert", "goldtodp")}</a>
                                </center>
							</section>
						</tr>
						<br />
						<div class="clear"></div>
					</div>
				</div>
			</center>
		</div>
		<div class="clear"></div>
	
	</section>
	<div class="clear"></div>
</section>
	
	
  <script>

       var slider = document.getElementById("goldbar");
       var cost2 = document.getElementById("cost2");
       var output = document.getElementById("demo");
       output.innerHTML = slider.value; 
    
         slider.oninput = function() 
		   {
                  output.innerHTML = '<img src="' + Config.URL + 'application/modules/goldtodp/img/money-gold.gif" align="absmiddle" />' + this.value;
    
                   cost2.innerHTML = '<img src="' + Config.URL + 'application/images/icons/coins_add.png" align="absmiddle" />' + ' {lang("DP", "goldtodp")} '+  (this.value / {$config->item('calc')}) ;
          }
</script>
