{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - Register{/block}
{block name=body}
<div class="container">
        <h1 class="display-3">Register</h1>
        <p><em>Already have an account? Log in <a href="/login.php">here</a>!</em></p>
    </div>
    <div class="container">
        <form>
            <fieldset class="form-group">
                <label for="emailInput">Email address</label>
                <input type="email" class="form-control" id="emailInput" placeholder="you@domain.com" required>
                <small class="text-muted">Your email address is never displayed or sent to the browser.</small> 
            </fieldset>
            <fieldset class="form-group">
                <label for="uernameInput">Username</label>
                <input type="text" class="form-control" id="usernameInput" placeholder="Xx_gamer_god_xX" required>
                <small class="text-muted">This is displayed.</small> 
            </fieldset>
            <fieldset class="form-group">
                <label class="passwordInput">Password</label>
                <input type="password" class="form-control" id="passwordInput" placeholder="*************"  required>
                <small class="text-muted">Enter it right the first time. No requirements. Be smart.</small> 
            </fieldset>
            <button type="button" id="register" class="btn btn-primary">Create Account</button>
        </form>
    </div>
    <script src="../js/register.js"></script>
{/block}