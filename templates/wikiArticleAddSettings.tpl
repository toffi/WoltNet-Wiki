{if $__wcf->getSession()->getPermission('mod.wiki.article.canActivateArticle')}
	<dd>
		<label><input id="activateArticle" name="activateArticle" type="checkbox" value="1"{if $activateArticle} checked="checked"{/if} /> {lang}wiki.article.article{$action|ucfirst}.activate{/lang}</label>
		<small>{lang}wiki.article.article{$action|ucfirst}.activate.description{/lang}</small>
	</dd>
{/if}