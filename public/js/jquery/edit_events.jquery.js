var pathname = window.location.pathname;
$(document).ready(function() {
    $("#deleteSchedule").on('click',function(){colorize();});
    colorize();
    $( document ).tooltip();
    $("#event_link").addClass('active');
    $('.description').summernote({height:120,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
            ['table', ['table']],
            ['insert', ['link','table']],,
            ['view', ['fullscreen', 'codeview']],
          ],
          /*callbacks: {onImageUpload: function(image) {
            uploadImage(image[0],this);
        }}*/
    });
    $('#custom_calendar_message').summernote({height:120,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
            ['table', ['table']],
            ['insert', ['link','table']],,
            ['view', ['fullscreen', 'codeview']],
          ]});
    $(".alert-success").hide();
    $(".page-header").css("font-size", "24px");
    $(".page-header").css("font-weight", "bold");
    $( "input.date-picker" ).datepicker({changeMonth: true,changeYear: true    });
    $(".datepicker button").prop("type", "button");
    $(".datepicker button").on("click",function(){
        $(this).closest(".datepicker").find(".date-picker").datepicker("show");
    });
    $( "input.daterangepicker" ).daterangepicker();
    $('input.timepicker').timepicker({});
    $("#event_name").on("keyup",function(){
        $("#event-title").html($("#event_name").val());
    });

    //banner image preloader
    $("#banner_image").change(function() {
        readURL(this,"#banner-preview");
      });
    /*
    remove auto populate dates appender
    $("#eventoverviewform #start_date").on("change",function(){
        appendEventDates();
    });
    $("#eventoverviewform #end_date").on("change",function(){
        appendEventDates();
    });
    */
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
    });
    $('#eventoverviewform').submit(function(){
        event.preventDefault();
        var overview_file = $('#overview_file').prop('files')[0];
        var banner_image = $('#banner_image').prop('files')[0];
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());   
        formData.append('banner_image', banner_image);
        formData.append('overview_file', overview_file);
        
        $.ajax({
            url: "/events/updateeventoverview",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){                    
                    $(".alert-success").show();
                    $("#success_msg").html(data.message);
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }
            return false;
            }            
        });
        return false;
    }); 
    
    $('#eventscheduleform').submit(function(){
        event.preventDefault();
        $('.update-evt-sched').attr('disabled',true);
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());  
        
        var banner_image = $('#banner_image').prop('files')[0];
        formData.append('banner_images', banner_image);

        $.each($("#eventscheduleform input[name='itinerary_file[]']"), function(z, file) {
            formData.append('itinerary_file['+z+']', file.files[0]);
            z++;
        });
        $.each($("#eventscheduleform input[name='sthumb_image[]']"), function(x, file) {
            console.log(x);
            formData.append('thumb_images['+x+']', file.files[0]);
            x++;
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/updateeventschedule",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                $("html, body").animate({ scrollTop: 0 }, "fast");
                window.location.reload();
                // if(data.success){                    
                //     $(".alert-success").show();
                //     $("#success_msg").html(data.message);
                //     $("html, body").animate({ scrollTop: 0 }, "slow");
                // }
            }
        });
        return false;
    });

    $('#eventaccomodationsform').submit(function(){
        event.preventDefault();
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());  
        var banner_image = $('#banner_image').prop('files')[0];
        formData.append('banner_images', banner_image);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/updateaccomodations",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    if(data.success){
                        /*$(".alert-success").show();
                        $("#success_msg").html(data.message);
                        $("html, body").animate({ scrollTop: 0 }, "fast");
                        colorize();
                        */
                        window.location.reload();
                        $("html, body").animate({ scrollTop: 0 }, "fast");       
                    }
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
        var banner_image = $('#banner_image').prop('files')[0];
        formData.append('banner_images', banner_image);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/updateeventdining",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    /*$(".alert-success").show();
                        $("#success_msg").html(data.message);
                        $("html, body").animate({ scrollTop: 0 }, "fast");
                        colorize();
                        */
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                    window.location.reload();                           
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
            url: "/events/updateeventtransportation",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    /*$(".alert-success").show();
                        $("#success_msg").html(data.message);
                        $("html, body").animate({ scrollTop: 0 }, "fast");
                        colorize();
                        */                       
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                    window.location.reload();       
                }
                else{
                    return false;
                }
            }
        });
        return false;
    });
    
    $('#travelhostinformationform').submit(function(){
        $('#loader').show();
        event.preventDefault();
        var formData =new FormData($(this)[0]);
        formData.append('event_id', $("#event_id").val());
        formData.append('step', $("#step").val());
        var countz=0;
        /*$.each($("#travelhostinformationform input[name='event_info_file[]']"), function(i, file) {
            formData.append('info_files['+i+']', file.files[0]);
            i++;
        });*/
        var event_info_file = $('#event_info_file').prop('files')[0];
        formData.append('info_files', event_info_file);
        var banner_image = $('#banner_image').prop('files')[0];
        formData.append('banner_images', banner_image);
        console.log("appended" + countz);
        $.each($("#travelhostinformationform input[name='sthumb_image[]']"), function(z, file) {
            formData.append('thumb_images['+z+']', file.files[0]);
            z++;
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/updateeventtravelhost",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    /*$(".alert-success").show();
                        $("#success_msg").html(data.message);
                        $("html, body").animate({ scrollTop: 0 }, "fast");
                        colorize();
                        */
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                    window.location.reload();   
                   // $('#loader').hide();
                }
            }
        });
        return false;
    });

    
    $('#updateMapNames').submit(function(){
        event.preventDefault();
        var formData =new FormData($(this)[0]);
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: "/events/maps/updatemapnames",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){
                    $(".alert-success").show();
                    $("#success_msg").html(data.message);
                    $("html, body").animate({ scrollTop: 0 }, "slow");
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
        alert("Event Update Complete.");
        window.location.replace("/events/");
        return false;
    });
    
    $("#eventattendeelistform").submit(function(){
        var formData =new FormData($(this)[0]);
       
        $.ajax({
            url: "/events/attendee/update-attendees",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){                    
                    $(".alert-success").show();
                    $("#success_msg").html(data.message);
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }
            return false;
            }            
        });
        return false;
    });
}); 

