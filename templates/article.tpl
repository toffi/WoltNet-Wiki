{include file='documentHeader'}

<head>
    <title>{$articleOverview->getTitle()} - {PAGE_TITLE|language}</title>

    {include file='headInclude'}

    <script type="text/javascript" src="{@$__wcf->getPath('wcf')}js/WCF.Moderation.js"></script>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            new WIKI.Article.TabMenu({@$articleOverview->articleID});

            new WCF.Moderation.Report.Content('com.woltnet.wiki.article', '.jsReportArticle');

            WCF.TabMenu.init();

            new WCF.User.ObjectWatch.Subscribe();

            {include file='__messageQuoteManager' wysiwygSelector='text' supportPaste=true}
            new WIKI.Article.QuoteHandler($quoteManager);
        });
        //]]>
    </script>
</head>

<body id="tpl{$templateName|ucfirst}">

{capture assign='headerNavigation'}
    {if $__wcf->user->userID}
        {if $articleOverview->getArticle()->isWatched()}
            <li><a title="{lang}wcf.user.watchedObjects.unsubscribe{/lang}" class="jsSubscribeButton jsTooltip" data-object-type="com.woltnet.wiki.article" data-object-id="{@$articleOverview->articleID}" data-subscribed="1"><span class="icon icon16 icon-bookmark"></span> <span class="invisible">{lang}wcf.user.watchedObjects.unsubscribe{/lang}</span></a></li>
        {else}
            <li><a title="{lang}wcf.user.watchedObjects.subscribe{/lang}" class="jsSubscribeButton jsTooltip" data-object-type="com.woltnet.wiki.article" data-object-id="{@$articleOverview->articleID}" data-subscribed="0"><span class="icon icon16 icon-bookmark-empty"></span> <span class="invisible">{lang}wcf.user.watchedObjects.subscribe{/lang}</span></a></li>
        {/if}
    {/if}
{/capture}

