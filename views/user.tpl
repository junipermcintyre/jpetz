{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - User View{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">{$name}</h1>
        <p><em>A friend to the people</em></p>
    </div>
    <div class="main-container container">
        <div class="row">
            <div class="card col-md-3">
                <img class="card-img-top" src="/images/avatars/{$avatar}" alt="Card image cap">
                <div class="card-block">
                    <h4 class="card-title">{$name}</h4>
                    <p class="card-text">{$role}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Scum points: {$scumPoints}</li>
                </ul>
                <!--<div class="card-block">
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>-->
            </div>
        </div>
    </div>
{/block}
