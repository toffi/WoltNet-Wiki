<div class="marginTop tabularBox tabularBoxTitle shadow messageGroupList articleList jsClipboardContainer" data-type="com.woltnet.wiki.article">
    <hgroup>
        <h1>{lang}wiki.article.articles{/lang} <span class="badge badgeInverse">{#$objects|count}</span></h1>
    </hgroup>

    <table class="table">
        <thead>
            <tr>
                <th colspan="2" class="columnTitle columnSubject">{lang}wiki.category.article.articleName{/lang}</th>
                <th class="columnText columnCategory">{lang}wiki.article.category{/lang}</th>
                <th class="columnDigits columnComments">{lang}wiki.category.article.comments{/lang}</th>
                <th class="columnDigits columnAuthor">{lang}wiki.category.article.author{/lang}</th>
                <th class="columnText columnLastPost">{lang}wiki.category.article.time{/lang}</th>
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

                            <a href="{link controller='Article' application='wiki' object=$article}{/link}" class="articleLink messageGroupLink" data-article-id="{@$article->articleID}">{$article->getTitle()}</a>
                        </h1>

                        <small>
                            <a href="{link controller='User' object=$article->getAuthor()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$article->userID}">{$article->username}</a>
                            - {@$article->time|time}
                        </small>
                    </td>
                    <td class="columnText columnCategory">
                        <p>
                            <a href="{link controller='Category' application='wiki' object=$article->getCategory()}{/link}">{$article->getCategory()->getTitle()}</a>
                        </p>
                    </td>
                    <td class="columnDigits columnComments"><p><a href="{link controller='Article' application='wiki' object=$article}#discuss{/link}">{#$article->getCommentList()|count}</a></p></td>
                    <td class="columnDigits columnUsername"><p><a href="{link controller='User' object=$article->getAuthor()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$article->userID}">{$article->username}</a></p></td>
                    <td class="columnText columnLastPost"><p>{@$article->time|time}</p></td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>