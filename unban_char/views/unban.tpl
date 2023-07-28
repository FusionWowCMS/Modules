<script type="text/javascript">
	$(document).ready(function()
	{
		function initializeUnban()
		{
			if(typeof Unban != "undefined")
			{
				Unban.User.initialize({$dp});
			}
			else
			{
				setTimeout(initializeUnban, 50);
			}
		}

		initializeUnban();
	});
</script>
     <center>
          <div style="padding: 8px 35px 8px 14px; margin-bottom: 20px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5); background-color: #131b10; border: 1px solid #FF006A; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
               <div style="text-align: right;"><span style="color: blue;"><span style="font-family: tahoma,geneva,sans-serif;"><span style="font-size: 12px;"></span></span></span>
			   </div>
               <div style="text-align: right;"></div>
               <div style="text-align: left;font-size: 14px"> 
	                       {lang("Server_fee", "unban_char")} 
	
                           {if $config->item('Server_fee') > 0} 
	                             <img src="{$url}application/images/icons/coins_add.png" align="absmiddle" height="12" width="12"/>
	                             {lang("dp", "unban_char")}  {$config->item('Server_fee')} 
	                  
                            {else}
	                             {lang("free", "unban_char")}
                            {/if}  
              </div>
		</div>
    </center>      
<section id="character_tools">
	<section id="select_character">
		<div class="online_realm_button"></div>
		
		{if $total}
			{foreach from=$characters item=realm}
				{foreach from=$realm.characters item=character}
               
                
					<div class="select_character">
						<div class="character store_item">
							<section class="character_buttons">
								<a href="javascript:void(0)" class="nice_button" onClick="Unban.selectCharacter(this, {$realm.realmId}, {$character.guid}, '{$character.name}')">
								 {lang("Select", "unban_char")}
								</a>
							</section>
			
							<img class="item_icon" width="45" height="45" src="{$url}application/images/avatars/{$character.avatar}.gif" align="absmiddle" data-tip="<img src='{$url}application/images/stats/{$character.class}.gif' align='absbottom'/> {$character.name} (Lv{$character.level})">
			
							<a class="character_name" data-tip="<img src='{$url}application/images/stats/{$character.class}.gif' align='absbottom'/> {$character.name} (Lv{$character.level})">{$character.name}</a>
							
                            
                           <div style="margin-top: -22px;margin-left:60px">   {lang("Level", "unban_char")} {$character.level}</div>   
							<div class="clear"></div>
						</div>
					</div>    
				{/foreach}
			{/foreach}
		{else}
			<center style="padding-top:10px;"><b>{lang("no_chars", "unban_char")}</b></center>
		{/if}
	</section>
    
	<section id="select_tool">
    	<div class="online_realm_button"></div>
        
        <div class="select_tools">
        
            <!-- Character unban_char -->
            <div class="select_tool">
                <div class="tool store_item">
                    <section class="tool_buttons">
                        <a href="javascript:void(0)" class="nice_button" onClick="Unban.UNban(this)">
                             {lang("UnBan", "unban_char")}
                        </a>
                    </section>
    
                    <div class="clear"></div>
                </div>
            </div>
        </div>
		<div class="clear"></div>
	</section>

	<div class="clear"></div>
</section>