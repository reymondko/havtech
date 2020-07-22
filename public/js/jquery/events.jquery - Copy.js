$(document).ready(function() {
    
    $("#eventoverviewform").validate({
        rules: {
            event_name: "required",
            start_date: "required",
            end_date: "required",
            "start_time[]": "required",
            "end_time[]": "required",
        },
        submitHandler: function(form) {
            $('#eventoverviewform').submit();
        }
    });
    var pathname = window.location.pathname;
    $(".page-header").css("font-size", "24px");
    $(".page-header").css("font-weight", "bold");
    $( "input.date-picker" ).datepicker({changeMonth: true,changeYear: true    });
    $(".datepicker button").prop("type", "button");
    $(".datepicker button").on("click",function(){
        $(this).closest(".datepicker").find(".date-picker").datepicker("show");
    });
    $(".datepicker button").prop("type", "button");
    $( "input.daterangepicker" ).daterangepicker();
    $(".date button").prop("type", "button");
    $(".event_schedule .date button").on("click",function(){
        $(this).closest(".date").find(".daterangepicker").datepicker("show");
    });
    $('input.timepicker').timepicker({});
    $('.description').summernote({        height:120,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
            ['table', ['table']],
            ['insert', ['link','table']],,
            ['view', ['fullscreen', 'codeview']],
          ],/*callbacks: {onImageUpload: function(image) {
            uploadImage(image[0],this);
        }}*/    });

    $('#eventoverviewform').submit(function(){
        event.preventDefault();
        var banner_image = $('#banner_image').prop('files')[0];
        var formData =new FormData($(this)[0]);
        formData.append('banner_image', banner_image);
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/addeventoverview",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    console.log(data.event_id+" "+data.step+" " +data.total+" "+data.url);
                    window.location.replace("/events/step/"+data.step+"of" +data.total+"/create-event-"+data.url+"/"+data.event_id);
                }
            }
        });
        return false;
    }); 
    
    $('#eventscheduleform').submit(function(){
        event.preventDefault();
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());        
        $.each($("input[name='sbanner_image[]']"), function(i, file) {
            formData.append('banner_images['+i+']', file.files[0]);
            i++;
        });
        $.each($("input[name='sthumb_image[]']"), function(z, file) {
            formData.append('thumb_images['+z+']', file.files[0]);
            z++;
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/addeventschedule",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    console.log(data.event_id+" "+data.step+" " +data.total+" "+data.url);
                    window.location.replace("/events/step/"+data.step+"of" +data.total+"/create-event-"+data.url+"/"+data.event_id);
                }
                
            }
        });
        return false;
    });

    $('#eventaccomodationsform').submit(function(){
        event.preventDefault();
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());  
        $.each($("input[name='banner_image[]']"), function(i, file) {
            formData.append('banner_images['+i+']', file.files[0]);
            i++;
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/addaccomodations",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    console.log(data.event_id+" "+data.step+" " +data.total+" "+data.url);
                    window.location.replace("/events/step/"+data.step+"of" +data.total+"/create-event-"+data.url+"/"+data.event_id);
                }
                else{
                    return false;
                }
            }
        });
        return false;
    });

    $('#eventdiningform').submit(function(){
        event.preventDefault();
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());
        $.each($("input[name='banner_image[]']"), function(i, file) {
            formData.append('banner_images['+i+']', file.files[0]);
            i++;
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/addeventdining",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    console.log(data.event_id+" "+data.step+" " +data.total+" "+data.url);
                    window.location.replace("/events/step/"+data.step+"of" +data.total+"/create-event-"+data.url+"/"+data.event_id);
                    
                }
                else{
                    return false;
                }
            }
        });
        return false;
    });

    $('#eventtransportationform').submit(function(){
        event.preventDefault();
        var banner_image = $('#banner_image').prop('files')[0];
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());
        formData.append('banner_image', banner_image);
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/addeventtransportation",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    console.log(data.event_id+" "+data.step+" " +data.total+" "+data.url);
                    window.location.replace("/events/step/"+data.step+"of" +data.total+"/create-event-"+data.url+"/"+data.event_id);
                }
                else{
                    return false;
                }
            }
        });
        return false;
    });
    
    $('#travelhostinformationform').submit(function(){
        event.preventDefault();
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());
        var info_file = $('#event_info_file').prop('files')[0];
        formData.append('info_file', info_file);
        var countz=0;
        $.each($("input[name='banner_image[]']"), function(i, file) {
            formData.append('banner_images['+i+']', file.files[0]);
            i++;
        });
        console.log("appended" + countz);
        $.each($("input[name='thumb_image[]']"), function(z, file) {
            formData.append('thumb_images['+z+']', file.files[0]);
            z++;
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/addeventtravelhost",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    console.log(data.event_id+" "+data.step+" " +data.total+" "+data.url);
                    window.location.replace("/events/step/"+data.step+"of" +data.total+"/create-event-"+data.url+"/"+data.event_id);
                }
                else{
                    return false;
                }
            }
        });
        return false;
    });

    //event map part (blank for now)
    $('#eventMapform').submit(function(){
        event.preventDefault();
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/addeventmap",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    console.log(data.event_id+" "+data.step+" " +data.total+" "+data.url);
                    window.location.replace("/events/step/"+data.step+"of" +data.total+"/create-event-"+data.url+"/"+data.event_id);
                }
                else{
                    return false;
                }
            }
        });
        return false;
    });

    //event attendee (skip for now)
    $('#eventattendeelistform').submit(function(){
        event.preventDefault();
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/addeventattendeelist",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    console.log(data.event_id+" "+data.step+" " +data.total+" "+data.url);
                    window.location.replace("/events/step/"+data.step+"of" +data.total+"/create-event-"+data.url+"/"+data.event_id);
                }
                else{
                    return false;
                }
            }
        });
        return false;
    });

    //event attendee (skip for now)
    $('#eventphotosform').submit(function(){
        alert("Event Creation Complete.");
        window.location.replace("/events/");
        return false;
    });
}); 

