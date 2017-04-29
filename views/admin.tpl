{extends file="../templates/parent.tpl"}
{block name=title}Green Gaming - Admin Panel{/block}
{block name=body}
    <div class="container">
        <h1 class="display-3">Admin Panel</h1>
        <p><em>POWER OVERWHELMING!</em></p>
    </div>
    <div class="container">
        <h3>Other Panels</h3>
        <ul class="list-group">
            <li class="list-group-item">
                {$unverifiedBadge}
                <a href="/questionadmin.php">Question Administration</a>
            </li>
            <li class="list-group-item">
                <a href="/scumadmin.php">Scum Administration</a>
            </li>
        </ul>
    </div>
{/block}
