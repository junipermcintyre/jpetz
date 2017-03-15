{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - {$pet.name}{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">{$name}'s {$pet.name}</h1>
        <p><em>A {$pet.species} J-Pet</em></p>
    </div>
    <div class="main-container container">
        <div class="jumbotron">
            <img src="/images/pets/{$pet.img}" class="mx-auto d-block" draggable="false">
        </div>
        <h2>Stats</h2>
        <div class="row stats">
            <div class="col-md-6 col-sm-12">
                <img src="/images/hp.gif"> {$pet.hp|string_format:"%d"}/{$pet.maxhp|string_format:"%d"}<br>
                <img src="/images/hunger.gif"> {$pet.hunger}/{$pet.maxhunger}<br>
                <strong>Type:</strong> {$pet.type}
            </div>
            <div class="col-md-6 col-sm-12">
                <img src="/images/att.gif"> {$pet.att|string_format:"%d"}<br>
                <img src="/images/def.gif"> {$pet.def|string_format:"%d"}<br>
                <strong>Species:</strong> {$pet.species}
            </div>
        </div>
        <h2>Bio</h2>
        <div class="row">
            <blockquote class="blockquote">
                <p class="mb-0">{$pet.text}</p>
                <footer class="blockquote-footer"><cite title="pet quote">{$pet.name}</cite></footer>
            </blockquote>
        </div>
    </div>
{/block}
