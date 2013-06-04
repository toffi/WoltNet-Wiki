{capture assign='sidebar'} {* labels *} {if $labelGroups|count}
<fieldset>
	<legend>{lang}wcf.label.label{/lang}</legend>

	<dl>
		<dd>
			<ul class="labelList jsOnly">
				{foreach from=$labelGroups item=labelGroup} {if $labelGroup|count}
				<li class="dropdown labelChooser"
					id="labelGroup{@$labelGroup->groupID}"
					data-group-id="{@$labelGroup->groupID}">
					<div class="dropdownToggle"
						data-toggle="labelGroup{@$labelGroup->groupID}">
						<span class="badge label">{lang}wcf.label.all{/lang}</span>
					</div>
					<ul class="dropdownMenu">
						{foreach from=$labelGroup item=label}
						<li data-label-id="{@$label->labelID}"><span><span
								class="badge label{if $label->cssClassName} {@$label->cssClassName}{/if}">{lang}{$label->label}{/lang}</span></span></li>
						{/foreach}
					</ul>
				</li> {/if} {/foreach}
			</ul>
			<noscript>
				{foreach from=$labelGroups item=labelGroup} {if $labelGroup|count} <select
					name="labelIDs[{@$labelGroup->groupID}]">
					<option value="0">{lang}wcf.label.all{/lang}</option>
					<option value="-1">{lang}wcf.label.withoutSelection{/lang}</option>
					{foreach from=$labelGroup item=label}
					<option value="{@$label->labelID}" {if $labelIDs[$labelGroup->groupID]|isset
						&& $labelIDs[$labelGroup->groupID] == $label->labelID}
						selected="selected"{/if}>{lang}{$label->label}{/lang}</option> {/foreach}
				</select> {/if} {/foreach}
			</noscript>
		</dd>
	</dl>
</fieldset>
<script type="text/javascript">
                //<![CDATA[
                $(function() {
                    WCF.Language.addObject({
                        'wcf.label.all': '{lang}wcf.label.all{/lang}',
                        'wcf.label.none': '{lang}wcf.label.none{/lang}',
                        'wcf.label.withoutSelection': '{lang}wcf.label.withoutSelection{/lang}'
                    });

                    new WCF.Label.Chooser({ {implode from=$labelIDs key=groupID item=labelID}{@$groupID}: {@$labelID}{/implode} }, '#sidebarContainer', undefined, true);
                });
                //]]>
            </script>
{/if} {/capture}
