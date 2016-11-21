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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
    <!-- Custom Style overwrites -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Scripts -->
    <!-- CDN for jQuery 2.1.4 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!-- Tether JS library, required for Bootstrap -->
    <script src="/plugins/tether/tether.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
    <!-- Grab our JavaScript functions file -->
    <script src="/js/js_functions.js"></script>
    <!-- Chart JS library for fancy metrics -->
    <script src="/plugins/chartjs/Chart.js"></script>
    <!-- End scripts -->
    <!-- Render the PHP DebugBar -->
    {$debugbarRenderer->renderHead()}
  </head>
  <body>
    <nav class="navbar navbar-fixed-top navbar-dark bg-inverse">
        <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2">&#9776;</button>
        <div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
            <a class="navbar-brand" href="/">Jerad McIntyre</a>
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link" href="/about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/web-development.php">Web Development</a>
                </li>-->
                <li class="nav-item">
                    <a class="nav-link" href="/league-stats.php">League Stats</a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link" href="/memes.php">Memes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact.php">Contact</a>
                </li>-->
                <li class="nav-item">
                    <a class="nav-link" href="/scum.php">Scum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/question.php">Question</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin.php">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/user.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>
    
    {block name=body}Default Body{/block}
    <!-- Render DebugBar -->
    {$debugbarRenderer->render()}
  </body>
</html>