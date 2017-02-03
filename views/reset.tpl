{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - Reset Password{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Reset Password</h1>
        <p><em>Did you really forget your password...</em></p>
    </div>
    <div class="main-container container">
        <form>
            <fieldset class="form-group">
                <label for="emailInput">Email address</label>
                <input type="email" class="form-control" id="emailInput" value="{$email}" disabled>
            </fieldset>
            <fieldset class="form-group">
                <label for="passwordInput">New password</label>
                <input type="password" class="form-control" id="passwordInput" placeholder="************">
                <small class="text-muted">Enter it right the first time. No requirements. Be smart.</small>
            </fieldset>
            <button type="button" id="resetButton" class="btn btn-primary">Reset password</button>
        </form>
    </div>
    <script>var token = "{$token}";</script>
    <script src="../js/reset.js"></script>
{/block}