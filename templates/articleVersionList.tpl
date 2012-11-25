<div id="version" class="container containerPadding tabMenuContent hidden">
		<div id="versionContainer" class="tabularBox tabularBoxTitle marginTop shadow">
			<hgroup>
				<h1>{lang}wiki.article.versions{/lang} <span class="badge" title="{lang}wiki.article.versionsCount{/lang}">CODE</span></h1>
			</hgroup>
			<table id="version1" class="table">
				<thead>
					<tr>
						<th><span class="emptyHead">{lang}wiki.article.author{/lang}</span></th>
						<th><span class="emptyHead">{lang}wiki.article.date{/lang}</span></th>
						<th><span class="emptyHead">{lang}wiki.global.options{/lang}</span></th>
					</tr>
				</thead>

				<tbody>
					{foreach from=$article->getVersions() item=$version}
					<tr class="jsBugRow">
						<td class="columnIcon columnAvatar">
							<div style="text-align: center;">
								<p class="framed">{@$version->getUserProfile()->getAvatar()->getImageTag(24)}</p>
								<p><a href="{link controller='User' object=$article->getUserProfile()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$article->userID}">{$article->username}</a></p>
							</div>
						</td>
						<td class="columnText"><a href="{link application='wiki' controller='Article' object=$version}{/link}" title="">{@$version->time|time}</td>
						<td class="columnText"><a href="{link application='wiki' controller='Article' object=$version}{/link}" title="">
							<ul>
								{if !$version->isActive && $version->getmoderatorPermission('canReadDeacticatedArticle')}<li><a href="{link application='wiki' controller='Article' object=$version}{/link}"><span>{lang}wiki.global.button.view{/lang}</span></a></li>{/if}
								{if !$version->isActive && $version->getModeratorPermission('canActivateArticle')}<li><a href="{link application='wiki' controller='ArticleActivate' object=$version}{/link}"><span>{lang}wiki.global.button.restore{/lang}</span></a></li>{/if}
								{if $version->getModeratorPermission('canTrashArticle')}<li><a href="{link application='wiki' controller='ArticleTrash' object=$version}{/link}"><span>{lang}wiki.global.button.trash{/lang}</span></a></li>{/if}
							</ul>
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>