{include file='__commentJavaScript' commentContainerID='articleCommentList'}

{if $commentCanAdd}
    <ul id="articleCommentList" class="commentList containerList" data-can-add="true" data-object-id="{@$article->articleID}" data-object-type-id="{@$commentObjectTypeID}" data-comments="{@$commentList->countObjects()}" data-last-comment-time="{@$lastCommentTime}">
        {include file='commentList'}
    </ul>
{else}
    {hascontent}
        <ul id="articleCommentList" class="commentList containerList" data-can-add="false" data-object-id="{@$article->articleID}" data-object-type-id="{@$commentObjectTypeID}" data-comments="{@$commentList->countObjects()}" data-last-comment-time="{@$lastCommentTime}">
            {content}
                {include file='commentList'}
            {/content}
        </ul>
    {hascontentelse}
        <div class="containerPadding">
            {lang}wiki.article.comments.noEntries{/lang}
        </div>
    {/hascontent}
{/if}