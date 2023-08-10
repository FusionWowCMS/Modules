<script type="text/javascript">
    $(document).ready(function () {
        function initializeCharacterTools() {
            if (typeof CharacterTools != "undefined") {
                CharacterTools.User.initialize({$dp});
            } else {
                setTimeout(initializeCharacterTools, 50);
            }
        }

        initializeCharacterTools();
    });
</script>


<div class="container">
    <div class="row">

        {$link_active = "character_tools"}
        {include file="../../ucp/views/ucp_navigation.tpl"}

        <div class="col-lg-8 py-lg-5 pb-5 pb-lg-0">
            <div class="section-header">{lang("select_char", "character_tools")}</div>
            <div class="section-body">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        {if $total}
                            {foreach from=$characters item=realm}
                                <table class="table table-striped table-hover table-responsive character-select">
                                    <thead>
                                    <tr>
                                        <th scope="col" colspan="3" class="h4">{$realm.realmName}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {if $realm.characters}
                                        {foreach from=$realm.characters item=character}
                                            <tr class="character-select">
                                                <td style="width:40px;"><img width="36" height="36"
                                                                             src="{$url}application/images/avatars/{$character.avatar}.gif"
                                                                             data-tip="<img src='{$url}application/images/stats/{$character.class}.gif' align='absbottom'/> {$character.name} (Lv{$character.level})">
                                                </td>
                                                <td>
                                                    <div class="d-block"
                                                         data-tip="<img src='{$url}application/images/stats/{$character.class}.gif' align='absbottom'/> {$character.name} (Lv{$character.level})">{$character.name}</div>
                                                    <div class="user-points d-block">
															<span class="gold-points">
																<i class="fa-solid fa-coins"></i>
																{floor($character.money / 10000)} {lang("gold", "character_tools")}
															</span>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <a href="javascript:void(0);"
                                                       onClick="CharacterTools.selectCharacter(this, {$realm.realmId}, {$character.guid}, '{$character.name}')">
                                                        {lang("select", "character_tools")}
                                                    </a>
                                                </td>
                                            </tr>
                                        {/foreach}
                                    {else}
                                        <tr>
                                            <td colspan="3"
                                                class="text-center py-4">{lang("no_chars", "character_tools")}</td>
                                        </tr>
                                    {/if}
                                    </tbody>
                                </table>
                            {/foreach}
                        {/if}
                    </div>
                    <div class="col-sm-12 col-lg-6 location-col">
                        <section id="select_tool">
                            <div class="online_realm_button">Select service</div>

                            <div class="select_tools">

                                <!-- Character Rename -->
                                <div class="select_tool">
                                    <div class="tool store_item">
                                        <section class="tool_buttons">
                                            <a href="javascript:void(0)" class="nice_button"
                                               onClick="CharacterTools.buy(this, 1, {$config->item('name_change_price')})">
                                                Purchase
                                            </a>
                                        </section>

                                        <img class="item_icon" data-tip="Change your characters’ names"
                                             src="{$url}application/modules/character_tools/css/images/inv_misc_note_06.jpg"
                                             width="36" height="36" src="" align="absmiddle">

                                        <a class="tool_name" data-tip="Change your characters’ names">Name Change</a>
                                        <br/>
                                        {if $config->item('name_change_price') > 0}Cost: {$config->item('name_change_price')} Donation Points{else}Free of charge{/if}
                                        <div class="clear"></div>
                                    </div>
                                </div>

                                <!-- Race Change -->
                                <div class="select_tool">
                                    <div class="tool store_item">
                                        <section class="tool_buttons">
                                            <a href="javascript:void(0)" class="nice_button"
                                               onClick="CharacterTools.buy(this, 2, {$config->item('race_change_price')})">
                                                Purchase
                                            </a>
                                        </section>

                                        <img class="item_icon"
                                             data-tip="Change a character’s race (within your current faction)"
                                             src="{$url}application/modules/character_tools/css/images/race_change.jpg"
                                             width="36" height="36" src="" align="absmiddle">

                                        <a class="tool_name"
                                           data-tip="Change a character’s race (within your current faction)">Race
                                            Change</a>
                                        <br/>
                                        {if $config->item('race_change_price') > 0}Cost: {$config->item('race_change_price')} Donation Points{else}Free of charge{/if}
                                        <div class="clear"></div>
                                    </div>
                                </div>

                                <!-- Faction Change -->
                                <div class="select_tool">
                                    <div class="tool store_item">
                                        <section class="tool_buttons">
                                            <a href="javascript:void(0)" class="nice_button"
                                               onClick="CharacterTools.buy(this, 3, {$config->item('faction_change_price')})">
                                                Purchase
                                            </a>
                                        </section>

                                        <img class="item_icon"
                                             data-tip="Change a character’s faction (Horde to Alliance or Alliance to Horde)"
                                             src="{$url}application/modules/character_tools/css/images/faction_change.jpg"
                                             width="36" height="36" src="" align="absmiddle">

                                        <a class="tool_name"
                                           data-tip="Change a character’s faction (Horde to Alliance or Alliance to Horde)">Faction
                                            Change</a>
                                        <br/>
                                        {if $config->item('faction_change_price') > 0}Cost: {$config->item('faction_change_price')} Donation Points{else}Free of charge{/if}
                                        <div class="clear"></div>
                                    </div>
                                </div>

                                <!-- Appearance Change -->
                                <div class="select_tool">
                                    <div class="tool store_item">
                                        <section class="tool_buttons">
                                            <a href="javascript:void(0)" class="nice_button"
                                               onClick="CharacterTools.buy(this, 4, {$config->item('appearance_change_price')})">
                                                Purchase
                                            </a>
                                        </section>

                                        <img class="item_icon"
                                             data-tip="Change your characters’ appearance (optional name change included)"
                                             src="{$url}application/modules/character_tools/css/images/achievement_character_human_female.jpg"
                                             width="36" height="36" src="" align="absmiddle">

                                        <a class="tool_name"
                                           data-tip="Change your characters’ appearance (optional name change included)">Appearance
                                            Change</a>
                                        <br/>
                                        {if $config->item('appearance_change_price') > 0}Cost: {$config->item('appearance_change_price')} Donation Points{else}Free of charge{/if}
                                        <div class="clear"></div>
                                    </div>
                                </div>

                                <!-- Revive -->
                                <div class="select_tool">
                                    <div class="tool store_item">
                                        <section class="tool_buttons">
                                            <a href="javascript:void(0)" class="nice_button"
                                               onClick="CharacterTools.buy(this, 5, {$config->item('revive_change_price')})">
                                                Purchase
                                            </a>
                                        </section>

                                        <img class="item_icon" data-tip="Brings a dead player back to life"
                                             src="{$url}application/modules/character_tools/css/images/spell_holy_resurrection.jpg"
                                             width="36" height="36" src="" align="absmiddle">

                                        <a class="tool_name" data-tip="Brings a dead player back to life">Revive</a>
                                        <br/>
                                        {if $config->item('revive_change_price') > 0}Cost: {$config->item('revive_change_price')} Donation Points{else}Free of charge{/if}
                                        <div class="clear"></div>
                                    </div>
                                </div>

                                <!-- Level Up -->
                                <div class="select_tool">
                                    <div class="tool store_item">
                                        <section class="tool_buttons">
                                            <a href="javascript:void(0)" class="nice_button"
                                               onClick="CharacterTools.buy(this, 6, {$config->item('levelup_change_price')})">
                                                Purchase
                                            </a>
                                        </section>

                                        <img class="item_icon" data-tip="Level Up character"
                                             src="{$url}application/modules/character_tools/css/images/spell_holy_innerfire.jpg"
                                             width="36" height="36" src="" align="absmiddle">

                                        <a class="tool_name" data-tip="Level Up character">Level Up</a>
                                        <br/>
                                        {if $config->item('levelup_change_price') > 0}Cost: {$config->item('levelup_change_price')} Donation Points{else}Free of charge{/if}
                                        <div class="clear"></div>
                                    </div>
                                </div>

                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>

                            <div class="ucp_divider"></div>

                        </section>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>