// event schedule clone function
// $("button.clone").on("click", cloneSchedule); 
    var regex = /^(.+?)(\d+)$/i;
    var eventscheduleIndex = $(".event_schedule").length;
    function cloneSchedule(){
        console.log("clicked  "+eventscheduleIndex + " waaaa");
        var nextnum=eventscheduleIndex + 1;
        var defaultc=$('#directions_button1').val();
        $("#event_schedule1").clone()
            .appendTo(".appender")
            .attr("id", "event_schedule" +  nextnum)
            .find("*")
            .each(function() {
                var id = this.id || "";
                var match = id.match(regex) || [];
                if (match.length == 3) {
                    this.id = match[1] + (eventscheduleIndex);
                }

            }).find(" :input:not(:checkbox):not(:radio)").val("").on('click', 'button.clone', cloneSchedule);
            $('#event_schedule'+  nextnum +' .description_cntr').html("");
            $('#event_schedule'+  nextnum +' .description_cntr').append('<textarea id="description" name="description[]" class="form-control "  autocomplete="off" ></textarea>');
            $('#event_schedule'+  nextnum +' .description_cntr textarea').summernote({height:120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                    ['table', ['table']],
                    ['insert', ['link','table']],,
                    ['view', ['fullscreen', 'codeview']],
                  ],/*callbacks: {onImageUpload: function(image) {
                    uploadImage(image[0],this);
                }}*/});
            $('#event_schedule'+  nextnum +'  #sched_start_date').daterangepicker();
            $('input.timepicker').timepicker({});
            $('#event_schedule' +  nextnum +'  input[name^="directions_button1"]').attr("name", "directions_button" +  nextnum).prop('checked', false);
            $('input:radio[name=directions_button1]').filter('[value='+defaultc+']').prop('checked', true);
            
        eventscheduleIndex++;
        console.log("Total  => " + eventscheduleIndex);
        $("#eventsched_cnt").val(eventscheduleIndex);
        return false;
    }
// END event schedule clone function

// accomodations clone function
// $("button.clone").on("click", cloneSchedule); 
var regex = /^(.+?)(\d+)$/i;
var accomodationsIndex = $(".accomodations").length;
function cloneAccomodations(){
    console.log("clicked  "+accomodationsIndex + " waaaa");
    var nextnum=accomodationsIndex + 1;    
    var defaultc=$('#directions_button1').val();
    $("#accomodations1").clone()
        .appendTo(".appender")
        .attr("id", "accomodations" +  nextnum)
        .find("*")
        .each(function() {
            var id = this.id || "";
            var match = id.match(regex) || [];
            if (match.length == 3) {
                this.id = match[1] + (accomodationsIndex);
            }
        }).find(" :input:not(:checkbox):not(:radio)").val("")
        .on('click', 'button.clone', cloneAccomodations);
        $('#accomodations'+  nextnum +' .description_cntr').html("");
        $('#accomodations'+  nextnum +' .description_cntr').append('<textarea id="description" name="description[]" class="form-control "  autocomplete="off" ></textarea>');
        $('#accomodations'+  nextnum +' .description_cntr textarea').summernote({height:120,});
        $('#accomodations'+  nextnum +'  #acc_ids').val('0');
        $('#accomodations'+  nextnum +' #delbtnhldr').html('<button type="button" onclick="$(\'#accomodations'+  nextnum +'\').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>');    
        $('input.timepicker').timepicker({});
        $('#accomodations' +  nextnum +'  input[name^="directions_button1"]').attr("name", "directions_button" +  nextnum).prop('checked', false);
        $('input:radio[name=directions_button1]').filter('[value='+defaultc+']').prop('checked', true);
    accomodationsIndex++;
    $("#accomodations_cnt").val(accomodationsIndex);
    return false;
}
// END event schedule clone function
 

