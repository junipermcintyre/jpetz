{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - Question{/block}
{block name=body}
    <div class="container">
        <h1 class="display-3">Question of the Day</h1>
        <p><em>Riddle me this...</em></p>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12" id="question">
	        	<div class="sk-folding-cube">
	                <div class="sk-cube1 sk-cube"></div>
	                <div class="sk-cube2 sk-cube"></div>
	                <div class="sk-cube4 sk-cube"></div>
	                <div class="sk-cube3 sk-cube"></div>
	                <p>Can I axe you a question?</p>
	            </div>
	            <p><em>If this is taking forever, the page probably isn't finished yet!</em></p>
            </div>
        </div>
    </div>
    <div class="main-container container">
    	<h2>Suggest a question!</h2>
    	<ol>
    		<li>Go to <a href="http://strawpoll.me/">StrawPoll</a>.</li>
    		<li>Make a question.</li>
    		<li>Look in the URL for your finished question. Find the poll ID (it's the string of numbers!)</li>
    		<li>Copy and paste JUST the numbers into the box below, and submit!</li>
		</ol>
		<p>Easy as 1-2-3 (4). Once approved, your question will enter daily rotation. First come first serve!</p>
		<div class="form-inline">
			<div class="form-group">
				<label for="questionInput">Got a StrawPoll code?</label>
    			<input type="text" class="form-control" id="qCode" placeholder="1234567890">
			</div>
			<button class="btn btn-primary" id="qBtn">Send question code</button>
		</div>
    </div>
    <div class="main-container container">
        <h3>Moderator Guidelines - For your reading pleasure</h3>
        <ol>
            <li>No political questions. Final ruling on that one.</li>
            <li>No questions which groups may find seriously offensive (satire is funny - mocking is not).</li>
            <li>Avoid re-posts (if you've noticed the question has run before, please don't).</li>
            <li>No questions where all the answers are the same, or variations of each other. While the question itself may be interesting, answering it is not.</li>
            <li>Have fun!</li>
        </ol>
    </div>
    <script src="/js/question.js"></script>
{/block}