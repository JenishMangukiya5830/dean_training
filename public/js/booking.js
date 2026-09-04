$("#imp-message").hide();

// DS=6, DSR=64 require Level 3 Emergency First Aid at Work
// CP=29, CPR=65 require Level 3 First Aid at Work
var emergencyFirstAidCourseIds = ['6', '64', '22'];
var firstAidAtWorkCourseIds = ['29', '65', '9'];

function showPackageSuggestion(courseId) {
    $('#package-suggestion').empty();
    if (!courseId || courseId === '') {
        return;
    }
    $('#package-suggestion').html(
        '<p class="text-muted"><i class="fa fa-spinner fa-spin"></i> Loading...</p>'
    );
    $.ajax({
        type: 'GET',
        url: '/DeanTraining/DeanTraining/sync/getpackagesbycourse/course_id/' + courseId,
        dataType: 'json',
        success: function (packages) {
            $('#package-suggestion').empty();
            if (!packages || packages.length === 0) {
                return;
            }
            var courseName = $('#courses option:selected').text();
            var html = '<div class="alert alert-info alert-dismissible" role="alert" style="margin-top:10px;">' +
                       '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                       '<h4><i class="fa fa-star"></i> Best Value for You!</h4>' +
                       '<p><strong>' + courseName + '</strong> is included in the following package deals — ' +
                       'you could save money by booking a package instead:</p>' +
                       '<ul class="list-unstyled">';
            $.each(packages, function (i, pkg) {
                // Strip course list in parentheses from name for cleaner display
                var displayName = pkg.name.replace(/\(.*?\)/g, '').trim();
                html += '<li style="margin-bottom:6px;">' +
                        '<strong>' + displayName + '</strong> &mdash; <strong>&pound;' + pkg.price + '</strong> &nbsp;' +
                        '<a href="' + pkg.book_url + '" class="btn btn-success btn-xs">' +
                        '<i class="fa fa-shopping-cart"></i> Book Package</a>' +
                        '</li>';
            });
            html += '</ul></div>';
            $('#package-suggestion').html(html);
        }
    });
}

function get_course_date() {
    $("#imp-message").hide();
    $('#dob-age-error').remove();
    var randomid = Math.floor((Math.random() * 100) + 1);
    var courseselected = $('#courses').val();
    var venueselected = $('#venue').val();
    console.log(courseselected, venueselected, "get_course_date");

    // Show/clear package suggestion whenever course changes
    showPackageSuggestion(courseselected);

    if (courseselected == '' || venueselected == '') {
        return;
    }

    // First Aid prerequisite alert
    if (emergencyFirstAidCourseIds.indexOf(courseselected) !== -1) {
        $("#imp-message")
            .removeClass('alert-warning')
            .addClass('alert-danger')
            .html(
                "<i class='fa fa-exclamation-triangle'></i> <strong>First Aid Qualification Required:</strong> " +
                "You must hold a valid <strong>Level 3 Emergency First Aid at Work</strong> qualification (or higher). " +
                "Your certificate must be provided before the course start date and have a minimum 12 months' validity remaining."
            )
            .slideDown();
    } else if (firstAidAtWorkCourseIds.indexOf(courseselected) !== -1) {
        $("#imp-message")
            .removeClass('alert-warning')
            .addClass('alert-danger')
            .html(
                "<i class='fa fa-exclamation-triangle'></i> <strong>First Aid Qualification Required:</strong> " +
                "You must hold a valid <strong>Level 3 First Aid at Work</strong> qualification (or higher). " +
                "Your certificate must be provided before the course start date and have a minimum 12 months' validity remaining."
            )
            .slideDown();
    }

    if (courseselected == '32') {
        $("#imp-message").html("This booking is for theory and practical to book theory only <a href='" + SITE_URL + "/book-now/course/course/31/'>click here</a>");
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
                // Parse start date from label "dd/mm/yyyy - dd/mm/yyyy"
                var startDate = this.start_date || (this.label ? this.label.split(' - ')[0].trim() : '');
                option.attr('value', this.value)
                      .attr('data-start', startDate)
                      .text(this.label);
                $('#course' + randomid).append(option);
            });
            $("#name" + randomid).html($('#courses option:selected').text());

            // Re-check age whenever the date dropdown changes (DOB may already be filled)
            $('#course' + randomid).on('change', function () {
                checkAgeOnDobChange();
            });

            if ($('#course' + randomid + ' option').length == 0) {
                $("#datesfoundbooking").html("<div class='row text-danger text-center'>No dates found for the selected course, please select any other course</div>");
                // $('#courses').val("");
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

            // Re-check age now that new dates are loaded (DOB may already be filled)
            checkAgeOnDobChange();
        }
    });
}

