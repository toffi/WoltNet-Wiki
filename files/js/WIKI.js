WIKI = {};

function showCategoryAddForm() {
	WCF.showDialog('categoryAddForm', {
		title: WCF.Language.get('wiki.category.categoryAdd')
	});
	return false;
}