{if $objects}
	<div class="marginTop tabularBox tabularBoxTitle shadow messageGroupList articleList jsClipboardContainer" data-type="com.woltnet.wiki.article">
		<hgroup>
			<h1>{lang}wiki.article.articles{/lang} <span class="badge badgeInverse">{#$objects|count}</span></h1>
		</hgroup>

		<table class="table">
			<thead>
				<tr>
					<th class="columnMark"><label><input type="checkbox" class="jsClipboardMarkAll" /></label></th>
					<th colspan="2" class="columnTitle columnSubject{if $sortField == 'subject'} active{/if}"><a href="{link controller='Category' object=$category}filter={@$filter}&pageNo={@$pageNo}&sortField=subject&sortOrder={if $sortField == 'subject' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wiki.category.article.articleName{/lang}{if $sortField == 'subject'} <img src="{icon}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnDigits columnComments{if $sortField == 'comments'} active{/if}"><a href="{link controller='Category' object=$category}filter={@$filter}&pageNo={@$pageNo}&sortField=comments&sortOrder={if $sortField == 'comments' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wiki.category.article.comments{/lang}{if $sortField == 'comments'} <img src="{icon}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnDigits columnAuthor{if $sortField == 'username'} active{/if}"><a href="{link controller='Category' object=$category}filter={@$filter}&pageNo={@$pageNo}&sortField=username&sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wiki.category.article.author{/lang}{if $sortField == 'username'} <img src="{icon}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
					<th class="columnText columnLastPost{if $sortField == 'time'} active{/if}"><a href="{link controller='Category' object=$category}filter={@$filter}&pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wiki.category.article.time{/lang}{if $sortField == 'time'} <img src="{icon}sort{@$sortOrder}{/icon}" alt="" />{/if}</a></th>
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=article}
					<tr class="article{if $article->isNew()} new{/if}" data-article-id="{@$article->articleID}" data-label-ids="[ {implode from=$article->getAssignedLabels() item=label}{@$label->labelID}{/implode} ]">
						<td class="columnMark">
							<label><input type="checkbox" class="jsClipboardItem" data-object-id="{@$article->articleID}" /></label>
						</td>
						<td class="columnIcon columnAvatar">
							{if $article->getUserProfile()->getAvatar()}
								<div>
									<p class="framed"><img src="{icon}documentColored{/icon}" class="icon32"></p>
								</div>
							{/if}
						</td>
						<td class="columnText columnSubject">
							<h1>
								{hascontent}
									<ul class="labelList">
										{content}
											{foreach from=$article->getAssignedLabels() item=label}
												<li><a href="{link controller='Category' object=$category}{if $filter}filter={@$filter}{/if}&sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}&labelID={@$label->labelID}{/link}" class="badge label{if $label->cssClassName} {@$label->cssClassName}{/if}">{$label->label}</a></li>
											{/foreach}
										{/content}
									</ul>
								{/hascontent}

								<a href="{link controller='Article' object=$article}{/link}" class="articleLink messageGroupLink" data-article-id="{@$article->articleID}">{$article->getTitle()}</a>
							</h1>

							<small>
								<a href="{link controller='User' object=$article->getUserProfile()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$article->userID}">{$article->username}</a>
								- {@$article->time|time}
								- <a class="jsArticleInlineEditor">{lang}wcf.global.button.edit{/lang}</a>
							</small>
						</td>
						<td class="columnDigits columnComments"><p>{#$article->comments}</p></td>
						<td class="columnDigits columnUsername"><p>{$article->username}</p></td>
						<td class="columnText columnLastPost">{@$article->time|time}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<div class="container marginTop containerPadding">{lang}wiki.article.noneAvailable{/lang}</div>
{/if}