{if
$__wcf->session->getPermission('user.wiki.category.write.canSuggestCategories')}
<li id="categorySuggest"><a id="categorySuggestLink"
	href="{link application='wiki' controller='CategorySuggestionAdd'}{/link}"
	title="{lang}wiki.category.categorySuggestionAdd{/lang}"
	class="jsTooltip"><span class="icon icon16 icon-folder-open"></span><span
		class="invisible">{lang}wiki.category.categorySuggestionAdd{/lang}</span></a></li>
{/if}