// dining clone function
var regex = /^(.+?)(\d+)$/i;
var diningIndex = $(".event_dining").length;
function cloneDining(){
    console.log("clicked  "+diningIndex + " waaaa");
    var nextnum=diningIndex + 1;
    var defaultc=$('#directions_button1').val();
    $("#event_dining1").clone()
        .appendTo(".appender")
        .attr("id", "event_dining" +  nextnum)
        .find("*")
        .each(function() {
            var id = this.id || "";
            var match = id.match(regex) || [];
            if (match.length == 3) {
                this.id = match[1] + (diningIndex);
            }
        }).find(" :input:not(:checkbox):not(:radio)").val("")
        .on('click', 'button.clone', cloneDining);
        $('#event_dining'+  nextnum +' .description_cntr').html("");
        $('#event_dining'+  nextnum +' .description_cntr').append('<textarea id="description" name="description[]" class="form-control "  autocomplete="off" ></textarea>');
        $('#event_dining'+  nextnum +' .description_cntr textarea').summernote({height:120,});
        $('#event_dining'+  nextnum +'  #e_id').val('0');
        $('#event_dining'+  nextnum +' #delbtnhldr').html('<button type="button" onclick="$(\'#event_dining'+  nextnum +'\').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>');
        $('#event_dining'+  nextnum +'  #meal_date').attr("id", "meal_date" +  nextnum).removeClass("hasDatepicker").datepicker({changeMonth: true,changeYear: true    });
        $('input.timepicker').timepicker({});
        $('#event_dining' +  nextnum +'  input[name^="directions_button1').attr("name", "directions_button" +  nextnum).prop('checked', false);
        $('input:radio[name=directions_button1]').filter('[value='+defaultc+']').prop('checked', true);
        
    diningIndex++;
    $("#form_cnt").val(diningIndex);
    return false;
}
//end dining clone

// travelhost clone function
var regex = /^(.+?)(\d+)$/i;
var travelhostIndex = $(".event_travelhost").length;
function cloneTravelHost(){
    console.log("clicked  "+travelhostIndex + " waaaa");
    var nextnum=travelhostIndex + 1;
    var defaultc=$('#email_button1').val();
    var defaultc2=$('#download_link1').val();
    $("#event_travelhost1").clone()
        .appendTo(".travelhostappender")
        .attr("id", "event_travelhost" +  nextnum)
        .find("*")
        .each(function() {
            var id = this.id || "";
            var match = id.match(regex) || [];
            if (match.length == 3) {
                this.id = match[1] + (travelhostIndex);
            }

        }).find(" :input:not(:checkbox):not(:radio)").val("")
        .on('click', 'button.clone', cloneTravelHost);
        $('#event_travelhost'+  nextnum +' .description_cntr').html("");
        $('#event_travelhost'+  nextnum +' .description_cntr').append('<textarea id="description" name="description[]" class="form-control "  autocomplete="off" ></textarea>');
        $('#event_travelhost'+  nextnum +' .description_cntr textarea').summernote({height:120,});
        $('#event_travelhost'+  nextnum +'  #travelhost_id').val('0');
        $('#event_travelhost'+  nextnum +'  #travelhost_id').val('0');
        $('#event_travelhost'+  nextnum +' #delbtnhldr').html('<button type="button" onclick="$(\'#event_travelhost'+  nextnum +'\').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>');    
        $('input.timepicker').timepicker({});
        $('#event_travelhost' +  nextnum +'  input[name^="email_button1"]').attr("name", "email_button" +  nextnum).prop('checked', false);
        $('input:radio[name=email_button1]').filter('[value='+defaultc+']').prop('checked', true);
        
    travelhostIndex++;
    $("#travelhost_cnt").val(travelhostIndex);
    return false;
}

