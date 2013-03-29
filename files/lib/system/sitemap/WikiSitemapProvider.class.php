<?php
namespace wiki\system\sitemap;
use wiki\data\category\WikiCategoryNodeList;
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
    /**
    * @see	wcf\system\sitemap\ISitemapProvider::getTemplate()
    */
    public function getTemplate() {
        $this->categoryNodeList = new WikiCategoryNodeList('com.woltnet.wiki.category');
        $this->categoryNodeList->setMaxDepth(0);

        WCF::getTPL()->assign(array(
            'categoryList' 		=> $this->categoryNodeList
        ));

        return WCF::getTPL()->fetch('sitemapWiki', 'wiki');
    }
}
