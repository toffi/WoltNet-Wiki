{include file='documentHeader'}

<head>
	<title>{$category->getTitle()} - {PAGE_TITLE|language}</title>

	{include file='headInclude'}
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Language.addObject({
				'wiki.article.edit.assignLabel': '{lang}wiki.article.edit.assignLabel{/lang}',
				'wiki.article.label.management': '{lang}wiki.article.label.management{/lang}',
				'wiki.article.label.management.addLabel.success': '{lang}wiki.article.label.management.addLabel.success{/lang}',
				'wiki.article.label.management.deleteLabel.confirmMessage': '{lang}wiki.article.label.management.deleteLabel.confirmMessage{/lang}',
				'wiki.article.label.management.editLabel': '{lang}wiki.article.label.management.editLabel{/lang}',
				'wiki.article.label.placeholder': '{lang}wiki.article.label.placeholder{/lang}',
			});

			WCF.Clipboard.init('wiki\\page\\CategoryPage', {@$hasMarkedItems}, { });

			var $editorHandler = new WIKI.Article.EditorHandler();
			var $inlineEditor = new WIKI.Article.InlineEditor('.article');
			$inlineEditor.setEditorHandler($editorHandler, 'list');

			new WIKI.Article.Clipboard($editorHandler);
			new WIKI.Article.Label.Manager('{link controller='Category' object=$category application='wiki'}{if $filter}filter={@$filter}{/if}&sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}{/link}');

			new WCF.User.ObjectWatch.Subscribe();

			WCF.Collapsible.Simple.init();
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{capture assign='headerNavigation'}
	{if @$__wcf->user->userID}
		{if $category->isWatched()}
			<li><a title="{lang}wcf.user.watchedObjects.unsubscribe{/lang}" class="jsSubscribeButton jsTooltip" data-object-type="com.woltnet.wiki.category" data-object-id="{@$category->categoryID}" data-subscribed="1"><span class="icon icon16 icon-bookmark"></span><span class="invisible">{lang}wcf.user.watchedObjects.unsubscribe{/lang}</span></a></li>
		{else}
			<li><a title="{lang}wcf.user.watchedObjects.subscribe{/lang}" class="jsSubscribeButton jsTooltip" data-object-type="com.woltnet.wiki.category" data-object-id="{@$category->categoryID}" data-subscribed="0"><span class="icon icon16 icon-bookmark-empty"></span><span class="invisible">{lang}wcf.user.watchedObjects.subscribe{/lang}</span></a></li>
		{/if}
	{/if}
{/capture}

{include file='categorySidebar' application='wiki'}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<h1>{$category->getTitle()}</h1>
	{hascontent}<h2>{content}{$category->description|language}{/content}</h2>{/hascontent}
</header>

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='Category' object=$category link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}

	<nav>
		<ul>
			{if $category->getPermission('canAddArticle')}
				<li><a href="{link application='wiki' controller='ArticleAdd' object=$category}{/link}" title="{lang}wiki.global.button.articleAdd{/lang}" class="button"><span class="icon icon24 icon-asterisk"></span><span>{lang}wiki.global.button.articleAdd{/lang}</span></a></li>
			{/if}
			{event name='largeButtonsTop'}
		</ul>
	</nav>
</div>

{hascontent}
<div class="wikiCategoryListIndex marginTop">
	{content}{include file='categoryNodeList' application='wiki'}{/content}
</div>
{/hascontent}

{include file='categoryArticleList' application='wiki'}

<div class="contentNavigation">
	{@$pagesLinks}

	<div class="jsClipboardEditor" data-types="[ 'com.woltnet.wiki.article' ]"></div>
</div>

{include file='footer'}

</body>
</html>
