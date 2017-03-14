{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - User View{/block}
{block name=body}
    <div class="main-container container">
    	<div class="row">
	    	<img src="/images/avatars/{$avatar}" alt="User profile image" class="profile col-md-2">
	    	<div class="col-md-10">
		        <h1 class="display-3">{$name}</h1>
		        <p><em>{$intro}</em></p>
		        <p><a href="/pet.php{$qry}">View J-Petz</a> | <a href="/raid.php{$qry}">Raid Scum Points</a></p>
		        <p></p>
	        </div>
        </div>
    </div>
    <div class="main-container container">
    	<h2>Info</h2>
        <div class="row">
        	<div class="col-md-10">
				<div class="form-group row">
					<label for="Name" class="col-md-2 col-xs-4 col-sm-4 col-form-label">Display name</label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<input class="form-control editable" type="text" value="{$name}" id="name" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label for="Role" class="col-md-2 col-xs-4 col-sm-4 col-form-label">Role</label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<input class="form-control" type="text" value="{$role}" id="role" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label for="Name" class="col-md-2 col-xs-4 col-sm-4 col-form-label"><a target="_blank" href="https://twitter.com/{$twitter}">Twitter handle</a></label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<div class="input-group">
						 	<span class="input-group-addon">@</span>
							<input class="form-control editable" type="text" value="{$twitter}" id="twitter" readonly>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="Points" class="col-md-2 col-xs-4 col-sm-4 col-form-label">Scum points</label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<input class="form-control" type="number" value="{$scumPoints}" id="scumPoints" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label for="Name" class="col-md-2 col-xs-4 col-sm-4 col-form-label"><a target="_blank" href="https://steamcommunity.com/id/{$s_id}">Steam ID</a></label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<input class="form-control editable" type="text" value="{$s_id}" id="steam" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label for="Name" class="col-md-2 col-xs-4 col-sm-4 col-form-label">Summoner name</label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<input class="form-control editable" type="text" value="{$l_id}" id="league" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label for="Website" class="col-md-2 col-xs-4 col-sm-4 col-form-label"><a target="_blank" href="{$website}">Website</a></label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<input class="form-control editable" type="url" value="{$website}" id="website" readonly>
					</div>
				</div>
				<div class="form-group row hidden" style="display: none;">
					<label for="Name" class="col-md-2 col-xs-4 col-sm-4 col-form-label">Intro text</label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<input class="form-control editable" type="text" value="{$intro}" id="intro" readonly>
					</div>
				</div>
				<div class="form-group row hidden" style="display: none;">
					<label for="profile picture upload" class="col-md-2 col-xs-4 col-sm-4 col-form-label">Profile image</label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<input type="file" class="form-control-file editable" id="profileImg" aria-describedby="profile upload">
						<small id="fileHelp" class="form-text text-muted">150px &times; 150px recommended</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="about" class="col-md-2 col-xs-4 col-sm-4 col-form-label">About</label>
					<div class="col-md-10 col-xs-8 col-sm-8">
						<textarea class="form-control editable" id="about" rows="3" readonly>{$about}</textarea>
					</div>
				</div>
            </div>
        </div>
		<div class="row col-md-12" id="button">
			{$edit}
		</div>
    </div>

    <div class="main-container container">
    	<h2>Question History</h2>
    	<table class="table table-bordered table-hover table-sm">
	    	<thead class="thead-inverse">
	    		<tr><th>Date Asked</th><th>Strawpoll Link</th></tr>
	    	</thead>
	    	<tbody>
	    	{foreach from=$questions item=q}
	    		<tr><td>{$q.date}</td><td>{$q.link}</td></tr>
	    	{/foreach}
	    	</tbody>
    	</table>
    </div>

    <script src="/js/user.js"></script>
{/block}
