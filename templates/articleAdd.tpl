{include file='documentHeader'}

<head>
	<title>{lang}wiki.article.article{$action|ucfirst}{/lang} - {PAGE_TITLE|language}</title>

	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wiki.article.article{$action|ucfirst}{/lang}</h1>
	</hgroup>
</header>
{if $__wcf->session->getPermission('mod.wiki.article.canManagePermissions')}
{include file='aclPermissions'}

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.ACL.List($('#articlePermissions'), {@$aclObjectTypeID}{if $groupID|isset}, '', {@$groupID}{/if});
	});
	//]]>
</script>
{/if}

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}


<form method="post" action="{if $action == 'add'}{link controller='ArticleAdd'}{/link}{else}{link controller='ArticleEdit'}{/link}{/if}" id="article{$action|ucfirst}Form">
	{if $articleID|isset}<input type="hidden" name="articleID" value="{$articleID}" />{/if}
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>
				{lang}wiki.article{$action|ucfirst}.data{/lang}
			</legend>
			{if !$__wcf->getUser()->userID}
				<dl>
					<dt>
						<label for="username">
							{lang}wiki.article{$action|ucfirst}.username{/lang}
						</label>
					</dt>
					<dd>
						<input type="text" id="username" name="username" value="{if $username|isset}{$username}{/if}" required="required" class="medium" />
						{if $errorField == 'username'}
							<small class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
								{if $errorType == 'notFound'}{lang}wcf.user.error.username.notFound{/lang}{/if}
							</small>
						{/if}
					</dd>
				</dl>
			{/if}
			{if $action == 'add'}
			<dl>
				<dt>
					<label for="category">
						{lang}wiki.article{$action|ucfirst}.category{/lang}
					</label>
				</dt>
				<dd>
					<select id="category" name="category" class="medium">
						{foreach from=$categoryNodeList item=category}
							{*if $category->getPermission('canAddArticle')*}
								<option value="{$category->categoryID}"{if $categoryID|isset && $categoryID == $category->categoryID} selected="selected"{/if}>{section name=i loop=$categoryNodeList->getDepth()}&nbsp;&nbsp;&nbsp;&nbsp;{/section}{$category->getTitle()}</option>
							{*/if*}
						{/foreach}
					</select>
				</dd>
			</dl>
			{/if}
			<dl>
				<dt>
					<label for="subject">
						{lang}wiki.article{$action|ucfirst}.title{/lang}
					</label>
				</dt>
				<dd>
					<input type="text" id="subject" name="subject" value="{if $subject|isset}{$subject}{/if}" required="required" class="medium" />
					{if $errorField == 'subject'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			{if $action == 'add'}
				{include file='messageFormMultilingualism'}
			{/if}
		</fieldset>

		<fieldset>
			<legend>
				{lang}wiki.article{$action|ucfirst}.text{/lang}
			</legend>
			<dd>
				<textarea id="text" name="text" rows="20" cols="40">{if $text|isset}{$text}{/if}</textarea>
				{if $errorField == 'texte'}
					<small class="innerError">
						{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
					</small>
				{/if}
			</dd>
		</fieldset>

		{include file='messageFormTabs' attachmentHandler=null}
	</div>

	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
 	</div>
</form>

{include file='footer'}
{include file='wysiwyg'}

</body>
</html>
