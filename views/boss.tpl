{extends file="../templates/bossparent.tpl"}
{block name=title}Jerad McIntyre - Raid Boss{/block}
{block name=body}
<div class="boss-container container">
    <h1 class="display-3">Raid Boss</h1>
    <p><em>getouttathefiregetouttathefiregetouttathefiregetouttathefire</em></p>
</div>
<div class="row">
    <div class="battle-box col-md-4">
        <h2>My J-Petz Party</h2>
        <div class="clearfix">
            {if empty($pets)}
                <p>No J-Petz available!</p>
            {else}
                {foreach from=$pets item=p}
                    <!--<div class="card pull-left quest-card">
                        <img class="card-img-top img-responsive" src="/images/pets/{$p.img}" id="pet-img-{$p.id}" alt="Pet image" />
                        <div class="card-block">
                            <h4 class="card-title stats">{$p.name}</h4>
                            <p class="card-text">
                                <img src="/images/hp.gif"> <span class="stats"><span id="pet-hp-{$p.id}">{$p.hp}</span>/{$p.maxhp}</span><br>
                                <img src="/images/att.gif"> <span class="stats">{$p.att}</span><br>
                                <img src="/images/def.gif"> <span class="stats">{$p.def}</span>
                            </p>
                            <button type="button" class="btn btn-primary btn-block btn-sm fight" data-toggle="tooltip" id="pet-fight-{$p.id}" data-html="true" data-placement="bottom" title="Deal {$p.ed} dmg<br>Take {$p.et} dmg">Attack</button>
                        </div>
                    </div>-->
                    <div class="pet-horizontal clearfix">
	                    <div class="row">
	                        <img src="/images/pets/{$p.img}" id="pet-img-{$p.id}" alt="Pet img" class="img-thumbnail pet-boss col-md-2">
	                        <div class="col-md-10">
	                        	<h4 class="stats">{$p.name}</h4>
		                        <div class="row">
		                            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
		                                <img src="/images/hp.gif"> <span class="stats"><span id="pet-hp-{$p.id}">{$p.hp|string_format:"%d"}</span>/{$p.maxhp|string_format:"%d"}</span>
		                            </div>
		                            <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12">
		                                <img src="/images/att.gif"> <span class="stats">{$p.att|string_format:"%d"}</span>
		                            </div>
		                            <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12">
		                                <img src="/images/def.gif"> <span class="stats">{$p.def|string_format:"%d"}</span>
		                            </div>
		                            <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
		                                <img src="/images/bttl.gif" class="fight" data-toggle="tooltip" id="pet-fight-{$p.id}" data-html="true" data-placement="right" title="Deal {$p.ed} dmg<br>Take {$p.et} dmg">
		                            </div>
		                        </div>
							</div>
                        </div>
                    </div>
                {/foreach}
            {/if}
        </div>
    </div>
    <div class="battle-box col-md-7">
        {if $boss}
        	<h2 class="boss">{$boss.owner}'s {$boss.name} {if $boss.hp <= 0}- Defeated!{/if} <small>Loot: {$boss.reward} SP | Last hit: {$boss.bonus} SP</small></h2>
        	<em>{$boss.species} | {$boss.type}</em>
            <div class="jumbotron pet-box {if $boss.hp <= 0}dead-pet{/if}">
                <img src="/images/pets/{$boss.img}" class="mx-auto d-block pet-frame" id="boss-img" draggable="false">
            </div>
            <div class="row">
            	<div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="/images/hp.gif">
                        </div>
                        <div class="col-md-10">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="{$boss.hp}" aria-valuemin="0" aria-valuemax="{$boss.maxhp}" style="width: {math equation="(x / y) * 100" x=$boss.hp y=$boss.maxhp}%">
                                    <span id="bosshp" class="boss">{$boss.hp|string_format:"%d"}</span> / {$boss.maxhp|string_format:"%d"}
                                </div>
                            </div>
                        </div>
                    </div>
            	</div>
            	<div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
            		<img src="/images/att.gif"> <span class="boss">{$boss.att|string_format:"%d"}</span>
            	</div>
            	<div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
            		<img src="/images/def.gif"> <span class="boss">{$boss.def|string_format:"%d"}</span>
            	</div>
            </div>
            {if $boss.hp <= 0}
                <p>This boss has been defeated! Rewards and bonuses given, and new raid boss selected at 12AM EST</p>
            {/if}
        {else}
            <h2>There doesn't seem to be a boss right now!</h2>
        {/if}
    </div>
</div>
<script src="/js/boss.js"></script>
{/block}
