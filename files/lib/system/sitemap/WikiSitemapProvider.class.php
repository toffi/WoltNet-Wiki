<?php
namespace wiki\system\sitemap;
use wiki\data\category\WikiCategoryNodeTree;
use wcf\system\sitemap\ISitemapProvider;
use wcf\system\WCF;

/**
 * Provides a sitemap for wiki.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2013 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.article
 * @category 	WoltNet Wiki
 */
class WikiSitemapProvider implements ISitemapProvider {
    public $categoryNodeList = null;
    public $categoryNodeTree = null;
    /**
    * @see	wcf\system\sitemap\ISitemapProvider::getTemplate()
    */
    public function getTemplate() {
        $this->categoryNodeTree = new WikiCategoryNodeTree('com.woltnet.wiki.category');
        $this->categoryNodeList = $this->categoryNodeTree->getIterator();
        $this->categoryNodeList->setMaxDepth(0);

        WCF::getTPL()->assign(array(
            'categoryList' 		=> $this->categoryNodeList
        ));

        return WCF::getTPL()->fetch('sitemapWiki', 'wiki');
    }
}