get_course_date();
if ($("#selected_course_id").val() != "") {
    $('#courses').val($("#selected_course_id").val());
    $('#venue').val("2");
    showPackageSuggestion($("#selected_course_id").val());
    get_course_date();
}

// Shared age check utility — returns true if the learner will be under 18 on courseStartStr (dd/mm/yyyy)
function isUnder18OnCourseDate(dobStr, courseStartStr) {
    if (!dobStr || !courseStartStr) { return false; }
    var dp = dobStr.trim().split('/');
    var sp = courseStartStr.trim().split('/');
    if (dp.length < 3 || sp.length < 3) { return false; }
    var dobObj    = new Date(parseInt(dp[2]), parseInt(dp[1]) - 1, parseInt(dp[0]));
    var courseObj = new Date(parseInt(sp[2]), parseInt(sp[1]) - 1, parseInt(sp[0]));
    var age = courseObj.getFullYear() - dobObj.getFullYear();
    var m   = courseObj.getMonth() - dobObj.getMonth();
    if (m < 0 || (m === 0 && courseObj.getDate() < dobObj.getDate())) { age--; }
    return age < 18;
}

function checkAgeOnDobChange() {
    var ageRestrictedCourses = ['6', '64', '29', '65', '10'];
    var courseId = $('#courses').val();

    // Remove any existing age error first
    $('#dob-age-error').remove();

    if (ageRestrictedCourses.indexOf(courseId) === -1) { return; }

    var dob       = $('#dob').val();
    var $selected = $('select[name="dates"] option:selected');
    var startDate = $selected.attr('data-start');

    if (!dob) { return; }

    if (isUnder18OnCourseDate(dob, startDate || '')) {
        $('#dob').closest('.form-group').after(
            '<p id="dob-age-error" class="text-danger" style="margin-top:5px;">' +
            '<i class="fa fa-exclamation-triangle"></i> ' +
            'You must be at least 18 years old on the course start date to enrol on this course.' +
            '</p>'
        );
    }
}

jQuery("#dob").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "dd/mm/yy",
    yearRange: "-100:+0",
    firstDay: 1,
    onSelect: function () {
        checkAgeOnDobChange();
    }
});

// Fire age check when DOB is edited manually (blur = tab/click away, input = live typing)
$('#dob').on('blur input', function () {
    checkAgeOnDobChange();
});

$('#bookingForm').submit(function (e) {

    if (!$(this).isValid()) {
        return false;
    }

    // Age check for restricted courses
    var ageRestrictedCourses = ['6', '64', '29', '65', '10'];
    var courseId = $('#courses').val();
    if (ageRestrictedCourses.indexOf(courseId) !== -1) {
        var dob       = $('#dob').val();
        var $selected = $('select[name="dates"] option:selected');
        var startDate = $selected.attr('data-start');
        if (isUnder18OnCourseDate(dob, startDate || '')) {
            $('#dob-age-error').remove();
            $('#dob').closest('.form-group').after(
                '<p id="dob-age-error" class="text-danger" style="margin-top:5px;">' +
                '<i class="fa fa-exclamation-triangle"></i> ' +
                'You must be at least 18 years old on the course start date to enrol on this course.' +
                '</p>'
            );
            $('#dob').closest('.form-group')[0].scrollIntoView({ behavior: 'smooth' });
            return false;
        }
    }

    $('#submitButton').hide();

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
