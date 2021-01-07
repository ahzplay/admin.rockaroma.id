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
                    <h1 class="m-0 text-dark">Video Content</h1>
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
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-plus-square"></i> New Video</button>
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
                                    @foreach($raw as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$val->title}}</td>
                                            <td>{{$val->created_at->toDateString()}}</td>
                                            <td><a href="#" id="edit-href" onclick="edit('{{$val->id}}')" style="color: blue;" data-toggle="modal" data-target=".bd-example-modal-lg">Edit</a> <span style="padding-right: 15px;"></span> <a href="#" onclick="destroy('{{$val->id}}','{{$val->public_id}}')" style="color: red;">Delete</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right d-none d-sm-inline">
                                {{--{{Request::segment(2)}}--}}
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item">
                                            @if(Request::segment(2)>1)
                                                @php
                                                    $prevPage = Request::segment(2)-1;
                                                @endphp
                                                <a class="page-link" href="{{url('video-page/').'/'.$prevPage.'/0/0'}}" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            @else
                                                <a class="page-link" href="" aria-label="Previous" style="pointer-events: none; cursor: default;">
                                                    <span aria-hidden="true">&laquo;</span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            @endif
                                        </li>
                                        @for ($i = 1; $i <= $page; $i++)
                                            <li class="page-item">
                                                <a class="page-link" href="{{url('video-page/').'/'.$i.'/0/0'}}">
                                                    @if(Request::segment(2) == $i)
                                                        <strong>{{$i}}</strong>
                                                    @else
                                                        {{$i}}
                                                    @endif
                                                </a>
                                            </li>
                                        @endfor
                                        <li class="page-item">
                                            @if(Request::segment(2)<$page)
                                                @php
                                                    $nextPage = Request::segment(2)+1;
                                                @endphp
                                                <a class="page-link" href="{{url('video-page/').'/'.$nextPage.'/0/0'}}" aria-label="Previous">
                                                    <span aria-hidden="true">&raquo;</span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            @else
                                                <a class="page-link" href="" aria-label="Previous" style="pointer-events: none; cursor: default;">
                                                    <span aria-hidden="true">&raquo;</span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="add-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div id="youtube-modal-content" class="modal-content" style="display: none; background-color: #212020;">
                <div class="video-wrapper text-center"></div>
            </div>
            <div id="form-modal-content"class="modal-content">
                <div class="modal-body">
                    <h4>Content Video</h4>
                    <br>
                    <div id="form-alert" class="row" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <strong>Something wrong !</strong>
                                <p>
                                    Please complete video's content.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div style="border-width: 1px; border-color: #F4F6F9; background-color: #F4F6F9; width: 100%; height: 210px;">
                                <img id="profile-img" src="" width="100%" style="max-height: 210px; min-height: 210px;">
                            </div>
                            <div style="padding-top: 10px;">
                                <label class="file-upload btn btn-primary btn-sm btn-block" style="color: black; background-color: #FFC108; border-color: #FFC108;">
                                    Upload Thumbnail
                                    <input type="file" id="video-thumb-file" name="videoThumb" accept="image/gif,image/jpeg, image/png" />
                                </label>
                            </div>
                            <div style="padding-top: 10px;">
                                <label style="font-size: 12px;">
                                    Upload your best image thumbnail. For best result please use photo ratio 16:9 with jpg, jpeg or png format
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding-left: 25px;">
                            <form id="formData" method="post" action="{{url('api/video-save')}}" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Title</label>
                                    <input type="text" class="form-control" name="videoTitle" id="video-title">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Video Embed</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fa fa-link"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="youtube-embed" name="youtubeEmbed" aria-describedby="basic-addon3">
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="videoPublish" value="1" id="publish-checkbox">
                                    <label class="form-check-label" for="exampleCheck1">Publish Video</label>
                                    | <a href="#" onclick="videoPreview()" style=" color: #670404;"><i class="fa fa-youtube-square"></i> Video Preview</a>
                                </div>
                           </form>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 25px;">
                        <div class="col-md-12">
                            <div class="float-right d-none d-sm-inline">
                                <button type="button" id="save-button" onclick="save()" class="btn btn-warning"> Save </button>
                                <button type="button" id="update-button" onclick="update()" class="btn btn-warning" style="display: none;"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--<div id="add-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Content Video</h4>
                    <br>
                    <div id="form-alert" class="row" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <strong>Something wrong !</strong>
                                <p>
                                    Please complete video's content.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div style="border-width: 1px; border-color: #F4F6F9; background-color: #F4F6F9; width: 100%; height: 210px;">
                                <img id="profile-img" src="" width="100%" style="max-height: 210px; min-height: 210px;">
                            </div>
                            <div style="padding-top: 10px;">
                                <label class="file-upload btn btn-primary btn-sm btn-block" style="color: black; background-color: #FFC108; border-color: #FFC108;">
                                    Upload Thumbnail
                                    <input type="file" id="video-thumb-file" name="videoThumb" accept="image/gif,image/jpeg, image/png" />
                                </label>
                            </div>
                            <div style="padding-top: 10px;">
                                <label style="font-size: 12px;">
                                    Upload your best image thumbnail. For best result please use photo ratio 16:9 with jpg, jpeg or png format
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding-left: 25px;">
                            <form id="formData" method="post" action="{{url('api/video-save')}}" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Title</label>
                                    <input type="text" class="form-control" name="videoTitle" id="video-title">
                                    --}}{{--<input type="file" id="video-thumb-file" name="videoThumb" accept="image/gif,image/jpeg, image/png" />--}}{{--
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Video Embed</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fa fa-link"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="youtube-embed" name="youtubeEmbed" aria-describedby="basic-addon3">
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="videoPublish" value="1" id="videoPublish">
                                    <label class="form-check-label" for="exampleCheck1">Publish Video</label>
                                </div>
                                --}}{{--<button type="submit" onclick="save()" class="btn btn-warning"> Save </button>--}}{{--
                            </form>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 25px;">
                        <div class="col-md-12">
                            <div class="float-right d-none d-sm-inline">
                                <button type="button" onclick="save()" class="btn btn-warning"> Save </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}

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
