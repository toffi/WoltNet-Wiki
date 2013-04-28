<article class="message dividers marginTop" style="display: inline-block;">
	<div>
		<section class="messageContent">
			<div>
				<div class="messageBody">
					<div class="messageText" style="border: 0;">
						{@$article->getFormattedMessage()}
					</div>

					<footer class="messageOptions contentOptions marginTop clearfix">
						<nav>
							<ul class="smallButtons buttonGroup">
								<li class="jsQuoteMessage" data-object-id="{@$article->articleID}" data-is-quoted="{if $__quoteFullQuote|isset && $article->articleID|in_array:$__quoteFullQuote}1{else}0{/if}"><a title="{lang}wiki.global.button.quote{/lang}" class="button jsTooltip{if $__quoteFullQuote|isset && $article->articleID|in_array:$__quoteFullQuote} active{/if}"><span class="icon icon16 icon-comment"></span> <span class="invisible">{lang}wiki.article.quote.quoteArticle{/lang}</span></a></li>
								{if $article->userID != $__wcf->getUser()->userID}<li class="jsReportArticle" data-object-id="{@$article->articleID}"><a title="{lang}wiki.global.button.report{/lang}" class="button jsTooltip"><span class="icon icon16 icon-warning-sign"></span></a></li>{/if}
								{if !$article->isActive && $article->getModeratorPermission('canActivateArticle')}<li><a href="{link application='wiki' controller='ArticleActivate' object=$article}{/link}" class="button jsTooltip" title="{lang}wiki.global.button.activate{/lang}"><span class="icon icon16 icon-ok"></span></a></li>{/if}
								{if $article->isEditable()}<li><a href="{link application='wiki' controller='ArticleEdit' object=$article}{/link}" class="button jsTooltip" title="{lang}wiki.global.button.edit{/lang}"><span class="icon icon16 icon-pencil"></span><span>{lang}wiki.global.button.edit{/lang}</span></a></li>{/if}
								{if $article->isTrashable()}<li><a href="{link application='wiki' controller='ArticleTrash' object=$article}{/link}" class="button jsTooltip" title="{lang}wiki.global.button.trash{/lang}"><span class="icon icon16 icon-trash"></span><span>{lang}wiki.global.button.trash{/lang}</span></a></li>{/if}
							</ul>
						</nav>
					</footer>
				</div>
			</div>
		</section>
	</div>
</article>