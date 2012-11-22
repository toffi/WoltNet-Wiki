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

<form method="post" action="{link controller='CategorySuggestionAdd'}{/link}" id="categorySuggestionAddForm">
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
						{foreach from=$categoryNodeList item=category}
							{*if $category->getPermission('canEnterCategory')*}
								<option value="{$category->categoryID}"{if $categoryID|isset && $categoryID == $category->categoryID} selected="selected"{/if}>{section name=i loop=$categoryNodeList->getDepth()}&nbsp;&raquo;&raquo;&nbsp;{/section}{$category->getTitle()}</option>
							{*/if*}
						{/foreach}
					</select>
				</dd>
			</dl>
			<dl>
				<dt>
					<label for="title">
						{lang}wiki.article.article{$action|ucfirst}.title{/lang}
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
					<input type="text" id="title" name="title" value="{if $title|isset}{$title}{/if}" required="required" class="medium" />
					{if $errorField == 'title'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
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
					<textarea id="reason" name="text" rows="10" cols="40">{if $reason|isset}{$reason}{/if}</textarea>
					{if $errorField == 'reason'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
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

