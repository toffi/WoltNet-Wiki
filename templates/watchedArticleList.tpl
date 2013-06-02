{include file='documentHeader'}

<head>
    <title>{lang}wiki.article.watchedArticles{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {/if}- {PAGE_TITLE|language}</title>

    {include file='headInclude'}

    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            WCF.Language.addObject({
                'wcf.user.objectWatch.manageSubscription': '{lang}wcf.user.objectWatch.manageSubscription{/lang}'
            });

            var $articles = $('.wikiArticleList tr.wikiArticle');
            if ($articles.length) {
                $articles.each(function(index, article) {
                    var $article = $(article);

                    $('<span> - <a class="jsSubscribeButton" data-object-id="' + $article.data('articleID') + '" data-object-type="com.woltnet.wiki.article">{lang}wcf.global.button.edit{/lang}</a></span>').appendTo($article.find('.columnSubject > small'));
                });

                new WCF.User.ObjectWatch.Subscribe();
            }

            WCF.Clipboard.init('wiki\\page\\WatchedArticleListPage', {@$hasMarkedItems}, { });
        });
        //]]>
    </script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header'}

<header class="boxHeadline">
    <h1>{lang}wiki.article.watchedArticles{/lang} <span class="badge">{#$items}</span></h1>
</header>

{include file='userNotice'}

<div class="contentNavigation">
    {pages print=true assign=pagesLinks application='wiki' controller='WatchedArticleList' link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}

    {hascontent}
        <nav>
            <ul>
                {content}
                    {event name='contentNavigationButtonsTop'}
                {/content}
            </ul>
        </nav>
    {/hascontent}
</div>

{if $objects|count}
    <div class="marginTop tabularBox tabularBoxTitle messageGroupList wikiArticleList jsClipboardContainer" data-type="com.woltnet.wiki.article">
        <header>
            <h2>{lang}wiki.article.watchedArticles{/lang}</h2>
        </header>

        <table class="table">
            <thead>
                <tr>
                    <th colspan="2" class="columnTitle columnSubject">{lang}wiki.category.article.articleName{/lang}</th>
                    <th class="columnText columnCategory">{lang}wiki.article.category{/lang}</th>
                    <th class="columnDigits columnComments">{lang}wiki.category.article.comments{/lang}</th>
                    <th class="columnDigits columnAuthor">{lang}wiki.category.article.author{/lang}</th>
                    <th class="columnText columnLastPost">{lang}wiki.category.article.time{/lang}</th>

                    {event name='columnHeads'}
                </tr>
            </thead>

            <tbody>
                {foreach from=$objects item=article}
                <tr class="article{if $article->isNew()} new{/if}" data-article-id="{@$article->articleID}" data-category-id="{@$article->categoryID}" data-label-ids="[ {implode from=$article->getAssignedLabels() item=label}{@$label->labelID}{/implode} ]">
                    <td class="columnIcon columnAvatar">
                        <div>
                            <p class="framed"><span class="icon icon32 icon-file"></span></p>
                        </div>
                    </td>
                    <td class="columnText columnSubject">
                        <div class="statusDisplay">
                            <ul class="statusIcons">
                                {if $article->getLanguage()|is_object}
                                <li>
                                    <img src="{@$article->getLanguage()->getIconPath()}" class="icon24" />
                                </li>
                                {/if}
                            </ul>
                        </div>

                        <h1>
                            {hascontent}
                                <ul class="labelList">
                                    {content}
                                        {foreach from=$article->getAssignedLabels() item=label}
                                            <li><a href="{link controller='Category' application='wiki' object=$category}{if $filter}filter={@$filter}{/if}&sortField={$sortField}&sortOrder={$sortOrder}&pageNo={@$pageNo}&labelID={@$label->labelID}{/link}" class="badge label{if $label->cssClassName} {@$label->cssClassName}{/if}">{$label->label}</a></li>
                                        {/foreach}
                                    {/content}
                                </ul>
                            {/hascontent}

                            <a href="{link controller='Article' application='wiki' object=$article->getActiveVersion()}{/link}" class="articleLink messageGroupLink framed" data-article-id="{@$article->articleID}">{$article->getTitle()}</a>
                        </h1>

                        <small>
                            <a href="{link controller='User' object=$article->getActiveVersion()->getAuthor()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$article->getActiveVersion()->userID}">{$article->getActiveVersion()->username}</a>
                            - {@$article->getActiveVersion()->getTime()|time}
                        </small>
                    </td>
                    <td class="columnText columnCategory">
                        <p>
                            <a href="{link controller='Category' application='wiki' object=$article->getCategory()}{/link}">{$article->getCategory()->getTitle()}</a>
                        </p>
                    </td>
                    <td class="columnDigits columnComments"><p><a href="{link controller='Article' application='wiki' categoryName=$article->getCategory()->getTitle() object=$article->getActiveVersion()}#discuss{/link}">{#$article->getCommentList()|count}</a></p></td>
                    <td class="columnDigits columnUsername"><p><a href="{link controller='User' object=$article->getActiveVersion()->getAuthor()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$article->getActiveVersion()->userID}">{$article->getActiveVersion()->username}</a></p></td>
                    <td class="columnText columnLastPost"><p>{@$article->getActiveVersion()->getTime()|time}</p></td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
{else}
    <p class="info">{lang}wiki.article.watchedArticles.noArticles{/lang}</p>
{/if}

<div class="contentNavigation">
    {@$pagesLinks}

    {hascontent}
        <nav>
            <ul>
                {content}
                    {event name='contentNavigationButtonsBottom'}
                {/content}
            </ul>
        </nav>
    {/hascontent}

    <div class="jsClipboardEditor" data-types="[ 'com.woltnet.wiki.article' ]"></div>
</div>

{include file='footer'}

</body>
</html>