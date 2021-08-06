define(['jquery', 'core/notification', 'core/str'], function($, notification, str) {

    var show_dialog_success = function() {
        str.get_strings([
            {'key' : 'title_success', component: 'block_evasys_sync'},
            {'key' : 'content_success', component: 'block_evasys_sync'},
            {'key' : 'confirm_box', component: 'block_evasys_sync'},
        ]).done(function(s) {
                notification.alert(s[0], s[1], s[2]);
        }
        ).fail(notification.exception);
    };

    var show_dialog_up_to_date = function() {
        str.get_strings([
            {'key' : 'title_uptodate', component: 'block_evasys_sync'},
            {'key' : 'content_uptodate', component: 'block_evasys_sync'},
            {'key' : 'confirm_box', component: 'block_evasys_sync'},
        ]).done(function(s) {
                notification.alert(s[0], s[1], s[2]);
        }
        ).fail(notification.exception);
    };

    var show_dialog_nostudents = function() {
        str.get_strings([
            {'key' : 'title_failure', component: 'block_evasys_sync'},
            {'key' : 'content_nostudents', component: 'block_evasys_sync'},
            {'key' : 'confirm_box', component: 'block_evasys_sync'},
        ]).done(function(s) {
                notification.alert(s[0], s[1], s[2]);
        }
        ).fail(notification.exception);
    };

    var show_dialog_failure = function() {
        str.get_strings([
            {'key' : 'title_failure', component: 'block_evasys_sync'},
            {'key' : 'content_failure', component: 'block_evasys_sync'},
            {'key' : 'confirm_box', component: 'block_evasys_sync'},
        ]).done(function(s) {
                notification.alert(s[0], s[1], s[2]);
        }
        ).fail(notification.exception);
    };

    return {
        show_dialog_failure: show_dialog_failure,
        show_dialog_no_students: show_dialog_nostudents,
        show_dialog_success: show_dialog_success,
        show_dialog_up_to_date: show_dialog_up_to_date
    };
});