//append dates part event overview
//append event dates Event overview
function appendEventDates(){
    $(".event_dates_appender").html("<div class='row'><div class='col-md-2'></div><div class='col-md-2'><label>Start Time</label></div><div class='col-md-2'><label>End Time</label> </div>    <div class='col-md-2'></div>    </div>");
    var startdate = $("#start_date").val();
    var enddate = $("#end_date").val();
    if(enddate==""){
        enddate =startdate;
    }
    startdate = new Date(startdate); //YYYY-MM-DD
    enddate = new Date(enddate); //YYYY-MM-DD
    //console.log(startdate+" - "+enddate);
    var arr = new Array();
    var dt = new Date(startdate);
    //console.log(dt);
    var monthNames = [
        "January", "February", "March",
        "April", "May", "June", "July",
        "August", "September", "October",
        "November", "December"
    ];
    var datex=0;
    while (dt <= enddate) {
        var date=new Date(dt);
        arr.push(date);        
        var day = date.getDate();
        var monthIndex = date.getMonth();
        var year = date.getFullYear();
        var event_date = monthNames[monthIndex] + ' ' + day + ',' +  year;
        //console.log(event_date);
        $(".event_dates_appender").append("<div class='row' id='datez"+datex+"'><div class='col-md-2'><div class='form-group'><label>"+event_date+"</label></div></div><div class='col-md-2'><div class='form-group'><input type='hidden' name='dates[]' value='"+event_date+"'><input id='start_time' name='start_time[]' class='form-control timepicker'  autocomplete='off' required></div></div><div class='col-md-2'><div class='form-group'> <input  id='end_time' name='end_time[]' class='form-control timepicker'  autocomplete='off' required>        </div>    </div>    <div class='col-md-2'>    <button type='button' id='deleteSchedule' class='btn btn-default btn-outline' onclick='removeDate("+datex+")'><i class='fa fa-times-circle-o' aria-hidden='true' title='click to Remove This Date'></i>  Remove</button>    </div>    </div>")
        dt.setDate(dt.getDate() + 1);
        datex++;
    }
    
    $('input.timepicker').timepicker({});
    console.log(arr);

}
function removeDate(datecontainer){
    console.log(datecontainer);
    var prompt = window.confirm("Are you sure you want to remove this Date?");
        if(prompt){
            $("#datez"+datecontainer).remove();
        }
}

