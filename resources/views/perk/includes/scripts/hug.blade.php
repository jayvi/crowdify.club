<script>
    $(document).ready(function(){

        function makeAjaxRequest(url, data, method, successCallback, errorCallback){
            $.ajax({
                url: url,
                data: data,
                method: method
            }).done(function(response){
                successCallback(response);
            }).fail(function(response){
                errorCallback(response);
            });
        }

        $('.completion-details').click(function(e){

            var hug_id = $(this).data('hug-id');
            var data = {
                hug_id: hug_id
            };
            var url = '{{ route('hugs::completers') }}';

            makeAjaxRequest(url, data, 'POST',
                    function(response){
                        if(response.success){
                            $('#hug-completer-modal-body').html(response.view);
                            $('#hug-completer').modal('show');
                        }
                    },
                    function(response){

                    }
            );
        });

        $('#hug-completer-modal-body, .hug-completer-div').on('click', '.revoke-button', (function(e){
            var button = $(this);
            var hug_id = $(this).data('hug-id');
            var completer_id = $(this).data('completer-id');
            var data = {
                hug_id: hug_id,
                completer_id: completer_id
            };
            var url = '{{ route('hugs::revokeCompletion') }}';

            makeAjaxRequest(url, data, 'POST',
                    function(response){
                        if(response.success){
                            button.closest('.hug-completer-div').remove();
                        }
                    },
                    function(response){

                    }
            );
        }));

        {{--$('#hug-completer-modal-body').on('click', '#approve-all-button', (function(e){--}}
            {{--var button = $(this);--}}
            {{--var hug_id = $(this).data('hug-id');--}}
            {{--var data = {--}}
                {{--hug_id: hug_id--}}
            {{--};--}}
            {{--var url = '{{ route('hugs::approveAllCompletion') }}';--}}

            {{--makeAjaxRequest(url, data, 'POST',--}}
                    {{--function(response){--}}
                        {{--if(response.success){--}}
                            {{--button.replaceWith('<p class="pull-right">Approved All</p>');--}}
                            {{--$('.revoke-button').replaceWith('<p class="pull-right">Approved</p>');--}}
                        {{--}--}}
                    {{--},--}}
                    {{--function(response){--}}

                    {{--}--}}
            {{--);--}}
        {{--}));--}}

        $('.delete-link').on('click', function(e){
            e.preventDefault();
            var delete_id = $(this).data('hug-id');
            $('#delete-modal-hug-id').val(delete_id);
        });
    });
</script>