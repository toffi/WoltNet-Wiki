{assign var="user" value=$article->getAuthor()}
<div class="box48">
    {if $user->getAvatar()}
        <a href="{link controller='User' object=$user->getDecoratedObject()}{/link}" class="framed">{@$user->getAvatar()->getImageTag(48)}</a>
    {/if}

    <div>
        <div class="containerHeadline">
            <h1><a href="{link controller='User' object=$user->getDecoratedObject()}{/link}">{$user->username}</a> <small>- {@$article->time|time}</small></h1>
        </div>

        <div>{@$article->getExcerpt()|nl2br}</div>
    </div>
</div>