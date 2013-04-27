<ul class="sitemapList">
    <li>
        <div>
            <h1>{lang}wiki.sitemap.category{/lang}</h1>
        </div>
        <ul>
            {foreach from=$categoryList item=categoryItem}
                <li><a href="{link controller='Category' object=$categoryItem->getDecoratedObject() application='wiki'}{/link}">{$categoryItem->getDecoratedObject()->getTitle()}</a></li>
            {/foreach}
        </ul>
    </li>
</ul>