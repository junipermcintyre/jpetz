<!-- Feature request modal and button -->
<div class="feature" id="feature"><img src="/images/bttl.gif" data-toggle="modal" data-target="#feature-modal"></div>
{if $nighttime}
  <div id="credit" class="footer-dark">
{else}
  <div id="credit" class="footer">
{/if}
  <span class="text-muted"><p class="text-center">All J-Petz images courtesy of <a href="https://friendlytaco.tumblr.com/succ" target="_blank">FriendlyTaco</a></p></span>
</div>
<div class="modal fade" id="feature-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Feature Request</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>If you think something should be changed - send it in here. You're probably right.</p>
        <p>A list of officially planned things can be found <a href="https://github.com/Jerad-M/jerweb" target="_blank">here</a>.</p>
        <div class="form-group row">
        	<label for="name" class="col-md-2 col-xs-12 col-form-label">Name</label>
        	<div class="col-md-10 col-xs-12">
				{if $usrInfo != ""}
		        	<input type="text" class="form-control" value="{$usrInfo.name}" id="featureName" readonly>
		        {else}
		        	<input type="text" class="form-control" id="featureName">
		        {/if}
		    </div>
        </div>
        <div class="form-group row">
        	<label for="request" class="col-md-2 col-xs-12 col-form-label">Request</label>
        	<div class="col-md-10 col-xs-12">
				<textarea class="form-control" id="featureRequest" rows="3"></textarea>
		    </div>
        </div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-secondary" id="featureClose" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="featureBtn">Send feature request</button>
      </div>
    </div>
  </div>
</div>
<script src="/js/js_after.js"></script>
