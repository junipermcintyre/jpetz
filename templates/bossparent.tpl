<!DOCTYPE html>
<html lang="en">
  <title>{block name=title}Jerad McIntyre{/block}</title>
  {include file="head.tpl"}
  {if $nighttime}
  	<body class="boss-body-dark">
  {else}
  	<body class="boss-body">
  {/if}
    {include file="nav.tpl"}
    {block name=body}Default Body{/block}
    <!-- Render DebugBar -->
    {include file="footer.tpl"}
  </body>
</html>