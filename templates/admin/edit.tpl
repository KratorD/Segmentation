{adminheader}
<div class="z-admin-content-pagetitle">
    {if isset($id)}{assign var='icontype' value='edit'}{else}{assign var='icontype' value='new'}{/if}
    {icon type=$icontype size="small"}
    <h3>{if isset($id)}{gt text="Edit Segmentation"}{else}{gt text="New Segmentation"}{/if}</h3>
</div>

{form cssClass="z-form" enctype="multipart/form-data"}
    <fieldset>
        <legend>{if isset($id)}{gt text="Edit Segmentation"}{else}{gt text="New Segmentation"}{/if}</legend>

        {formvalidationsummary}

        <div class="z-formrow">
            {formlabel for="newName" __text="New Group Name"}
            {formtextinput id="newName" maxLength=255}
        </div>
		<div class="z-formrow">
            {formlabel for="oldName" __text="or Select Group"}
			{formdropdownlist id='oldName' items=$groups}
        </div>

        <div class="z-formrow">
            {formlabel for="regDateFrom" __text="Registration date (from)"}
            {formdateinput id="regDateFrom"}
        </div>

		<div class="z-formrow">
            {formlabel for="regDateTo" __text="Registration date (to)"}
            {formdateinput id="regDateTo"}
        </div>

		<div class="z-formrow">
            {formlabel for="lastLoginFrom" __text="Last Login (from)"}
            {formdateinput id="lastLoginFrom"}
        </div>

		<div class="z-formrow">
            {formlabel for="lastLoginTo" __text="Last Login (to)"}
            {formdateinput id="lastLoginTo"}
        </div>

		{foreach item='property' from=$properties}
			<div class="z-formrow">
				{formlabel for="prop" text=$property.prop_attribute_name}
				{formtextinput id=$property.prop_id width='40em' maxLength=255 group="properties"}
			</div>
		{/foreach}

    </fieldset>

    <div class="z-buttons z-formbuttons">
        {formbutton class='z-bt-ok' commandName='create' __text='Generate'}
        {formbutton class='z-bt-cancel' commandName='cancel' __text='Cancel'}
        {if isset($id)}{formbutton class="z-bt-delete z-btred" commandName="delete" __text="Delete" __confirmMessage='Delete'}{/if}
    </div>
{/form}
{adminfooter}