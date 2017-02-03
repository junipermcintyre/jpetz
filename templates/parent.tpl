<!DOCTYPE html>
<html lang="en">
  {include file="head.tpl"}
  <body class="main-body">
    {include file="nav.tpl"}
    {block name=body}Default Body{/block}
    <!-- Render DebugBar -->
    {$debugbarRenderer->render()}
  </body>
</html>