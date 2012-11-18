{if $findArticles|isset}
	{if $i == 0}
		<div class="marginTop tabularBox shadow wpbtBugList">
			<table class="table">
				<thead>
					<tr>
						<th colspan="2" class="columnTitle columnTopic">{lang}wiki.article.subject{/lang}</th>
						<th class="columnDigits columnComments">{lang}wiki.article.comments{/lang}</th>
						<th class="columnText columnLastPostTime">{lang}wiki.article.lastPostTime{/lang}</th>
					</tr>
				</thead>

				<tbody>
	{/if}
				{assign var=article value=$item.message}
				{assign var=articleID value=$article->articleID}
				<tr>
					<td class="columnIcon columnAvatar">
					{*TODO*}
						{if $article->getAuthor()->getAvatar()}
							<div>
								<p class="framed">{@$article->getAuthor()->getAvatar()->getImageTag(32)}</p>
							</div>
						{/if}
					</td>
					<td class="columnText columnTopic">
						<h1><a title="{$article->subject}" data-article-id="{@$article->articleID}" class="wikiSubjectLink" href="{link application='wiki' controller='Article' object=$article}{/link}">{@$article->subject}</a></h1>

						<small>
							{lang}wiki.category.article.postBy{/lang} <a class="userLink" data-user-id="{@$article->userID}" href="{* link controller='User' object=$article->getUserProfile()->getDecoratedObject()}{/link *}">{$article->username}</a> - {@$article->time|time}
						</small>
					</td>
					<td class="columnComments columnIcon" style="text-align: center;">
						{#$article->comments}
					</td>
					<td class="columnLastPostTime columnText">
						{@$article->time|time}
					</td>
				</tr>

	{if $i == $length - 1}
				</tbody>
			</table>
		</div>
	{/if}

{else}
	{* <article class="message messageReduced marginTop shadow">
		<div>
			<section class="messageContent">
				<div>
					<header class="messageHeader">
						<p class="messageCounter"><a href="{link controller='Article'}articleID={@$item.message->articleID}{/link}" title="{lang}wpbt.bug.permalink{/lang}" class="button jsTooltip">{#$startIndex}</a></p>

						<div class="messageCredits box32">
							{if $item.message->getUserProfile()->getAvatar()}
								<a href="{link controller='User' object=$item.message->getUserProfile()}{/link}" class="framed">{@$item.message->getUserProfile()->getAvatar()->getImageTag(32)}</a>
							{/if}
							<div>
								<p><a href="{link controller='User' object=$item.message->getUserProfile()}{/link}">{$item.message->username}</a><p>

								{@$item.message->time|time}
							</div>
						</div>

						{if $item.message->topic}<h1 class="messageTitle"><a href="{link controller='Bug'}bugID={@$item.message->bugID}&highlight={$query|urlencode}{/link}">{$item.message->topic}</a></h1>{/if}
					</header>

					<div class="messageBody">
						<div>
							{@$item.message->getFormattedMessage()}
						</div>

						<footer class="contentOptions clearfix">
							<nav class="breadcrumbs marginTop">
								<ul>
									<li><a href="{link controller='Product' object=$item.message->getProduct()}{/link}" title="{$item.message->getProduct()->title|language}"><span>{$item.message->getBoard()->title|language}</span></a> <span class="pointer"><span>&raquo;</span></span></li>
								</ul>
							</nav>

							<nav>
								<ul class="smallButtons">
									<li class="toTopLink"><a href="{@$__wcf->getAnchor('top')}" title="{lang}wcf.global.scrollUp{/lang}" class="button jsTooltip"><img src="{icon}circleArrowUp{/icon}" alt="" /> <span class="invisible">{lang}wcf.global.scrollUp{/lang}</span></a></li>
								</ul>
							</nav>
						</footer>
					</div>
				</div>
			</section>
		</div>
	</article> *}
{/if}