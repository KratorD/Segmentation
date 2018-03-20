{adminheader}
<div class="z-admin-content-pagetitle">
    {if isset($id)}{assign var='icontype' value='edit'}{else}{assign var='icontype' value='new'}{/if}
    {icon type=$icontype size="small"}
    <h3>{if isset($id)}{gt text="Edit eMail"}{else}{gt text="New eMail"}{/if}</h3>
</div>

{form cssClass="z-form" enctype="multipart/form-data"}
    <fieldset>
        <legend>{if isset($id)}{gt text="Edit eMail"}{else}{gt text="New eMail"}{/if}</legend>

        {formvalidationsummary}

        <div class="z-formrow">
            {formlabel for="codEmail" __text="Email ID"}
            {formtextinput id="codEmail" maxLength=50}
        </div>

		<div class="z-formrow">
            {formlabel for="description" __text="Description"}
			{formtextinput id="description" maxLength=255}
        </div>

        <div class="z-formrow">
            {formlabel for="body" __text="Email Body"}
			{formtextinput textMode="Multiline" id="body"}
        </div>

    </fieldset>

	{notifydisplayhooks eventname='segmentation.ui_hooks.segmentation.form_edit' id=null}

    <div class="z-buttons z-formbuttons">
        {formbutton class='z-bt-ok' commandName='create' __text='Save'}
        {formbutton class='z-bt-cancel' commandName='cancel' __text='Cancel'}
        {if isset($id)}{formbutton class="z-bt-delete z-btred" commandName="delete" __text="Delete" __confirmMessage='Delete'}{/if}
    </div>
{/form}
{adminfooter}