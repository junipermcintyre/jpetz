{extends file="../templates/parent.tpl"}
{block name=title}Spooky Memes{/block}
{block name=body}
    <h2>Products</h2>
    <table id="grid-data-api" class="table table-condensed table-hover table-striped" data-toggle="bootgrid" data-ajax="true" data-url="">
        <thead>
            <tr>
                <th data-column-id="id" data-type="numeric">ID</th>
                <th data-column-id="name">Name</th>
                <th data-column-id="descr">Description</th>
                <th data-column-id="price" data-formatter="price">Link</th>
                <th data-column-id="image">Image</th>
            </tr>
        </thead>
    </table>
    <script src="../js/products.js"></script>
{/block}