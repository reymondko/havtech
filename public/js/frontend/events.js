function filterEventstype(eventtype_id){
    $("#event_type").val(eventtype_id);
    $(".e-nav ").removeClass("active");
    $("#event"+eventtype_id).addClass("active");
    LoadEvents();
}

function filterEventsdate(event_date){
    $("#event_date").val(event_date);
    $("#date_filter_val").html(event_date);
    $('#datedrop').removeClass('w--open');
    if(event_date == null){
        $("#date_filter_val").html('Select Date');
    }
    LoadEvents();
}

function filterEventslocation(loc){
    $('#w-dropdown-list-2').removeClass('w--open');
    if(loc == null){
        $("#location_filter_val").html('Select Location');
        $("#event_location_city").val(null);
        $("#event_location_state").val(null);
    }else{
        if (loc.indexOf(',') > -1) { 
            var locarr=loc.split(','); 
            $("#event_location_city").val(locarr[0]);
            $("#event_location_state").val(locarr[1]);
        }
        else{
            $("#event_location_city").val(loc);
            $("#event_location_state").val(loc);
        }
    }
    LoadEvents();
}


function LoadEvents(){
    console.log($("#event_type").val());
    $("#events-container").hide().html("");
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
    });
    $.ajax({
        url: "/events_filtered",
        type: 'POST',
        data: {eventtype_id: $("#event_type").val(),event_date :$("#event_date").val(),
        city :$("#event_location_city").val(),
        state :$("#event_location_state").val()},
        success: function (data) {
            //console.log(data);
            if(data){
               $("#events-container").hide().html(data).fadeIn();
            }
            else{
                $("#events-container").hide().html("<center>No Events Found</center>").fadeIn();
            }
        }
    });
}


var regex = /^(.+?)(\d+)$/i;
var attendeeIndex = $(".attendees").length;
function cloneAttendee(){
    console.log("clicked  "+attendeeIndex + " waaaa");
    var nextnum=$(".attendees_hldr").length; // + 1
    
    //var defaultc=$('#directions_button1').val();        
    $("#attendees0").clone()
        .appendTo(".appender")
        .attr("id", "attendees" +  nextnum).show()
        .find("*")
        .each(function() {
            var id = this.id || "";
            var match = id.match(regex) || [];
            if (match.length == 3) {
                this.id = match[1] + (attendeeIndex);
            }
        }).find(" :input:not(:checkbox):not(:radio)").val("");
    $('#attendees'+  nextnum).prepend('<a onclick="removeClone('+nextnum+')" class="add-icon w-inline-block"><div class="add">-</div><div class="orange-text">Remove Attendee</div></a>')
     //.find(" :input:not(:checkbox):not(:radio)").val("").on('click', 'button.clone', cloneSchedule)   
     attendeeIndex++;
    //console.log("Total  => " + attendeeIndex);
    var suma = nextnum+1;
    $("#attendees_num").val(suma);
    calculateTotal();
    return false;
}

function removeClone(attendeeid){
    var r = confirm("Are you sure you want to delete this Attendee?");
    if (r == true) {
        $("#attendees" + attendeeid).fadeOut(300, function() { $("#attendees" + attendeeid).remove(); });
        var attendee_count=$("#attendees_num").val()-1;
        $("#attendees_num").val(attendee_count); 
        calculateTotal();      
    } else {
        return false();
    }
}
calculateTotal();
function calculateTotal(price){
    
    var attendees = $("#attendees_num").val();
    $(".total-attendee").html(attendees);
    if(attendees > 1 ){
        $("#num_attendees").show();
    }
    else{ $("#num_attendees").hide(); }
    var regfee = $("#registration_fee").val();

    var attendees = $("#attendees_num").val();
    var sched_total=0;
    $('.schedules:checkbox:checked').each(function () {
        var schedid= $(this).val();
        sched_total = sched_total + parseFloat($("#price"+schedid).val());
        
    });
    var schedtotal =sched_total * attendees;
    console.log(schedtotal);
    var total =  regfee * attendees;
    
    console.log(total);
    var overallTotal= total+ schedtotal;
    $("#overallTotalhtml").html("$"+ parseFloat(overallTotal).toFixed(2));
    
    $("#overalltotal").val(parseFloat(overallTotal).toFixed(2));
}

$('.cc-input').change(function(e){
   if(e.target.value != null){
       $('.check-input').prop('required',false);
       $('.cc-input').prop('required',true);
   }
})

$('.check-input').change(function(e){
    if(e.target.value != null){
        $('.cc-input').prop('required',false);
        $('.check-input').prop('required',true);
    }
 })

 $(document).ready(function(){
    var requiredCheckboxes = $('.schedules_options :checkbox[required]');
    requiredCheckboxes.change(function(){
        if(requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
        } else {
            requiredCheckboxes.attr('required', 'required');
        }
    });

    $("input[name=payment_method]").on("change",function(){
        if($(this).val()=="cc"){
            $("#payment_cc").fadeIn();
            $("#payment_check").fadeOut();
            
            $("#Business-Check-Number").removeAttr('required');

            $("#Card-Number").attr('required', 'required');
            $("#Name-On-Card").attr('required', 'required');
            $("#Expiration-Date-Month").attr('required', 'required');
            $("#Expiration-Date-Year").attr('required', 'required');
            $("#CVV-CVC").attr('required', 'required');
            $("#Billing-Address").attr('required', 'required');
            //$("#Apt-Suite").attr('required', 'required');
            $("#Billing-City").attr('required', 'required');
            $("#Billing-State").attr('required', 'required');
            $("#Billing-Zip").attr('required', 'required');
        }
        else{
            $("#payment_check").fadeIn();
            $("#payment_cc").fadeOut();
            
            $("#Business-Check-Number").attr('required', 'required');

            $("#Card-Number").removeAttr('required');
            $("#Name-On-Card").removeAttr('required');
            $("#Expiration-Date-Month").removeAttr('required');
            $("#Expiration-Date-Year").removeAttr('required');
            $("#CVV-CVC").removeAttr('required');
            $("#Billing-Address").removeAttr('required');
            //$("#Apt-Suite").removeAttr('required');
            $("#Billing-City").removeAttr('required');
            $("#Billing-State").removeAttr('required');
            $("#Billing-Zip").removeAttr('required');

        }
        
        console.log($(this).val());
    });

 })