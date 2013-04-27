{foreach from=$categoryList item=categoryItem}
	<div class="wikiCategoryListIndex container marginTop shadow{if $categoryItem->getUnreadArticles()} new{/if}">
		<ul class="containerList">
			<li class="box48" data-category-id="{@$categoryItem->categoryID}">
				<span class="icon icon48 icon-folder{if $categoryItem->getUnreadArticles()}New{/if}Colored {if $categoryItem->getUnreadArticles()} markAsRead{/if}"></span>
				<div>
					<div class="containerHeadline">
						<h1><a {if $categoryItem->getUnreadArticles()}class="wikiCategoryNew"{/if} title="{$categoryItem->getTitle()}" href="{link application='wiki' controller='Category' id=$categoryItem->categoryID title=$categoryItem->title|language}{/link}">{$categoryItem->getTitle()}</a> ({#$categoryItem->getArticles()}) {if $categoryItem->getUnreadArticles()}<span class="badge">{$categoryItem->getUnreadArticles()}</span>{/if}</h1>
						{hascontent}<h2 class="wikiCategoryDescription">{content}{$categoryItem->description|language}{/content}</h2>{/hascontent}


						{* Subcategorys *}
						{if $categoryItem->hasChildren()}
							<ul class="subCategory">
								{implode from=$categoryItem->getChildCategories(WIKI_CATEGORY_LIST_DEPTH - 1) item=subCategoryItem}
									<li data-category-id="{@$subCategoryItem->categoryID}"{if $subCategoryItem->getUnreadArticles()} class="new"{/if}>
										<span class="icon icon16 icon-folder{if $subCategoryItem->getUnreadArticles()}New{/if}Colored {if $subCategoryItem->getUnreadArticles()} markAsRead{/if}"></span>
										<a href="{link application='wiki' controller='Category' id=$subCategoryItem->categoryID title=$subCategoryItem->title|language}{/link}">{$subCategoryItem->title|language}</a> ({#$subCategoryItem->getArticles()})
										{if $subCategoryItem->getUnreadArticles()}<span class="badge badgeUpdate">{$subCategoryItem->getUnreadArticles()}</span>{/if}
									</li>
								{/implode}
							</ul>
						{/if}
					</div>
				</div>
			</li>
		</ul>
	</div>
{/foreach}