{if $categoryList|count == 0}
	<div class="container marginTop containerPadding">{lang}wiki.category.noCategory{/lang}</div>
{else}
	<div class="wikiCategoryListIndex marginTop">
		{include file='categoryNodeList'}
	</div>
{/if}