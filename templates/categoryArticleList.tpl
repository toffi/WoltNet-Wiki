{if $objects|count}
	<div class="marginTop tabularBox tabularBoxTitle shadow messageGroupList articleList jsClipboardContainer" data-type="com.woltnet.wiki.article">
		<header>
			<h1>{lang}wiki.article.articles{/lang} <span class="badge badgeInverse">{#$objects|count}</span></h1>
		</header>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnMark jsOnly"><label><input type="checkbox" class="jsClipboardMarkAll" /></label></th>
					<th colspan="2" class="columnTitle columnSubject{if $sortField == 'subject'} active {@$sortOrder}{/if}"><a href="{link controller='Category' application='wiki' object=$category}filter={@$filter}pageNo={@$pageNo}&sortField=subject&sortOrder={if $sortField == 'subject' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wiki.category.article.articleName{/lang}</a></th>
					<th class="columnDigits columnComments{if $sortField == 'comments'} active {@$sortOrder}{/if}"><a href="{link controller='Category' application='wiki' object=$category}filter={@$filter}&pageNo={@$pageNo}&sortField=comments&sortOrder={if $sortField == 'comments' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wiki.category.article.comments{/lang}</a></th>
					<th class="columnDigits columnAuthor{if $sortField == 'username'} active {@$sortOrder}{/if}"><a href="{link controller='Category' application='wiki' object=$category}filter={@$filter}&pageNo={@$pageNo}&sortField=username&sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wiki.category.article.author{/lang}</a></th>
					<th class="columnText columnLastPost{if $sortField == 'time'} active {@$sortOrder}{/if}"><a href="{link controller='Category' application='wiki' object=$category}filter={@$filter}&pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wiki.category.article.time{/lang}</a></th>
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=article}
					<tr class="article jsClipboardObject{if $article->isNew()} new{/if}" data-article-id="{@$article->articleID}" data-category-id="{@$article->categoryID}" data-label-ids="[ {implode from=$article->getAssignedLabels() item=label}{@$label->labelID}{/implode} ]">
						<td class="columnMark jsOnly">
							<label><input type="checkbox" class="jsClipboardItem" data-object-id="{@$article->articleID}" /></label>
						</td>
						<td class="columnIcon columnAvatar">
							<div>
								<p class="framed"><span class="icon icon32 icon-file"></span></p>
							</div>
						</td>
						<td class="columnText columnSubject">
							<div class="statusDisplay">
								<ul class="statusIcons">
									{if $article->getLanguage()|is_object}
										<li><img src="{@$article->getLanguage()->getIconPath()}" class="icon24" /></li>
									{/if}
								</ul>
							</div>
							
							<h1>
								{hascontent}
									<ul class="labelList">
										{content}
											{foreach from=$article->getAssignedLabels() item=label}
												<li><a href="{link controller='Category' application='wiki' object=$category}{if $filter}filter={@$filter}{/if}&sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}&labelID={@$label->labelID}{/link}" class="badge label{if $label->cssClassName} {@$label->cssClassName}{/if}">{$label->label}</a></li>
											{/foreach}
										{/content}
									</ul>
								{/hascontent}
								
								<a href="{link controller='Article' application='wiki' object=$article}{/link}" class="articleLink messageGroupLink framed" data-article-id="{@$article->articleID}">{$article->getTitle()}</a>
							</h1>
							
							<small>
								<a href="{link controller='User' object=$article->getDecoratedObject()->getActiveVersion()->getAuthor()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$article->getDecoratedObject()->getActiveVersion()->getUserID}">{$article->getDecoratedObject()->getActiveVersion()->getUsername()}</a>
								- {@$article->time|time}
								- <a class="jsOnly jsArticleInlineEditor articleEditLink">{lang}wcf.global.button.edit{/lang}</a>
							</small>
						</td>
						<td class="columnDigits columnComments"><p><a href="{link controller='Article' application='wiki' object=$article}#discuss{/link}">{#$article->getCommentList()|count}</a></p></td>
						<td class="columnDigits columnUsername"><p><a href="{link controller='User' object=$article->getAuthor()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$article->userID}">{$article->username}</a></p></td>
						<td class="columnText columnLastPost">{@$article->time|time}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<div class="container marginTop containerPadding">{lang}wiki.article.noneAvailable{/lang}</div>
{/if}