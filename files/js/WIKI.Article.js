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

			this._articleContent.bind('wcftabsselect', $.proxy(this._loadContent, this));
		}
	},

	/**
	 * Prepares to load content once tabs are being switched.
	 *
	 * @param	object		event
	 * @param	object		ui
	 */
	_loadContent: function(event, ui) {
		var $panel = $(ui.panel);
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