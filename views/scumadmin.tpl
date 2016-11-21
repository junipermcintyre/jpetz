{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - Scum Admin{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Scum Points Administration</h1>
        <p><em>With great power, comes great responsibility</em></p>
    </div>
    <div class="main-container container">
        <div id="scumTable">
            <!-- This is where the scum point charts will go - on load, it's a CSS spinner though -->
            <div class="sk-folding-cube">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
                <p>Choose, don't abuse (wat)</p>
            </div>
            <p><em>If this is taking forever, the page probably isn't finished yet!</em></p>
        </div>
        <div id="controls">
            <p><strong>Bonuses and Penalties</strong></p>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary" id="btn_bp">Beautiful Person</button>
                <button type="button" class="btn btn-secondary" id="btn_ss">Semi-Scum</button>
                <button type="button" class="btn btn-secondary" id="btn_sc">Scum</button>
            </div>
        </div>
    </div>
    <div class="main-container container">
        <h3>Instructions</h3>
        <p>This page is used to make modifications to users scum points when their role is altered.</p>
        <p>Selecting the <font color="purple">Beautiful people</font> button will add 1 point to the chosen users.</p>
        <p>Selecting the <font color="green">Semi-scum</font> button will remove 1 point from the chosen users.</p>
        <p>Selecting the <font color="brown">Scum</font> button will remove 2 points from the chosen users.</p>
        <p>Point modifications are made with the following in mind: each user will always gain +1 point <em>automatically</em> each day. Taking this into account, if a user has their points modified by +1 (<font color="purple">Beautiful person</font>), then their net point modification that day is +2 (as listed on <a href="/scum.php">the scum page</a>). This applies to all roles.</p>
        <p>Please do not abuse point modifications. Ideally, a user will only be subject to one given modification a day at max.</p>
    </div>
    <script src="/js/scumadmin.js"></script>
{/block}
