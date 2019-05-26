<!DOCTYPE html>
<html lang="en">
  <title>{block name=title}J McIntyre{/block}</title>
  {include file="head.tpl"}
  {if $nighttime}
  	<body class="spooky-body-dark">
  {else}
  	<body class="spooky-body">
  {/if}
    {include file="nav.tpl"}
    {block name=body}Default Body{/block}
    <!-- Render DebugBar -->
    {include file="footer.tpl"}
  </body>
</html>