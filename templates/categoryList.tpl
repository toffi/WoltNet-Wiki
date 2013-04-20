{if $categoryList|count == 0}
	<div class="container marginTop containerPadding">{lang}wiki.category.noneAvailable{/lang}</div>
{else}
	<div class="wikiCategoryListIndex marginTop">
		{include file='categoryNodeList' application='wiki'}
	</div>
{/if}