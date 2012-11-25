{assign var=article value=$item[message]}
<article class="message messageReduced marginTop">
	<div>
		<section class="messageContent">
			<div>
				<header class="messageHeader">
					<p class="messageCounter"><a href="{link controller='Article' object=$article application='wiki'}{/link}" title="{lang}wiki.article.permalink{/lang}" class="button jsTooltip">{#$startIndex}</a></p>
					
					<div class="messageCredits box32">
						{if $article->getUserProfile()->getAvatar()}
							<a href="{link controller='User' object=$article->getUserProfile()}{/link}" class="framed">{@$article->getUserProfile()->getAvatar()->getImageTag(32)}</a>
						{/if}
						<div>
							<p><a href="{link controller='User' object=$article->getUserProfile()}{/link}" class="userLink" data-user-id="{@$article->userID}">{$article->username}</a><p>
							
							{@$item[message]->time|time}
						</div>
					</div>
					
					<h1 class="messageTitle"><a href="{link controller='Article' object=$article}highlight={$query|urlencode}{/link}">{$article->getTitle()}</a></h1>
				</header>
				
				<div class="messageBody">
					<div>
						{@$article->getExcerpt(255, true)}
					</div>
					
					<footer class="messageOptions clearfix">
						<nav class="breadcrumbs marginTop">
							<ul>
								<li><a href="{link controller='Index'}{/link}" title="{lang}wiki.index.title{/lang}"><span>{lang}wiki.index.title{/lang}</span></a> <span class="pointer"><span>&raquo;</span></span></li>
								{foreach from=$article->getCategory()->getParentCategories() item=$categoryItem}
									<li><a href="{link controller='Category' object=$categoryItem}{/link}" title="{$categoryItem->getTitle()}"><span>{$categoryItem->getTitle()}</span></a> <span class="pointer"><span>&raquo;</span></span></li>
								{/foreach}
								<li><a href="{link controller='Article' object=$article}highlight={$query|urlencode}{/link}" title="{$article->getTitle()}"><span>{$article->getTitle()}</span></a> <span class="pointer"><span>&raquo;</span></span></li>
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
</article>