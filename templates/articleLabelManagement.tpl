{hascontent}
<fieldset>
	<legend>{lang}wiki.article.label.management.existingLabels{/lang}</legend>
</fieldset>

<ul class="articleLabelList">
	{content} {foreach from=$labelList item=label}
	<li><a
		class="badge label{if $label->cssClassName} {@$label->cssClassName}{/if}"
		data-label-id="{@$label->labelID}"
		data-css-class-name="{if $label->cssClassName}{@$label->cssClassName}{else}none{/if}">{$label->label}</a></li>
	{/foreach} {/content}
</ul>

<small>{lang}wiki.article.label.management.edit.description{/lang}</small>
{/hascontent}

<fieldset id="articleLabelManagementForm"
	data-category-id="{$categoryID}">
	<legend>{lang}wiki.article.label.management.addLabel{/lang}</legend>

	<dl>
		<dt>
			<label for="labelName">{lang}wiki.article.label.labelName{/lang}</label>
		</dt>
		<dd>
			<input type="text" id="labelName" class="long" />
		</dd>
	</dl>
	<dl>
		<dt>{lang}wiki.article.label.cssClassName{/lang}</dt>
		<dd>
			<ul id="labelManagementList">
				{foreach from=$cssClassNames item=cssClassName}
				<li><label> <input type="radio" name="cssClassName"
						value="{@$cssClassName}" {if $cssClassName==
						'none'} checked="checked" {/if} /> <span
						class="badge label{if $cssClassName != 'none'} {@$cssClassName}{/if}">{lang}wiki.article.label.placeholder{/lang}</span>
				</label></li> {/foreach}
			</ul>
		</dd>
	</dl>

	<div class="formSubmit">
		<button id="addLabel" class="buttonPrimary">{lang}wcf.global.button.save{/lang}</button>
		<button id="editLabel" style="display: none;" class="buttonPrimary">{lang}wcf.global.button.save{/lang}</button>
		<button id="deleteLabel" style="display: none;">{lang}wiki.article.label.management.deleteLabel{/lang}</button>
	</div>
</fieldset>