//delete Part Schedule
function deleteEventSchedule(schedid,divc){
    var r = confirm("Are you sure you want to delete this Event Schedule?");
    if (r == true) {
        $.ajax({
            url: "/events/deleteeventschedule",
            type: 'POST',
            data: {sched_id: schedid},
            success: function (data) {
                console.log(data);
                if(data.success){
                    $(".alert-success").show();
                    $("#success_msg").html(data.message); 
                    $("#" + divc).fadeOut(300, function() { $("#" + divc).remove();
                    
                    colorize();
                    });
                    $("#success_msg").html(data.message);
                }
                else{
                    return false;
                }
            }
        });
    } else {
        return false();
    }
}

//delete Part Accomodation
function deleteEventAccomodation(accid,divc){
    var r = confirm("Are you sure you want to delete this Event Accomodation?");
    if (r == true) {
        $.ajax({
            url: "/events/deleteaccomodations",
            type: 'POST',
            data: {acc_id: accid},
            success: function (data) {
                if(data.success){
                    $(".alert-success").show();
                    $("#success_msg").html(data.message); 
                    $("#" + divc).fadeOut(300, function() { $("#" + divc).remove(); });
                    $("#success_msg").html(data.message);
                }
                else{
                    return false;
                }
            }
        });
    } else {
        return false();
    }
}
//delete Part Dining
function deleteEventDining(edata_id,divc){
    if($(".event_dining").length == 1){
        alert("There should be atleast one Dining.");
        return false;
    }
    var r = confirm("Are you sure you want to delete this Event Dining?");
    if (r == true) {
        $.ajax({
            url: "/events/deleteeventdining",
            type: 'POST',
            data: {id: edata_id},
            success: function (data) {
                if(data.success){
                    $(".alert-success").show();
                    $("#success_msg").html(data.message); 
                    $("#" + divc).fadeOut(300, function() { $("#" + divc).remove(); });
                    $("#success_msg").html(data.message);
                }
                else{
                    return false;
                }
            }
        });
    } else {
        return false();
    }
}
//delete Part TravelHost 
function deleteTravelHost(edata_id,divc){
    var r = confirm("Are you sure you want to delete this Travel Host?");
    if (r == true) {
        $.ajax({
            url: "/events/deleteeventtravelhost",
            type: 'POST',
            data: {id: edata_id},
            success: function (data) {
                if(data.success){
                    $(".alert-success").show();
                    $("#success_msg").html(data.message); 
                    $("#" + divc).fadeOut(300, function() { $("#" + divc).remove(); });
                    $("#success_msg").html(data.message);
                }
                else{
                    return false;
                }
            }
        });
    } else {
        return false;
    }
}

//delete Part Dining 
function deleteEventInfo(edata_id,divc){
    var r = confirm("Are you sure you want to delete this Travel Host?");
    if (r == true) {
        $.ajax({
            url: "/events/deleteeventfaq",
            type: 'POST',
            data: {id: edata_id},
            success: function (data) {
                if(data.success){
                    $(".alert-success").show();
                    $("#success_msg").html(data.message); 
                    $("#" + divc).fadeOut(300, function() { $("#" + divc).remove(); });
                    $("#success_msg").html(data.message);
                }
                else{
                    return false;
                }
            }
        });
    } else {
        return false();
    }
}

//delete Part Maps 
function deleteMap(edata_id,divc){
    var r = confirm("Are you sure you want to delete this Map?");
    if (r == true) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="token"]').attr('content')
            }
        });
        $.ajax({
            url: '/events/maps/images-delete',
            type: 'POST',
            data: {id: edata_id, _token: $('meta[name="token"]').attr('content')},
            success: function (data) {
                    $(".alert-success").show();
                    $("#success_msg").html(data.message); 
                    $("#" + divc).fadeOut(300, function() { $("#" + divc).remove(); });
                    $("#success_msg").html(data.message);
            }
        });
    } else {
        return false();
    }
}
//delete Part Maps 
function deletePhoto(edata_id,divc){
    var r = confirm("Are you sure you want to delete this Photo?");
    if (r == true) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="token"]').attr('content')
            }
        });
        $.ajax({
            url: '/events/photos/images-delete',
            type: 'POST',
            data: {id: edata_id, _token: $('meta[name="token"]').attr('content')},
            success: function (data) {
                    $(".alert-success").show();
                    $("#success_msg").html(data.message); 
                    $("#" + divc).fadeOut(300, function() { $("#" + divc).remove(); });
                    $("#success_msg").html(data.message);
            }
        });
    } else {
        return false();
    }
}

