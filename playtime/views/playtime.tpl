<script type="text/javascript">
    $(document).ready(function () {
        function initializePlaytime() {
            if (typeof Playtime != "undefined") {
                Playtime.User.initialize({$dp});
            } else {
                setTimeout(initializeCharacterTools, 50);
            }
        }

        initializePlaytime();
    });
</script>
<center>
    <div style="padding: 8px 35px 8px 14px;margin-bottom: 20px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);background-color: #171624;border: 1px solid #00ff05;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;">
        <div style="text-align: left;">
		   <span style="color: green;">
			   <span style="font-family: tahoma,geneva,sans-serif;">
				   <span style="font-size: 12px;">
				   {lang("Attention", "playtime")}
				   </span>
			   </span>
		   </span>


        </div>
        <div style="text-align: left;"></div>
        <div style="text-align: left;">
            {lang("Playtime", "playtime")} {$config->item('convert_min_time')/3600} {lang("msg1", "playtime")}
            <br/>
            <a href="{$url}item/1/{$config->item('itemid')}" data-realm="1" rel="item={$config->item('itemid')}"
               class="item_name q{$QualityID}">
                <img class="item_icon" src="https://icons.wowdb.com/retail/small/{$IconName}.jpg" align="absmiddle"
                     rel="item={$config->item('itemid')}">
                {$Name}
            </a>

            {if $config->item('item_count')}
                × {$config->item('item_count')} {lang("msg2", "playtime")}
            {else} × 1
            {/if}

            {if $config->item('reward_amount')}
                <br/>
                <span>
		   
		         <img src="{$url}application/images/icons/coins_add.png" align="absmiddle" height="12" width="12"/> 
				{lang("DP", "playtime")}
                    {$config->item('reward_amount')}
	       </span>
            {/if}
        </div>

    </div>
</center>
<section id="character_tools">
    <section id="select_character">


        {if $total}
            {foreach from=$characters item=realm}
                <center>
                    <div style="padding: 8px 35px 8px 14px;margin-bottom: 20px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);background-color: #172217;border: 1px solid #151b14;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;">
                        <div style="text-align: left;">
			   <span style="color: black;">
				   <span style="font-family: tahoma,geneva,sans-serif;">
					   <span style="font-size: 12px;">{lang("realm_name", "playtime")} {$realm.realmName}</span>
				   </span>
			   </span>
                        </div>
                        <div style="text-align: left;"></div>
                        <div style="text-align: left;"> {lang("msg3", "playtime")}</div>
                    </div>
                </center>
                {foreach from=$realm.characters item=character}
                    {if $character.online==0}
                        <div class="select_character">
                            <div class="character store_item">
                                <section class="character_buttons">
                                    <a href="javascript:void(0)" class="nice_button"
                                       onClick="Playtime.selectCharacter(this, {$realm.realmId}, {$character.guid}, '{$character.name}')">
                                        {lang("select", "playtime")}
                                    </a>
                                </section>

                                <img class="item_icon" width="36" height="36"
                                     src="{$url}application/images/avatars/{$character.avatar}.gif" align="absmiddle"
                                     data-tip="<img src='{$url}application/images/stats/{$character.class}.gif' align='absbottom'/> {$character.name} (Lv{$character.level})">

                                <a class="character_name"
                                   data-tip="<img src='{$url}application/images/stats/{$character.class}.gif' align='absbottom'/> {$character.name} (Lv{$character.level})">
                                    {$character.name}
                                </a>

                                <div style="font-size: 9px;color: #35cd3e;margin-top: -13px;padding-left: 52px;">
                                    {lang("Playtime", "playtime")}
                                    ({$this->Gototaltime({$realm.realmId}, {$character.guid})})
                                </div>


                                <div class="clear"></div>
                            </div>
                        </div>
                    {/if}
                {/foreach}
            {/foreach}
        {else}
            <center style="padding-top:10px;"><b>{lang("no_chars", "playtime")} </b></center>
        {/if}
    </section>

    <section id="select_tool">


        <div class="select_tools">

            <!-- Convert Times Character  -->
            <div class="select_tool">
                <div class="tool store_item">
                    <div style="color: white;margin-top: -1px; float: left">
                        {lang("Playtime", "playtime")} {$config->item('convert_min_time')/3600} {lang("msg4", "playtime")}
                    </div>
                    <br/>
                    <a style="float: left" href="{$url}item/1/{$config->item('itemid')}" data-realm="1"
                       rel="item={$config->item('itemid')}" class="item_name q{$QualityID}">
                        <img src="https://icons.wowdb.com/retail/small/{$IconName}.jpg" align="absmiddle"
                             rel="item={$config->item('itemid')}"> {$Name}

                    </a>
                    <div style="font-size: 13px;color: white;margin-top: 3px;">
                        {if $config->item('item_count')}

                            × {$config->item('item_count')}

                        {else}
                            × 1
                        {/if}
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="tool store_item">

                    <section class="tool_buttons">
                        <a href="javascript:void(0)" class="nice_button" onClick="Playtime.Convert(this)">
                            {lang("Convert", "playtime")}
                        </a>
                    </section>


                    <div class="clear"></div>
                </div>
            </div>


        </div>
        <div class="clear"></div>

        <div class="ucp_divider"></div>

    </section>

    <div class="clear"></div>
</section>