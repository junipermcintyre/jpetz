{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - Quests{/block}
{block name=body}
    <div class="container">
        <h1 class="display-3">Quest Board</h1>
        <p><em>Not all those who wander are lost</em></p>
    </div>
    <div class="container">
        <h2>Available quests</h1>
        {assign var="count" value=0}
        {if empty($available)}
            <p>No quests available!</p>
        {else}
            {foreach from=$available item=q}
            	{if empty($pets["{$q.id}"])}
            		<!-- Gross if condition -->
            	{else}
            		{if $count == 3}
                    	<div id="hidden-available" style="display: none;">
    	            {/if}
    	            <hr>
    	            <div class="quest-available row clearfix">
    	            	<div class="col-md-3 col-sm-4">
    		                <div class="card quest-card doable">
    		                    <img class="card-img-top" src="/images/quest.png" alt="quest available">
    		                    <div class="card-block">
    		                    <button type="button" class="btn btn-sm btn-block btn-primary" data-toggle="modal" data-target="#view-available-{$q.id}">Requirements</button>
    		                    </div>
    		                </div>
    	                </div>
    	                <div class="col-md-9 col-sm-8">
    		                <h3>{$q.title}</h3>
    		                <p><em>{$q.description}</em></p>
    		                <blockquote class="blockquote">
    		                    <p class="mb-0">Length: <u>{$q.length} day(s)</u><br>Reward: <u>{$q.reward} SP</u></p>
    		                </blockquote>
    	                </div>
    	            </div>

    	            <div class="modal fade" id="view-available-{$q.id}" tabindex="-1" role="dialog" aria-hidden="true">
    	                <div class="modal-dialog" role="document">
    	                    <div class="modal-content">
    	                        <div class="modal-header">
    	                            <h5 class="modal-title">{$q.title} - {$q.length} day(s)</h5>
    	                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    	                                <span aria-hidden="true">&times;</span>
    	                            </button>
    	                        </div>
    	                        <div class="modal-body">
    	                            {$q.description}
    	                            <hr>
    	                            <h4>Requirements</h4>
    	                            <ul>
    	                            {if $q.reqatt != "none"}
    	                                <li><img src="/images/att.gif"> {$q.reqatt|string_format:"%d"}</li>
    	                            {/if}
    	                            {if $q.reqdef != "none"}
    	                                <li><img src="/images/def.gif"> {$q.reqdef|string_format:"%d"}</li>
    	                            {/if}
    	                            {if $q.type != "none"}
    	                                <li><strong>Type:</strong> {$q.type}</li>
    	                            {/if}
    	                            {if $q.species != "none"}
    	                                <li><strong>Species:</strong> {$q.species}</li>
    	                            {/if}
    	                            </ul>
    	                            <hr>
    	                            <h4>Rewards</h4>
    	                            {$q.reward} SP
    	                            <hr>
    	                            <h4>Send available J-Pet?</h4>
    	                            <select class="custom-select" id="questpicker-{$q.id}">
    	                            {foreach from=$pets["{$q.id}"] item=p}
    	                                <option pet="{$p.id}" value="pet-{$q.id}-{$p.id}" data-toggle="tooltip" data-placement="right" title="HP: {$p.hp} / {$p.maxhp}">{$p.name}</option>
    	                            {/foreach}
    	                            </select>
    	                        </div>
    	                        <div class="modal-footer">
    	                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    	                            <button type="button" id="{$q.id}" class="btn btn-primary send-pet" data-dismiss="modal">Send J-Pet</button>
    	                        </div>
    	                    </div>
    	                </div>
    	            </div>
    	            {assign var="count" value=$count+1}
            	{/if}
            {/foreach}
        {/if}
        {if $count > 3}
            </div>
            <br>
            <button type="button" id="show-available" class="btn btn-info btn-lg btn-block">Show all</button>
        {/if}
    </div>
    <div class="main-container container">
        <h2>In progress quests</h1>
        {assign var="count" value=0}
        {if empty($progress)}
            <p>No quests in progress!</p>
        {else}
            {foreach from=$progress item=q}
                {if $count == 3}
                    <div id="hidden-progress" style="display: none;">
                {/if}
                <hr>
                <div class="quest-progress row clearfix">
                	<div class="col-md-2 col-sm-4">
    	                <div class="card quest-card onquest">
    	                    <img class="card-img-top" src="/images/onquest.png" alt="quest progress">
    	                    <div class="card-block">
    	                    <button type="button" class="btn btn-sm btn-block btn-primary" data-toggle="modal" data-target="#view-progress-{$q.id}">Status</button>
    	                    </div>
    	                </div>
                    </div>
                    <div class="col-md-2 col-sm-4">
    	                <div class="card quest-card">
    	                    <img class="card-img-top rarity-{$q.rarity}" src="/images/pets/{$q.img}" alt="quest progress pet">
    	                    <div class="card-block">
    	                    	<h4 class="card-title stats">{$q.pet}</h4>
    	                    	<p class="card-text"><img src="/images/hp.gif"> {$q.hp} / {$q.maxhp}</p>
    	                    </div>
    	                </div>
                    </div>
                    <div class="col-md-8 col-sm-4">
    	                <h3>{$q.title}</h3>
    	                <p><em>{$q.description}</em></p>
    	                <blockquote class="blockquote">
    	                    <p class="mb-0">Length: <u>{$q.length} day(s)</u><br>Reward: <u>{$q.reward}SP</u></p>
    	                </blockquote>
                    </div>
                </div>

                <div class="modal fade" id="view-progress-{$q.id}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{$q.title} - {$q.progress}/{$q.length} day(s) complete</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {$q.description}
                                <hr>
                                <h4>Requirements</h4>
                                <ul>
                                {if $q.reqatt != "none"}
                                    <li><img src="/images/att.gif"> {$q.reqatt|string_format:"%d"}</li>
                                {/if}
                                {if $q.reqdef != "none"}
                                    <li><img src="/images/def.gif"> {$q.reqdef|string_format:"%d"}</li>
                                {/if}
                                {if $q.type != "none"}
                                    <li><strong>Type:</strong> {$q.type}</li>
                                {/if}
                                {if $q.species != "none"}
                                    <li><strong>Species:</strong> {$q.species}</li>
                                {/if}
                                </ul>
                                <hr>
                                <h4>Rewards</h4>
                                {$q.reward} SP
                                <hr>
                                <p>This quest was accepted by <strong><em>{$q.pet}</em></strong></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            {assign var="count" value=$count+1}
            {/foreach}
        {/if}
        {if $count > 3}
            </div>
            <br>
            <button type="button" id="show-progress" class="btn btn-info btn-lg btn-block">Show all</button>
        {/if}
    </div>

    <script src="/js/quests.js"></script>
{/block}
