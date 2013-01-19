{include file='header'}
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		WCF.TabMenu.init();
	});
	//]]>
</script>

<style type="text/css">
	#credits dd > ul > li {
		display: inline;
	}
	#credits dd > ul > li:after {
		content: ", ";
	}
	#credits dd > ul > li:last-child:after {
		content: "";
	}
</style>
<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wiki.acp.index{/lang}</h1>
	</hgroup>
</header>

<div class="tabMenuContainer marginTop" data-active="system" data-store="activeTabMenuItem">
	<nav class="tabMenu">
		<ul>
			<li><a href="#system" title="{lang}wiki.acp.index.system{/lang}">{lang}wiki.acp.index.system{/lang}</a></li>
			<li><a href="#credits" title="{lang}wiki.acp.index.credits{/lang}">{lang}wiki.acp.index.credits{/lang}</a></li>
			{event name='tabs'}
		</ul>
	</nav>
	
	{* system *}
	<div id="system" class="container containerPadding shadow hidden tabMenuContent">
		
		<fieldset>
			<legend>{lang}wiki.acp.index.system.software{/lang}</legend>
			
			<dl>
				<dt>{lang}wiki.acp.index.system.wiki.version{/lang}</dt>
				<dd>{PACKAGE_VERSION}</dd>
			</dl>
			<dl>
				<dt>{lang}wiki.acp.index.system.wiki.installationDate{/lang}</dt>
				<dd>{@INSTALL_DATE|time}</dd>
			</dl>
			<dl>
				<dt>{lang}wiki.acp.index.system.wcf.version{/lang}</dt>
				<dd>{WCF_VERSION}</dd>
			</dl>
		</fieldset>
		
		<fieldset>
			<legend>{lang}wiki.acp.index.system.server{/lang}</legend>
			
			<dl>
				<dt>{lang}wiki.acp.index.system.os{/lang}</dt>
				<dd>{$os}</dd>
			</dl>
			<dl>
				<dt>{lang}wiki.acp.index.system.webserver{/lang}</dt>
				<dd>{$webserver}</dd>
			</dl>
			<dl>
				<dt>{lang}wiki.acp.index.system.php{/lang}</dt>
				<dd>{PHP_VERSION}</dd>
			</dl>
			<dl>
				<dt>{lang}wiki.acp.index.system.sql.type{/lang}</dt>
				<dd>{$sqlType} &quot;{$dbName}&quot;</dd>
			</dl>
			<dl>
				<dt>{lang}wiki.acp.index.system.sql.version{/lang}</dt>
				<dd>{$sqlVersion}</dd>
			</dl>
			{if $load}
				<dl>
					<dt>{lang}wiki.acp.index.system.sql.load{/lang}</dt>
					<dd>{$load}</dd>
				</dl>
			{/if}
		</fieldset>
	</div>
	
	{* credits *}
	<div id="credits" class="container containerPadding shadow hidden tabMenuContent">
		<fieldset>
			<dl>
				<dt>{lang}wiki.acp.index.credits.developedBy{/lang}</dt>
				<dd><a href="{@RELATIVE_WCF_DIR}acp/dereferrer.php?url={"http://www.woltnet.com"|rawurlencode}" class="externalURL">WoltNet</a></dd>
			</dl>
			
			<dl>
				<dt>{lang}wiki.acp.index.credits.productManager{/lang}</dt>
				<dd>
					<ul>
						<li>Ren&eacute Gessinger</li>
						<li>Christoph Summerer</li>
					</ul>
				</dd>
			</dl>
			
			<dl>
				<dt>{lang}wiki.acp.index.credits.developer{/lang}</dt>
				<dd>
					<ul>
						<li>Ren&eacute Gessinger</li>
						<li>Jean-Marc Licht</li>
					</ul>
				</dd>
			</dl>
			
			<dl>
				<dt>{lang}wiki.acp.index.credits.designer{/lang}</dt>
				<dd>
					<ul>
						<li>Christoph Summerer</li>
					</ul>
				</dd>
			</dl>
			
			<dl>
				<dt>{lang}wiki.acp.index.credits.contributor{/lang}</dt>
				<dd>
					<ul>
						<li>Christoph Summerer</li>
					</ul>
				</dd>
			</dl>
			
			<dl>
				<dt></dt>
				<dd>Copyright &copy; 2012 WoltNet.</dd>
			</dl>
		</fieldset>
	</div>
	
	{event name='tabContent'}
</div>

{include file='footer'}
