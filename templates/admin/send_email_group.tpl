{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="mail" size="small"}
    <h3>{gt text="E-mail Users"}</h3>
</div>

<form id="send_email" class="z-form" method="post" action="{modurl modname='Segmentation' type='admin' func='sendEmailGroup'}">
    <div>
        <input id="send_email_csrftoken" name="csrftoken" type="hidden" value="{insert name='csrftoken'}" />
        <input id="send_email_formid" name="formid" type="hidden" value="send_email" />
        <fieldset>
            <legend>{gt text="Select recipients"}</legend>
            <div class="z-formrow">
                <label for="users_ugroup">{gt text="Group membership"}</label>
                <select id="users_ugroup" name="ugroup">
                    <option value="0" selected="selected">{gt text="Any group"}</option>
                    {section name=group loop=$groups}
                    <option value="{$groups[group].gid}">{$groups[group].name}</option>
                    {/section}
                </select>
            </div>
        </fieldset>

		<fieldset>
            <legend>{gt text='Compose message'}</legend>
            <p class="z-informationmsg">{gt text="Notice: This e-mail message will be sent to your address and to all other recipients you select. Your address will be the entered as the main recipient, and all your selected recipients will be included in the blind carbon copies ('Bcc') list. You can specify the number of 'Bcc' recipients to be added to each e-mail message. If the number of your selected recipients exceeds the number you enter here, then repeat messages will be sent until everyone in your selection has been mailed (you will receive a copy of each message). The allowed batch size may be set by your hosting provider."}</p>
            <div class="z-formrow">
                <label for="users_from">{gt text="Sender's name"}</label>
                <input id="users_from" name="sendmail[from]" type="text" size="40" />
            </div>
            <div class="z-formrow">
                <label for="users_rpemail">{gt text="Address to which replies should be sent"}</label>
                <input id="users_rpemail" name="sendmail[rpemail]" type="text" size="40" />
            </div>
            <div class="z-formrow">
                <label for="users_subject">{gt text="Subject"}</label>
                <input id="users_subject" name="sendmail[subject]" type="text" size="40" />
            </div>
            <div class="z-formrow">
                <label for="users_message">{gt text="Message"}</label>
                <textarea id="users_message" name="sendmail[message]" cols="50" rows="10"></textarea>
            </div>
            <div class="z-formrow">
                <label for="batchsize">{gt text="Send e-mail messages in batches"}</label>
                <span>
                    <input name="sendmail[batchsize]" type="text" id="batchsize" value="100" size="5" />
                    <em>{gt text="messages per batch"}</em>
                </span>
            </div>
        </fieldset>

        {notifydisplayhooks eventname='segmentation.ui_hooks.segmentation.form_edit' id=null}

        <div class="z-formbuttons z-buttons">
            {button type='submit' src='mail_generic.png' set='icons/extrasmall' __alt="Send e-mail to selected recipients" __title="Send e-mail to selected recipients" __text="Send e-mail to selected recipients"}
            <a href="{modurl modname='Segmentation' type='admin' func='main'}" title="{gt text='Cancel'}">{img modname='core' src='button_cancel.png' set='icons/extrasmall'  __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
        </div>
    </div>
</form>
{adminfooter}
