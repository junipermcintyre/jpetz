{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - Home{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Hello Friends</h1>
        <p><em>What are you looking for?</em></p>
    </div>
    <div class="main-container container">
        <div class="row">
        	<div class="col-md-6">
        		<h2>Looking to talk?</h2>
        		<iframe src="https://discordapp.com/widget?id=77577034204721152&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0"></iframe>
        	</div>
        	<div class="col-md-6">
                <h2>Projects</h2>
                <div class="list-group">
                    <a href="projects/fb" class="list-group-item">
                        <h4 class="list-group-item-heading">FB</h4>
                        <p class="list-group-item-text">Attempt to make a "Sign in with Facebook" style page</p>
                    </a>
                    <a href="projects/game" class="list-group-item">
                        <h4 class="list-group-item-heading">Game</h4>
                        <p class="list-group-item-text">HTML5 canvas & JavaScript game</p>
                    </a>
                    <a href="projects/generator" class="list-group-item">
                        <h4 class="list-group-item-heading">Generator</h4>
                        <p class="list-group-item-text">Quote generator for Computer Science meme-ery, uses WTFEngine</p>
                    </a>
                    <a href="projects/glype" class="list-group-item">
                        <h4 class="list-group-item-heading">Glype</h4>
                        <p class="list-group-item-text">Install of the Glype webproxy</p>
                    </a>
                    <a href="projects/iptext" class="list-group-item">
                        <h4 class="list-group-item-heading">IPText</h4>
                        <p class="list-group-item-text">It just shows you your IP... or does it?</p>
                    </a>
                    <a href="projects/pizza" class="list-group-item">
                        <h4 class="list-group-item-heading">Pizza</h4>
                        <p class="list-group-item-text">The OG pizza project from DB I, much better than the DB II project</p>
                    </a>
                    <a href="projects/referralpad" class="list-group-item">
                        <h4 class="list-group-item-heading">ReferralPad</h4>
                        <p class="list-group-item-text">Referral network installation</p>
                    </a>
                    <a href="projects/signage" class="list-group-item">
                        <h4 class="list-group-item-heading">Signage</h4>
                        <p class="list-group-item-text">Download branded signage player</p>
                    </a>
                    <a href="projects/texts" class="list-group-item">
                        <h4 class="list-group-item-heading">Texts</h4>
                        <p class="list-group-item-text">You can spam a Bell Phone with texts</p>
                    </a>
                    <a href="projects/web" class="list-group-item">
                        <h4 class="list-group-item-heading">Web Proxy</h4>
                        <p class="list-group-item-text">Install of PHPProxy</p>
                    </a>
                </div>
        	</div>
        </div>
    </div>
    <div class="main-container container">
        <h2>Communities</h2>
        <div class="row">
        	<div class="col-md-6">
                <h3>Steam</h3>
                <a href="http://steamcommunity.com/id/andwhatnot/" target="_blank">
                    <img src="http://badges.steamprofile.com/profile/default/steam/76561198048462067.png" border="0" alt="Steamprofile badge by Steamprofile.com">
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