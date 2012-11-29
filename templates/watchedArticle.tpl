<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.User.ObjectWatch.Subscribe();
	});
	//]]>
</script>
<article class="message messageReduced marginTop">
	<div>
		<section class="messageContent">
			<div>
				<header class="messageHeader">
					<p class="messageCounter"><a title="{lang}wcf.user.watchedObjects.unsubscribe{/lang}" class="jsSubscribeButton jsTooltip" data-object-type="com.woltnet.wiki.article" data-object-id="{@$watchedObject->articleID}" data-subscribed="1"><img src="{icon}bookmarkColored{/icon}" class="icon16" alt="" /> <span class="invisible">{lang}wcf.user.watchedObjects.unsubscribe{/lang}</span></a></p>
					
					<div class="messageCredits box32">
						{if $watchedObject->getUserProfile()->getAvatar()}
							<a href="{link controller='User' object=$watchedObject->getUserProfile()}{/link}" class="framed">{@$watchedObject->getUserProfile()->getAvatar()->getImageTag(32)}</a>
						{/if}
						<div>
							<p><a href="{link controller='User' object=$watchedObject->getUserProfile()}{/link}" class="userLink" data-user-id="{@$watchedObject->userID}">{$watchedObject->username}</a><p>
							
							{@$watchedObject->time|time}
						</div>
					</div>
					
					<h1 class="messageTitle"><a href="{link controller='Article' object=$watchedObject}{/link}">{$watchedObject->getTitle()}</a></h1>
				</header>
				
				<div class="messageBody">
					<div>
						{@$watchedObject->getExcerpt()}
					</div>
					
					<footer class="messageOptions clearfix">
						<nav class="breadcrumbs marginTop">
							<ul>
								<li><a href="{link controller='Index'}{/link}" title="{lang}wiki.index.title{/lang}"><span>{lang}wiki.index.title{/lang}</span></a> <span class="pointer"><span>&raquo;</span></span></li>
								{foreach from=$watchedObject->getCategory()->getParentCategories() item=$categoryItem}
									<li><a href="{link controller='Category' object=$categoryItem}{/link}" title="{$categoryItem->getTitle()}"><span>{$categoryItem->getTitle()}</span></a> <span class="pointer"><span>&raquo;</span></span></li>
								{/foreach}
								<li><a href="{link controller='Article' object=$watchedObject}{/link}" title="{$watchedObject->getTitle()}"><span>{$watchedObject->getTitle()}</span></a> <span class="pointer"><span>&raquo;</span></span></li>
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