$("#imp-message").hide();

function get_course_date() {
    $("#imp-message").hide();
    var randomid = Math.floor((Math.random() * 100) + 1);
    var courseselected = $('#courses').val();
    var venueselected = $('#venue').val();

    if (courseselected == '' || venueselected == '') {
        return;
    }

    if (courseselected == '32') {
        $("#imp-message").html("This booking is for theory and practical to book theory only <a href='/book-now/course/course/31/'>click here</a>");
        $("#imp-message").slideDown();
    }


    $.ajax({
        type: "POST",
        url: "https://isecuredirect.com/booking/coursedate/", // Name of the php files
        data: {course: courseselected, venue: venueselected},
        dataType: 'json',
        success: function (data) {
            //create the date drop down
            $("#datesfoundbooking").html("<div class='form-group'><label for='course" + randomid + "'>Please select date for <span id='name" + randomid + "'>Loading...</span> <span class='text-danger'>*</span></label><div class='input-group'><span class='input-group-addon'><i class='fa fa-calendar'></i></span><select name='dates' id='course" + randomid + "' class='form-control' data-validation='required'></select></div></div>");
            $(data.dates).each(function () {
                var option = $('<option />');
                option.attr('value', this.value).text(this.label);
                $('#course' + randomid).append(option);
            });
            $("#name" + randomid).html($('#courses option:selected').text());
            if ($('#course' + randomid + ' option').length == 0) {
                $("#datesfoundbooking").html("<div class='row text-danger text-center'>No dates found for the selected course, please select any other course</div>");
                $('#courses').val("");
                $('.selectpicker').selectpicker('render');

            } else {
                var fullprice = data.price.full;
                var orginalPrice = parseInt(parseInt(data.price.full) * 0.10 + parseInt(data.price.full)) + 1;
                var depositprice = data.price.deposit;
                var pod = data.pod;
                var offlineprice = ((10 / 100) * fullprice) + parseInt(fullprice);
                $("#datesfoundbooking").append("<label>Payment Type <span class='text-danger'>*</span></label><br/><small>For the book pay on the day there is 10% administration charge and your place is not guaranteed</small>");

                if ($('#promotionStatus').val() == '1') {
                    $("#datesfoundbooking").append("<div class='radio fixheight'> <label> <input id='PayFull' type='radio' value='1' name = 'ptype' checked = '' > <b><span class='price-cut'>&pound;" + orginalPrice + "</span> &pound;<span id='PayFullAmount'>" + fullprice + "</span> 10% off</b> - Pay Full Online (Guaranteed Place) </label></div>");
                } else {
                    $("#datesfoundbooking").append("<div class='radio fixheight'> <label> <input id='PayFull' type='radio' value='1' name = 'ptype' checked = '' > <b>&pound;<span id='PayFullAmount'>" + fullprice + "</span></b> - Pay Full Online (Guaranteed Place) </label></div>");
                }

                $("#datesfoundbooking").append("<div class='radio'> <label> <input id='PayDeposit' type='radio' value='2' name='ptype' > <b>&pound;<span id='PayDepositAmount'>" + depositprice + "</span></b> - Pay Deposit Online (Guaranteed Place) </label></div>");
                if (pod == 1) {
                    $("#datesfoundbooking").append("<div class='radio'> <label> <input id='PayOnDay' type='radio' value='3' name='ptype'> <b>&pound;<span id='PayOnDayAmount'>" + offlineprice + "</span></b> - Pay on Day (Place Not Guaranteed)</label></div>");
                }
                $("#datesfoundbooking").append("<small>Our course prices include examination, certification, and all training materials. No hidden costs and no more to pay.</small>");
            }

            if ($("#selected_date_id").val() != "") {
                $('#course' + randomid).val($("#selected_date_id").val());
            }

            if ($("#selected_course_id").val() != '') {
                $('#courses').val($("#selected_course_id").val());
                $('.selectpicker').selectpicker('render');
            }
        }
    });
}

get_course_date();
if ($("#selected_course_id").val() != "") {
    $('#courses').val($("#selected_course_id").val());
    $('#venue').val("2");
    get_course_date();
}

jQuery("#dob").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "dd/mm/yy",
    yearRange: "-100:+0",
    maxDate: "-18Y",
    firstDay: 1
});

$('#bookingForm').submit(function (e) {

    if (!$(this).isValid()) {
        return false;
    } else {
        $('#submitButton').hide();
    }

    try {
        var paymentType = $("input[name='ptype']:checked")[0].id;
        var amount = $("#" + paymentType + "Amount").html();
        wc_transaction_ypbib
        (
            Math.random().toString(36).substring(7),
            amount,
            '0',
            '0',
            {
                'Payment Type': paymentType,
                'Full Name': $('#firstname').val() + " " + $('#midname').val() + $('#lastname').val(),
                'Email': $('#email').val(),
                'phoneNumber': $('#mobile').val(),
                'course': $("#courses option:selected").text(),
                'date': $("select[name='dates'] option:selected").text(),
                'Medium': $("#refer option:selected").text()
            }
        );
    } catch (err) {
    }
    return true;
});
