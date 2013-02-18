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
	init: function(availableLabels) {
		this._articles = { };
		
		var self = this;
		$('.article').each(function(index, article) {
			var $article = $(article);
			var $articleID = $article.data('articleID');
			
			if (!self._articles[$articleID]) {
				self._articles[$articleID] = $article;
				var $labelIDs = eval($article.data('labelIDs'));
				
				// set attributes
				self._attributes[$articleID] = {
					labelIDs: $labelIDs
				};
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
		switch (key) {
			case 'labelIDs':
				if (this._attributes[articleID].labelIDs === undefined) {
					// TODO: fetch label ids
					this._attributes[articleID].labelIDs = [ ];
				}
				
				return this._attributes[articleID].labelIDs;
			break;
		}
	},
	
	/**
	 * Counts available labels.
	 * 
	 * @return	integer
	 */
	countAvailableLabels: function() {
		return (this.getAvailableLabels()).length;
	},
	
	getAvailableLabels: function() {
		var $labels = [ ];
		
		$('#articleLabelFilter > .dropdownMenu li').each(function(index, listItem) {
			var $listItem = $(listItem);
			if ($listItem.hasClass('dropdownDivider')) {
				return false;
			}
			
			var $span = $listItem.find('span');
			$labels.push({
				cssClassName: $span.data('cssClassName'),
				labelID: $span.data('labelID'),
				label: $span.text()
			});
		});
		
		return $labels;
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
		
		switch (key) {		
			case 'labelIDs':
				var $labels = { };
				$('#articleLabelFilter > .dropdownMenu > li > a > span').each(function(index, span) {
					var $span = $(span);
					
					$labels[$span.data('labelID')] = {
						cssClassName: $span.data('cssClassName'),
						label: $span.text(),
						url: $span.parent().attr('href')
					};
				});
				
				var $labelList = $article.find('.columnSubject > h1 > .labelList');
				if (!data.length) {
					if ($labelList.length) $labelList.remove();
				}
				else {
					// create label list if missing
					if (!$labelList.length) {
						$labelList = $('<ul class="labelList" />').prependTo($article.find('.columnSubject > h1'));
					}
					
					// remove all existing labels
					$labelList.empty();
					
					// insert labels
					for (var $i = 0, $length = data.length; $i < $length; $i++) {
						var $label = $labels[data[$i]];
						$('<li><a href="' + $label.url + '" class="badge label' + ($label.cssClassName ? " " + $label.cssClassName : "") + '">' + $label.label + '</a>&nbsp;</li>').appendTo($labelList);
					}
				}
			break;
		}
	}
});

/**
 * Article editor handler for article page.
 * 
 * @see	WIKI.Article.EditorHandler
 * @param	array<object>	availableLabels
 */
WIKI.Article.EditorHandlerArticle = WIKI.Article.EditorHandler.extend({
	/**
	 * list of available labels
	 * @var	array<object>
	 */
	_availableLabels: null,
	
	/**
	 * @see	WIKI.Article.EditorHandler.init()
	 * 
	 * @param	array<object>	availableLabels
	 */
	init: function(availableLabels) {
		this._availableLabels = availableLabels || [ ];
		
		this._super();
	},
	
	/**
	 * @see	WIKI.Article.EditorHandler.getAvailableLabels()
	 */
	getAvailableLabels: function() {
		return this._availableLabels;
	},
	
	/**
	 * @see	WIKI.Article.EditorHandler.update()
	 */
	update: function(articleID, key, data) {
		if (!this._articles[articleID]) {
			console.debug("[WIKI.Article.EditorHandler] Unknown article id '" + articleID + "'");
			return;
		}
		var $article = this._articles[articleID];
		
		switch (key) {
			case 'labelIDs':
				var $container = $('#content > header h1');
				if (!data.length) {
					// remove all labels
					$container.find('ul.labelList').remove();
				}
				else {
					var $labelList = $container.find('ul.labelList');
					if (!$labelList.length) {
						$labelList = $('<ul class="labelList" />').appendTo($container);
					}
					
					// remove existing labels
					$labelList.empty();
					
					// add new labels
					for (var $i = 0, $length = data.length; $i < $length; $i++) {
						var $labelID = data[$i];
						
						for (var $j = 0, $innerLength = this.getAvailableLabels().length; $j < $innerLength; $j++) {
							var $label = this.getAvailableLabels()[$j];
							if ($label.labelID == $labelID) {
								$('<li><span class="label badge' + ($label.cssClassName ? " " + $label.cssClassName : "") + '">' + $label.label + '</span>&nbsp;</li>').appendTo($labelList);
								
								break;
							}
						}
					}
				}
			break;
		}
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
		if (type === 'com.woltnet.wiki.article' && actionName === 'article.assignLabel') {
			new WIKI.Article.Label.Editor(this._editorHandler, null, parameters.objectIDs);
		}
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
		
		switch (optionName) {
			case 'assignLabel':
				return (this._editorHandler.countAvailableLabels()) ? true : false;
			break;
		}
		
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
		
		switch (optionName) {
			case 'assignLabel':
				new WIKI.Article.Label.Editor(this._editorHandler, elementID);
			break;
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
 * Namespace for label-related classes.
 */
WIKI.Article.Label = { };

/**
 * Providers an editor for article labels.
 * 
 * @param	WIKI.Article.EditorHandler	editorHandler
 * @param	string				elementID
 * @param	array<integer>			articleIDs
 */
WIKI.Article.Label.Editor = Class.extend({
	/**
	 * list of article id
	 * @var	array<integer>
	 */
	_articleIDs: 0,
	
	/**
	 * list of category id
	 * @var	array<integer>
	 */
	_categoryID: 0,
	
	/**
	 * dialog object
	 * @var	jQuery
	 */
	_dialog: null,
	
	/**
	 * editor handler object
	 * @var	WIKI.Article.EditorHandler
	 */
	_editorHandler: null,
	
	/**
	 * system notification object
	 * @var	WCF.System.Notification
	 */
	_notification: null,
	
	/**
	 * action proxy object
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * Initializes the label editor for given article.
	 * 
	 * @param	WIKI.Article.EditorHandler	editorHandler
	 * @param	string				elementID
	 * @param	array<integer>			articleIDs
	 */
	init: function(editorHandler, elementID, articleIDs) {
		if (elementID) {
			this._articleIDs = [ $('#' + elementID).data('articleID') ];
		}
		else {
			this._articleIDs = articleIDs;
		}
		
		if (elementID) {
			this._categoryID = [ $('#' + elementID).data('categoryID') ];
		}
		
		this._dialog = null;
		this._editorHandler = editorHandler;
		
		this._notification = new WCF.System.Notification(WCF.Language.get('wiki.article.label.management.addLabel.success'));
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		this._loadDialog();
	},
	
	/**
	 * Loads label assignment dialog.
	 */
	_loadDialog: function() {
		this._proxy.setOption('data', {
			actionName: 'getLabelForm',
			className: 'wiki\\data\\article\\label\\ArticleLabelAction',
			parameters: {
				articleIDs: this._articleIDs,
				categoryID: this._categoryID
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles successful AJAX requests.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		switch (data.returnValues.actionName) {
			case 'assignLabel':
				this._assignLabels(data);
			break;
			
			case 'getLabelForm':
				this._renderDialog(data);
			break;
		}
	},
	
	/**
	 * Renders the label assignment form overlay.
	 * 
	 * @param	object		data
	 */
	_renderDialog: function(data) {
		if (this._dialog === null) {
			this._dialog = $('#articleLabelForm');
			if (!this._dialog.length) {
				this._dialog = $('<div id="articleLabelForm" />').hide().appendTo(document.body);
			}
		}
		
		this._dialog.html(data.returnValues.template);
		this._dialog.wcfDialog({
			title: WCF.Language.get('wiki.articlen.label.assignLabels')
		});
		this._dialog.wcfDialog('render');
		
		$('#assignLabels').click($.proxy(this._save, this));
	},
	
	/**
	 * Saves label assignments for current article id.
	 */
	_save: function() {
		var $labelIDs = [ ];
		this._dialog.find('input').each(function(index, checkbox) {
			var $checkbox = $(checkbox);
			if ($checkbox.is(':checked')) {
				$labelIDs.push($checkbox.data('labelID'));
			}
		});
		
		this._proxy.setOption('data', {
			actionName: 'assignLabel',
			className: 'wiki\\data\\article\\label\\ArticleLabelAction',
			parameters: {
				articleIDs: this._articleIDs,
				labelIDs: $labelIDs,
				categoryID: this._categoryID
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Updates article labels.
	 * 
	 * @param	object		data
	 */
	_assignLabels: function(data) {
		// update article
		for (var $i = 0, $length = this._articleIDs.length; $i < $length; $i++) {
			this._editorHandler.update(this._articleIDs[$i], 'labelIDs', data.returnValues.labelIDs);
		}
		
		// close dialog and show a 'success' notice
		this._dialog.wcfDialog('close');
		this._notification.show();
	}
});

/**
 * Label manager for articles.
 * 
 * @param	string		link
 */
WIKI.Article.Label.Manager = Class.extend({
	/**
	 * deleted label id
	 * @var	integer
	 */
	_deletedLabelID: 0,
	
	/**
	 * dialog object
	 * @var	jQuery
	 */
	_dialog: null,
	
	/**
	 * list of labels
	 * @var	jQuery
	 */
	_labels: null,
	
	/**
	 * parsed label link
	 * @var	string
	 */
	_link: '',
	
	/**
	 * action proxy object
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * Initializes the label manager for articles.
	 * 
	 * @param	string		link
	 */
	init: function(link) {
		this._deletedLabelID = 0;
		this._link = link;
		
		this._labels = $('#articleLabelFilter .dropdownMenu');
		$('#manageLabel').click($.proxy(this._click, this));
		
		this._notification = new WCF.System.Notification(WCF.Language.get('wiki.article.label.management.addLabel.success'));
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
	},
	
	/**
	 * Handles clicks on the 'manage labels' button.
	 */
	_click: function() {
		this._proxy.setOption('data', {
			actionName: 'getLabelManagement',
			className: 'wiki\\data\\article\\ArticleAction',
			parameters: {
				data: {
					categoryID: $('#manageLabel').data('categoryID')
				}
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles successful AJAX requests.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		if (this._dialog === null) {
			this._dialog = $('<div id="labelManagement" />').hide().appendTo(document.body);
		}
		
		if (data.returnValues && data.returnValues.actionName) {
			switch (data.returnValues.actionName) {
				case 'add':
					this._insertLabel(data);
				break;
				
				case 'getLabelManagement':
					// render dialog
					this._dialog.empty().html(data.returnValues.template);
					this._dialog.wcfDialog({
						title: WCF.Language.get('wiki.article.label.management')
					});
					this._dialog.wcfDialog('render');
					
					// bind action listeners
					this._bindListener();
				break;
			}
		}
		else {
			// check if delete label id is present within URL (causing an IllegalLinkException if reloading)
			if (this._deletedLabelID) {
				var $regex = new RegExp('(\\?|&)labelID=' + this._deletedLabelID);
				window.location = window.location.toString().replace($regex, '');
			}
			else {
				// reload page
				window.location.reload();
			}
		}
	},
	
	/**
	 * Inserts a previously created label.
	 * 
	 * @param	object		data
	 */
	_insertLabel: function(data) {
		var $listItem = $('<li><a href="' + this._link + '&labelID=' + data.returnValues.labelID + '"><span class="badge label' + (data.returnValues.cssClassName ? ' ' + data.returnValues.cssClassName : '') + '">' + data.returnValues.label + '</span></a></li>');
		$listItem.find('a > span').data('labelID', data.returnValues.labelID).data('cssClassName', data.returnValues.cssClassName);
		
		var $divider = this._labels.find('.dropdownDivider:eq(0)').show();
		$listItem.insertBefore($divider);
		
		this._notification.show();
	},
	
	/**
	 * Binds event listener for label management.
	 */
	_bindListener: function() {
		$('#labelName').keyup($.proxy(this._updateLabels, this));
		$('#addLabel').disable().click($.proxy(this._addLabel, this));
		$('#editLabel').disable();
		
		this._dialog.find('.articleLabelList a.label').click($.proxy(this._edit, this));
	},
	
	/**
	 * Prepares a label for editing.
	 * 
	 * @param	object		event
	 */
	_edit: function(event) {
		var $label = $(event.currentTarget);
		
		// replace legends
		var $legend = WCF.Language.get('wiki.article.label.management.editLabel').replace(/#labelName#/, $label.text());
		$('#articleLabelManagementForm').data('labelID', $label.data('labelID')).children('legend').html($legend);
		
		// update text input
		$('#labelName').val($label.text()).trigger('keyup');
		
		// select css class name
		var $cssClassName = $label.data('cssClassName');
		$('#labelManagementList input').each(function(index, input) {
			var $input = $(input);
			
			if ($input.val() == $cssClassName) {
				$input.attr('checked', 'checked');
			}
		});
		
		// toggle buttons
		$('#addLabel').hide();
		$('#editLabel').show().click($.proxy(this._editLabel, this));
		$('#deleteLabel').show().click($.proxy(this._deleteLabel, this));
	},
	
	/**
	 * Edits a label.
	 */
	_editLabel: function() {
		this._proxy.setOption('data', {
			actionName: 'update',
			className: 'wiki\\data\\article\\label\\ArticleLabelAction',
			objectIDs: [ $('#articleLabelManagementForm').data('labelID') ],
			parameters: {
				data: {
					cssClassName: $('#labelManagementList input:checked').val(),
					label: $('#labelName').val()
				}
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Deletes a label.
	 */
	_deleteLabel: function() {
		var $title = WCF.Language.get('wiki.article.label.management.deleteLabel.confirmMessage').replace(/#labelName#/, $('#labelName').val());
		WCF.System.Confirmation.show($title, $.proxy(function(action) {
			if (action === 'confirm') {
				this._proxy.setOption('data', {
					actionName: 'delete',
					className: 'wiki\\data\\article\\label\\ArticleLabelAction',
					objectIDs: [ $('#articleLabelManagementForm').data('labelID') ]
				});
				this._proxy.sendRequest();
				
				this._deletedLabelID = $('#articleLabelManagementForm').data('labelID');
			}
		}, this));
	},
	
	/**
	 * Updates label text within label management.
	 */
	_updateLabels: function() {
		var $value = $('#labelName').val();
		if ($value) {
			$('#addLabel, #editLabel').enable();
		}
		else {
			$('#addLabel, #editLabel').disable();
			$value = WCF.Language.get('wiki.article.label.placeholder');
		}
		
		$('#labelManagementList').find('span.label').text($value);
	},
	
	/**
	 * Sends an AJAX request to add a new label.
	 */
	_addLabel: function() {
		var $labelName = $('#labelName').val();
		var $cssClassName = $('#labelManagementList input:checked').val();
		var $categoryID = $('#articleLabelManagementForm').data('categoryID');
		
		this._proxy.setOption('data', {
			actionName: 'add',
			className: 'wiki\\data\\article\\label\\ArticleLabelAction',
			parameters: {
				data: {
					cssClassName: $cssClassName,
					labelName: $labelName,
					categoryID: $categoryID
				}
			}
		});
		this._proxy.sendRequest();
		
		// close dialog
		this._dialog.wcfDialog('close');
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