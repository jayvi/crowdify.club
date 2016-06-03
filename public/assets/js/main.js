$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


toastr.options = {
    "closeButton": true,
    "debug": true,
    "newestOnTop": true,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

 var CrowdifyAjaxService = new function() {
    this.get = function(url, data, onSuccess, onError){
        this.makeRequest(url, 'GET', data, onSuccess, onError );
    }
    this.post = function(url, data, onSuccess, onError){
        this.makeRequest(url, 'POST', data, onSuccess, onError );
    }
     this.put = function(url, data, onSuccess, onError){
         this.makeRequest(url, 'PUT', data, onSuccess, onError );
     }
     this.patch = function(url, data, onSuccess, onError){
         this.makeRequest(url, 'PATCH', data, onSuccess, onError );
     }
     this.delete = function(url, data, onSuccess, onError){
         this.makeRequest(url, 'DELETE', data, onSuccess, onError );
     }
     this.makeRequest = function(url, method, data, onSuccess, onError){

     $.ajax({
         url: url,
         type: method,
         data: data,
         success: function(result,status,xhr) {
             if(result.message){
                 toastr.success(result.message);
             }
             onSuccess(result);
         },
         error: function(xhr,status,error){
             var error = JSON.parse(xhr.responseText);
             if(error.message){
                 toastr.error(error.message);
             }
             onError(error);
         }
     });
 }
     this.uploadImage = function(url, data,onProgress ,onSuccess ,onError){
         $.ajax({
             url: url,
             type: 'POST',
             data: data,
             cache: false,
             dataType: 'json',
             processData: false, // Don't process the files
             contentType: false, // Set content type to false as jQuery will tell the server its a query string request
             xhr: function()
             {
                 var xhr = new window.XMLHttpRequest();
                 //Upload progress
                 xhr.upload.addEventListener("progress", function(evt){
                     if (evt.lengthComputable) {
                         var percentComplete = evt.loaded / evt.total;
                         //Do something with upload progress
                         onProgress(percentComplete);
                     }
                 }, false);
                 //Download progress
                 //xhr.addEventListener("progress", function(evt){
                 //    if (evt.lengthComputable) {
                 //        var percentComplete = evt.loaded / evt.total;
                 //        //Do something with download progress
                 //        console.log(percentComplete);
                 //    }
                 //}, false);
                 return xhr;
             },
             success: function(result,status,xhr)
             {
                 onSuccess(result);
             },
             error: function(xhr,status,error)
             {
                 var error = JSON.parse(xhr.responseText);
                 if(error.message){
                     toastr.error(error.message);
                 }
                 onError(error);
             }
         });
     }
 };

$.fn.extend({
    imageUpload:function(options){
        var html = '<div class="image-upload-secion">' +
            '<a href="" class="upload-text upload-link"><span class="ion-camera"></span></a> ' +
            '<a href="" class="upload-link">' +
            '<img src=""  width="100%" alt="Upload Image">' +
            '</a>' +
            '<input type="file" class="hidden input-file">'+
            '<div class="progress hidden">' +
            '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0% </div> ' +
            '</div>'+
            '</div>'
        this.html(html);
        var imageUrl = this.data('image-url');
        this.find('img').attr('src',imageUrl ? imageUrl: options.defaultImageUrl);
        this.on('click','.upload-link',function(e){
            e.preventDefault();
            $(this).closest('.image-upload-secion').find('input[type=file]').trigger('click');
        });

        var mainDiv = this;
        var progress = this.find('.progress');

        this.on('change', 'input[type=file]',function(e){
            e.preventDefault();
            var files = event.target.files;
            console.log(files);
            var data = new FormData();
            $.each(files, function(key, value)
            {
                data.append('file', value);
            });

            progress.removeClass('hidden');

            if(files.length>0){
                $.ajax({
                    url: options.url,
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    xhr: function()
                    {
                        var xhr = new window.XMLHttpRequest();
                        //Upload progress
                        xhr.upload.addEventListener("progress", function(evt){
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                //Do something with upload progress
                                progress.children().css('width',Math.floor(percentComplete*100)+'%');
                                progress.children().text(Math.floor(percentComplete*100)+'%');
                            }
                        }, false);
                        //Download progress
                        //xhr.addEventListener("progress", function(evt){
                        //    if (evt.lengthComputable) {
                        //        var percentComplete = evt.loaded / evt.total;
                        //        //Do something with download progress
                        //        console.log(percentComplete);
                        //    }
                        //}, false);
                        return xhr;
                    },
                    success: function(result,status,xhr)
                    {
                        mainDiv.find('img').attr('src',result.file_name);
                        progress.addClass('hidden')
                        options.onUploaded(result);
                    },
                    error: function(xhr,status,error)
                    {
                        progress.addClass('hidden')
                        var error = JSON.parse(xhr.responseText);
                        options.onError(error);
                    }
                });
            }

        });
    }
});

var GOOGLE_MAP_STYLES = [
    {
        featureType: "all",
        stylers: [
            { saturation: -80 }
        ]
    },{
        featureType: "road",
        elementType: "geometry",
        stylers: [
            { hue: "#FFFFFF" },
            { saturation: 50 },
            {color : '#FFFFFF'}
        ]
    },{
        featureType: "poi",
        elementType: "geometry",
        stylers: [
            { visibility: "off" },
            {color : '#FEDE8C'}
        ]
    },
    {
        featureType: "landscape",
        elementType: "geometry",
        stylers: [
//                { visibility: "off" },
            {color : '#FEDE8C'}
        ]
    },
    {
        featureType : 'water',
        elementType: "geometry",
        stylers : [
            { color : '#34BBFF'}
        ]
    },

];




