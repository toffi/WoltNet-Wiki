/**
 * wiki namespace
 */
WIKI = {};

function showCategoryAddForm() {
    WCF.showDialog('categoryAddForm', {
        title: WCF.Language.get('wiki.category.categoryAdd')
    });
    return false;
}

/**
 * Article namespace
 */
WIKI.Article = {};

/**
 * Provides methods to load tab menu content upon request.
 */
WIKI.Article.TabMenu = Class.extend({
    /**
     * list of containers
     * @var	object
     */
    _hasContent: { },

    /**
     * article content
     * @var	jQuery
     */
    _articleContent: null,

    /**
     * action proxy
     * @var	WCF.Action.Proxy
     */
    _proxy: null,

    /**
     * target article id
     * @var	integer
     */
    _articleID: 0,

    /**
     * Initializes the tab menu loader.
     *
     * @param	integer		articleID
     */
    init: function(articleID) {
        this._articleContent = $('#articleContent');
        this._articleID = articleID;

        var $activeMenuItem = this._articleContent.data('active');
        var $enableProxy = false;

        // fetch content state
        this._articleContent.find('div.tabMenuContent').each($.proxy(function(index, container) {
            var $containerID = $(container).wcfIdentify();

            if ($activeMenuItem === $containerID) {
                this._hasContent[$containerID] = true;
            }
            else {
                this._hasContent[$containerID] = false;
                $enableProxy = true;
            }
        }, this));

        // enable loader if at least one container is empty
        if ($enableProxy) {
            this._proxy = new WCF.Action.Proxy({
                success: $.proxy(this._success, this)
            });

            this._articleContent.bind('wcftabsbeforeactivate', $.proxy(this._loadContent, this));
        }
    },

    /**
     * Prepares to load content once tabs are being switched.
     *
     * @param	object		event
     * @param	object		ui
     */
    _loadContent: function(event, ui) {
        var $panel = $(ui.newPanel);
        var $containerID = $panel.attr('id');

        if (!this._hasContent[$containerID]) {
            this._proxy.setOption('data', {
                actionName: 'getContent',
                className: 'wcf\\data\\article\\menu\\item\\ArticleMenuItemAction',
                parameters: {
                    data: {
                        containerID: $containerID,
                        menuItem: $panel.data('menuItem'),
                        articleID: this._articleID
                    }
                }
            });
            this._proxy.sendRequest();
        }
    },

    /**
     * Shows previously requested content.
     *
     * @param	object		data
     * @param	string		textStatus
     * @param	jQuery		jqXHR
     */
    _success: function(data, textStatus, jqXHR) {
        var $containerID = data.returnValues.containerID;
        this._hasContent[$containerID] = true;

        // insert content
        var $content = this._articleContent.find('#' + $containerID);
        $('<div>' + data.returnValues.template + '</div>').hide().appendTo($content);

        // slide in content
        $content.children('div').wcfBlindIn();
    }
});

/**
 * Core editor handler for articles.
 */
WIKI.Article.EditorHandler = Class.extend({
    /**
     * list of attributes per article
     * @var	object
     */
    _attributes: { },

    /**
     * list of articles
     * @var	object
     */
    _articles: { },

    /**
     * Initializes the core editor handler for articles.
     */
    init: function() {
        this._articles = { };

        var self = this;
        $('.article').each(function(index, article) {
            var $article = $(article);
            var $articleID = $article.data('articleID');

            if (!self._articles[$articleID]) {
                self._articles[$articleID] = $article;
            }
        });
    },

    /**
     * Returns an attribute's value for given article id.
     *
     * @param	integer		articleID
     * @param	string		key
     * @return	mixed
     */
    getValue: function(articleID, key) {

    },

    /**
     * Updates article data.
     *
     * @param	integer		articleID
     * @param	object		data
     */
    update: function(articleID, key, data) {
        if (!this._articles[articleID]) {
            console.debug("[WIKI.Article.EditorHandler] Unknown article id '" + articleID + "'");
            return;
        }
        var $article = this._articles[articleID];

    }
});

/**
 * Provides extended actions for article clipboard actions.
 */
