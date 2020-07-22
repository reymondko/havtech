$(document).ready(function() {
    $("#search_user").keyup(function(){
       search_user();
    });
    $("#event_id").on("change",function(){
        search_user();
    });

    function search_user(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: '/notifications/user-list',
            type: 'POST',  
            data: {search_user: $('#search_user').val(), _token: $('meta[name="_token"]').val(),notif_id: $("#notif_id").val(),event_id:$("#event_id").val()},
            success: function (data) {
                //reset the user list
                $("#recipientlist").html("");
                
               // var attendees=data['attendees'];
                console.log(data);
                for(var x=0;x<data['users'].length;x++){
                    //check if the user has already been invited
                    var selected="  ";
                    if(data["users"][x]["inv_id"]!==null ){
                        selected=" checked ";
                    }
                    else{
                        
                    }
                    $("#recipientlist").append(" <div class=\"col-md-2\"><div class=\"form-group \"><label class=\"checkbox-inline\"><input type=\"checkbox\" name=\"recipient[]\" "+selected+" onclick=\"AddRecipientUser('"+data["users"][x]["id"]+"')\" id=\"recipient"+data["users"][x]["id"]+"\"  value=\""+data["users"][x]["id"]+"\">"+data["users"][x]["first_name"]+" "+data["users"][x]["last_name"]+"</label> <!-- onclick=\"AddRecipientUser('{{$user['id']}}')\"!--></div></div>");
                }
                return false;
            }

        });
    }

    $('.main-header').hide();
    $("#notification_title").keyup(function(){
        $("#notification-title").html($("#notification_title").val());
    });
    $("#visibility1").on("change",function(){
        $("#visibility2").val($("#visibility1").val());
    });
    $("#visibility2").on("change",function(){
        $("#visibility1").val($("#visibility2").val());
    });

    $("#sendtoall").on("click",function(){
        //$('input:checkbox').prop("checked", !checkBoxes.prop("checked"));
        $('input:checkbox').attr('checked',true);
        $('input:checkbox').prop('checked',true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: '/notifications/add-recipient-all',
            type: 'POST',  
            data: { _token: $('meta[name="_token"]').val(), notif_id: $("#notif_id").val()
            ,notification_title: $("#notification_title").val()
            ,notif_date: $("#notif_date").val()
            ,start_time: $("#start_time").val()
            ,description: $("#description").val()
            ,with_button_url: $("#with_button_url").val()
            ,button_url: $("#button_url").val()
            ,visibility: $("#visibility").val()
        },      
        success: function (data) {    
            $("#notif_id").val(data)        ; 
            return false;
        }});
    });
    $("#removeall").on("click",function(){
        //$('input:checkbox').prop("checked", !checkBoxes.prop("checked"));
        $('input:checkbox').attr('checked',false);
        $('input:checkbox').prop('checked',false);
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: '/notifications/remove-recipient-all',
            type: 'POST',  
            data: {_token: $('meta[name="_token"]').val(),notif_id: $("#notif_id").val()},
            success: function (data) {   
                $("#search_user").change();
               // return false;
            }
        });
    });
    
    $(".page-header").css("font-size", "24px");
    $(".page-header").css("font-weight", "bold");
    $( "input.date-picker" ).datepicker({changeMonth: true,changeYear: true    });
    
    $(".datepicker button").prop("type", "button");
    $(".datepicker button").on("click",function(){
        $(this).closest(".datepicker").find(".date-picker").datepicker("show");
    });
    $( "input.daterangepicker" ).daterangepicker();
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

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
    });

    $('#notificationform').submit(function(){
        $(':button').prop('disabled', true);
        //event.preventDefault();
        // Check if textarea has value
        if($('.description').summernote('isEmpty')){
            alert('Please add a description');
            $(':button').prop('disabled', false);
            $('.description').focus();
            return false;
        }
        var formData =new FormData($(this)[0]);
        $.ajax({
            url: "/notifications/create-notification",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){                    
                   window.location.replace("/notifications/");
                }
            return false;
            }            
        });
        return false;
    }); 

    $('#editnotificationform').submit(function(){
        // Check if textarea has value
        //event.preventDefault();
        if($('.description').summernote('isEmpty')){
            alert('Please add a description');
            $('.description').focus();
            return false;
         }
        var formData =new FormData($(this)[0]);
        $.ajax({
            url: "/notifications/update-notification",
            type: 'POST',
            data: formData,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (data) {
                console.log(data);
                if(data.success){                    
                    window.location.replace("/notifications/");
                }
            return false;
            }            
        });
        return false;
    }); 
});

//invite user
function AddRecipientUser(userid){
    if($("#notification_title").val()==""){
        $('#notificationform')[0].checkValidity();
        $("#notification_title").focus();
        if($(this).is(':checked')){
            $(this).attr('checked', false);
        }
        else{
            $(this).attr("checked", true);
        }
        return false;
    }
    if($("#notif_date").val()==""){
        $('#notificationform')[0].checkValidity();
        $(this).prop("checked", !checkBoxes.prop("checked"));
        $("#notif_date").focus();
        return false;
    }
    if($("#start_time").val()==""){
        $('#notificationform')[0].checkValidity();
        $(this).prop("checked", !checkBoxes.prop("checked"));
        $("#start_time").focus();
        return false;
    }
    if($("#button_link").val()==""){
        $('#notificationform')[0].checkValidity();
        $(this).prop("checked", !checkBoxes.prop("checked"));
        $("#button_link").focus();
        return false;
    }
    
    if($('#recipient' + userid).is(":checked")){
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url: '/notifications/add-recipient-user',
            type: 'POST',  
            data: {
                userid: userid, _token: $('meta[name="_token"]').val(),notif_id: $("#notif_id").val()
                ,notification_title: $("#notification_title").val()
                ,notif_date: $("#notif_date").val()
                ,start_time: $("#start_time").val()
                ,description: $("#description").val()
                ,with_button_url: $("#with_button_url").val()
                ,button_url: $("#button_url").val()
                ,visibility: $("#visibility").val()
            },      
            success: function (data) {   
                console.log(data);
                $("#notif_id").val(data); 
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
            url: '/notifications/remove-recipient-user',
            type: 'POST',  
            data: {userid: userid, _token: $('meta[name="_token"]').val(),notif_id: $("#notif_id").val()},
            success: function (data) {                
                return false;
            }
        });
    }
    return false;

}

//delete notif
function deleteNotif(id){
    var prompt = window.confirm("Are you sure you want to delete this notification?");
    if(prompt){
        window.location.href = "/notifications/delete?notif_id="+id;
    }
}