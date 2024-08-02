define(['jquery', 'core/str'], function($, str) {
    var init = function() {
        if ($('#deactivate_standard').length > 0) {
            $('#deactivate_standard').prop("disabled", false);
            $(document).on("change", "#deactivate_standard", function () {
                if (this.checked) {
                    $('#inputfieldset').prop("disabled", false);
                    $('#activate_standard').prop("value", false);
                    str.get_string('different_period', 'block_evasys_sync').done(function (s) {
                        $('#evaluationperiod').text(s);
                    });
                    enable();
                } else {
                    $('#inputfieldset').prop("disabled", true);
                    $('#activate_standard').prop("value", true);
                    str.get_string('standard_period', 'block_evasys_sync').done(function (s) {
                        $('#evaluationperiod').text(s);
                    });
                    disable();
                }
            });
        }
    };

    var disable = function() {
        $('[name=minute_start]')[0].disabled = true;
        $('[name=hour_start]')[0].disabled = true;
        $('[name=day_start]')[0].disabled = true;
        $('[name=month_start]')[0].disabled = true;
        $('[name=year_start]')[0].disabled = true;
        $('[name=minute_end]')[0].disabled = true;
        $('[name=hour_end]')[0].disabled = true;
        $('[name=day_end]')[0].disabled = true;
        $('[name=month_end]')[0].disabled = true;
        $('[name=year_end]')[0].disabled = true;
        $('#only_end').val(false);
        $('#datedisabled').val(true);
    };

    var enable = function() {
        $('[name=minute_start]')[0].disabled = false;
        $('[name=hour_start]')[0].disabled = false;
        $('[name=day_start]')[0].disabled = false;
        $('[name=month_start]')[0].disabled = false;
        $('[name=year_start]')[0].disabled = false;
        $('[name=minute_end]')[0].disabled = false;
        $('[name=hour_end]')[0].disabled = false;
        $('[name=day_end]')[0].disabled = false;
        $('[name=month_end]')[0].disabled = false;
        $('[name=year_end]')[0].disabled = false;
    };

    return {
        init: init
    };
});