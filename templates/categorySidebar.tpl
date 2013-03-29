{capture assign='sidebar'}
  {if $__wcf->session->getPermission('mod.wiki.category.canManageLabels')}
    <fieldset>
      <legend>{lang}wiki.article.label{/lang}</legend>

      <div id="articleLabelFilter" class="dropdown">
        <div class="dropdownToggle" data-toggle="articleLabelFilter">
          {if $labelID}
            {foreach from=$labelList item=label}
              {if $label->labelID == $labelID}
                <span class="badge label{if $label->cssClassName} {@$label->cssClassName}{/if}">{$label->label}</span>
              {/if}
            {/foreach}
          {else}
            <span class="badge">{lang}wiki.article.label.filter{/lang}</span>
          {/if}
        </div>

        <ul class="dropdownMenu">
          {foreach from=$labelList item=label}
            <li><a href="{link controller='Category' application='wiki' object=$category}{if $filter}filter={@$filter}{/if}&sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}&labelID={@$label->labelID}{/link}"><span class="badge label{if $label->cssClassName} {@$label->cssClassName}{/if}" data-css-class-name="{if $label->cssClassName}{@$label->cssClassName}{/if}" data-label-id="{@$label->labelID}">{$label->label}</span></a></li>
          {/foreach}
          <li class="dropdownDivider"{if !$labelList|count} style="display: none;"{/if}></li>
          <li><a href="{link controller='Category' application='wiki' object=$category}{if $filter}filter={@$filter}{/if}&sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}{/link}">{lang}wiki.article.label.disableFilter{/lang}</a></li>
        </ul>
      </div>

      <button id="manageLabel" data-category-id="{@$category->categoryID}" >{lang}wiki.article.label.management{/lang}</button>
    </fieldset>
  {/if}
{/capture}