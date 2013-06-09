{if $__wiki->isActiveApplication() && $__searchAreaInitialized|empty}
    {capture assign='__searchInputPlaceholder'}
        {if $category|isset}
            {lang}wiki.category.searchCategory{/lang}{else}{lang}wiki.category.searchAllCategories{/lang}
        {/if}
    {/capture}
    {capture assign='__searchHiddenInputFields'}
        <input type="hidden" name="types[]" value="com.woltnet.wiki.article" />
        {if $category|isset}
            <input type="hidden" name="categoryIDs[]" value="{@$category->categoryID}" />
        {/if}
    {/capture}
{/if}