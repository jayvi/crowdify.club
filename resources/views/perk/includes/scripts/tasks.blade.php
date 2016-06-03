<script>
    $(document).ready(function(){

        initilizeFileUpload(null);

        $(document).on('click','.image-remove-link',function(e){
            e.preventDefault();
            var imageDiv = $(this).closest('.image');
            revertImageUploadView(imageDiv);
        });

        $('#btn-create-task').click(function(e){
            var data = getTaskData($(this).closest('.task-form'));
            console.log(data);
            if(!validateInputs(data,['title','description','link','reward','total_amount'])){
                toastr.warning('Please fill the values');
                return;
            }

            var taskForm = $(this).closest('.task-form');

            var url = '{{route('hugs::create')}}';
            CrowdifyAjaxService.makeRequest(url,'POST' ,data, function(response){
                $('#tasks').prepend(response.view);
                invalidateCreateView(taskForm);
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

        function getTaskData(form){
            return {
                title: form.find('input[name=title]').val(),
                description: form.find('.description').val(),
                link: form.find('input[name=link]').val(),
                photo: form.find('input[name=photo]').val(),
                reward: form.find('input[name=reward]').val(),
                total_amount: form.find('input[name=total_amount]').val(),
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
