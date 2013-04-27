{if $latestArticles|count > 0}
<div>
    <h1>{lang}com.woltnet.wiki.latestArticles{/lang}</h1>
</div>

{include file='categoryArticleListDashboard' application='wiki' objects=$latestArticles}
{/if}