// information clone function
var regex = /^(.+?)(\d+)$/i;
var informationIndex = $(".event_information").length;
var defaultc = $('input:radio[name=download_link1]').val();
function cloneEventInfo(){
    console.log("clicked  "+informationIndex + " waaaa");
    var nextnum=informationIndex + 1;
    $("#event_information1").clone()
        .appendTo(".informationappender")
        .attr("id", "event_information" +  nextnum)
        .find("*")
        .each(function() {
            var id = this.id || "";
            var match = id.match(regex) || [];
            if (match.length == 3) {
                this.id = match[1] + (informationIndex);
            }

        }).find(" :input:not(:checkbox):not(:radio)").val("")
        .on('click', 'button.clone', cloneEventInfo);
        $('#event_information'+  nextnum +' .description_cntr').html("");
        $('#event_information'+  nextnum +' .description_cntr').append('<textarea id="faq_answer" name="faq_answer[]" class="form-control "  autocomplete="off" ></textarea>');
        $('#event_information'+  nextnum +' .description_cntr textarea').summernote({height:120,});
        $('#event_information'+  nextnum +'  #faq_id').val('0');
        $('#event_information'+  nextnum +' #delbtnhldr').html('<button type="button" onclick="$(\'#event_information'+  nextnum +'\').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>');    
        $('#event_information' +  nextnum +'  input[name^="download_link1"]').attr("name", "download_link" +  nextnum).prop('checked', false);
        $('input:radio[name=download_link1]').filter('[value='+defaultc+']').prop('checked', true);
    informationIndex++;
    $("#info_cnt").val(informationIndex);
    return false;
}



// Attendee JS scripts Part
$('#search_attendee').keyup(function(){
    attendeeList();
});
$('#filterby').on("change",function(){
    attendeeList();
});

function checkallAttendees(){
    console.log("checkeeeeeed");    
    $('input:checkbox').trigger('click');
    console.log($("#checkAll").html());
    $("#checkAll").html($("#checkAll").html() == 'Check All' ? 'Uncheck All' : 'Check All');

}

// load Attendee Lists
function attendeeList(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
    });
    $.ajax({
        url: '/events/attendee/attendee-list',
        type: 'POST',  
        data: {search_attendee: $('#search_attendee').val(),filterby: $('#filterby').val(), _token: $('meta[name="_token"]').val(),event_id: $("#event_id").val(),step: $("#step").val(),total: $("#total").val()},
        success: function (data) {
            //reset the user list
            $("#attendeelist").html("");
            
           // var attendees=data['attendees'];
            console.log(data['users']);
            for(var x=0;x<data['users'].length;x++){
                //check if the user has already been invited
                var selected="  ";
                if(data["users"][x]["inv_id"]!==null){
                    selected=" checked ";
                }

               
                $("#attendeelist").append("<div class=\"col-md-2\"><div class=\"form-group \"><label class=\"checkbox-inline\"><input type=\"checkbox\" name=\"attendee[]\" "+selected+" id=\"attendee"+data["users"][x]["id"]+"\" onclick=\"inviteUser('"+data["users"][x]["id"]+"')\" value=\""+data["users"][x]["id"]+"\">"+data["users"][x]["first_name"]+" "+data["users"][x]["last_name"]+"</label></div></div></div>");
            }
            return false;
        }
    });
}

//invite user
function inviteUser(userid){
    if($('#attendee' + userid).is(":checked")){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: '/events/attendee/invite-user',
            type: 'POST',  
            data: {userid: userid, _token: $('meta[name="_token"]').val(),event_id: $("#event_id").val()},
            success: function (data) {                
                return false;
            }
        });
    }
    else{
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: '/events/attendee/uninvite-user',
            type: 'POST',  
            data: {userid: userid, _token: $('meta[name="_token"]').val(),event_id: $("#event_id").val()},
            success: function (data) {                
                return false;
            }
        });
    }
    return false;

}

// end JS for Attendee LIst
// add event tab switcher
   /* var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab        

    function showTab(n) {
        var formtitles=["Create An Event (Step 1)","Event Schedule (Step 2)","Accommodations (Step 3)","Dining (Step 4)","Transportation (Step 5)","Map (Step 6)","Travel Host & Information (Step 7)","Create Attendee List (Step 8)","Add Event Photos (Step 9)"];
        // This function will display the specified tab of the form ...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        $(".form-title").html(formtitles[n]);
        // ... and fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        // ... and run a function that displays the correct step indicator:
        window.scrollTo(0, 0);
        
        
        $( "input.date-picker" ).datepicker({changeMonth: true,changeYear: true    });
        $('input.timepicker').timepicker({});
        $('.description').summernote({        height:120,    });
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        switch(currentTab){
            case 0:
                    if($("#step1").val()=="0"){
                        $('#eventoverviewform').submit();
                    }
                    break;
            case 1:
                    //if($("#step2").val()=="0"){
                        $('#eventscheduleform').submit();
                    //}
                    break;
            default:                

        }
        
        // Hide the current tab:
        x[currentTab].style.display = "none";
        
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        var coundisplay= currentTab + 1;
        $(".counter").html(coundisplay);
        $(".counter2").html(coundisplay);
        // if you have reached the end of the form... :
        if (currentTab >= x.length) {
            //...the form gets submitted:
            //document.getElementById("regForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

        function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false:
                valid = false;
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    
*/