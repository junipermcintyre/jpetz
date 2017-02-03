{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - Login{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Login</h1>
        <p><em>No account? Make one <a href="/register.php">here!</a></em></p>
    </div>
    <div class="main-container container">
        <form>
            <fieldset class="form-group">
                <label for="emailInput">Email address</label>
                <input type="email" class="form-control" id="emailInput" placeholder="you@domain.com">
            </fieldset>
            <fieldset class="form-group">
                <label class="passwordInput">Password</label>
                <input type="password" class="form-control" id="passwordInput" placeholder="*************">
                <small class="text-muted"><a href="/password-reset.php">Reset password</a></small> 
            </fieldset>
			<div class="form-check">
				<label class="form-check-label">
					<input type="checkbox" id="rememberme" class="form-check-input">
					Remember me
				</label>
			</div>
            <button type="button" id="loginButton" class="btn btn-primary">Login</button>
        </form>
    </div>
    <script>
        var gt = "{$gt}";
    </script>
    <script src="../js/login.js"></script>
{/block}