{assign var="user" value=$article->getUserProfile()->getDecoratedObject()}
<div class="box128 articlePreview">
	<a href="{link controller='User' object=$user}{/link}" title="{$user->username}">{@$user->getAvatar()->getImageTag(128)}</a>

	<div class="articleInformation">
		{$article->getExcerpt()}
	</div>
</div>