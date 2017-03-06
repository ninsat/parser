$(document).ready(function(e) {
    $('.protocol').selectpicker();
    $('#count-export').hide();

    var stat = $('#statAds').text();

    if (stat !== undefined) {
        $("#export-quantity").attr({
            "max" : stat,        // substitute your own
            "min" : 1          // values (or variables) here
        });
    }


});

$(".incr-btn").on("click", function (e) {
    var $button = $(this);
    var oldValue = $button.parent().find('.quantity').val();
    $button.parent().find('.incr-btn[data-action="decrease"]').removeClass('inactive');
    if ($button.data('action') == "increase") {
        var newVal = parseFloat(oldValue) + 1;
    } else {
        if (oldValue > 1) {
            var newVal = parseFloat(oldValue) - 1;
        } else {
            newVal = 1;
            $button.addClass('inactive');
        }
    }
    $button.parent().find('.quantity').val(newVal);
    e.preventDefault();
});

$("#count-export-manual").on("click", function () {
    $("#count-export").show();
});

$("#count-export-all").on("click", function () {
    $("#count-export").hide();
});
