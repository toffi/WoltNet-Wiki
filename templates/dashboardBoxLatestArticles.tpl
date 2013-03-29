{if $latestArticles|count > 0}
<header class="boxHeadline boxSubHeadline">
    <hgroup>
        <h1>{lang}com.woltnet.wiki.latestArticles{/lang}</h1>
    </hgroup>
</header>

{include file='categoryArticleListDashboard' objects=$latestArticles}
{/if}