{if $__wcf->session->getPermission('mod.wiki.article.canManagePermissions') && $aclObjectTypeID}
		<fieldset id="permissions" class="permissionsContent tabMenuContent container containerPadding">
				<legend>{lang}wcf.acl.permissions{/lang}</legend>

				<dl id="articlePermissions" class="wide">
						<dt>{lang}wcf.acl.permissions{/lang}</dt>
						<dd></dd>
				</dl>

				{event name='permissionFields'}
		</fieldset>
{/if}