{capture assign='sidebar'}
    <aside class="sidebar">
        <fieldset class="wikiArticleSidebarContainer">
            <legend>{lang}wiki.article.sidebar.informations{/lang}</legend>
            <div>
                <ul class="sidebarBoxList">
                    {if $availableContentLanguagesCount > 0 && $articleOverview->getArticle()->getLanguage() !== null}
                    <li class="box24">
                        <div class="sidebarBoxHeadline">
                            <h1>{lang}wiki.article.sidebar.language{/lang}</h1>
                            <h2>
                                <small id="languageIDContainer">
                                    <script type="text/javascript">
                                        //<![CDATA[
                                            $(function() {
                                                var $languages = {
                                                {implode from=$articleOverview->getArticle()->getAvailableLanguages($contentLanguages) item=language}
                                                    '{@$language->languageID}': {
                                                        iconPath: '{@$language->getIconPath()}',
                                                        languageName: '{$language}'
                                                    }
                                                {/implode}
                                                };

                                                new WCF.Language.Chooser('languageIDContainer', 'languageID', {@$articleOverview->languageID}, $languages);
                                                });
                                        //]]>
                                    </script>
                                    <noscript>
                                        <span>
                                            <img src="{@$articleOverview->getArticle()->getLanguage()->getIconPath}" alt="{$articleOverview->getLanguage()}" />
                                        </span>
                                        <select name="languageID" id="languageID">
                                        {foreach from=$articleOverview->getArticle()->getAvailableLanguages($contentLanguages) item=language}
                                            <option value="{@$language->languageID}"{if $language->languageID == $articleOverview->languageID} selected="selected"{/if}>{$language}</option>
                                        {/foreach}
                                        </select>
                                    </noscript>
                                </small>
                            </h2>
                        </>
                    </li>
                    {/if}
                    <li class="box24" {if $availableContentLanguagesCount > 0 && $articleOverview->getLanguage() !== null}style="margin-top: 20px;"{/if}>
                        <div class="sidebarBoxHeadline">
                            <h1>{lang}wiki.article.sidebar.articleName{/lang}</h1>
                            <h2><small>{$articleOverview->getTitle()}</small></h2>
                        </div>
                    </li>
                    <li class="box24" style="margin-top: 20px;">
                        <div class="sidebarBoxHeadline">
                            <h1>{lang}wiki.article.sidebar.articleCategory{/lang}</h1>
                            <h2><small><a title="{$articleOverview->getArticle()->getCategory()->getTitle()}" href="{link application='wiki' controller='Category' object=$articleOverview->getArticle()->getCategory()}{/link}">{$articleOverview->getArticle()->getCategory()->getTitle()}</a></small></h2>
                        </div>
                    </li>
                    <li class="box24" style="margin-top: 20px;">
                        <div class="sidebarBoxHeadline">
                            <h1>{lang}wiki.article.sidebar.lastEditedDate{/lang}</h1>
                                <h2><small>{@$articleOverview->time|time}</small></h2>
                        </div>
                    </li>
                </ul>
            </div>
        </fieldset>
        <fieldset class="wikiArticleSidebarContainer">
            <legend>{lang}wiki.article.sidebar.share{/lang}</legend>

            <div class="container wikiArticleInfo">
                <ul class="sidebarBoxList containerList">
                    <li class="sidebarBox box32" style="margin: 0;">
                        <p><span onclick="document.getElementById('articleLink').select()" class="icon icon32 icon-link"></span></p>
                        <div class="sidebarBoxHeadline">
                            <h1><input type="text" class="long" id="articleLink" readonly="readonly" onclick="this.select()" value="{link application='wiki' controller='Article' object=$article}{/link}" /></h1>
                            <h2 onclick="document.getElementById('articleLink').select()"><small>{lang}wiki.article.sidebar.share.description{/lang}</small></h2>
                        </div>
                    </li>
                    <li class="sidebarBox box32" style="margin: 0;">
                        <p><span onclick="document.getElementById('articleLinkBBCode').select()" class="icon icon32 icon-share"></span></p>
                        <div class="sidebarBoxHeadline">
                            <h1><input type="text" class="long" id="articleLinkBBCode" readonly="readonly" onclick="this.select()" value="[url='{link application='wiki' controller='Article' object=$article}{/link}']{$article->getTitle()}[/url]" /></h1>
                            <h2 onclick="document.getElementById('articleLinkBBCode').select()"><small>{lang}wiki.article.sidebar.share.bbcode.description{/lang}</small></h2>
                        </div>
                    </li>
                </ul>
            </div>
        </fieldset>
    </aside>
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
    <h1>{$articleOverview->getTitle()}</h1>
    <h2>
        {*<script type="text/javascript">
            //<![CDATA[
                $(function() {
                    var $languages = {
                        {implode from=$articleOverview->getAvailableLanguages() item=language}
                            '{@$language->languageID}': {
                                iconPath: '{@$language->getIconPath()}',
                                languageName: '{$language}'
                            }
                        {/implode}
                    };

                    new WCF.Language.Chooser('languageIDContainer', 'languageID', {@$articleOverview->languageID}, $languages);
                });
            //]]>
        </script>
        <noscript>
            <span><img src="{@$__wcf->getLanguage()->getIconPath}" alt="{$__wcf->getLanguage()}" /></span>
            <select name="languageID" id="languageID">
                {foreach from=$articleOverview->getAvailableLanguages() item=language}
                    <option value="{@$language->languageID}"{if $language->languageID == $articleOverview->languageID} selected="selected"{/if}>{$language}</option>
                {/foreach}
            </select>
        </noscript>*}
    {lang}wiki.article.overview.author{/lang}
    </h2>
</header>

{if $showNotActive}
    <p class="error">{lang}wiki.article.notActive{/lang}</p>
{/if}

<section id="articleContent" class="tabMenuContainer" data-active="{$__wcf->getArticleMenu()->getActiveMenuItem()->getIdentifier()}">
    <nav class="tabMenu">
        <ul>
            {foreach from=$__wcf->getArticleMenu()->getMenuItems() item=menuItem}
            <li><a href="{$__wcf->getAnchor($menuItem->getIdentifier())}" title="{lang}{@$menuItem->menuItem}{/lang}">{lang}wiki.article.menu.{@$menuItem->menuItem}{/lang}</a></li>
            {/foreach}
        </ul>
    </nav>

    {foreach from=$__wcf->getArticleMenu()->getMenuItems() item=menuItem}
        <div id="{$menuItem->getIdentifier()}" class="container containerPadding tabMenuContent shadow" data-menu-item="{$menuItem->menuItem}">
            {if $menuItem === $__wcf->getArticleMenu()->getActiveMenuItem()}
                {@$articleContent}
            {/if}
        </div>
    {/foreach}
</section>

{include file='footer'}

</body>
</html>