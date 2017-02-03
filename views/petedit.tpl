{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - {$pet.name}{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Hello, <span id="title-name" class="stats">{$pet.name}!</span></h1>
        <p><em>A {$pet.species} J-Pet</em></p>
    </div>
    <div class="main-container container">
        <div class="jumbotron pet-box">
            <img src="/images/pets/{$pet.img}" class="mx-auto d-block pet-frame" draggable="false">
            <hr>
            <button type="button" class="btn btn-primary" id="feed">Feed (2 SP)</button>
            <button type="button" class="btn btn-info" id="flaunt">Flaunt (10 SP)</button>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#petModal">Edit</button>
        </div>
        
        <h2>Stats</h2>
        <div class="row stats">
            <div class="col-md-6 col-sm-12">
                <img src="/images/hp.gif"> {$pet.hp}/{$pet.maxhp}<br>
                <img src="/images/hunger.gif"> {$pet.hunger}/{$pet.maxhunger}<br>
                <strong>Type:</strong> {$pet.type}
            </div>
            <div class="col-md-6 col-sm-12">
                <img src="/images/att.gif"> {$pet.att}<br>
                <img src="/images/def.gif"> {$pet.def}<br>
                <strong>Species:</strong> {$pet.species}
            </div>
        </div>
        <h2>Bio</h2>
        <div class="row">
            <blockquote class="blockquote">
                <p id="bio-display" class="mb-0">{$pet.text}</p>
                <footer class="blockquote-footer"><cite title="pet quote">{$pet.name}</cite></footer>
            </blockquote>
        </div>
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
