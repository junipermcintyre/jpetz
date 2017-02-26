<!DOCTYPE html>
<html lang="en">
  {include file="head.tpl"}
  <body class="spooky-body">
    {include file="nav.tpl"}
    {block name=body}Default Body{/block}
    <!-- Render DebugBar -->
    {include file="footer.tpl"}
    {$debugbarRenderer->render()}
  </body>
</html>