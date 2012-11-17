<dl>
	<dt><label for="searchCategories">{lang}wiki.search.categories{/lang}</label></dt>
	<dd>
		<select id="searchCategories" name="categoryIDs[]" multiple="multiple" size="10">
			<option value="*"{if $selectAllCategories} selected="selected"{/if}>{lang}wiki.search.categories.all{/lang}</option>
			<option value="-">--------------------</option>


			{include file='categorySelect' categories=$categoryList}
			{*foreach from=$categoryList item=categoryItem}
				<option value="{@$categoryItem->categoryID}">{$categoryItem->categoryName|language}</option>
			{/foreach*}
		</select>
		<small>{lang}wcf.global.multiSelect{/lang}</small>
	</dd>
</dl>