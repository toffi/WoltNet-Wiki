<ul class="sitemapList">
    <li>
        <hgroup>
            <h1>{lang}wiki.sitemap.category{/lang}</h1>
        </hgroup>
        <ul>
            {foreach from=$categoryList item=categoryItem}
                <li><a href="{link controller='Category' object=$categoryItem->getDecoratedObject() application='wiki'}{/link}">{$categoryItem->getDecoratedObject()->getTitle()}</a></li>
            {/foreach}
        </ul>
    </li>
</ul>