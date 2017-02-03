{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - {$name}'s J-Petz{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">{$name}'s J-Petz</h1>
        <p><em>Behold! My collection...</em></p>
    </div>
    <div class="main-container container">
    	<h2>Available J-Petz</h2>
    	{if empty($pets)}
    		<p>No J-Petz available!</p>
    	{else}
    		<div class="clearfix">
		        {foreach from=$pets item=p}
	                <div class="card pull-left pet-item">
	                    <img class="card-img-top img-responsive" src="/images/pets/{$p.img}" alt="Pet image" />
	                    <div class="card-block">
	                        <h4 class="card-title stats">{$p.name}</h4>
	                        <h6 class="text-muted stats"><em>{$p.species} | {$p.type} collection</em></h6>
	                        <button data-toggle="modal" data-target="#pet-{$p.id}" class="btn btn-primary btn-lg btn-block">Stats</button>
	                    </div>
	                </div>

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
			                                    	<img src="/images/hp.gif"> {$p.hp}/{$p.maxhp}<br>
			                                    	<img src="/images/hunger.gif"> {$p.hunger}/{$p.maxhunger}<br>
			                                    	<strong>Type:</strong> {$p.type}
			                                    </div>
			                                    <div class="col-md-6 col-sm-12">
			                                    	<img src="/images/att.gif"> {$p.att}<br>
			                                    	<img src="/images/def.gif"> {$p.def}<br>
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
    		<div class="clearfix">
		        {foreach from=$bpets item=p}
		            <div class="card pull-left pet-item">
	                    <img class="card-img-top img-responsive" src="/images/pets/{$p.img}" alt="Pet image" />
	                    <div class="card-block">
	                        <h4 class="card-title stats">{$p.name}</h4>
	                        <h6 class="text-muted stats"><em>{$p.species} | {$p.type} collection</em></h6>
	                    </div>
	                </div>
		        {/foreach}
	        </div>
	    {/if}
    </div>
{/block}
