{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - User View{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Shop</h1>
        <p><em>Buying... or selling? Hehehehe...</em></p>
    </div>
    <div class="main-container container">
        <h2>J-Capz</h2>
        <div class="row">
        {assign var=pCount value=1}
        {foreach from=$caps item=p}
            <div class="card pet-item col-lg-3 col-md-3 col-sm-4 col-xs-6">
                <img class="card-img-top img-responsive" src="/images/caps/{$p.pic}" alt="Pet image" />
                <div class="card-block">
                    <h4 class="card-title stats">{$p.name}</h4>
                    <p class="card-text">Cost: {$p.cost} SP</p>
                    <button class="btn btn-success btn-lg btn-block buyBtn" id="pet-buy-{$p.id}">Purchase</button>
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
    </div>

    <!-- Purchased pet modal! -->
    <div class="modal fade" id="pet" tabindex="-1" role="dialog" aria-labelledby="Pet Modal Window" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Congratulations on your new <span id="pName"></span>!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <img class="img-thumbnail" id="pImg">
                        </div>
                        <div class="col-md-9 col-sm-12">
                            <div class="row stats">
                                <div class="col-md-6 col-sm-12">
                                    <img src="/images/hp.gif"> <span id="pHp"></span><br>
                                    <img src="/images/hunger.gif"> <span id="pHunger"></span>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <img src="/images/att.gif"> <span id="pAtt"></span><br>
                                    <img src="/images/def.gif"> <span id="pDef"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    <a href="/pet.php?id=#" id="pLink" type="button" class="btn btn-secondary">Visit pet</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End purchased pet modal -->

    <script src="/js/caps.js"></script>
{/block}
