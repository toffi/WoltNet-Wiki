{if $templateName == 'articleAdd' && $__wcf->session->getPermission('mod.wiki.article.canManagePermissions')}
    <li>
        <a href="{$__wcf->getAnchor('permissions')}" title="{lang}wiki.article.permissions{/lang}">{lang}wiki.article.permissions{/lang}</a>
    </li>
{/if}
