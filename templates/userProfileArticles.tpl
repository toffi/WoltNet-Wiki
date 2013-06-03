{hascontent}
<ul id="searchResultArticles"
	class="containerList searchResultArticleList">{content} {include
	file='__searchResultArticleList' application='wiki'
	objects=$userArticleList} {/content}
</ul>
{hascontentelse}
<div class="containerPadding">
	<p class="info">{lang}wcf.user.profile.userArticles.noneAvailable{/lang}</p>
</div>
{/hascontent}
