/**
 * ownCloud - deadmanswitch
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author DMS Developers <dms-devs@web.de>
 * @copyright DMS Developers 2016


(function ($, OC) {

	$(document).ready(function () {
		$('#hello').click(function () {
			alert('Hello from your script file');
		});

		$('#echo').click(function () {
			var url = OC.generateUrl('/apps/deadmanswitch/echo');
			var data = {
				echo: $('#echo-content').val()
			};

			$.post(url, data).success(function (response) {
				$('#echo-result').text(response.echo);
			});

		});
	});

})(jQuery, OC);

*/
(function (OC, window, $, undefined) {
 
'use strict';

$(document).ready(function () {



var translations = {
    newNote: $('#new-note-string').text()
};

// this notes object holds all our notes
var Notes = function (baseUrl) {
    this._baseUrl = baseUrl;
    this._notes = [];
    this._activeNote = undefined;
};

Notes.prototype = {
    load: function (id) {
        var self = this;
        this._notes.forEach(function (note) {
            if (note.id === id) {
                note.active = true;
                self._activeNote = note;
            	
	    } else {
                note.active = false;
            }
        });
    },
    getActive: function () {
        return this._activeNote;
    },
    removeActive: function () {
        var index;
        var deferred = $.Deferred();
        var id = this._activeNote.id;
        this._notes.forEach(function (note, counter) {
            if (note.id === id) {
                index = counter;
            }
        });

        if (index !== undefined) {
            // delete cached active note if necessary
            if (this._activeNote === this._notes[index]) {
                delete this._activeNote;
            }

            this._notes.splice(index, 1);

            $.ajax({
                url: this._baseUrl + '/' + id,
                method: 'DELETE'
            }).done(function () {
                deferred.resolve();
            }).fail(function () {
                deferred.reject();
            });
        } else {
            deferred.reject();
        }
        return deferred.promise();
    },
    create: function (note) {
        var deferred = $.Deferred();
        var self = this;
        $.ajax({
            url: this._baseUrl,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(note)
        }).done(function (note) {
            self._notes.push(note);
            self._activeNote = note;
            self.load(note.id);
            deferred.resolve();
        }).fail(function () {
            deferred.reject();
        });
        return deferred.promise();
    },
    getAll: function () {
        return this._notes;
    },
    loadAll: function () {
        var deferred = $.Deferred();
        var self = this;
        $.get(this._baseUrl).done(function (notes) {
            self._activeNote = undefined;
            self._notes = notes;
            deferred.resolve();
        }).fail(function () {
            deferred.reject();
        });
        return deferred.promise();
    },
    updateActive: function (title, content, target, time, timespan, trigger, attachment) {
        var note = this.getActive();
        note.title = title;
        note.content = content;
	note.target = target;
	note.time = time;
	note.timespan = timespan;
	note.trigger = trigger;
	note.attachment = attachment;
	

        return $.ajax({
            url: this._baseUrl + '/' + note.id,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(note)
        });
    }
};

// this will be the view that is used to update the html
var View = function (notes) {
    this._notes = notes;
};

View.prototype = {
    renderContent: function () {
        var source = $('#content-tpl').html();
        var template = Handlebars.compile(source);
        var html = template({note: this._notes.getActive()});

        $('#editor').html(html);


		function addAttachment() {

			var self = this;
			OC.dialogs.filepicker(
				t('mail', 'Choose a file to add as attachment'),
			function (path) {

			document.getElementById('attachment').innerHTML = path;
			$('.new-message-attachments-action').fadeIn();
			});
		}


		function removeAttachment() {
			document.getElementById('attachment').innerHTML = '';
			$('.icon-delete').hide();
		}

		// handle saves

		var tmp = this._notes.getActive();

	        //fix default select value
	        if(tmp.trigger=="login" || tmp.trigger=="manual"){
                        document.getElementById('trigger').value = tmp.trigger;
                }

	
		document.getElementById('attachment').innerHTML = tmp.attachment;
		document.getElementById('time').value = tmp.time;
		
		var d = new Date (document.getElementById('time').value);
		
		$("#time").datepicker(
		{
			defaultDate: d,
			dateFormat: "yy/mm/dd",
			minDate: 0,
		        showOtherMonths: true,
            		selectOtherMonths: true,	
			
		});
		
		
		if(tmp.trigger=="manual"){
			$('#reset_button').show();	
		}
		var override;
		$('#reset_button').click(function () {
			override = document.getElementById('timespan').value;
			var time =  $("#time").datepicker("getDate");
			
			$( "#time" ).datepicker({dateFormat: 'yy/mm/dd'});
			time.setTime( time.getTime() - time.getTimezoneOffset()*60*1000);
			time.setTime(time.getTime() + override*24*60*60*1000);
			
			document.getElementById('time').value = time;
					
		});
	
		$('#mail_new_attachment').click(function () {
		
			addAttachment();
		});

	
		$('.icon-delete').click(function () {

			removeAttachment();
		});			



		var self = this;
		$('#save_button').click(function () {

            
		    var title = document.getElementById('subject').value;
		    var target = document.getElementById('target').value;
		    var content = document.getElementById('msg').value;
		    var trigger = document.getElementById('trigger').value;
		    var attachment = document.getElementById('attachment').innerHTML;
		    
			
				
		    var time =  $("#time").datepicker("getDate");
		    $( "#time" ).datepicker({dateFormat: 'yy/mm/dd'});
		    time.setTime( time.getTime() - time.getTimezoneOffset()*60*1000 );
		    if(override){
			time.setTime(time.getTime() + override*24*60*60*1000);
		    }
			
			
		    var diff = time - new Date();
		    var timespan =  Math.floor(diff / 86400000);	//converts milliseconds to days	
		    timespan = timespan + 1;
		
		    if(override){
				
			timespan = override;	
		    }			
			
			

		    self._notes.updateActive(title, content, target, time, timespan, trigger, attachment).done(function () {
			self.render();
			alert('Note saved');
		    }).fail(function () {
			alert('Could not update note, not found');
		    });
		});
    },
    renderNavigation: function () {
        var source = $('#navigation-tpl').html();
        var template = Handlebars.compile(source);
        var html = template({notes: this._notes.getAll()});

        $('#app-navigation ul').html(html);

        // create a new note
        var self = this;
        $('#new-note').click(function () {
            var note = {
                title: translations.newNote,
                content: 'This is a message from a "Dead Man Switch"! The sender of this message has intended you to receive the following message and it\'s contents in case of their unintentional absencse: '
            };

            self._notes.create(note).done(function() {
                self.render();
                $('#editor textarea').focus();
            }).fail(function () {
                alert('Could not create note');
            });
        });

        // show app menu
        $('#app-navigation .app-navigation-entry-utils-menu-button').click(function () {
            var entry = $(this).closest('.note');
            entry.find('.app-navigation-entry-menu').toggleClass('open');
        });

        // delete a note
        $('#app-navigation .note .delete').click(function () {
            var entry = $(this).closest('.note');
            entry.find('.app-navigation-entry-menu').removeClass('open');

            self._notes.removeActive().done(function () {
                self.render();
            }).fail(function () {
                alert('Could not delete note, not found');
            });
        });

        // load a note
        $('#app-navigation .note > a').click(function () {
            var id = parseInt($(this).parent().data('id'), 10);
            self._notes.load(id);
            self.render();
            $('#editor textarea').focus();
        });
    },
    render: function () {
        this.renderNavigation();
        this.renderContent();
    }
};

var notes = new Notes(OC.generateUrl('/apps/deadmanswitch/notes'));
var view = new View(notes);


notes.loadAll().done(function () {
    view.render();
}).fail(function () {
    alert('Could not load notes');
});


});

})(OC, window, jQuery);

