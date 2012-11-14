{capture assign='sidebar'}
	<nav id="sidebarContent" class="sidebarContent">
		{if $__boxSidebar|isset && $__boxSidebar}
			<ul>
				{@$__boxSidebar}
			</ul>
		{/if}
	</nav>
{/capture}