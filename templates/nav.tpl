<nav class="navbar fixed-top navbar-toggleable-md navbar-inverse bg-inverse">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="/">Green Gaming</a>
    <div class="collapse navbar-collapse" id="exCollapsingNavbar2">
        <ul class="navbar-nav" style="padding-right: 20px;">
            <li class="nav-item">
                <a class="nav-link" href="/user.php">Profile</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Scumconomy</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="/pet.php">Visit J-Petz</a>
                    <a class="dropdown-item" href="/quests.php">Quests</a>
                    <a class="dropdown-item" href="/boss.php">Raid Boss</a>
                    <a class="dropdown-item" href="/question.php">Question of the day</a>
                    <a class="dropdown-item" href="/scum.php">Leaderboards</a>
                    <a class="dropdown-item" href="/shop.php">Points Shop</a>
                </div>
            </li>
            {if $usrInfo != ""}
                {if $usrInfo.role < 3}
                    <li class="nav-item">
                        <a class="nav-link" href="/admin.php">Admin</a>
                    </li>
                {/if}
            {/if}
            {if $usrInfo == ""}
                <li class="nav-item">
                    <a class="nav-link" href="/login.php">Login</a>
                </li>
            {else}
                <li class="nav-item">
                    <a class="nav-link" href="/logout.php">Logout</a>
                </li>
            {/if}
            <li class="nav-item">
                <a class="nav-link" href="http://jeradmcintyre.com/projects/memedb">MemeDB</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0 mr-auto">
            <label class="form-check-label unselectable">
                <input id="nighttime" class="form-check-input mr-sm-2" type="checkbox" placeholder="Search" {if $nighttime}checked{/if}>
                <small class="nav-link-color"><em>Nighttime Browsing<sup>tm</sup></em> (Beta)</small>
            </label>
        </form>
        {if $usrInfo != ""}
            <span class="navbar-text pull-right">
                <a class="text-white" href="/user.php">Hello {$usrInfo.role} {$usrInfo.name} | SP: <span id="usr-scum">{$usrInfo.scumPoints}</span></a>
            </span>
        {/if}
    </div>
</nav>