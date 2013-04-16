{if $latestArticles|count > 0}
<hgroup>
    <h1>{lang}com.woltnet.wiki.latestArticles{/lang}</h1>
</hgroup>

{include file='categoryArticleListDashboard' application='wiki' objects=$latestArticles}
{/if}