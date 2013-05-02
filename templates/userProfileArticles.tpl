<div class="containerPadding">
  <div>
    {hascontent}
      <ul>
        {content}
          {foreach from=$userArticleList item=articleItem}
            <li class="wikiProfileContainer box48 marginTop">
              <a href="{link application='wiki' controller='Article' object=$articleItem->getDecoratedObject()}{/link}" class="framed jsTooltip" title="{lang}wiki.article.goToArticle{/lang}"><p class="framed"><span class="icon icon32 icon-file"></span></p></a>

              <div class="wikiProfileContainerHeadline">
                <h1><a href="{link application='wiki' controller='Article' categoryName=$articleItem->getCategory()->getTitle() object=$articleItem->getDecoratedObject()}{/link}" data-article-id="{@$articleItem->getID()}" class="wikiArticleTopicLink articleLink framed" title="{$articleItem->getTitle()}">{$articleItem->getTitle()}</a></h1>
                <h2><small>{if $articleItem->getUserID()}<a href="{link controller='User' object=$articleItem->getAuthor()->getDecoratedObject()}{/link}">{$articleItem->getUsername()}</a>{else}{$articleList->username}{/if} - {@$articleItem->getTime()|time} - {lang}wcf.user.profile.userArticles.category{/lang} <a href="{link application='wiki' controller='Category' object=$articleItem->getCategory()}{/link}" class="jsTooltip" title="{$articleItem->getCategory()->getTitle()}">{$articleItem->getCategory()->getTitle()}</a></small></h2>
              </div>
            </li>
          {/foreach}
        {/content}
      </ul>
    {hascontentelse}
      <p class="info">{lang}wcf.user.profile.userArticles.noneAvailable{/lang}</p>
    {/hascontent}
  </div>
</div>