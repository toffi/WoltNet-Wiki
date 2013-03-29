<ul class="sitemapList">
    <li>
        <hgroup>
            <h1>{lang}wiki.sitemap.category{/lang}</h1>
        </hgroup>
        <ul>
            {foreach from=$categoryList item=categoryItem}
                <li><a href="{$categoryItem->getLink()}">{$categoryItem->getTitle()}</a></li>
            {/foreach}
        </ul>
    </li>
</ul>