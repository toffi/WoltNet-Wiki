{include file='header' pageTitle='wiki.acp.category.list'}

{if $categoryTree->getTree()|count}
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			{if $__wcf->session->getPermission('admin.category.canDeleteCategory')}
				new WCF.ACP.Category.Delete('wiki\\data\\category\\CategoryAction', $('.jsCategory'));
			{/if}
			{if $__wcf->session->getPermission('admin.category.canEditCategory')}
				new WCF.Action.Toggle('wiki\\data\\category\\CategoryAction', $('.jsCategory'), '> .buttons > .jsToggleButton');
				
				{if $categoryNodeList|count > 1}
					var sortableNodes = $('.sortableNode');
					sortableNodes.each(function(index, node) {
						$(node).wcfIdentify();
					});
					
					new WCF.Sortable.List('categoryList', 'wiki\\data\\category\\CategoryAction', 0, {
						/**
						 * Updates the sortable nodes after a sorting is started with
						 * regard to their possibility to have child the currently sorted
						 * category as a child category.
						 */
						start: function(event, ui) {
							var sortedListItem = $(ui.item);
							var itemNestingLevel = sortedListItem.find('.sortableList:has(.sortableNode)').length;
							
							sortableNodes.each(function(index, node) {
								node = $(node);
								
								if (node.attr('id') != sortedListItem.attr('id')) {
									if (node.parents('.sortableList').length + itemNestingLevel >= {2 + 1}) {
										node.addClass('sortableNoNesting');
									}
									else if (node.hasClass('sortableNoNesting')) {
										node.removeClass('sortableNoNesting');
									}
								}
							});
						},
						/**
						 * Updates the sortable nodes after a sorting is completed with
						 * regard to their possibility to have child categories.
						 */
						stop: function(event, ui) {
							sortableNodes.each(function(index, node) {
								node = $(node);
								
								if (node.parents('.sortableList').length == {2 + 1}) {
									node.addClass('sortableNoNesting');
								}
								else if (node.hasClass('sortableNoNesting')) {
									node.removeClass('sortableNoNesting');
								}
							});
						}
					});
				{/if}
			{/if}
		});
		//]]>
	</script>
{/if}

<header class="box48 boxHeadline">
	<hgroup>
		<h1>{lang}wiki.acp.category.list{/lang}</h1>
	</hgroup>
</header>

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

{if $categoryTree|count}
	<section id="categoryList" class="container containerPadding marginTop shadow{if $__wcf->session->getPermission('admin.category.canEditCategory') && $categoryTree->getTree()|count > 1} sortableListContainer{/if}">
		<ol class="categoryList sortableList" data-object-id="0">
			{assign var=oldDepth value=0}
			{foreach from=$categoryTree->getTree() item=category}
				{section name=i loop=$oldDepth-$categoryTree->getMaxDepth()}</ol></li>{/section}
				
				<li class="{if $__wcf->session->getPermission('admin.category.canEditCategory') && $categoryTree->getTree()|count > 1}sortableNode {if $category->getCategory()->getDepth() == $categoryTree->getTree()->getMaxDepth()}sortableNoNesting {/if}{/if}jsCategory" data-object-id="{@$category->getCategory()->categoryID}">
					<span class="sortableNodeLabel">
						<span class="buttons">
							{if $__wcf->session->getPermission('admin.category.canEditCategory')}
								<a href="{link controller='CategoryEdit' object='$category->getCategory()'}{/link}"><img src="{@$__wcf->getPath()}icon/edit.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 jsTooltip" /></a>
							{else}
								<img src="{@$__wcf->getPath()}icon/edit.svg" alt="" title="{lang}wcf.global.button.edit{/lang}" class="icon16 disabled" />
							{/if}

							{if $__wcf->session->getPermission('admin.category.canDeleteCategory')}
								<img src="{@$__wcf->getPath()}icon/delete.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 jsDeleteButton jsTooltip" data-object-id="{@$category->categoryID}" data-confirm-message="{@$objectType->getProcessor()->getLanguageVariable('delete.sure')}" />
							{else}
								<img src="{@$__wcf->getPath()}icon/delete.svg" alt="" title="{lang}wcf.global.button.delete{/lang}" class="icon16 disabled" />
							{/if}

							{event name='buttons'}
						</span>

						<span class="title">
							{$category->getCategory()->getTitle()}
						</span>
					</span>
					
					<ol class="categoryList sortableList" data-object-id="{@$category->getCategory()->categoryID}">
				{if !$category->getCategory()->hasChildren()}
					</ol></li>
				{/if}
				{assign var=oldDepth value=$categoryTree->getMaxDepth()}
			{/foreach}
			{section name=i loop=$oldDepth}</ol></li>{/section}
		</ol>
		
		{if $__wcf->session->getPermission('admin.category.canEditCategory') && $categoryTree->getTree()|count > 1}
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