{extends file="parent.tpl"}
{block name=title}Login{/block}
{block name=body}
    <h2>Here is a Smarty dropdown</h2>
    <select name=user>
        {html_options values=$id output=$names}
    </select>
    
    <h2>Here are Smarty tables</h2>
    <table>
    {foreach $names as $name}
    {strip}
       <tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
          <td>{$name}</td>
       </tr>
    {/strip}
    {/foreach}
    </table>
    
    <hr />
    
    <table>
    {foreach $users as $user}
    {strip}
       <tr bgcolor="{cycle values="#aaaaaa,#bbbbbb"}">
          <td>{$user.name}</td>
          <td>{$user.phone}</td>
       </tr>
    {/strip}
    {/foreach}
    </table>
{/block}