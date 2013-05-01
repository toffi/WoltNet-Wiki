{hascontent}
	<ul>
		{content}
			{foreach from=$updatedArticleList item=updatedArticleObject}
				<li class="sidebarBox box24">
					<a href="{link controller='Article' object=$updatedArticleObject}{/link}" class="framed">{@$updatedArticleObject->getUserProfile()->getAvatar()->getImageTag(24)}</a>

					<div class="sidebarBoxHeadline">
						<h1><a href="{link controller='Article' object=$updatedArticleObject category=$updatedArticleObject->getCategory()}{/link}" class="wikiArticleTopicLink articleLink" data-article-id="{@$updatedArticleObject->articleID}" data-sort-order="DESC" title="{$updatedArticleObject->getTitle()}">{$updatedArticleObject->getTitle()}</a></h1>
						<h2><small>{if $updatedArticleObject->userID}<a href="{link controller='User' object=$updatedArticleObject->getAuthor()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$updatedArticleObject->getDecoratedObject()->userID}">{$updatedArticleObject->username}</a>{else}{$updatedArticleObject->username}{/if} - {@$updatedArticleObject->time|time}</small></h2>
					</div>
				</li>
			{/foreach}
		{/content}
	</ul>
{/hascontent}