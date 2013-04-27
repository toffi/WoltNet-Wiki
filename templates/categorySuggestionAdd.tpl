{include file='documentHeader'}

<head>
	<title>{lang}wiki.category.categorySuggestionAdd{/lang} - {PAGE_TITLE|language}</title>

	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wiki.category.categorySuggestionAdd{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wiki.category.categorySuggestionAdd.success{/lang}</p>
{/if}

<form method="post" action="{link controller='CategorySuggestionAdd' application='wiki'}{/link}" id="categorySuggestionAddForm">
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>
				{lang}wiki.category.categorySuggestionAdd.data{/lang}
			</legend>
			<dl>
				<dt>
					<label for="parentCategory">
						{lang}wiki.category.categorySuggestionAdd.parentCategory{/lang}
					</label>
				</dt>
				<dd>
					<select id="parentCategory" name="parentCategory" class="medium">
						<option value="0"{if $parentCategoryID|isset && $parentCategoryID == 0} selected="selected"{/if}>{lang}wiki.category.categorySuggestionAdd.parentCategory.none{/lang}</option>
						{foreach from=$categoryNodeList item=category}
							{*if $category->getPermission('canEnterCategory')*}
								<option value="{$category->categoryID}"{if $parentCategoryID|isset && $categoryID == $category->categoryID} selected="selected"{/if}>{section name=i loop=$categoryNodeList->getDepth()}&nbsp;&raquo;&raquo;&nbsp;{/section}{$category->getTitle()}</option>
							{*/if*}
						{/foreach}
					</select>
					{if $errorField == 'parentCategory'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
							{if $errorType == 'notValid'}{lang}wiki.global.form.error.notValid{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			<dl>
				<dt>
					<label for="title">
						{lang}wiki.category.categorySuggestionAdd.title{/lang}
					</label>
				</dt>
				<dd>
					<script type="text/javascript">
						//<![CDATA[
						$(function() {
							var $availableLanguages = { {implode from=$availableLanguages key=languageID item=languageName}{@$languageID}: '{$languageName}'{/implode} };
							var $titleValues = { {implode from=$i18nValues[title] key=languageID item=value}'{@$languageID}': '{$value}'{/implode} };
							new WCF.MultipleLanguageInput('title', false, $titleValues, $availableLanguages);
						});
						//]]>
					</script>
					<input type="text" id="title" name="title" value="{$i18nPlainValues['title']}" required="required" class="medium" />
					{if $errorField == 'title'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
							{if $errorType == 'notValid'}{lang}wiki.global.form.error.notValid{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			<dl>
				<dt>
					<label for="reason">
						{lang}wiki.category.categorySuggestionAdd.reason{/lang}
					</label>
				</dt>
				<dd>
					<script type="text/javascript">
						//<![CDATA[
						$(function() {
							var $availableLanguages = { {implode from=$availableLanguages key=languageID item=languageName}{@$languageID}: '{$languageName}'{/implode} };
							var $reasonValues = { {implode from=$i18nValues[title] key=languageID item=value}'{@$languageID}': '{$value}'{/implode} };
							new WCF.MultipleLanguageInput('reason', false, $reasonValues, $availableLanguages);
						});
						//]]>
					</script>
					<textarea id="reason" name="reason" rows="10" cols="40">{$i18nPlainValues['reason']}</textarea>
					{if $errorField == 'reason'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
							{if $errorType == 'notValid'}{lang}wiki.global.form.error.notValid{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
		</fieldset>
	</div>
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
 	</div>
</form>

{include file='footer'}

</body>
</html>

