{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - {$pet.name}{/block}
{block name=body}
    <div class="container">
        <h1 class="display-3">Hello, <span id="title-name" class="stats">{$pet.name}!</span></h1>
        <p><em>A {$pet.species} J-Pet</em></p>
    </div>
    <div class="container">
        <div class="jumbotron pet-box">
            <img src="/images/pets/{$pet.img}" class="mx-auto d-block pet-frame" draggable="false">
            <hr>
            <button type="button" class="btn btn-primary" id="feed">Feed (2 SP)</button>
            <button type="button" class="btn btn-info" id="flaunt">Flaunt (10 SP)</button>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#petModal">Edit</button>
            <div class="pull-right">
                Train: 
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" id="att" class="train btn btn-secondary"><img src="/images/att.gif"></button>
                    <button type="button" id="def" class="train btn btn-secondary"><img src="/images/def.gif"></button>
                    <button type="button" id="maxhp" class="train btn btn-secondary"><img src="/images/hp.gif"></button>
                </div>
            </div>
        </div>
        
        <h2>Stats</h2>
        <div class="row stats">
            <div class="col-md-6 col-sm-12">
                <img src="/images/hp.gif"> {$pet.hp|string_format:"%d"}/<span id="petmaxhp">{$pet.maxhp|string_format:"%d"}</span><br>
                <img src="/images/hunger.gif"> <span id="hunger">{$pet.hunger}</span>/{$pet.maxhunger}<br>
                <strong>Type:</strong> {$pet.type}<br>
                <strong>Actions:</strong> <span id="actions">{$pet.actions}</span>
            </div>
            <div class="col-md-6 col-sm-12">
                <img src="/images/att.gif"> <span id="petatt">{$pet.att|string_format:"%d"}</span><br>
                <img src="/images/def.gif"> <span id="petdef">{$pet.def|string_format:"%d"}</span><br>
                <strong>Species:</strong> {$pet.species}
            </div>
        </div>
        <br>
        <h2>Bio</h2>
        <blockquote class="blockquote">
            <p id="bio-display" class="mb-0">{$pet.text}</p>
            <footer class="blockquote-footer"><cite title="pet quote">{$pet.name}</cite></footer>
        </blockquote>
    </div>

	<div class="modal fade" id="petModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h5 class="modal-title" id="exampleModalLabel">Edit {$pet.name}</h5>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="Name" class="col-md-2 col-xs-4 col-sm-4 col-form-label">Name</label>
						<div class="col-md-10 col-xs-8 col-sm-8">
							<input class="form-control" type="text" value="{$pet.name}" id="name">
						</div>
					</div>
					<div class="form-group row">
						<label for="bio" class="col-md-2 col-xs-4 col-sm-4 col-form-label">Bio</label>
						<div class="col-md-10 col-xs-8 col-sm-8">
							<textarea class="form-control" id="bio" rows="3">{$pet.text}</textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal" id="edit">Save changes</button>
				</div>
			</div>
		</div>
	</div>

    <script>var petId = {$pet.id};</script>
    <script src="/js/petedit.js"></script>
{/block}
