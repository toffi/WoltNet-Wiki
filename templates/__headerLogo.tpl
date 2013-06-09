{if $__wiki->isActiveApplication()}
    {if !$__wcf->getStyleHandler()->getStyle()->getPageLogo()}
        <img src="{@$__wcf->getPath('wiki')}images/wikiLogo.svg" width="256" height="64" alt="" />
    {/if}
{/if}
