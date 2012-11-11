{include file='header' pageTitle='wiki.acp.category.list'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wiki.acp.category.list{/lang}</h1>
		<h2>{lang}wiki.acp.category.subtitle{/lang}</h2>
	</hgroup>
</header>

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='CategoryAdd'}{/link}" title="{lang}wiki.acp.menu.link.wiki.category.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add.svg" alt="" class="icon24" /> <span>{lang}wiki.acp.menu.link.wiki.category.add{/lang}</span></a></li>
		</ul>
	</nav>
</div>

{if $categoryList|count}
	<div id="categoryList" class="container containerPadding sortableListContainer marginTop shadow">
		<ol id="categoryContainer0" class="sortableList" data-object-id="0">
			{foreach from=$categoryList item=categoryItem}
				<li class="sortableNode jsCategory" data-object-id="{@$categoryItem->categoryID}">
					<span class="sortableNodeLabel">
						<img src="{@$__wcf->getPath()}icon/folder.svg" alt="" class="icon16" />

						<a href="{link controller='CategoryEdit' object=$categoryItem}{/link}">{$categoryItem->getTitle()}</a>

						<span class="statusDisplay sortableButtonContainer">
							{if $__wcf->session->getPermission('admin.category.canEditCategory')}
								<a href="{link controller='CategoryEdit' object=$categoryItem}{/link}"><img src="{@$__wcf->getPath()}icon/edit.svg" alt="" class="icon16" /></a>
							{else}
								<img src="{@$__wcf->getPath()}icon/edit.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 disabled" />
							{/if}

							{if $__wcf->session->getPermission('admin.category.canAddCategory')}
								<a href="{link controller='CategoryAdd'}parentID={@$categoryItem->categoryID}{/link}"><img src="{@$__wcf->getPath()}icon/add.svg" alt="" class="icon16" /></a>
							{else}
								<img src="{@$__wcf->getPath()}icon/enlarge.svg" alt="" title="{lang}wpbt.acp.product.button.status{/lang}" class="icon16 disabled" />
							{/if}

							{if $__wcf->session->getPermission('admin.category.canDeleteCategory')}
								<img src="{@$__wcf->getPath()}icon/delete.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 jsDeleteButton jsTooltip" data-object-id="{@$categoryItem->categoryID}" data-confirm-message="{lang}wiki.acp.category.delete.sure{/lang}" />
							{else}
								<img src="{@$__wcf->getPath()}icon/delete.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 disabled" />
							{/if}

							{event name='buttons'}
						</span>
					</span>

				<ol id="categoryContainer{@$categoryItem->categoryID}" class="sortableList" data-object-id="{@$categoryItem->categoryID}">{if !$categoryItem->getChildren()}</ol></li>{/if}
			{/foreach}
		</ol>

		{if $__wcf->session->getPermission('admin.category.canEditProject')}
			<div class="formSubmit">
				<button class="button buttonPrimary" data-type="submit">{lang}wcf.global.button.submit{/lang}</button>
			</div>
		{/if}
	</div>
{else}
	<p class="warning">{lang}wiki.acp.category.list.noneAvailable{/lang}</p>
{/if}

{include file='footer'}