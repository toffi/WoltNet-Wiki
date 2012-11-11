{foreach from=$categoryList item=categoryItem}
	<div class="wikiCategory container marginTop shadow{*if $categoryItem->getUnreadArticles()} new{/if*}">
		<ul class="containerList">
			<li class="box48" data-category-id="{@$categoryItem->categoryID}">
				<img src="{icon}folder{*if $categoryItem->getUnreadArticles()}New{/if*}Colored{/icon}" alt="" class="icon48 {*if $categoryItem->getUnreadArticles()} markAsRead{/if*}" />
				<div>
					<hgroup class="containerHeadline">
						<h1><a {*if $categoryItem->getUnreadArticles()}class="wikiCategoryNew"{/if*} title="{$categoryItem->getTitle()}" href="{link application='wiki' controller='Category' object=$categoryItem}{/link}">{$categoryItem->getTitle()}</a> ({#$categoryItem->getArticles()}) {*if $categoryItem->getUnreadArticles()}<span class="badge">{$categoryItem->getUnreadArticles()}</span>{/if*}</h1>
						<h2 class="wikiCategoryDescription">{if $categoryItem->allowDescriptionHtml}{@$categoryItem->description|language}{else}{$categoryItem->description|language}{/if}</h2>
					
					
						{* Subcategorys *}
						{if $categoryItem->getSubCategories(WIKI_CATEGORY_LIST_DEPTH)|count}
							<ul class="subCategory">
								{implode from=$categoryItem->getSubCategories(WIKI_CATEGORY_LIST_DEPTH) item=subCategoryItem}
									<li data-category-id="{@$subCategoryItem->categoryID}"{*if $subCategoryItem->getUnreadArticles()} class="new"{/if*}>
										<img src="{icon}folder{*if $subCategoryItem->getUnreadArticles()}New{/if*}Colored{/icon}" alt="" class="icon16{*if $subCategoryItem->getUnreadArticles()} markAsRead{/if*}" /> 
										<a href="{link application='wiki' controller='Category' object=$subCategoryItem}{/link}">{$subCategoryItem->getTitle()}</a> ({#$subCategoryItem->getArticles()})
										{*if $subCategoryItem->getUnreadArticles()}<span class="badge badgeUpdate">{$subCategoryItem->getUnreadArticles()}</span>{/if*}
									</li>
								{/implode}
							</ul>
						{/if}
					</hgroup>
				</div>
			</li>
		</ul>
	</div>
{/foreach}