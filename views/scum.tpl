{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - Scum Points{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Scum Points</h1>
        <p><em>Have you been a bad boy?</em></p>
    </div>
    <div id="scumTable" class="main-container container">
    	<!-- This is where the scum point charts will go - on load, it's a CSS spinner though -->
		<div class="sk-folding-cube">
			<div class="sk-cube1 sk-cube"></div>
			<div class="sk-cube2 sk-cube"></div>
			<div class="sk-cube4 sk-cube"></div>
			<div class="sk-cube3 sk-cube"></div>
			<p>Getting points in my sights...</p>
		</div>
		<p><em>If this is taking forever, the page probably isn't finished yet!</em></p>
    </div>
    <div class="main-container container">
    	<h2>What are scum points?</h2>
    	<p>Scum points are a representation of how good of a person one is on the Green-Gaming Discord server. Points are calculated at the end of each day, and depend on your current role. Everyone's role is reset to '<font color="blue">Not scum</font>' at the beginning of a day.<p>
    	<p>Your role can be changed at any given time by a moderator or administrator. Don't hold anything against them - points don't <a href="https://youtu.be/RkMgAzpcI8k?t=30" target="_blank">really matter</a>.
    	<p>Points can be added or subtracted in larger quantities based on the exploits of the member. If you find yourself with a surplus/deficiency, maybe you did something great/awful!</p>
    	<p>At some point in the future, these will count for something, somehow. Start saving up now!</p>
    	<p>The table below breaks down each role and it's point modifier. Better roles have all bonuses of lower roles!</p>
    	<table class="table table-sm">
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
