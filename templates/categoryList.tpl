{include file='documentHeader'}

<head>
<title>{lang}wiki.categoryList.title{/lang} -
	{PAGE_TITLE|language}</title> {include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

	{capture assign='sidebar'}
	<nav id="sidebarContent" class="sidebarContent">
		{if $__boxSidebar|isset && $__boxSidebar}
		<ul>{@$__boxSidebar}
		</ul>
		{/if}
	</nav>
	{/capture} {include file='header' sidebarOrientation='right'}

	<header class="boxHeadline">
		<h1>{PAGE_TITLE|language}</h1>
		{hascontent}
		<h2>{content}{PAGE_DESCRIPTION|language}{/content}</h2>
		{/hascontent}
	</header>

	{hascontent}
	<div class="marginTop container containerPadding wikiAnnouncement">
		<fieldset>
			<legend>{lang}wiki.categoryList.announcement{/lang}</legend>
			<p>{content}{@$wikiAnnouncement}{/content}</p>
		</fieldset>
	</div>
	{/hascontent}

	<section id="dashboard">{if
		$__boxContent|isset}{@$__boxContent}{/if}</section>

	{hascontent}
	<div class="contentNavigation">
		<nav>
			<ul>
				{content} {if $categoryList|count > 0 &&
				$__wcf->session->getPermission('user.wiki.article.write.canAddArticle')}
				<li><a
					href="{link application='wiki' controller='ArticleAdd'}{/link}"
					title="{lang}wiki.global.button.articleAdd{/lang}" class="button"><span
						class="icon icon24 icon-asterisk"></span> <span>{lang}wiki.global.button.articleAdd{/lang}</span></a></li>
				{/if} {event name='largeButtonsTop'} {/content}
			</ul>
		</nav>
	</div>
	{/hascontent} {if $categoryList|count == 0}
	<div class="container marginTop containerPadding">{lang}wiki.category.noneAvailable{/lang}</div>
	{else}
	<div class="wikiCategoryListIndex marginTop">{include
		file='categoryNodeList' application='wiki'}</div>
	{/if}

	<div class="container marginTop shadow">
		<ul class="containerList">
			{if WIKI_INDEX_ENABLE_STATS}
			<li class="box32"><span class="icon icon32 icon-bar-chart"></span>
				<div>
					<div class="containerHeadline">
						<h1>{lang}wiki.global.statistics{/lang}</h1>
						<h2>{lang}wiki.global.statistics.description{/lang}</h2>
					</div>
				</div></li> {/if}
		</ul>
	</div>

	{include file='footer'}

</body>
</html>
