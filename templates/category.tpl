{include file='documentHeader'}

<head>
	<title>{$category->getTitle()} - {PAGE_TITLE|language}</title>

	{include file='headInclude'}
	<script type="text/javascript" src="{@$__wcf->getPath('wiki')}js/WIKI.Article.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
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

{include file='sidebar'}

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

<div class="wikiCategoryListIndex marginTop">
	{include file='categoryList'}
</div>
{include file='categoryArticleList'}

<div class="contentNavigation">
	{@$pagesLinks}
</div>

{include file='footer'}

</body>
</html>
