{hascontent}
    <ul id="taggedArticles" class="containerList taggedArticleList">
        {content}
            {include file='__searchResultArticleList' application='wiki' objects=$objects}
        {/content}
    </ul>
{hascontentelse}
    <div class="containerPadding">
        <p class="info">{lang}wcf.tagging.taggedObjects.noResults{/lang}</p>
    </div>
{/hascontent}
