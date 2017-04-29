{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - Home{/block}
{block name=body}
    <div class="container">
        <h1 class="display-3">Hello Friends</h1>
        <p><em>What are you looking for?</em></p>
    </div>
    <div class="container">
        <div class="row">
        	<div class="col-md-6">
        		<h2>Looking to talk?</h2>
        		<iframe src="https://discordapp.com/widget?id=77577034204721152&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0"></iframe>
        	</div>
        	<div class="col-md-6">
                <h2>Projects</h2>
                <div class="list-group">
                    <a href="projects/game" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Game</h4>
                        </div>
                        <p class="mb-1">HTML5 canvas & JavaScript game</p>
                    </a>
                    <a href="projects/generator" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Generator</h4>
                        </div>
                        <p class="mb-1">Quote generator for Computer Science meme-ery, uses WTFEngine</p>
                    </a>
                    <a href="projects/iptext" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">IPText</h4>
                        </div>
                        <p class="mb-1">It just shows you your IP... or does it?</p>
                    </a>
                    <a href="projects/pizza" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Pizza</h4>
                        </div>
                        <p class="mb-1">The OG pizza project from DB I, much better than the DB II project</p>
                    </a>
                    <a href="projects/referralpad" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">ReferralPad</h4>
                        </div>
                        <p class="mb-1">Referral network installation</p>
                    </a>
                    <a href="projects/texts" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Texts</h4>
                        </div>
                        <p class="mb-1">You can spam a Bell Phone with texts</p>
                    </a>
                    <a href="projects/web" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Web Proxy</h4>
                        </div>
                        <p class="mb-1">Install of PHPProxy</p>
                    </a>
                    <a href="projects/minecraft" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">JulianCraft</h4>
                        </div>
                        <p class="mb-1">The MC server to end all mc servers</p>
                    </a>
                </div>
        	</div>
        </div>
    </div>
    <div class="container">
        <h2>Communities</h2>
        <div class="row">
        	<div class="col-md-6">
                <h3>Steam</h3>
                <a href="http://steamcommunity.com/id/andwhatnot/" target="_blank">
                    <img src="http://badges.steamprofile.com/profile/default/steam/76561198048462067.png" border="0" style="width: 100%;" alt="Steamprofile badge by Steamprofile.com">
                </a>
        	</div>
        	<div class="col-md-6">
                <h3>Twitter</h3>
                <a class="twitter-timeline" href="https://twitter.com/Andwhatnot2" data-widget-id="727549712589578240">Tweets by @Andwhatnot2</a>
                {literal}<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>{/literal}
        	</div>
        </div>
    </div>
{/block}