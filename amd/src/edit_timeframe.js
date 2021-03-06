define(['jquery', 'core/modal_factory', 'core/templates', 'core/str', 'core/url'], function ($, ModalFactory, Templates, str, url) {

    var get_timestamp = function () {
        var startmin = $('[name=minute_start]').last()[0].selectedIndex;
        var starthour = $('[name=hour_start]').last()[0].selectedIndex;
        var startday = $('[name=day_start]').last()[0].selectedIndex;
        var startmonth = $('[name=month_start]').last()[0].selectedIndex;
        var startyear = $('[name=year_start]').last()[0].selectedIndex + 2000;

        var endmin = $('[name=minute_end]').last()[0].selectedIndex;
        var endhour = $('[name=hour_end]').last()[0].selectedIndex;
        var endday = $('[name=day_end]').last()[0].selectedIndex;
        var endmonth = $('[name=month_end]').last()[0].selectedIndex;
        var endyear = $('[name=year_end]').last()[0].selectedIndex + 2000;
        var startdate = new Date(startyear, startmonth, startday + 1, starthour, startmin);
        var enddate = new Date(endyear, endmonth, endday + 1, endhour, endmin);
        return {
            'starttime': startdate.getTime() / 1000,
            'endtime': enddate.getTime() / 1000
        };
    };

    var ready = function () {
        if (this.readyState === 4 && this.status >= 200 && !(this.responseText == "")) {
            require(['core/notification', ], function (notification) {
                str.get_string('save_failure', 'block_evasys_sync').done(function (s) {
                    notification.alert('Error', s, 'OK');
                });
            });
        }
    };

    var initialize = function (startdates, enddates) {
        var i = 0;
        str.get_string('edit_time', 'block_evasys_sync').done(function (s) {
            var dummy  = true;
            while (dummy) {
                var element = $("#timeediturl_" + i);
                if (element.length <= 0) {
                    dummy = false;
                    break;
                }
                element.on('click', function (e) {
                    var clickedLink = $(e.currentTarget);
                    ModalFactory.create({
                        type: ModalFactory.types.SAVE_CANCEL,
                        title: s,
                        body: Templates.render('block_evasys_sync/edittime', {}),
                    })
                        .then(function (modal) {
                            var root = modal.getRoot();
                            root.on('modal-save-cancel:save', function () {
                                var saveid = new URL(clickedLink.prop('href')).searchParams.get("id");
                                var times = {};
                                var settime = $('[name=use_standardtime]').last()[0].checked;
                                if (settime) {
                                    times = get_timestamp();
                                }
                                times.category = saveid;
                                var xhttp = new XMLHttpRequest();
                                xhttp.onreadystatechange = ready;
                                var s = url.relativeUrl("/blocks/evasys_sync/alterstandardtime.php", times, true);
                                xhttp.open('GET', s);
                                xhttp.send();
                                var elementNo = clickedLink.prop('id').split("_")[1];
                                startdates[elementNo] = times.starttime;
                                enddates[elementNo] = times.endtime;
                                if (settime) {
                                    str.get_string('time_set', 'block_evasys_sync').done(function (s) {
                                        document.getElementById('timehint_' + elementNo).innerHTML = s;
                                    });
                                } else {
                                    document.getElementById('timehint_' + elementNo).innerHTML = "";
                                }
                            });
                            modal.show();
                            require(['block_evasys_sync/initialize'], function(timesetter) {
                                var elementNo = clickedLink.prop('id').split("_")[1];
                                if (startdates[elementNo] === null || startdates[elementNo] === undefined) {
                                    startdates[elementNo] = Date.now() / 1000;
                                    enddates[elementNo] = Date.now() / 1000;
                                } else {
                                    $('[name=use_standardtime]').last()[0].checked = true;
                                }
                                timesetter.init(startdates[elementNo], enddates[elementNo]);
                            });
                        });
                });
                i++;
            }
        });
    };
    return {
        initialize: initialize,
    };
});