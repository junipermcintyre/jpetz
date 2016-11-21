{extends file="../templates/parent.tpl"}
{block name=title}Jerad McIntyre - Forbidden{/block}
{block name=body}
    <div class="main-container container">
        <h1 class="display-3">Uh oh!</h1>
        <p><em>You're forbidden from accessing this page!</em></p>
    </div>
    <div class="main-container container">
        <h2>Taking steps</h2>
        <p>If you really want to view this page, you should consider <a href="/login.php">logging in</a>! This will probably give you access.</p>
        <p>Alternatively, if you don't have an account, <a href="register.php">you can make one</a>! Registering is free, and only takes a minute. We'll never disclose passwords or email addresses to third parties.</p> 
    </div>
{/block}