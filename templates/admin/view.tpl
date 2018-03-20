{ajaxheader modname='Segmentation' ui=true}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Segmentation'}</h3>
</div>

{insert name="getstatusmsg"}

<table class="z-datatable">
    <thead>
        <tr>
            <th><a class='{$sort.class.codEmail}' href='{$sort.url.codEmail|safetext}'>{gt text='Email ID'}</a></th>
            <th><a class='{$sort.class.description}' href='{$sort.url.description|safetext}'>{gt text='Description'}</a></th>
			<th><a class='{$sort.class.createdUserId}' href='{$sort.url.createdUserId|safetext}'>{gt text='Created by'}</a></th>
			<th><a class='{$sort.class.createdTime}' href='{$sort.url.createdTime|safetext}'>{gt text='Created at'}</a></th>
			<th><a class='{$sort.class.updatedUserId}' href='{$sort.url.updatedUserId|safetext}'>{gt text='Updated by'}</a></th>
			<th><a class='{$sort.class.updatedTime}' href='{$sort.url.updatedTime|safetext}'>{gt text='Updated at'}</a></th>
			<th class="z-nowrap z-right">{gt text='Actions'}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$mails item='mail'}
        <tr class="{cycle values="z-odd,z-even"}">
            <td>{$mail->getCodEmail()|safetext}</td>
            <td>{$mail->getDescription()|safetext}</td>
			<td>
				 {usergetvar name='uname' uid==$mail.createdUserId assign='cr_uname'}
				 {if $modvars.ZConfig.profilemodule ne ''}
					{* if we have a profile module link to the user profile *}
					{modurl modname=$modvars.ZConfig.profilemodule type='user' func='view' uname=$cr_uname assign='profileLink'}
					{assign var='profileLink' value=$profileLink|safetext}
					{assign var='profileLink' value="<a href=\"`$profileLink`\">`$cr_uname`</a>"}
				{else}
					{* else just show the user name *}
					{assign var='profileLink' value=$cr_uname}
				{/if}
				{$profileLink}
			</td>
			<td>{$mail.createdTime|dateformat}</td>
			<td>
				{usergetvar name='uname' uid=$mail.updatedUserId assign='lu_uname'}
				{if $modvars.ZConfig.profilemodule ne ''}
					{* if we have a profile module link to the user profile *}
					{modurl modname=$modvars.ZConfig.profilemodule type='user' func='view' uname=$lu_uname assign='profileLink'}
					{assign var='profileLink' value=$profileLink|safetext}
					{assign var='profileLink' value="<a href=\"`$profileLink`\">`$lu_uname`</a>"}
				{else}
					{* else just show the user name *}
					{assign var='profileLink' value=$lu_uname}
				{/if}
				{$profileLink}
			</td>
			<td>{$mail.updatedTime|dateformat}</td>
            <td class="z-nowrap z-right">
                <a href="{modurl modname="Segmentation" type="admin" func="displayMail" id=$mail->getId()}">{img modname='core' set='icons/extrasmall' src='14_layer_visible.png' __title='View' __alt='View' class='tooltips'}</a>
                <a href="{modurl modname="Segmentation" type="admin" func="editMail" id=$mail->getId()}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}</a>
				<a href="{modurl modname="Segmentation" type="admin" func="deleteMail" id=$mail->getId()}">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png' __title='Delete' __alt='Delete' class='tooltips'}</a>
            </td>
        </tr>
        {foreachelse}
        <tr class='z-datatableempty'><td colspan='6' class='z-center'>{gt text='No records. Try a different type.'}</td></tr>
        {/foreach}
    </tbody>
</table>

{pager rowcount=$rowcount limit=$modvars.Segmentation.perpage posvar='startnum'}
{adminfooter}
<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>