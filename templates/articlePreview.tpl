{assign var="user" value=$article->getAuthor()}
<div class="box128 articlePreview">
	<a href="{link controller='User' object=$user->getDecoratedObject()}{/link}" title="{$user->getDecoratedObject()->username}">{@$user->getAvatar()->getImageTag(128)}</a>

    <div class="articleInformation">
		{@$article->getExcerpt()}
	</div>
</div>