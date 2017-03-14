{extends file="../templates/raidparent.tpl"}
{block name=title}Jerad McIntyre - {$name}'s J-Petz{/block}
{block name=body}
    <div class="raid-container container">
        <h1 class="display-3">Your Raid Defence</h1>
        <p><em>A good defence is the best offence. Except in this case.</em></p>
    </div>
    <div class="raid-container container">
    	<h2>Defending J-Petz</h2>
    	{if empty($pets)}
    		<p>No J-Petz are defending!</p>
    	{else}
    		<div class="row clearfix">
    			{assign var=pCount value=1}
		        {foreach from=$pets item=p}
	                <div class="card pet-item col-lg-2 col-md-2 col-sm-3 col-xs-4">
	                    <img class="card-img-top img-responsive rarity-{$p.rarity}" src="/images/pets/{$p.img}" alt="Pet image" />
	                    <div class="card-block">
	                        <h6 class="text-muted stats">{$p.name}</h6>
	                        <p class="stats"><img class="tiny-icon" src="/images/def.gif"><small> {$p.def} <img class="tiny-icon" src="/images/hp.gif"> {$p.hp}/{$p.maxhp}</small></p>
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
    </div>
{/block}
