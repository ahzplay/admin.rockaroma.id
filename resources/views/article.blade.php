@extends('layouts.base')

@section('css-add-on')
    <link rel="stylesheet" href="{{asset('css/file-upload.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="{{asset('css/loading-modal.css')}}">
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Article</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-left d-none d-sm-inline">
                                <div class="input-group">
                                    <input type="text" class="form-control startdate" placeholder="Start Date"  />
                                    <div class="input-group-append" style="padding-right: 10px;">
                                        <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input type="text" class="form-control enddate" placeholder="End Date" />
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right d-none d-sm-inline">
                                <button type="button" class="btn btn-warning" onclick="window.location = '{{url('article-add-page')}}';"><i class="fa fa-plus-square"></i> New Article </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                    <tr>
                                        <th> # </th>
                                        <th> Video Title </th>
                                        <th> Date </th>
                                        <th> Action  </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right d-none d-sm-inline">
                                {{--{{Request::segment(2)}}--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js-add-on')
    <script src="{{asset('js/file-upload.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://www.jqueryscript.net/demo/Fullscreen-Loading-Modal-Indicator-Plugin-For-jQuery-loadingModal/js/jquery.loadingModal.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            setDateRangePicker(".startdate", ".enddate")

            $('#edit-href').on('click', '.modal-toggle', function (e){

            });

            $('#edit-href').click(function(){
                console.log('afadsfasdf asdfas');
                $('#add-modal').modal('show');
            });

            $("#add-modal").on('hidden.bs.modal', function (e) {
                $('#form-alert').hide();
                $('#save-button').show();
                $('#update-button').hide();
                $('#profile-img').attr('src', '');
                $('#video-title').val('');
                $('#youtube-embed').val('');
                $('#publish-checkbox').prop('checked', false);
                $('#videoId').remove();
                $('#youtube-modal-content').hide();
                $('#form-modal-content').show();
            });

            $('.enddate').change(function(){
                if($('.startdate').val() == '' || $('.enddate').val() == '') {
                    $.alert({
                        title: "Something wrong !",
                        content: "Please set start date then end date"
                    })
                } else {
                    location.href = '{{url('video-page/1')}}/'+$('.startdate').val()+'/'+$('.enddate').val();
                }

            });
        });

        function setDateRangePicker(input1, input2){
            $(input1).datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
            }).on("change", function(){
                $(input2).val("").datepicker('setStartDate', $(this).val());
            }).attr("readonly", "readonly").css({"cursor":"pointer", "background":"white"});
            $(input2).datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                orientation: "bottom right"
            }).attr("readonly", "readonly").css({"cursor":"pointer", "background":"white"});
        }

        function readURL(input_files) {
            if (input_files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#profile-img').attr('src', e.target.result);
                    $('#profile-img').attr('size', e.target.result);
                }
                var base64 = reader.readAsDataURL(input_files[0]);
                console.log(base64); // convert to base64 string
            }
        }

        $(".file-upload").change(function() {
            readURL($('#video-thumb-file').prop('files'));
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function videoPreview() {
            if($('#youtube-embed').val() == '') {
                $.alert({
                    title: 'Something wrong !',
                    content: 'Please fill valid youtube embed code'
                });
            } else {
                $('#youtube-modal-content').show();
                $('#form-modal-content').hide();
                $(".video-wrapper").html($('#youtube-embed').val()+'<button type="button" class="btn btn-warning btn-lg btn-block" onclick="backToForm();" style="border-radius: 0 !important;">Back</button>');
            }
        }

        function backToForm() {
            $('#youtube-modal-content').hide();
            $('#form-modal-content').show();
        }

        function save() {
            if(
                $('#video-thumb-file').get(0).files.length <= 0 ||
                $('#video-title').val() == '' ||
                $('#youtube-embed').val() == ''
            ) {
                $('#form-alert').show();
            } else {
                $('#form-alert').hide();
                $('#save-button').show();
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'Video will be uploaded to website',
                    buttons: {
                        confirm: function () {
                            $('body').loadingModal({
                                position: 'auto',
                                text: 'Please Wait...',
                                color: '#FFC108',
                                opacity: '0.7',
                                backgroundColor: 'rgb(0,0,0)',
                                animation: 'wanderingCubes'
                            });
                            $('#form-alert').hide();
                            console.log($('#video-thumb-file').prop('files')[0]);
                            var formData = new FormData($('form')[0]);
                            formData.append('videoThumb', $('#video-thumb-file').prop('files')[0]);
                            $.ajax({
                                type: "POST",
                                url: "{{url('api/video-save')}}",
                                contentType: false,
                                cache: false,
                                processData:false,
                                timeout: 300000,
                                data: formData,
                                success: function(response){
                                    $('body').loadingModal('destroy') ;
                                    if(response.status == 'success') {
                                        $('#formData').trigger("reset");
                                        $('#profile-img').attr('src', '');
                                        $.confirm({
                                            title: 'Succeded !!',
                                            content: response.message,
                                            buttons: {
                                                confirm: function() {
                                                    location.reload();
                                                }
                                            }
                                        });
                                    } else {
                                        $('body').loadingModal('destroy') ;
                                        $.alert({
                                            title: "Something wrong !",
                                            content: response.message
                                        })
                                    }
                                },
                                error: function(){
                                    $('body').loadingModal('destroy');
                                    $.alert({
                                        title: 'Something wrong !',
                                        content: 'Uploaded failed, please make sure your internet connection is stable'
                                    });
                                },
                            });
                        },
                        cancel: function () {},
                    }
                });
            }
        }

        function destroy(id, publicId) {
            $.confirm({
                title: 'Are you sure ?',
                content: 'Video will be deleted permanently',
                buttons: {
                    confirm: function () {
                        $('body').loadingModal({
                            position: 'auto',
                            text: 'Please Wait...',
                            color: '#FFC108',
                            opacity: '0.7',
                            backgroundColor: 'rgb(0,0,0)',
                            animation: 'wanderingCubes'
                        });
                        $.ajax({
                            type: "POST",
                            url: "{{url('api/video-destroy')}}",
                            data: 'id='+id+'&publicId='+publicId,
                            timeout: 300000,
                            success: function(response){
                                $('body').loadingModal('destroy') ;
                                if(response.status == 'success') {
                                    $.confirm({
                                        title: 'Succeded !!',
                                        content: response.message,
                                        buttons: {
                                            confirm: function() {
                                                location.reload();
                                            }
                                        }
                                    });
                                } else {
                                    $('body').loadingModal('destroy') ;
                                    $.alert({
                                        title: "Something wrong !",
                                        content: response.message
                                    })
                                }
                            },
                            error: function(){
                                $('body').loadingModal('destroy') ;
                                $.alert({
                                    title: 'Something wrong !',
                                    content: 'Deleted failed, please make sure your internet connection is stable'
                                });
                            },
                        });
                    },
                    cancel: function () {},
                }
            });
        }

        function edit(id) {
            $('<input>').attr({
                type: 'hidden',
                id: 'videoId',
                name: 'id',
                value: id
            }).appendTo('form');
            $('body').loadingModal({
                position: 'auto',
                text: 'Please Wait...',
                color: '#FFC108',
                opacity: '0.7',
                backgroundColor: 'rgb(0,0,0)',
                animation: 'wanderingCubes'
            });

            $('#save-button').hide();
            $('#update-button').show();
            $.ajax({
                type: "POST",
                url: "{{url('api/video-get')}}",
                timeout: 300000,
                data: 'id='+id,
                success: function(response) {
                    $('body').loadingModal('destroy');
                    console.log(response);
                    $('#add-modal').modal('show');
                    $('#profile-img').attr('src', response[0].secure_url);
                    $('#video-title').val(response[0].title);
                    $('#youtube-embed').val(response[0].youtube_embeded);
                    $('#publish-checkbox').prop('checked', response[0].is_active);
                    $('#form-alert').hide();
                },
                error: function(){
                    $('body').loadingModal('destroy');
                    $.alert({
                        title: 'Something wrong !',
                        content: 'Data failed, please make sure your internet connection is stable'
                    });
                },
            });
        }

        function update(id) {
            $('#form-alert').hide();
            $.confirm({
                title: 'Are you sure ?',
                content: 'Video will be uploaded to website',
                buttons: {
                    confirm: function () {
                        $('body').loadingModal({
                            position: 'auto',
                            text: 'Please Wait...',
                            color: '#FFC108',
                            opacity: '0.7',
                            backgroundColor: 'rgb(0,0,0)',
                            animation: 'wanderingCubes'
                        });
                        $('#form-alert').hide();
                        var formData = new FormData($('form')[0]);
                        //formData.append('id', id);
                        formData.append('videoThumb', $('#video-thumb-file').prop('files')[0]);
                        $.ajax({
                            type: "POST",
                            url: "{{url('api/video-update')}}",
                            contentType: false,
                            cache: false,
                            processData:false,
                            timeout: 300000,
                            data: formData,
                            success: function(response){
                                console.log(response);
                                $('body').loadingModal('destroy') ;
                                if(response.status == 'success') {
                                    $('#formData').trigger("reset");
                                    $('#profile-img').attr('src', '');
                                    $.confirm({
                                        title: 'Succeded !!',
                                        content: response.message,
                                        buttons: {
                                            confirm: function() {
                                                location.reload();
                                            }
                                        }
                                    });
                                } else {
                                    $('body').loadingModal('destroy') ;
                                    $.alert({
                                        title: "Something wrong !",
                                        content: response.message
                                    })
                                }
                            },
                            error: function(){
                                $('body').loadingModal('destroy');
                                $.alert({
                                    title: 'Something wrong !',
                                    content: 'Uploaded failed, please make sure your internet connection is stable'
                                });
                            },
                        });
                    },
                    cancel: function () {},
                }
            });
        }
    </script>
@endsection
