{hascontent}
	<ul>
		{content}
			{foreach from=$latestArticleList item=latestArticle}
				{assign var=latestArticleObject value=$latestArticle->getActiveVersion()}
				{if $latestArticleObject->versionID == 0}
				<li class="sidebarBox box24">
					<a href="{link controller='Article' object=$latestArticleObject}{/link}" class="framed jsTooltip">{@$latestArticleObject->getAuthor()->getAvatar()->getImageTag(24)}</a>

					<hgroup class="sidebarBoxHeadline">
						<h1><a href="{link controller='Article' object=$latestArticle->getActiveVersion()}{/link}" class="wikiArticleTopicLink" data-project-id="{@$latestArticle->getActiveVersion()->articleID}" data-sort-order="DESC" title="{$latestArticle->getActiveVersion()->getTitle()}">{$latestArticle->getActiveVersion()->getTitle()}</a></h1>
						<h2><small>{if $latestArticleObject->userID}<a href="{link controller='User' object=$latestArticleObject->getAuthor()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$latestArticleObject->getDecoratedObject()->userID}">{$latestArticleObject->username}</a>{else}{$latestArticleObject->username}{/if} - {@$latestArticleObject->time|time}</small></h2>
					</hgroup>
				</li>
				{/if}
			{/foreach}
		{/content}
	</ul>
{/hascontent}