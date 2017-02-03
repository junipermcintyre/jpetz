<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Webpage on jeradmcintyre.com">
    <meta name="author" content="Jerad McIntyre">
    <title>{block name=title}Jerad McIntyre{/block}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <!-- Custom Style overwrites -->
    <link rel="stylesheet" href="/css/style.css?ver=1.1">

    <!-- Scripts -->
    <!-- CDN for jQuery 3.1.1 -->
    <script src="/plugins/jquery-3.1.1.min.js"></script>
    <!-- JS Cookie stuff -->
    <script src="/plugins/cookie.js"></script>
    <!-- Tether JS library, required for Bootstrap -->
    <script src="/plugins/tether/tether.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <!-- Grab our JavaScript functions file -->
    <script src="/js/js_functions.js"></script>
    <!-- Chart JS library for fancy metrics -->
    <script src="/plugins/chartjs/Chart.js"></script>
    <!-- animate.css for animations -->
    <link rel="stylesheet" href="/plugins/animate.css">
    {literal}
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-89631730-1', 'auto');
            ga('send', 'pageview');

        </script>
    {/literal}
    <!-- End scripts -->
    <!-- Render the PHP DebugBar -->
    {$debugbarRenderer->renderHead()}
    <link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah|Walter+Turncoat" rel="stylesheet">
  </head>
  <body class="boss-body">
    <nav class="navbar fixed-top navbar-toggleable-md navbar-inverse bg-inverse">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/">Jerad McIntyre</a>
        <div class="collapse navbar-collapse" id="exCollapsingNavbar2">
            <ul class="mr-auto navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Green Gaming</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/user.php">Profile</a>
                        <a class="dropdown-item" href="/pet.php">My J-Petz</a>
                        <a class="dropdown-item" href="/quests.php">Quests</a>
                        <a class="dropdown-item" href="/boss.php">Raid Boss</a>
                        <a class="dropdown-item" href="/question.php">Question of the day</a>
                        <a class="dropdown-item" href="/scum.php">Leaderboards</a>
                        <a class="dropdown-item" href="/shop.php">Points Shop</a>
                    </div>
                </li>
                {if $usrInfo == ""}
                    <li class="nav-item">
                        <a class="nav-link" href="/login.php">Login</a>
                    </li>
                {else}
                    <li class="nav-item">
                        <a class="nav-link" href="/logout.php">Logout</a>
                    </li>
                {/if}

                {if $usrInfo != ""}
                    {if $usrInfo.role < 3}
                        <li class="nav-item">
                            <a class="nav-link" href="/admin.php">Admin</a>
                        </li>
                    {/if}
                {/if}
                <li class="nav-item">
                    <a class="nav-link" href="/contact.php">Contact</a>
                </li>
            </ul>
            {if $usrInfo != ""}
                <span class="navbar-text pull-right">
                    <a class="text-white" href="/user.php">Hello {$usrInfo.role} {$usrInfo.name} | SP: <span id="usr-scum">{$usrInfo.scumPoints}</span></a>
                </span>
            {/if}
        </div>
        <div id="notify"></div>
    </nav>
    {block name=body}Default Body{/block}
    <!-- Render DebugBar -->
    {$debugbarRenderer->render()}
  </body>
</html>