// event schedule clone function
// $("button.clone").on("click", cloneSchedule); 
    var regex = /^(.+?)(\d+)$/i;
    var eventscheduleIndex = $(".event_schedule").length;
    function cloneSchedule(){
        console.log("clicked  "+eventscheduleIndex + " waaaa");
        var nextnum=$(".event_schedule").length; // + 1
        //var defaultc=$('#directions_button1').val();        
        $("#event_schedule0").clone()
            .appendTo(".appender")
            .attr("id", "event_schedule" +  nextnum).show()
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
            $('#event_schedule'+  nextnum +'  #schedule_ids').val('0');
            $('#event_schedule'+  nextnum +' #delbtnhldr').html('<button type="button" onclick="$(\'#event_schedule'+  nextnum +'\').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>');    
            $('#event_schedule'+  nextnum +'  #sched_start_date').daterangepicker();
            //$('#event_schedule'+  nextnum +'  #sched_start_date').attr("id", "sched_start_date" +  nextnum).removeClass("hasDatepicker").datepicker({changeMonth: true,changeYear: true    });
            $('#event_schedule'+  nextnum +'  #sched_end_date').attr("id", "sched_end_date" +  nextnum).removeClass("hasDatepicker").datepicker({changeMonth: true,changeYear: true    });
            //$('#event_schedule' +  nextnum +'  input[name^="directions_button1"]').attr("name", "directions_button" +  nextnum).prop('checked', false);
            $('#event_schedule'+  nextnum +' .itinum').html("#"+nextnum);
            $('#event_information' +  nextnum +'  input[name^="download_link1"]').attr("name", "download_link" +  nextnum).prop('checked', false);
            $('input.timepicker').timepicker({});
            //$('input:radio[name=directions_button1]').filter('[value='+defaultc+']').prop('checked', true);
            $('#event_schedule' +  nextnum +" #location").change(function(){
                if($(this).val()=="Specify Address"){
                    $('#event_schedule' +  nextnum +" .specific-address").show();
                    $('#event_schedule' +  nextnum +" .room-num").hide();
                }
                else{
                    $('#event_schedule' +  nextnum +" .room-num").show();
                    $('#event_schedule' +  nextnum +" .specific-address").hide();
                }
            });
            
            $("#event_schedule"+  nextnum +" #title").keyup(function(){
                $("#event_schedule"+  nextnum +" #iti_title").html($(this).val());
            });
            $('#event_schedule' +  nextnum +" #location").val("Specify Address").trigger("change");
            $("#event_schedule"+nextnum+" #thumb_image").change(function() {
                readURL(this,"#event_schedule"+nextnum+" #thumb-preview");
            });
            colorize();
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
    var nextnum=$(".accomodations").length;    // + 1
    //var defaultc=$('#directions_button1').val();
    $("#accomodations0").clone()
        .appendTo(".appender")
        .attr("id", "accomodations" +  nextnum).show()
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
        $('#accomodations'+  nextnum +' .description_cntr textarea').summernote({height:120,
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
        $('#accomodations'+  nextnum +'  #acc_ids').val('0');
        $('#accomodations'+  nextnum +' #delbtnhldr').html('<button type="button" onclick="$(\'#accomodations'+  nextnum +'\').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>');    
        $('input.timepicker').timepicker({});
        $('#accomodations'+  nextnum +' .itinum').html(nextnum);
        $("#accomodations"+  nextnum +" #hotel").keyup(function(){
            $("#accomodations"+  nextnum +" #iti_title").html($(this).val());
        });
        colorize();
        //$('#accomodations' +  nextnum +'  input[name^="directions_button1"]').attr("name", "directions_button" +  nextnum).prop('checked', false);
        //$('input:radio[name=directions_button1]').filter('[value='+defaultc+']').prop('checked', true);
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
    var nextnum=$(".event_dining").length;// + 1
    //var defaultc=$('#directions_button1').val();
    $("#event_dining0").clone()
        .appendTo(".appender")
        .attr("id", "event_dining" +  nextnum).show()
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
        $('#event_dining'+  nextnum +' .description_cntr textarea').summernote({height:120,
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
        $('#event_dining'+  nextnum +'  #e_id').val('0');
        $('#event_dining'+  nextnum +' #delbtnhldr').html('<button type="button" onclick="$(\'#event_dining'+  nextnum +'\').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>');
        $('#event_dining'+  nextnum +'  #meal_date').attr("id", "meal_date" +  nextnum).removeClass("hasDatepicker").datepicker({changeMonth: true,changeYear: true    });
        $('input.timepicker').timepicker({});
        $('#event_dining'+  nextnum +' .itinum').html(nextnum);
        $("#event_dining"+  nextnum +" #location").keyup(function(){
            $("#event_dining"+  nextnum +" #iti_title").html($(this).val());
        });
        //$('#event_dining' +  nextnum +'  input[name^="directions_button1"]').attr("name", "directions_button" +  nextnum).prop('checked', false);
        //$('input:radio[name=directions_button1]').filter('[value='+defaultc+']').prop('checked', true);
        
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
    var nextnum=$(".event_travelhost").length;// + 1
    var defaultc=$('#email_button1').val();
    $("#event_travelhost0").clone()
        .appendTo(".travelhostappender")
        .attr("id", "event_travelhost" +  nextnum).show()
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
        $('#event_travelhost'+  nextnum +' .description_cntr textarea').summernote({height:120,
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
        $('#event_travelhost'+  nextnum +'  #travelhost_id').val('0');
        $('#event_travelhost'+  nextnum +' #delbtnhldr').html('<button type="button" onclick="$(\'#event_travelhost'+  nextnum +'\').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>');    
        $('input.timepicker').timepicker({});
        $("#event_travelhost"+nextnum+" #thumb_image").change(function() {
            readURL(this,"#event_travelhost"+nextnum+" #thumb-preview");
        });
        var zzzz=travelhostIndex;
        console.log("dri daw" + zzzz);
        $('#event_travelhost' +  nextnum +'  input[name^="email_button1"]').attr("name", "email_button" +  nextnum).prop('checked', false);
        $('input:radio[name=email_button1]').filter('[value='+defaultc+']').prop('checked', true);
        $('#event_travelhost'+  nextnum +' .itinum').html(nextnum);
        $("#event_travelhost"+  nextnum +" #host_name").keyup(function(){
            $("#event_travelhost"+  nextnum +" #iti_title").html($(this).val());
        });        
        colorize();
        
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
    var nextnum=$(".event_information").length;// + 1
    $("#event_information0").clone()
        .appendTo(".informationappender")
        .attr("id", "event_information" +  nextnum).show()
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
            
        $('#event_information'+  nextnum +' .description_cntr textarea').summernote({height:120,
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

        $('#event_information'+  nextnum +'  #faq_id').val('0');
        $('#event_information'+  nextnum +' #delbtnhldr').html('<button type="button" onclick="$(\'#event_information'+  nextnum +'\').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>');    
        $('#event_information' +  nextnum +'  input[name^="download_link1"]').attr("name", "download_link" +  nextnum).prop('checked', false);
        $('input:radio[name=download_link1]').filter('[value='+defaultc+']').prop('checked', true);
        $('#event_information'+  nextnum +' .itinum').html(nextnum);
        $("#event_information"+  nextnum +" #faq_title").keyup(function(){
            $("#event_information"+  nextnum +" #iti_title").html($(this).val());
        });        
        colorize();
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
$('#customer_type').on("change",function(){
    attendeeList();
});


function uncheckallAttendees(){
    $('input:checkbox').attr("checked",false);       
    $('input:checkbox').prop("checked",false);
    $("#todo").val("3");
    $("#eventattendeelistform").submit();
}

function checkallAttendees(){
    $('input:checkbox').attr("checked",true);       
    $('input:checkbox').prop("checked",true);
    $("#todo").val("2");
    $("#eventattendeelistform").submit();
}

function sendAttendees(){
    //alert($("input[name='attendee[]']:checked").length);
    var prompt = window.confirm("Are you sure you want to send invites?");
    if(prompt){
        $("#todo").val("1");
        $("#eventattendeelistform").submit();
        $( "input[name='attendee[]']:checked" ).parent().css( "background-color", "#f69031" );
    };
}

//export attendees
function exportAttendees(){
    console.log($("#event_id").val());
    //alert($("input[name='attendee[]']:checked").length);
   $.ajax({
        url: '/events/attendee/attendee-export',
        type: 'POST',  
        data: {event_id: $("#event_id").val()},
        success: function (data) {
           
            return false;
        }
    });
}


// preload upload image
function readURL(input,target) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      //check file size
      var size = input.files[0].size;
      var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
      i=0;while(size>900){size/=1024;i++;}
      //var exactSize = (Math.round(size*100)/100)+' '+fSExt[i];
      var exactSize = (Math.round(size*100)/100);
      var imgerror="";
      if(fSExt[i] == 'MB' && exactSize > 2){
        imgerror="<div class='error' style='display:inline-block;margin-top:50px;font-weight: bold;'>Image is too large, maximum file size is 2MB. </div>"
      }
      reader.onload = function(e) {
        var targetz= target.includes("#thumb-preview");
        if(targetz){
            var addremove='';
        }
        else{
            var addremove='<button type="button" id="deleteBanner" class="deleteBanner btn " onclick="removeBannerPrev()"><i class="fa fa-close"></i> Remove</button>';
        }

        $(target).html('<div class="photo-preview" ><div class="photo-image"><img data-dz-thumbnail=""  src="'+e.target.result+'" style="width:120px">'+addremove+'</div></div>'+imgerror);
      }
      reader.readAsDataURL(input.files[0]);
    }
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
        data: {search_attendee: $('#search_attendee').val(),filterby: $('#filterby').val(),customer_type: $('#customer_type').val(), _token: $('meta[name="_token"]').val(),event_id: $("#event_id").val(),step: $("#step").val(),total: $("#total").val()},
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
                var bacground = "  ";
                if(data["users"][x]["email_sent_approved"]==1){
                    bacground = 'background-color:#f69031;';
                }
                $("#attendeelist").append("<div class=\"col-md-2\"><div class=\"form-group \"><label style='padding:0px 5px;"+bacground+"' class=\"checkbox-inline\"><input type=\"checkbox\" name=\"attendee[]\" class=\"attendees\" "+selected+" id=\"attendee"+data["users"][x]["id"]+"\" onclick=\"inviteUser('"+data["users"][x]["id"]+"')\" value=\""+data["users"][x]["id"]+"\">"+data["users"][x]["first_name"]+" "+data["users"][x]["last_name"]+"</label></div></div></div>");
            }
            return false;
        }
    });
}
/*
$(document).ready(function(){    
    $('.attendees').change(function() {
        console.log("sasd");
        inviteUser(this.val());     
    });
});*/
//invite user
function inviteUser(userid){
    console.log(userid);
    var checked=$('#attendee' + userid).is(":checked");
    if(checked){
        /*if(!confirm('ARE YOU SURE YOU WANT TO SEND?')){         
            $(this).removeAttr('checked');
            $('#attendee' + userid).prop('checked', false);
            return false;
        }*/
        
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
        $('#attendee' + userid).parent().css("background-color","#FFFFFFF");
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

}
//delete event
function deleteEvent(id){
    var prompt = window.confirm("Are you sure you want to delete this Event?");
    if(prompt){
        window.location.href = "/events/deleteevents?event_id="+id;
    }
}

//remove banner images
function removeBanner(eventType){
    var prompt = window.confirm("Are you sure you want to delete this banner?");
    if(prompt){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: '/events/delete-banner',
            type: 'POST',  
            data: {eventType: eventType, _token: $('meta[name="_token"]').val(),event_id: $("#event_id").val()},
            success: function (data) {                
                if(data.success){                    
                    $(".alert-success").show();
                    $("#success_msg").html(data.message);
                    $("#banner-preview").html("");
                }
            }
        });
    }

}
// delete prev
function removeBannerPrev(){
    $(this).parent(".photo-preview").find('.photo-image').remove();
    console.log("wawha");
    $("#banner_image").val("");    
    $("#banner-preview").html("");
}

function colorize(){
    $(".event-heading:even").css("background-color", "#07549c !important");
    $(".event-heading:odd").css("background-color", "#f69031 !important");
}


function uploadImage(image,element) {
    $('#loader').show();
    var data = new FormData();
    data.append("image", image);
    $.ajax({
        url: '/events/admin-file-upload',
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "post",
        success: function(url) {
            $('#loader').hide();
            var image = $('<img>').attr('src',url);
            $(element).summernote("insertImage", url,'filename');
        },
        error: function(data) {
            $('#loader').hide();
            console.log(data);
        }
    });
}


//remove overview file
function removeOverviewFile(eventId){
    var prompt = window.confirm("Are you sure you want to delete this itinerary file?");
    if(prompt){
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: '/events/delete-itinerary-file',
            type: 'POST',  
            data: {_token: $('meta[name="_token"]').val(),event_id:eventId},
            success: function (data) {                
                if(data.success){                    
                    $(".alert-success").show();
                    $("#success_msg").html(data.message);
                    $("#itinerary_file_div").html("");
                }
            }
        });
    }

}