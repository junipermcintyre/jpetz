{extends file="../templates/bossparent.tpl"}
{block name=title}Jerad McIntyre - Raid Boss{/block}
{block name=body}
<div class="boss-container container">
    <h1 class="display-3">Raid Boss</h1>
    <p><em>getouttathefiregetouttathefiregetouttathefiregetouttathefire </em></p>
</div>
<div class="row">
    <div class="battle-box col-md-4">
        <h2>My J-Petz Party</h2>
        <div class="clearfix">
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
                    <img src="/images/pets/{$p.img}" id="pet-img-{$p.id}" alt="Pet img" class="img-thumbnail pet-boss pull-left">
                    <h4 class="stats">{$p.name}</h4>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
                            <img src="/images/hp.gif"> <span class="stats"><span id="pet-hp-{$p.id}">{$p.hp}</span>/{$p.maxhp}</span>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12">
                            <img src="/images/att.gif"> <span class="stats">{$p.att}</span>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12">
                            <img src="/images/def.gif"> <span class="stats">{$p.def}</span>
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                            <img src="/images/att.gif" class="fight" data-toggle="tooltip" id="pet-fight-{$p.id}" data-html="true" data-placement="right" title="Deal {$p.ed} dmg<br>Take {$p.et} dmg">
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
    <div class="battle-box col-md-7">
    	<h2 class="boss">{$boss.owner}'s {$boss.name}</h2>
    	<em>{$boss.species} | {$boss.type}</em>
        <div class="jumbotron pet-box">
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
                                <span id="bosshp" class="boss">{$boss.hp}</span> / {$boss.maxhp}
                            </div>
                        </div>
                    </div>
                </div>
        	</div>
        	<div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
        		<img src="/images/att.gif"> <span class="boss">{$boss.att}</span>
        	</div>
        	<div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
        		<img src="/images/def.gif"> <span class="boss">{$boss.def}</span>
        	</div>
        </div>
    </div>
</div>
<script src="/js/boss.js"></script>
{/block}
