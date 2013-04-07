<div class="containerPadding">
  <div>
    {hascontent}
      <ul>
        {content}
          {foreach from=$userArticleList item=articleList}
            <li class="wikiProfileContainer box48 marginTop">
              <a href="{link application='wiki' controller='Article' object=$articleList->getDecoratedObject()}{/link}" class="framed jsTooltip" title="{lang}wiki.article.goToArticle{/lang}"><p class="framed"><span class="icon icon32 icon-file"></span></p></a>

              <hgroup class="wikiProfileContainerHeadline">
                <h1><a href="{link application='wiki' controller='Article' object=$articleList->getDecoratedObject()}{/link}" data-article-id="{@$articleList->getID()}" class="wikiArticleTopicLink articleLink framed" title="{$articleList->getTitle()}">{$articleList->getTitle()}</a></h1>
                <h2><small>{if $articleList->userID}<a href="{link controller='User' object=$articleList->getAuthor()->getDecoratedObject()}{/link}">{$articleList->username}</a>{else}{$articleList->username}{/if} - {@$articleList->time|time} - {lang}wcf.user.profile.userArticles.category{/lang} <a href="{link application='wiki' controller='Category' object=$articleList->getCategory()}{/link}" class="jsTooltip" title="{$articleList->getCategory()->getTitle()}">{$articleList->getCategory()->getTitle()}</a></small></h2>
              </hgroup>
            </li>
          {/foreach}
        {/content}
      </ul>
    {hascontentelse}
      <p class="info">{lang}wcf.user.profile.userArticles.noneAvailable{/lang}</p>
    {/hascontent}
  </div>
</div>