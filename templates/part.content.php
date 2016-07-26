<script id="content-tpl" type="text/x-handlebars-template">
    {{#if note}}
		
	<div id="new-message">

		<div id="new-message-fields">
	
			<label id="to-label" for="target" class="transparency"><?php p($l->t('to')); ?></label>
			<input type="text" name="target" id="target" placeholder="<?php p($l->t('email address of recipient')); ?>" value="{{ note.target }}"></input>
			
			<label id="subject-label" for="subject" class="transparency"><?php p($l->t('Subject')); ?></label>
			<input type="text" name="subject" id="subject"  placeholder="<?php p($l->t('Subject')); ?>" value="{{note.title}}"></input>
			
			<label id="time-label" for="time" class="transparency"><?php p($l->t('Release Date')); ?></label>
			<input type="text" name="time" id="time"  placeholder="<?php p($l->t('Date')); ?>" value="{{note.time}}" readonly></input>
			
			<label id="timespan-label" for="timespan" class="transparency"><?php p($l->t('Timespan (in days)')); ?></label>
			<input type="text" name="timespan" id="timespan"  placeholder="<?php p($l->t('Timespan')); ?>" value="{{note.timespan}}"></input>
			
			<label id="trigger-label" for="trigger" class="transparency"><?php p($l->t('Reset by')); ?></label>	
			<select id="trigger" name="trigger"><option value="login" selected>login<option value="manual">manual</select>
			<button id="reset_button" type="button" style="display:none;"><?php p($l->t('Reset')); ?></button> 
			 
			<textarea name="msg" id="msg" placeholder="<?php p($l->t('Message …')); ?>">{{ note.content }}</textarea>
			<p>Warning: Please be aware that attachments larger then 20 MB will be blocked by most mail providers. Use password protected links to share large files instead.</p>
			<div id="attachment" style="float:left;" > {{note.attachment}} </div>  {{#if note.attachment}} <div class="new-message-attachments-action svg icon-delete" style="float:left;"></div> {{/if}}		
			
			<input type="button" id="mail_new_attachment" style="clear: both; display: block; " value="<?php p($l->t('Add attachment from Files')); ?>">
			<div class="save"><button id="save_button"><?php p($l->t('Save')); ?></button></div>		

	{{else}}
			<div class="input"><textarea disabled></textarea></div>
			<div class="save"><button disabled><?php p($l->t('Save')); ?></button></div>
	{{/if}}

		</div>
	</div>  

</script> <div id="editor"></div>


