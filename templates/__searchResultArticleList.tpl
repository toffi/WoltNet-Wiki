{foreach from=$objects item=object}
    <li>
        <div class="box48">
            <a href="{link controller='User' object=$object->getUserProfile()}{/link}" title="{$object->getUserProfile()->username}" class="framed">{@$object->getUserProfile()->getAvatar()->getImageTag(48)}</a>

            <div>
                <div class="containerHeadline">
                    <h3>
                        <a href="{link controller='User' object=$object->getUserProfile()}{/link}" class="userLink" data-user-id="{@$object->getUserProfile()->userID}">{$object->getUserProfile()->username}</a><small> - {@$object->getTime()|time}</small>
                    </h3>
                    <p>
                        <strong>{@$object->getTitle()}</strong>
                    </p>
                    <small class="containerContentType">
                        {lang}wiki.searchResult.article{/lang}
                    </small>
                </div>

                <div>{@$object->getExcerpt()}</div>

                <div>
                    <small>{lang}wiki.searchResult.article.category{/lang} <a href="{link controller='Category' object=$object->getCategory()}{/link}" title="{$object->getCategory()->getTitle()}" class="framed jsTooltip">{$object->getCategory()->getTitle()}</a></small>
                </div>
            </div>
        </div>
    </li>
{/foreach}
