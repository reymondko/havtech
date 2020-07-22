$(document).ready(function() {
    //$(".select-p").hide();
    $("#event_id").change(function(){
        window.location.replace('/photos/'+$(this).val());
    });
    //$("a.group").fancybox();
    $("#select-photos").click(function(){
        $(".select-p").fadeToggle();
    });
    $('#uploadPhotoModal').on('shown.bs.modal', function (e) {
        var event_title = $("#event_id option:selected").html();
        $("#uploadPhotoModal #event_id").val($("#event_id option:selected").val());
        $("#event_title").html(event_title);
        console.log("clicked"+event_title);
    });
    $("#uploadbtn").click(function(){
        if($("#event_id option:selected").val()==0){
            alert("Please select an event.")
            return false;
        }
        else{
            $('#uploadPhotoModal').modal('show');
        }
    });
});

function DownloadAll(){
    $.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('input[name=_token]').val()
    }
    });
    $.ajax({
        url: "/photos/downloadall",
        type: 'POST',  
        data: { _token: $('meta[name="_token"]').val(),event_id: $("#event_id").val()},
        success: function (data) { 
            if(data.success){
                //window.location.replace("/events/step/"+data.step+"of" +data.total+"/create-event-"+data.url+"/"+data.event_id);

            }   
            
            window.location.replace("/download/photos.zip");
            return false;            
        }
    });
}
function DownloadPhotos(){
    if($('input[name="photoselect[]"]:checked').length == 0){
        alert("Please select a photo.");
        return false;
    }
    //var PhotoForm = $('#PhotoForm');
    var  formData = $("form").serializeArray();
    $.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('input[name=_token]').val()
    }
    });
    $.ajax({
        url: "/photos/downloadphotos",
        type: 'POST',  
        data: formData,
        success: function (data) { 
            window.location.replace("/download/photos.zip");
            return false;            
        }
    });
}
function DeletePhotos(){
    if($('input[name="photoselect[]"]:checked').length == 0){
        alert("Please select a photo.");
        return false;
    }
    
    $.confirm({
        title: 'Delete Photo(s)?',
        content: 'Are you sure you want to permanently delete these photos?'+
                'This action cannot be undone',
        buttons: {
            confirm: function () {
                var  formData = $("#PhotoForm").serializeArray();
                $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('input[name=_token]').val()
                }
                });
                $.ajax({
                    url: "/photos/deletephotos",
                    type: 'POST',  
                    data: formData,
                    success: function (data) { 
                        location.reload();
                        return false;            
                    }
                });
            },
            cancel: function () {
            }
        }
    });
}

function DeletePendingPhotos(){
    if($('input[name="photoselect[]"]:checked').length == 0){
        alert("Please select a photo.");
        return false;
    }
    
    $.confirm({
        title: 'Delete Photo(s)?',
        content: 'Are you sure you want to permanently delete these photos?'+
                'This action cannot be undone',
        buttons: {
            confirm: function () {
                var  formData = $("#PendingPhotoForm").serializeArray();
                $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('input[name=_token]').val()
                }
                });
                $.ajax({
                    url: "/photos/deletephotos",
                    type: 'POST',  
                    data: formData,
                    success: function (data) { 
                        location.reload();
                        return false;            
                    }
                });
            },
            cancel: function () {
            }
        }
    });
}


function ApprovePhotos(){
        var  formData = $("#PendingPhotoForm").serializeArray();
        $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
        });
        $.ajax({
            url: "/photos/approvephotos",
            type: 'POST',  
            data: formData,
            success: function (data) { 
                $.alert({
                    title: '',
                    content: 'Photo(s) Have Been Approved.',
                });
                location.reload();
                return false;            
            }
        });
}

function ApproveAllPendingPhotos(event_id){
    if($("#event_id").val()=="0"){
        $.alert({
            title: 'Alert!',
            content: 'Event is required!',
        });
        $("#event_id").focus();
        $("#event_id").css("border-color","red");
        return false;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
        });
        $.ajax({
            url: "/photos/approveall",
            type: 'POST',  
            data: {event_id:event_id},
            success: function (data) { 
                location.reload();
                return false;            
            }
        });
    
}

function DeleteAllPhotos(event_id,pending){
    if(pending==1){
        var type="Pending";
    }
    else{
        var type="Approved";
    }
    $.confirm({
        title: 'Delete Photo(s)?',
        content: 'Are you sure you want to permanently delete these '+type+' photos?'+
                'This action cannot be undone',
        buttons: {
            confirm: function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-Token': $('input[name=_token]').val()
                    }
                });
                $.ajax({
                    url: "/photos/deleteallphotos",
                    type: 'POST',  
                    data: {event_id:event_id,pending:pending},
                    success: function (data) { 
                        location.reload();
                        return false;            
                    }
                });
            },
            cancel: function () {
            }
        }
    });
}

function selectAllPhotos(photoType){
    if(photoType == "pending"){
        var checkBoxes = $("input.pending");
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        if($(".pending-all-btn").html()=="Select All"){
            $(".pending-all-btn").html("Deselect All");
        }
        else{
            $(".pending-all-btn").html("Select All");
        }
    }
    if(photoType == "approved"){
        var checkBoxes = $("input.approved");
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        if($(".approve-all-btn").html()=="Select All"){
            $(".approve-all-btn").html("Deselect All");
        }
        else{
            $(".approve-all-btn").html("Select All");
        }
    }

}