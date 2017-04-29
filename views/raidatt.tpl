{extends file="../templates/raidparent.tpl"}
{block name=title}Green Gaming - {$name}'s Raid Defence{/block}
{block name=body}
    <div class="container">
        <h1 class="display-3">{$name}'s Raid Defence</h1>
        <p><em>As you approach the castle gate, your eyes wander up to the battlements...</em></p>
    </div>
    <div class="container">
    	<h2>Defending J-Petz</h2>
    	{if empty($dPets)}
    		<p>No J-Petz are defending! You wouldn't hit an unarmed opponent, would you?</p>
    	{else}
    		<div class="row clearfix">
    			{assign var=pCount value=1}
		        {foreach from=$dPets item=p}
	                <div class="card pet-item col-lg-2 col-md-2 col-sm-3 col-xs-4">
	                    <img class="card-img-top img-responsive rarity-{$p.rarity} dPet" src="/images/pets/{$p.img}" alt="Pet image" />
	                    <div class="card-block">
	                        <h6 class="text-muted stats">{$p.name}</h6>
	                        <p class="stats"><img class="tiny-icon" src="/images/def.gif"><small> {$p.def|string_format:'%d'} <img class="tiny-icon" src="/images/hp.gif"> {$p.hp|string_format:'%d'}/{$p.maxhp|string_format:'%d'}</small></p>
	                    </div>
	                </div>
	                {if $pCount % 6 == 0}
	                	<div class="clearfix hidden-sm-down"></div>
	                {/if}

	                {if $pCount % 4 == 0}
	                	<div class="clearfix hidden-md-up hidden-xs-down"></div>
	                {/if}

	                {if $pCount % 3 == 0}
	                	<div class="clearfix hidden-sm-up"></div>
	                {/if}
	                {assign var=pCount value=$pCount+1}
		        {/foreach}
	        </div>
	    {/if}
	    <p class="stats">Total <img src="/images/def.gif" class="dPet">: {$def|string_format:'%d'}</p>
    </div>
    <div class="raid-container container">
    	<h2>Attacking J-Petz</h2>
    	{if $rFlag}
    		<div style="display: none" id="defender">{$defender}</div>
	    	{if empty($aPets)}
	    		<p>You and what army?</p>
	    		<button type="button" class="btn btn-danger btn-lg btn-block" disabled>CHAAAAAAAAAAAARGE!</button>
	    	{else}
	    		<div class="row clearfix">
	    			{assign var=pCount value=1}
			        {foreach from=$aPets item=p}
		                <div class="card pet-item col-lg-2 col-md-2 col-sm-3 col-xs-4">
		                    <img class="card-img-top img-responsive rarity-{$p.rarity} aPet" src="/images/pets/{$p.img}" alt="Pet image" />
		                    <div class="card-block">
		                        <h6 class="text-muted stats">{$p.name}</h6>
	                        	<p class="stats"><img class="tiny-icon" src="/images/att.gif"><small> {$p.att|string_format:'%d'} <img class="tiny-icon" src="/images/hp.gif"> {$p.hp|string_format:'%d'}/{$p.maxhp|string_format:'%d'}</small></p>
		                    </div>
		                </div>
		                {if $pCount % 6 == 0}
		                	<div class="clearfix hidden-sm-down"></div>
		                {/if}

		                {if $pCount % 4 == 0}
		                	<div class="clearfix hidden-md-up hidden-xs-down"></div>
		                {/if}

		                {if $pCount % 3 == 0}
		                	<div class="clearfix hidden-sm-up"></div>
		                {/if}
		                {assign var=pCount value=$pCount+1}
			        {/foreach}
		        </div>
		        <p class="stats">Total <img src="/images/att.gif">: {$att|string_format:'%d'}</p>
		        <div id="action">
		        	<button type="button" id="attack" data-toggle="tooltip" data-html="true" data-placement="bottom" title="{$chance|string_format:'%d'}% chance of success" class="btn btn-danger btn-lg btn-block">CHAAAAAAAAAAAARGE!</button>
		        </div>
		    {/if}
		{else}
			<p>You're too powerful to raid this player!</p>
		{/if}
    </div>
    <script src="/js/raid.js"></script>
{/block}
