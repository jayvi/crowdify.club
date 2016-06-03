<script>
    $(document).ready(function(){

        initilizeFileUpload(null);

        $(document).on('click','.image-remove-link',function(e){
            e.preventDefault();
            var imageDiv = $(this).closest('.image');
            revertImageUploadView(imageDiv);
        });

        $('#btn-create-status').click(function(e){
            var data = getTaskData($(this).closest('.status-form'));
            console.log(data);
            if(!validateInputs(data,['status'])){
                toastr.warning('Please fill the values');
                return;
            }
            if(data.status.length > 200){
                toastr.warning('Sorry, You can update your status max 200 characters.');
                return;
            }

            var taskForm = $(this).closest('.status-form');
            //var userId = {{$auth->id()}};

            var url = '{{route('status::create')}}';
            CrowdifyAjaxService.makeRequest(url,'POST' ,data, function(response){

                $('#entities').prepend(response.view);
                linkify($('[linkify]'));
                invalidateCreateView(taskForm);
                twttr.widgets.load();
            }, function(error){

            });
        });

        function invalidateCreateView(taskForm){
            taskForm.find("input[type=text], input[type=number],textarea").val("");
            revertImageUploadView(taskForm.find('.image'));
        }

        function revertImageUploadView(imageDiv){
            imageDiv.addClass('hidden');
            imageDiv.find('.photo').val('');
            imageDiv.find('img').attr('src','');
        }


        $('#status-textarea').bind('input propertychange', function() {
            invalidateCharacterLimit($(this).closest('.status-form') ,200 - $(this).val().length);
        });

        function invalidateCharacterLimit(form, remainingCharacter){
            form.find('.character-limit-text').text('Remaining characters:  '+ remainingCharacter);
            console.log(remainingCharacter);
            if(remainingCharacter < 0){
                form.find('.character-limit-text').removeClass('hidden');
                form.find('.character-limit-text').addClass('text-danger');
                form.find('#btn-create-status').prop( "disabled", true );
            }else{
                form.find('.character-limit-text').removeClass('hidden');
                form.find('.character-limit-text').removeClass('text-danger');
                form.find('#btn-create-status').prop( "disabled", false );
                if(remainingCharacter >= 200){
                    form.find('.character-limit-text').addClass('hidden');
                }
            }
        }

        function getTaskData(form){
            return {
                status: form.find('textarea').val(),
            }
        }

        function validateInputs(data, requiredFileds){
            var isValid = true;
            for(var key in data){

                if(requiredFileds.indexOf(key) != -1 && !data[key]){
                    console.log(key);
                    isValid = false;
                    break;
                }
            }
            return isValid;
        }

        function initilizeFileUpload(fileUploadIcon){
            if(fileUploadIcon){

            }else{


                $('.task-create-div').on('click','.photo-upload', function(e){
                    e.preventDefault();
                    $(this).closest('.panel-footer').find('input[type=file]').trigger('click');
                });
                $('.task-create-div').on('change','input[type=file]', function(e){
                    e.preventDefault();
                    var imageUpload = $(this).closest('.panel').find('.uploaded-image-div');
                    var files = e.target.files;

                    var data = new FormData();
                    $.each(files, function(key, value)
                    {
                        data.append('file', value);
                    });

                    var progressDiv = imageUpload.find('.progress');

                    var url = '{{route('image::upload')}}';
                    CrowdifyAjaxService.uploadImage(url, data,function(progress){

                        progressDiv.removeClass('hidden');
                        progressDiv.children().css('width',Math.floor(progress * 100)+'%');
                        progressDiv.children().text(Math.floor(progress * 100)+'%')

                    } ,function(response){
                        progressDiv.children().css('width', 100+'%');
                        progressDiv.children().text('100%');
                        progressDiv.addClass('hidden');

                        imageUpload.find('img').attr('src', response.file_name);
                        imageUpload.find('.image').removeClass('hidden')
                    }, function(error){
                        console.log(error);
                    });
                });

            }

        }
    });
</script>

{{--for twitter share button--}}
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.async=true;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
