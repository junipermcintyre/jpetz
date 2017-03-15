{extends file="../templates/shopparent.tpl"}
{block name=title}Green Gaming - Shop{/block}
{block name=body}
    <div class="shop-container container">
        <h1 class="display-3">Shop</h1>
        <p><em>Buying... or selling? Hehehehe...</em></p>
    </div>
    <div class="shop-container container">
        <h2>J-Petz</h2>
        {foreach from=$pets item=i key=k}
            <hr>
            <h4>{$k|upper} <small><em>J-Petz</em></small></h4>
            <div class="row">
                {assign var=pCount value=1}
                {foreach from=$i item=p}
                    <div class="card pet-item col-lg-3 col-md-3 col-sm-4 col-xs-6">
                        <img class="card-img-top img-responsive" src="/images/pets/{$p.img}" alt="Pet image" />
                        <div class="card-block">
                            <h4 class="card-title stats">{$p.name}</h4>
                            <h6 class="text-muted stats"><em>{$p.type}</em> J-Pet</h6>
                            <p class="card-text">{$p.cost} points</p>
                            {if $p.stock > 0}
                                <button data-toggle="modal" data-target="#pet-{$p.id}" class="btn btn-success btn-lg btn-block">Purchase</button>
                            {else}
                                <button data-toggle="modal" data-target="#pet-{$p.id}" class="btn btn-info btn-lg btn-block">Stats</button>
                            {/if}
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
                                    <h5 class="modal-title">Purchase a <span class="stats">{$p.name}</span>?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <img src="/images/pets/{$p.img}" class="img-thumbnail rarity-{$p.rarity}">
                                        </div>
                                        <div class="col-md-9 col-sm-12">
                                            <div class="row stats">
                                                <div class="col-md-6 col-sm-12">
                                                    <img src="/images/hp.gif"> {$p.basehp}<br>
                                                    <img src="/images/hunger.gif"> {$p.basehunger}<br>
                                                    <strong>Type:</strong> {$p.type}
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <img src="/images/att.gif"> {$p.baseatt}<br>
                                                    <img src="/images/def.gif"> {$p.basedef}<br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <blockquote class="blockquote">
                                            <p class="mb-0">{$p.flavour}</p>
                                            <footer class="blockquote-footer"><cite title="pet quote">{$p.name}</cite></footer>
                                        </blockquote>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Nah</button>
                                    {if $p.stock > 0}
                                        <button type="button" class="btn btn-primary buyBtn" data-dismiss="modal" id="pet-buy-{$p.id}">Yah!</button>
                                    {else}
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">No stock!</button>
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        {/foreach}
    </div>

    <script src="/js/shop.js"></script>
{/block}
