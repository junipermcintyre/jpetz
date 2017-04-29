{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - Leaderboards{/block}
{block name=body}
    <div class="container">
        <h1 class="display-3">Leaderboards</h1>
        <p><em>Have you been a bad boy?</em></p>
    </div>
    <div class="container">
        <h2>Scum</h2>
        <div id="scumTable">
        	<table class="table table-bordered table-hover table-sm">
                <thead class="thead-inverse">
                    <th>User</th><th>Role</th><th>Scum points</th>
                </thead>
                <tbody>
                    {foreach from=$scum item=u}
                        <tr><td>{$u.user}</td><td>{$u.role}</td><td>{$u.sp}</td></tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        <h2>J-Pet Collector</h2>
        <div id="jpetTable">
            <table class="table table-bordered table-hover table-sm">
                <thead class="thead-inverse">
                    <th>User</th><th>J-Petz collected</th>
                </thead>
                <tbody>
                    {foreach from=$collectors item=c}
                        <tr><td>{$c.user}</td><td>{$c.count}</td></tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>

    <div class="container">
    	<h2>What are scum points?</h2>
    	<p>Scum points are a representation of how good of a person one is on the Green-Gaming Discord server. Points are calculated at the end of each day, and depend on your current role. Everyone's role is reset to '<font color="blue">Not scum</font>' at the beginning of a day.<p>
    	<p>Your role can be changed at any given time by a moderator or administrator. Don't hold anything against them - points don't <a href="https://youtu.be/RkMgAzpcI8k?t=30" target="_blank">really matter</a>.
    	<p>Points can be added or subtracted in larger quantities based on the exploits of the member. If you find yourself with a surplus/deficiency, maybe you did something great/awful!</p>
    	<p>At some point in the future, these will count for something, somehow. Start saving up now!</p>
    	<p>The table below breaks down each role and it's point modifier. Better roles have all bonuses of lower roles!</p>
    	<table class="table table-sm table-responsive">
    		<thead>
    			<tr><th>Role</th><th>Modifier</th><th>Bonuses</th></tr>
    		</thead>
    		<tbody>
    			<tr><td><font color="purple">Beautiful people</font></td><td>+2 scum points</td><td>Access to /tts, mute other members</td></tr>
    			<tr><td><font color="blue">Not scum</font></td><td>+1 scum points</td><td>Invite others to server, mention @everyone</td></tr>
    			<tr><td><font color="green">Semi-scum</font></td><td>No modification</td><td>Send files and embed links</td></tr>
    			<tr><td><font color="brown">Scum</font></td><td>-1 scum points</td><td>Access to voice activated speech (<em>not</em> push-to-talk)</td></tr>
    		</tbody>
    	</table>
    </div>
    <script src="/js/scum.js"></script>
{/block}
