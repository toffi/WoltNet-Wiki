{hascontent}
    <ul>
        {content}
            {foreach from=$latestArticleList item=latestArticleObject}
                {if $latestArticleObject->versionID == 0}
                <li class="sidebarBox box24">
                    <a href="{link controller='Article' object=$latestArticleObject}{/link}" class="framed">{@$latestArticleObject->getAuthor()->getAvatar()->getImageTag(24)}</a>

                    <div class="sidebarBoxHeadline">
                        <h1><a href="{link controller='Article' object=$latestArticleObject}{/link}" class="wikiArticleTopicLink articleLink" data-article-id="{@$latestArticleObject->articleID}" data-sort-order="DESC" title="{$latestArticleObject->getTitle()}">{$latestArticle->Object->getTitle()}</a></h1>
                        <h2><small>{if $latestArticleObject->userID}<a href="{link controller='User' object=$latestArticleObject->getAuthor()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$latestArticleObject->getDecoratedObject()->userID}">{$latestArticleObject->username}</a>{else}{$latestArticleObject->username}{/if} - {@$latestArticleObject->time|time}</small></h2>
                    </div>
                </li>
                {/if}
            {/foreach}
        {/content}
    </ul>
{/hascontent}