define(['jquery', 'core/notification', 'core/str'], function ($, notification, str) {
    var init = function(starttime, endtime) {
        var start = new Date(starttime * 1000);
        var end = new Date(endtime * 1000);
        $('[name=minute_start]')[0].selectedIndex = start.getMinutes();
        $('[name=hour_start]')[0].selectedIndex = start.getHours();
        $('[name=day_start]')[0].selectedIndex = start.getDate() - 1;
        $('[name=month_start]')[0].selectedIndex = start.getMonth();
        $('[name=year_start]')[0].selectedIndex = start.getFullYear() - 2000;

        $('[name=minute_end]')[0].selectedIndex = end.getMinutes();
        $('[name=hour_end]')[0].selectedIndex = end.getHours();
        $('[name=day_end]')[0].selectedIndex = end.getDate() - 1;
        $('[name=month_end]')[0].selectedIndex = end.getMonth();
        $('[name=year_end]')[0].selectedIndex = end.getFullYear() - 2000;
        if ($('#reactivate').length > 0) {
            $(document).on("change", "#reactivate", function() {
                if (this.checked) {
                    str.get_strings([
                        {'key': 'confirm'},
                        {'key': 'content_confirm_reactivate', component: 'block_evasys_sync'},
                        {'key': 'yes'},
                        {'key': 'no'}
                    ]).done(function(s) {
                        notification.confirm(s[0], s[1], s[2], s[3],
                            function () {
                                $($('[name=minute_start]')[0]).prop("disabled", false);
                                $($('[name=hour_start]')[0]).prop("disabled", false);
                                $($('[name=day_start]')[0]).prop("disabled", false);
                                $($('[name=month_start]')[0]).prop("disabled", false);
                                $($('[name=year_start]')[0]).prop("disabled", false);
                                $($('[name=direct_invite]')[0]).prop("disabled", false);
                                $('#only_end').prop("value", false);
                            },
                            function () {
                                $('#reactivate').prop("checked", false);
                            });
                    });
                } else {
                    $($('[name=minute_start]')[0]).prop("disabled", true);
                    $($('[name=hour_start]')[0]).prop("disabled", true);
                    $($('[name=day_start]')[0]).prop("disabled", true);
                    $($('[name=month_start]')[0]).prop("disabled", true);
                    $($('[name=year_start]')[0]).prop("disabled", true);
                    $($('[name=direct_invite]')[0]).prop("disabled", true);
                    $('#only_end').prop("value", true);
                }
            });
        }
    };

    return {
        init: init
    };
});