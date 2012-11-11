{include file='header' pageTitle='wiki.acp.category.list'}

{if $categoryList|count}
	<script type="text/javascript">
		//<![CDATA[
		$(function() {		
			{if $__wcf->session->getPermission('admin.category.canDeleteCategory')}
				new WCF.ACP.Category.Delete('wiki\\data\\category\\CategoryAction', $('.jsCategory'));
			{/if}
			{if $__wcf->session->getPermission('admin.category.canEditCategory')}
				{if $categoryList|count > 1}
					var sortableNodes = $('.sortableNode');
					sortableNodes.each(function(index, node) {
						$(node).wcfIdentify();
					});
					
					new WCF.Sortable.List('categoryList', 'wiki\\data\\category\\CategoryAction', 0);
				{/if}
			{/if}
		});
		//]]>
	</script>
{/if}

<header class="box48 boxHeadline">
	<hgroup>
		<h1>{lang}wiki.acp.category.list{/lang}</h1>
		<h2>{lang}wiki.acp.category.subtitle{/lang}</h2>
	</hgroup>
</header>

{hascontent}
	<div class="contentNavigation">
		<nav>
			<ul>
				{content}
					{*if $__wcf->session->getPermission('admin.category.canAddCategory')*}
						<li><a href="{link controller='CategoryAdd'}{/link}" title="{lang}wiki.acp.category.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add.svg" alt="" class="icon24" /> <span>{lang}wiki.acp.category.add{/lang}</span></a></li>
					{*/if*}
					
					{event name='contentNavigationButtons'}
				{/content}
			</ul>
		</nav>
	</div>
{/hascontent}

{if $categoryList|count}
	<section id="categoryList" class="container containerPadding marginTop shadow{if $__wcf->session->getPermission('canEditCategory') && $categoryList|count > 1} sortableListContainer{/if}">
		<ol class="categoryList sortableList" data-object-id="0">
			{assign var=oldDepth value=0}
			{foreach from=$categoryList item=category}
				{section name=i loop=$oldDepth-$category->getDepth()}</ol></li>{/section}
				
				<li class="{if $__wcf->session->getPermission('canEditCategory') && $categoryList|count > 1}sortableNode {/if}jsCategory" data-object-id="{@$category->categoryID}">
					<span class="sortableNodeLabel">
						<span class="buttons">
							{if $__wcf->session->getPermission('admin.category.canEditCategory')}
								<a href="{link controller='CategoryEdit' object=$category}{/link}"><img src="{@$__wcf->getPath()}icon/edit.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 jsTooltip" /></a>
							{else}
								<img src="{@$__wcf->getPath()}icon/edit.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 disabled" />
							{/if}

							{if $__wcf->session->getPermission('admin.category.canDeleteCategory')}
								<img src="{@$__wcf->getPath()}icon/delete.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 jsDeleteButton jsTooltip" data-object-id="{@$category->categoryID}" data-confirm-message="{lang}wiki.acp.category.delete.sure{/lang}" />
							{else}
								<img src="{@$__wcf->getPath()}icon/delete.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 disabled" />
							{/if}

							{event name='buttons'}
						</span>

						<span class="title">
							{$category->getTitle()}
						</span>
					</span>
					
					<ol class="categoryList sortableList" data-object-id="{@$category->categoryID}">
				{if !$category->getChildren()}
					</ol></li>
				{/if}
				{assign var=oldDepth value=$category->getDepth()}
			{/foreach}
			{section name=i loop=$oldDepth}</ol></li>{/section}
		</ol>
		
		{if $__wcf->session->getPermission('admin.category.canEditCategory') && $categoryList|count > 1}
			<div class="formSubmit">
				<button class="button default" data-type="submit">{lang}wcf.global.button.save{/lang}</button>
			</div>
		{/if}
	</section>
		
	{hascontent}
		<div class="contentNavigation">
			<nav>
				<ul>
					{content}
						{if $__wcf->session->getPermission('admin.category.canAddCategory')}
							<li><a href="{link controller='CategoryAdd'}{/link}" title="{lang}wiki.acp.category.add{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/add.svg" alt="" class="icon24" /> <span>{lang}wiki.acp.category.add{/lang}</span></a></li>
						{/if}
					
						{event name='contentNavigationButtons'}
					{/content}
				</ul>
			</nav>
		</div>
	{/hascontent}
{else}
	<p class="info">{lang}wiki.acp.category.noneAvailable{/lang}</p>
{/if}

{include file='footer'}