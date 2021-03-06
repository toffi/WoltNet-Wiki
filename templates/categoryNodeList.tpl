{foreach from=$categoryList item=categoryItem}
<div class="wikiCategoryListIndex container marginTop shadow">
	<ul class="containerList">
		<li class="box48" data-category-id="{@$categoryItem->categoryID}">
			<span class="icon icon48 icon-folder-close-alt"></span>
			<div>
				<div class="containerHeadline">
					<h1>
						<a title="{$categoryItem->getTitle()}"
							href="{link application='wiki' controller='Category' id=$categoryItem->categoryID title=$categoryItem->title|language}{/link}">{$categoryItem->getTitle()}</a>
						({#$categoryItem->getArticles()|count}) {if
						$categoryItem->getUnreadArticles()|count > 0}<span class="badge">{$categoryItem->getUnreadArticles()|count}</span>{/if}
					</h1>
					{hascontent}
					<h2 class="wikiCategoryDescription">{content}{$categoryItem->description|language}{/content}</h2>
					{/hascontent} {* Subcategorys *} {if $categoryItem->hasChildren()}
					<ul class="subCategory">
						{implode
						from=$categoryItem->getChildCategories(WIKI_CATEGORY_LIST_DEPTH -
						1) item=subCategoryItem}
						<li data-category-id="{@$subCategoryItem->categoryID}"><span
							class="icon icon16 icon-folderColored"></span> <a
							href="{link application='wiki' controller='Category' id=$subCategoryItem->categoryID title=$subCategoryItem->title|language}{/link}">{$subCategoryItem->title|language}</a>
							({#$subCategoryItem->getArticles()|count}) {if
							$subCategoryItem->getUnreadArticles()|count > 0}<span
							class="badge badgeUpdate">{$subCategoryItem->getUnreadArticles()|count}</span>{/if}
						</li> {/implode}
					</ul>
					{/if}
				</div>
			</div>
		</li>
	</ul>
</div>
{/foreach}
