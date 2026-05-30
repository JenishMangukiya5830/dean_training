// DS=6, DSR=64, CP=29, CPR=65 — require FAW/EFA qualification
var securityCourseIds = ['6', '64', '29', '65'];

function get_course_date() {
    var nameofdropdown = "";
    var packageselected = $('#package').val();
    var venueselected = $('#venue').val();
    $("#datesfoundbooking").html("");
    $("#pkg-imp-message").hide();

    if (packageselected == '' || venueselected == '') {
        return;
    }

    //create the window
    $("#datesfoundbooking").html("<div class='row text-danger text-center'>Loading...</div>");
    $.ajax({
        type: "POST",
        url: "https://isecuredirect.com/package/packagedate/", // Name of the php files
        data: {package: packageselected, venue: venueselected},
        dataType: 'json',
        success: function (data)
        {
            $("#datesfoundbooking").html("");

            // Check API response: if any course in this package is DS/DSR/CP/CPR, show FAW alert
            var hasSecurityCourse = false;
            $.each(data.courses.dates, function (key, value) {
                if (securityCourseIds.indexOf(String(value.name)) !== -1) {
                    hasSecurityCourse = true;
                }
            });

            if (hasSecurityCourse) {
                $("#pkg-imp-message")
                    .html(
                        "<i class='fa fa-exclamation-triangle'></i> <strong>FAW / EFA Qualification Required:</strong> " +
                        "This package includes a security course (DS / DSR / CP / CPR). You must hold a valid " +
                        "<strong>First Aid at Work (FAW)</strong> or <strong>Emergency First Aid at Work (EFA)</strong> " +
                        "qualification before attending."
                    )
                    .slideDown();
            }

            $.each(data.courses.dates, function (key, value) {
                nameofdropdown = value.name;
                $("#datesfoundbooking").append("<div class='form-group'><label for='course" + nameofdropdown + "'>Please select date for <span>" + key + "</span> <span class='text-danger'>*</span></label><div class='input-group'><span class='input-group-addon'><i class='fa fa-calendar'></i></span><select name='course" + nameofdropdown + "' id='course" + nameofdropdown + "' class='form-control datescheck' data-validation='required'></select></div></div>");
                $(value.date).each(function ()
                {
                    var option = $('<option />');
                    option.attr('value', this.value).text(this.label);
                    $('#course' + nameofdropdown).append(option);
                });
            });

            var fullprice = data.courses.price;
            $("#datesfoundbooking").append("<label>Payment Type <span class='text-danger'>*</span></label><br/>");
            $("#datesfoundbooking").append("<div class = 'radio fixheight'> <label> <input type = 'radio' value = '1' name = 'ptype' checked = '' > <b>&pound;" + fullprice + "</b> - Pay Full Online</label></div>");
            $("#datesfoundbooking").append("<div class = 'radio'> <label> <input type = 'radio' value = '2' name = 'ptype' ><b>&pound;" + parseFloat(fullprice / 2).toFixed(2) + "</b> - Pay Deposit Online</label></div>");

            $('.datescheck').each(function () {
                if ($(this).val() == null) {
                    $("#datesfoundbooking").html("<div class='row text-danger text-center'>No dates found for the selected package, please select any other package</div>");
                    $('#package').val("");
                    $("#package").trigger("chosen:updated");
                }
            });
        }
    });
}

if ($("#selected_package_id").val() != "") {
    $('#package').val($("#selected_package_id").val());
    $('#venue').val("2");
    get_course_date();
}
