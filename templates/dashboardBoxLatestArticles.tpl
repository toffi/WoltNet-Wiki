<header class="boxHeadline boxSubHeadline">
    <hgroup>
        <h1>{lang}com.woltnet.wiki.latestArticles{/lang}</h1>
    </hgroup>
</header>

<div class="container marginTop">
    <ul id="latestArticles" class="containerList articleList">
        {include file='categoryArticleList' objects=$latestArticles}
    </ul>
</div>