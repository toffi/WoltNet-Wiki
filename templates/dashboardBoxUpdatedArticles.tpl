{hascontent}
	<ul>
		{content}
			{foreach from=$updatedArticleList item=updatedArticle}
				{assign var=updatedArticleObject value=$updatedArticle->getActiveVersion()}
				<li class="sidebarBox box24">
					<a href="{link controller='Article' object=$updatedArticleObject}{/link}" class="framed jsTooltip">{@$updatedArticleObject->getUserProfile()->getAvatar()->getImageTag(24)}</a>

					<hgroup class="sidebarBoxHeadline">
						<h1><a href="{link controller='Article' object=$updatedArticle->getActiveVersion()}{/link}" class="wikiArticleTopicLink" data-project-id="{@$updatedArticle->getActiveVersion()->articleID}" data-sort-order="DESC" title="{$updatedArticle->getActiveVersion()->getTitle()}">{$updatedArticle->getActiveVersion()->getTitle()}</a></h1>
						<h2><small>{if $updatedArticleObject->userID}<a href="{link controller='User' object=$updatedArticleObject->getUserProfile()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$updatedArticleObject->getUserProfile()->userID}">{$updatedArticleObject->username}</a>{else}{$updatedArticleObject->username}{/if} - {@$updatedArticleObject->time|time}</small></h2>
					</hgroup>
				</li>
			{/foreach}
		{/content}
	</ul>
{/hascontent}