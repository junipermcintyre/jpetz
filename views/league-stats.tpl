{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - League Stats{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Hall of Fame</h1>
        <p><em>Get ready for a party</em></p>
    </div>
    <div class="main-container container">
    	<h2>Players</h2>
    	<div class="card-group" id="player-boxes">
        	<!-- This is where player boxes are dynamically created! -->
        </div>
    </div>
    <div id="charts" class="main-container container">
    	<!-- This is where the stat charts will go - on load, it's a CSS spinner though -->
		<div class="sk-folding-cube">
			<div class="sk-cube1 sk-cube"></div>
			<div class="sk-cube2 sk-cube"></div>
			<div class="sk-cube4 sk-cube"></div>
			<div class="sk-cube3 sk-cube"></div>
			<p>Breaking it down...</p>
		</div>
		<p><em>If this is taking forever, we may have exhausted API limits!</em></p>
    </div>
    <script src="/js/league-stats.js"></script>
{/block}
