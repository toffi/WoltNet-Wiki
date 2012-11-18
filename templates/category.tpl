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
			new WIKI.Article.Label.Manager('{link controller='Category' object=$category}{if $filter}filter={@$filter}{/if}&sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}{/link}');
			
			WCF.Icon.addObject({
				'wcf.icon.closed': '{icon}arrowRightColored{/icon}',
				'wcf.icon.opened': '{icon}arrowDownColored{/icon}'
			});

			WCF.Collapsible.Simple.init();
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='categorySidebar'}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{$category->getTitle()}</h1>
		{hascontent}<h2>{content}{$category->description|language}{/content}</h2>{/hascontent}
	</hgroup>
</header>

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='Category' object=$category link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}

	<nav>
		<ul>
			{if $category->getPermission('canAddArticle')}
				<li><a href="{link application='wiki' controller='ArticleAdd' object=$category}{/link}" title="{lang}wiki.global.button.articleAdd{/lang}" class="button"><img src="{icon size='M'}asterisk{/icon}" alt="" class="icon24" /> <span>{lang}wiki.global.button.articleAdd{/lang}</span></a></li>
			{/if}
			{event name='largeButtonsTop'}
		</ul>
	</nav>
</div>

{hascontent}
<div class="wikiCategoryListIndex marginTop">
	{content}{include file='categoryNodeList'}{/content}
</div>
{/hascontent}

{include file='categoryArticleList'}

<div class="contentNavigation">
	{@$pagesLinks}
	
	<div class="jsClipboardEditor" data-types="[ 'com.woltnet.wiki.article' ]"></div>
</div>

{include file='footer'}

</body>
</html>
