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
                    <h1 class="m-0 text-dark">Image Slider</h1>
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
                            <div class="card-body table-responsive p-0">
                                <table id="slider-table" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th> # </th>
                                        <th> Title </th>
                                        <th> Link </th>
                                        <th> Image </th>
                                        <th> Status </th>
                                        <th> Action  </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($raw as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$val->title}}</td>
                                            <td> - </td>
                                            <td><a href="#" onclick="showImage('{{$val->secure_url}}')">Show Image</a></td>
                                            {{--<td>{{$val->is_active=1?'Active':'Not Active'}}</td>--}}
                                            <td>
                                                @if($val->is_active==1)
                                                    <button type="button" id="status-btn" onclick="updateStatus('{{$val->id}}','{{$val->is_active}}','{{$val->order}}')" class="btn btn-success btn-xs">Active</button>
                                                @else
                                                    <button type="button" id="status-btn" onclick="updateStatus('{{$val->id}}','{{$val->is_active}}','{{$val->order}}')" class="btn btn-default btn-xs">Not Active</button>
                                                @endif
                                            </td>
                                            <td><a href="#" onclick="edit('{{$val->id}}')" style="color: blue;">Edit</a> {{--<span style="padding-right: 15px;"></span> <a href="#" onclick="destroy('{{$val->id}}','{{$val->public_id}}')" style="color: red;">Delete</a>--}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="image-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div id="image-modal-content" class="modal-content" style="display: none; background-color: #212020;">
                <div class="modal-body">asdfasdfasdf</div>
                {{--<div class="image-wrapper text-center">
                    adsfadsfasdfasdfadsf
                </div>--}}
            </div>
        </div>
    </div>


    <div id="img-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="img-wrapper" width="765">
                </div>
            </div>
        </div>
    </div>

    <div id="add-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Image Slider</h4>
                    <br>
                    <div id="form-alert" class="row" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <strong>Something wrong !</strong>
                                <p>
                                    Please complete slider's content.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div style="border-width: 1px; border-color: #F4F6F9; background-color: #F4F6F9; width: 100%; height: 210px;">
                                <img id="image-preview" src="" width="100%" style="max-height: 210px; min-height: 210px;">
                            </div>
                            <div style="padding-top: 10px;">
                                <label class="file-upload btn btn-primary btn-sm btn-block" style="color: black; background-color: #FFC108; border-color: #FFC108;">
                                    Upload Thumbnail
                                    <input type="file" id="image-file" name="imageFile" accept="image/gif,image/jpeg, image/png" />
                                </label>
                            </div>
                            <div style="padding-top: 10px;">
                                <label style="font-size: 12px;">
                                    Upload your best image slider. For best result please use photo size 1280x720 pixel with jpg, jpeg or png format
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding-left: 25px;">
                            <form id="formData" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Title</label>
                                    <input type="text" class="form-control" name="sliderTitle" id="slider-title">
                                    {{--<input type="file" id="image-file" name="imageFile" accept="image/gif,image/jpeg, image/png" />--}}
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Link</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fa fa-link"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="link" name="link" aria-describedby="basic-addon3" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Image URL</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fa fa-image"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="secure-url" name="secureUrl" aria-describedby="basic-addon3" disabled>
                                    </div>
                                </div>
                                {{--<div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="videoPublish" value="1" id="videoPublish">
                                    <label class="form-check-label" for="exampleCheck1">Publish Video</label>
                                </div>--}}
                                {{--<button type="submit" onclick="save()" class="btn btn-warning"> Save </button>--}}
                            </form>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 25px;">
                        <div class="col-md-12">
                            <div class="float-right d-none d-sm-inline">
                                <button type="button" onclick="update()" class="btn btn-warning"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-add-on')
    <script src="{{asset('js/file-upload.js')}}"></script>
    <script src="{{asset('js/row-sorter.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://www.jqueryscript.net/demo/Fullscreen-Loading-Modal-Indicator-Plugin-For-jQuery-loadingModal/js/jquery.loadingModal.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            setDateRangePicker(".startdate", ".enddate")

            $("#add-modal").on('hidden.bs.modal', function (e) {
                $('#form-alert').hide();
                $('#save-button').show();
                $('#update-button').hide();
                $('#image-preview').attr('src', '');
                $('#slider-title').val('');
                $('#secure-url').val('');
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

            $("#slider-table").rowSorter({
                onDrop: function(tbody, row, index, oldIndex) {
                    console.log('tBody = '+tbody.tagName);
                    console.log(row);
                    console.log('INDEX = '+index);
                    console.log('OLD INDEX = '+oldIndex);
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
                    $('#image-preview').attr('src', e.target.result);
                    $('#image-preview').attr('size', e.target.result);
                }
                var base64 = reader.readAsDataURL(input_files[0]);
                console.log(base64); // convert to base64 string
            }
        }

        $(".file-upload").change(function() {
            readURL($('#image-file').prop('files'));
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showImage(imgLink) {
            $('#img-modal').modal('show');
            $("#img-wrapper").attr('src',imgLink);
        }

        function backToForm() {
            $('#youtube-modal-content').hide();
            $('#form-modal-content').show();
        }

        function save() {
            if(
                $('#image-file').get(0).files.length <= 0 ||
                $('#slider-title').val() == '' ||
                $('#secure-url').val() == ''
            ) {
                $('#form-alert').show();
            } else {
                $('#form-alert').hide();
                $('#save-button').show();
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'Slider will be uploaded to website',
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
                            console.log($('#image-file').prop('files')[0]);
                            var formData = new FormData($('form')[0]);
                            formData.append('imageFile', $('#image-file').prop('files')[0]);
                            $.ajax({
                                type: "POST",
                                url: "{{url('api/dashboard-slider-update')}}",
                                contentType: false,
                                cache: false,
                                processData:false,
                                timeout: 300000,
                                data: formData,
                                success: function(response){
                                    $('body').loadingModal('destroy') ;
                                    if(response.status == 'success') {
                                        $('#formData').trigger("reset");
                                        $('#image-preview').attr('src', '');
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

        /*function destroy(id, publicId) {
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
                            url: "",
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
        }*/

        function updateStatus(id, status, order) {
            if(order == '0' && status == '1') {
                $.alert({
                    title: 'Something wrong !',
                    content: 'Sliders in the first order cannot be disabled'
                });

                return;
            }

            var postStatus;
            if(status == '1')
                postStatus = 0;
            else
                postStatus = 1;

            console.log(postStatus);
            $.ajax({
                type: "POST",
                url: "{{url('api/dashboard-slider-update-status')}}",
                timeout: 300000,
                data: 'id='+id+'&status='+postStatus,
                success: function(response) {
                    $('body').loadingModal('destroy');
                    location.reload();
                    /*if(postStatus == 1) {
                        $('#status-btn').attr('class','btn btn-success btn-xs')
                        location.reload();
                    }
                    else {
                        $('#status-btn').attr('class','btn btn-default btn-xs')
                        location.reload();
                    }*/
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
                url: "{{url('api/dashboard-slider-get')}}",
                timeout: 300000,
                data: 'id='+id,
                success: function(response) {
                    $('body').loadingModal('destroy');
                    console.log(response);
                    $('#add-modal').modal('show');
                    $('#image-preview').attr('src', response[0].secure_url);
                    $('#slider-title').val(response[0].title);
                    $('#secure-url').val(response[0].secure_url);
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
                        formData.append('imageFile', $('#image-file').prop('files')[0]);
                        $.ajax({
                            type: "POST",
                            url: "{{url('api/dashboard-slider-update')}}",
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
                                    $('#image-preview').attr('src', '');
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