WIKI.Article.Clipboard = Class.extend({
    /**
     * editor handler
     * @var	WIKI.Article.EditorHandler
     */
    _editorHandler: null,

    /**
     * Initializes a new WIKI.Article.Clipboard object.
     *
     * @param	WIKI.Article.EditorHandler	editorHandler
     */
    init: function(editorHandler) {
        this._editorHandler = editorHandler;

        // bind listener
        $('.jsClipboardEditor').each($.proxy(function(index, container) {
            var $container = $(container);
            var $types = eval($container.data('types'));
            if (WCF.inArray('com.woltnet.wiki.article', $types)) {
                $container.on('clipboardAction', $.proxy(this._execute, this));
                $container.on('clipboardActionResponse', $.proxy(this._evaluateResponse, this));
                return false;
            }
        }, this));
    },

    /**
     * Handles clipboard actions.
     *
     * @param	object		event
     * @param	string		type
     * @param	string		actionName
     * @param	object		parameters
     */
    _execute: function(event, type, actionName, parameters) {

    },

    /**
     * Evaluates AJAX responses.
     *
     * @param	object		event
     * @param	object		data
     * @param	string		type
     * @param	string		actionName
     * @param	object		parameters
     */
    _evaluateResponse: function(event, data, type, actionName, parameters) {
        if (type !== 'com.woltnet.wiki.article') {
            // ignore unreleated events
            return;
        }
    }
});

/**
 * Inline editor implementation for articles.
 *
 * @see	WCF.Inline.Editor
 */
WIKI.Article.InlineEditor = WCF.InlineEditor.extend({
    /**
     * editor handler object
     * @var	WIKI.Article.EditorHandler
     */
    _editorHandler: null,

    /**
     * execution environment
     * @var	string
     */
    _environment: 'article',

    /**
     * @see	WCF.InlineEditor._setOptions()
     */
    _setOptions: function() {
        this._options = [
            // assign labels
            { label: WCF.Language.get('wiki.article.edit.assignLabel'), optionName: 'assignLabel' },

            // divider
            { optionName: 'divider' }
        ];
    },

    /**
     * Sets editor handler object.
     *
     * @param	WIKI.Article.EditorHandler	editorHandler
     * @param	string				environment
     */
    setEditorHandler: function(editorHandler, environment) {
        this._editorHandler = editorHandler;
        this._environment = (environment == 'list') ? 'list' : 'article';
    },

    /**
     * @see	WCF.InlineEditor._getTriggerElement()
     */
    _getTriggerElement: function(element) {
        return element.find('.jsArticleInlineEditor');
    },

    /**
     * @see	WCF.InlineEditor._validate()
     */
    _validate: function(elementID, optionName) {
        var $articleID = $('#' + elementID).data('articleID');

        return false;
    },

    /**
     * @see	WCF.InlineEditor._execute()
     */
    _execute: function(elementID, optionName) {
        // abort if option is invalid or not accessible
        if (!this._validate(elementID, optionName)) {
            return false;
        }
    },

    /**
     * Updates article properties.
     *
     * @param	string		elementID
     * @param	string		optionName
     * @param	object		data
     */
    _updateArticle: function(elementID, optionName, data) {
        var $articleID = this._elements[elementID].data('articleID');

    },

    /**
     * @see	WCF.InlineEditor._updateState()
     */
    _updateState: function() {
        for (var $i = 0, $length = this._updateData.length; $i < $length; $i++) {
            var $data = this._updateData[$i];
            var $articleID = this._elements[$data.elementID].data('articleID');

        }
    }
});

/**
 * Provides the quote manager for conversation messages.
 *
 * @param	WCF.Message.Quote.Manager	quoteManager
 * @see		WCF.Message.Quote.Handler
 */
WIKI.Article.QuoteHandler = WCF.Message.Quote.Handler.extend({
    /**
     * @see	WCF.Message.QuoteManager.init()
     */
    init: function(quoteManager) {
        this._super(quoteManager, 'wiki\\data\\article\\ArticleAction', 'com.woltnet.wiki.article', '.message', '.messageBody > div.messageText');
    }
});

/**
 * Loads article previews.
 *
 * @see	WCF.Popover
 */
WIKI.Article.ArticlePreview = WCF.Popover.extend({
    /**
     * action proxy
     * @var	WCF.Action.Proxy
     */
    _proxy: null,

    /**
     * list of user profiles
     * @var	object
     */
    _articles: { },

    /**
     * @see	WCF.Popover.init()
     */
    init: function() {
        this._super('.articleLink');

        this._proxy = new WCF.Action.Proxy({
            showLoadingOverlay: false
        });
    },

    /**
     * @see	WCF.Popover._loadContent()
     */
    _loadContent: function() {
        var $element = $('#' + this._activeElementID);
        var $articleID = $element.data('articleID');

        if (this._articles[$articleID]) {
            // use cached user profile
            this._insertContent(this._activeElementID, this._articles[$articleID], true);
        }
        else {
            this._proxy.setOption('data', {
                actionName: 'getArticles',
                className: 'wiki\\data\\article\\ArticleAction',
                objectIDs: [ $articleID ]
            });

            var $elementID = this._activeElementID;
            var self = this;
            this._proxy.setOption('success', function(data, textStatus, jqXHR) {
                // cache article
                self._articles[$articleID] = data.returnValues.template;

                // show article
                self._insertContent($elementID, data.returnValues.template, true);
            });
            this._proxy.sendRequest();
        }
    }
});