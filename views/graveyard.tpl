{extends file="../templates/graveparent.tpl"}
{block name=title}Green Gaming - J-Petz Graveyard{/block}
{block name=body}
    <div class="container">
        <h1 class="display-3">Graveyard</h1>
        <p><em>Please take better care of your J-Petz</em></p>
    </div>
    <div class="container">
    	{if empty($pets)}
    		<p>Somehow, there are no dead pets!</p>
    	{else}
    		<div class="clearfix">
    			<div class="row">
		        {foreach from=$pets item=p}
		        	<div class="col-12 col-sm-12 col-md-4">
		                <div class="card pull-left pet-item">
		                    <img class="card-img-top img-responsive dead-pet" src="/images/pets/{$p.img}" alt="Pet image" />
		                    <div class="card-block">
		                        <h4 class="card-title stats">{$p.ownerName}'s {$p.name}</h4>
		                        <h6 class="text-muted stats"><em>{$p.species} | {$p.type} collection</em></h6>
		                        <button data-toggle="modal" data-target="#pet-{$p.id}" class="btn btn-primary btn-lg btn-block">Stats</button>
		                    </div>
		                </div>
		            </div>

		            <div class="modal fade" id="pet-{$p.id}" tabindex="-1" role="dialog" aria-labelledby="Pet Modal Window" aria-hidden="true">
		                <div class="modal-dialog" role="document">
		                    <div class="modal-content">
		                        <div class="modal-header">
		                            <h5 class="modal-title stats"><a href="/user.php?id={$p.owner}">{$p.ownerName}</a>'s {$p.name}</h5>
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
													<img src="/images/att.gif"> {$p.att|string_format:"%d"}<br>
			                                    	<strong>Type:</strong> {$p.type}
			                                    </div>
			                                    <div class="col-md-6 col-sm-12">
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
		                           	<h6>Memorial</h6>
		                           	<label>Leave a message</label>
		                           	<textarea rows="2" class="form-control" id="pet-mem-{$p.id}"></textarea><br>
		                           	<button type="button" class="btn btn-secondary">Save</button>
		                        </div>
		                        <div class="modal-footer">
		                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		                            <button type="button" class="btn btn-primary" id="pet-respect-{$p.id}" data-dismiss="modal">Pay respects (5 SP)</button>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        {/foreach}
		        </div>
	        </div>
	    {/if}
    </div>
{/block}
