{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - {$name}'s J-Petz{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">{$name}'s J-Petz</h1>
        <p><em>Behold! My collection...</em></p>
    </div>
    <div class="main-container container">
    	<h2>Available J-Petz {if $editable}<button type="button" id="feedAll" class="btn btn-danger btn-sm"><img src="/images/hunger.gif"> Feed all (<span id="hungerTtl">{$hunger * 2}</span> SP)</button>{/if}</h2>
    	{if empty($pets)}
    		<p>No J-Petz available!</p>
    	{else}
    		<div class="row clearfix">
    			{assign var=pCount value=1}
		        {foreach from=$pets item=p}
	                <div class="card pet-item col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                    <img class="card-img-top img-responsive rarity-{$p.rarity}" src="/images/pets/{$p.img}" alt="Pet image" />
	                    <div class="card-block">
	                        <h4 class="card-title stats">{$p.name}</h4>
	                        <h6 class="text-muted stats"><em>{$p.species} | {$p.type} collection</em></h6>
	                        <button data-toggle="modal" data-target="#pet-{$p.id}" class="btn btn-primary btn-lg btn-block">Stats</button>
	                    </div>
	                </div>
	                {if $pCount % 4 == 0}
	                	<div class="clearfix hidden-sm-down"></div>
	                {/if}

	                {if $pCount % 3 == 0}
	                	<div class="clearfix hidden-md-up hidden-xs-down"></div>
	                {/if}

	                {if $pCount % 2 == 0}
	                	<div class="clearfix hidden-sm-up"></div>
	                {/if}

	                {assign var=pCount value=$pCount+1}
		            <div class="modal fade" id="pet-{$p.id}" tabindex="-1" role="dialog" aria-labelledby="Pet Modal Window" aria-hidden="true">
		                <div class="modal-dialog" role="document">
		                    <div class="modal-content">
		                        <div class="modal-header">
		                            <h5 class="modal-title stats">{$p.name}</h5>
		                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                                <span aria-hidden="true">&times;</span>
		                            </button>
		                        </div>
		                        <div class="modal-body">
		                            <div class="row">
		                                <div class="col-md-3 col-sm-12">
		                                    <img src="/images/pets/{$p.img}" class="img-thumbnail">
		                                </div>
		                                <div class="col-md-9 col-sm-12">
		                                    <div class="row stats">
			                                    <div class="col-md-6 col-sm-12">
			                                    	<img src="/images/hp.gif"> {$p.hp|string_format:"%d"}/{$p.maxhp|string_format:"%d"}<br>
			                                    	<img src="/images/hunger.gif"> {$p.hunger}/{$p.maxhunger}<br>
			                                    	<strong>Type:</strong> {$p.type}
			                                    </div>
			                                    <div class="col-md-6 col-sm-12">
			                                    	<img src="/images/att.gif"> {$p.att|string_format:"%d"}<br>
			                                    	<img src="/images/def.gif"> {$p.def|string_format:"%d"}<br>
													<strong>Species:</strong> {$p.species}
			                                    </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="row">
		                                <blockquote class="blockquote">
		                                    <p class="mb-0">{$p.text}</p>
		                                    <footer class="blockquote-footer"><cite title="pet quote">{$p.name}</cite></footer>
		                                </blockquote>
		                            </div>
		                            {if $editable}
			                            <hr>
										<div class="form-check">
											<label class="form-check-label">
											<input type="checkbox" class="form-check-input petDef" id="pet-def-{$p.id}" {if $p.defending}checked{/if}>
												Defending base
											</label>
										</div>
									{/if}
		                        </div>
		                        <div class="modal-footer">
		                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		                            <a class="btn btn-primary visitBtn" href="/pet.php?pet={$p.id}{$qry}">Visit!</a>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        {/foreach}
	        </div>
	    {/if}
    </div>
    <div class="main-container container">
    	<h2>Busy J-Petz</h2>
    	{if empty($bpets)}
    		<p>No busy J-Petz!</p>
    	{else}
    		<div class="row clearfix">
    			{assign var=pCount value=1}
		        {foreach from=$bpets item=p}
		            <div class="card pet-item col-lg-3 col-md-3 col-sm-4 col-xs-6">
	                    <img class="card-img-top img-responsive rarity-{$p.rarity}" src="/images/pets/{$p.img}" alt="Pet image" />
	                    <div class="card-block">
	                        <h4 class="card-title stats">{$p.name}</h4>
	                        <h6 class="text-muted stats"><em>{$p.species} | {$p.type} collection</em></h6>
	                    </div>
	                </div>
	                {if $pCount % 4 == 0}
	                	<div class="clearfix hidden-sm-down"></div>
	                {/if}

	                {if $pCount % 3 == 0}
	                	<div class="clearfix hidden-md-up hidden-xs-down"></div>
	                {/if}

	                {if $pCount % 2 == 0}
	                	<div class="clearfix hidden-sm-up"></div>
	                {/if}

	                {assign var=pCount value=$pCount+1}
		        {/foreach}
	        </div>
	    {/if}
    </div>
    <script src="/js/petfull.js"></script>
{/block}
