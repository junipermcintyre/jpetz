{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - Question Admin{/block}
{block name=body}
    <div class="container">
        <h1 class="display-3">Question Administration</h1>
        <p><em>Put another one onto the stack</em></p>
    </div>
    <div class="container">
        <h3>Unverified Questions</h3>
        <div id="qTable">
            <!-- This is where the unverified questions charts will go - on load, it's a CSS spinner though -->
            <div class="sk-folding-cube">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
                <p>Options, options!</p>
            </div>
            <p><em>If this is taking forever, the page probably isn't finished yet!</em></p>
        </div>
        <div id="controls">
            <p><strong>Select questions and set their status using the buttons below</strong></p>
            <div class="btn-group" role="group" aria-label="Question verify button">
                <button type="button" class="btn btn-success" id="btn_verify">Verify</button>
                <button type="button" class="btn btn-danger" id="btn_discard">Discard</button>
            </div>
            <div class="sk-folding-cube" id="mod-loader" style="display:none;">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
                <p>Modifying...</p>
            </div>
        </div>
    </div>
    <div class="main-container container">
        <h3>Instructions</h3>
        <p>This page is used to verify strawpolls submitted for question of the day.</p>
        <p>Select all checkboxes for desired questions and mark them as either verified, or discarded. Verified questions will be placed into rotation for question of the day.</p>
        <p>Please do not abuse question of the day submissions.</p>
        <h3>Guidelines</h3>
        <ul>
            <li>No political questions. Final ruling on that one.</li>
            <li>No questions which groups may find offensive.</li>
            <li>Avoid re-posts (if you've noticed the question has run before, discard it).</li>
            <li>No questions where all the answers are the same, or variations of each other. While the question itself may be interesting, answering it is not.</li>
            <li>Avoid boring questions. Use your judgement.</li>
            <li>Have fun!</li>
        </ul>
    </div>
    <div class="main-container container">
        <h3>Upcoming questions</h3>
        <div id="uTable">
        <!-- This is where the verified and active questions will go - on load, it's a CSS spinner though -->
            <div class="sk-folding-cube">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
                <p>Look what's on the menu</p>
            </div>
            <p><em>If this is taking forever, the page probably isn't finished yet!</em></p>
        </div>
    </div>
    <script src="/js/questionadmin.js"></script>
{/block}
