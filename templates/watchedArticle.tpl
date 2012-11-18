<div class="box48">
	<div>
		<p class="framed">{@$watchedObject->getUserProfile()->getAvatar()->getImageTag(48)}</p>
	</div>

	<div>
		<hgroup class="containerHeadline">
			<h1>
				<a href="{link controller='Article' application='wiki' object=$watchedObject}{/link}" class="wikiArticleTopicLink" data-article-id="{@$watchedObject->articleID}">{$watchedObject->getTitle()}</a>
			</h1>
			<h2><small>
				{if $watchedObject->userID}<a href="{link controller='User' object=$watchedObject->getUserProfile()}{/link}" class="userLink" data-user-id="{@$watchedObject->userID}">{$watchedObject->username}</a>{else}{$watchedObject->username}{/if}
				- {@$watchedObject->time|time}
			</small></h2>
		</hgroup>
	</div>
</div>