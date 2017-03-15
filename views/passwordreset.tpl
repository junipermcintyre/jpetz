{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - Reset Password{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Reset Password</h1>
        <p><em>Did you really forget your password...</em></p>
    </div>
    <div class="main-container container">
        <form>
            <fieldset class="form-group">
                <label for="emailInput">Email address</label>
                <input type="email" class="form-control" id="emailInput" placeholder="you@domain.com">
            </fieldset>
            <button type="button" id="resetButton" class="btn btn-primary">Send reset email</button>
        </form>
    </div>
    <script src="../js/passwordreset.js"></script>
{/block}