@extends('layouts.base')

@section('css-add-on')
    <link rel="stylesheet" href="{{asset('css/file-upload.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="{{asset('css/loading-modal.css')}}">

    <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="{{url('article-page')}}"><i class="fa fa-arrow-left"></i> Article</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <form id="formData" method="post" enctype="multipart/form-data">
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title Here">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <textarea id="content" name="content" class="ckeditor"></textarea>
                                    <input type="hidden" id="content-out" name="contentOut" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header" style="background-color: lightgrey;cursor: move;">
                                    <h3 class="card-title" >
                                        Article Images Gallery
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <label>Please use image with size 600×400 pixel</label>
                                    <div class="input-group">
                                        <input type="file" id="gallery-1-file" name="gallery1File" class="jfilestyle" data-size="100%" data-input="true">
                                        &nbsp;&nbsp;
                                        <input type="file" id="gallery-2-file" name="gallery2File" class="jfilestyle" data-size="100%" data-input="true">
                                    </div>
                                    <div class="input-group">
                                        <input type="file" id="gallery-3-file" name="gallery3File" class="jfilestyle" data-size="100%" data-input="true">
                                        &nbsp;&nbsp;
                                        <input type="file" id="gallery-4-file" name="gallery4File" class="jfilestyle" data-size="100%" data-input="true">
                                    </div>
                                    <div class="input-group">
                                        <input type="file" id="gallery-5-file" name="gallery5File" class="jfilestyle" data-size="100%" data-input="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-0 ui-sortable-handle" style="background-color: lightgrey;cursor: move;">
                                    <h3 class="card-title">
                                        Publish
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-default btn-sm" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-default btn-block btn-sm" style="color: red;">
                                                Discard
                                            </button>
                                        </div>
                                        {{--<div class="col-md-4">
                                            <button type="button" class="btn btn-primary btn-block btn-sm">
                                                Preview
                                            </button>
                                        </div>--}}
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-warning btn-block btn-sm" onclick="save()">
                                                <i class="fa fa-cloud-upload"></i> Publish
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-0 ui-sortable-handle" style="background-color: lightgrey;cursor: move;">
                                    <h3 class="card-title">
                                        Image Article
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-default btn-sm" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" style="display: block;">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <center>Banner Image</center>
                                            <div style="border-width: 1px; border-color: #F4F6F9; background-color: #F4F6F9; width: 100%; height: 180px;">
                                                <img id="banner-img" src="" width="100%" style="max-height: 180px; min-height: 180px;">
                                            </div>
                                            <div style="padding-top: 10px;">
                                                <label class="file-upload btn btn-primary btn-sm btn-block" style="color: black; background-color: #FFC108; border-color: #FFC108;">
                                                    Banner Image
                                                    <input type="file" id="banner-file" name="bannerFile" accept="image/jpeg, image/png" />
                                                </label>
                                                <label style="font-size: 12px;">
                                                    *Please use image with size 1280x720 pixel
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">

                                        <div class="col-md-12">
                                            <center>Thumbnail Image</center>
                                            <div style="border-width: 1px; border-color: #F4F6F9; background-color: #F4F6F9; width: 100%; height: 320px;">
                                                <img id="thumb-img" src="" width="100%" style="max-height: 320px; min-height: 320px;">
                                            </div>
                                            <div style="padding-top: 10px;">
                                                <label class="file-upload btn btn-primary btn-sm btn-block" style="color: black; background-color: #FFC108; border-color: #FFC108;">
                                                    Thumbnail Image
                                                    <input type="file" id="thumb-file" name="thumbFile" accept="image/jpeg, image/png" />
                                                </label>
                                                <label style="font-size: 12px;">
                                                    *Please use image with size 440x550 pixel
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
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
            CKEDITOR.instances['content'].on('change', function() {
                $('#content-out').val(CKEDITOR.instances['content'].getData());
            });

        });

        function readURLBanner(input_files) {
            if (input_files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    console.log(e.target.result);
                    $('#banner-img').attr('src', e.target.result);
                    $('#banner-img').attr('size', e.target.result);
                }
                reader.readAsDataURL(input_files[0]);
            }
        }

        $("#banner-file").change(function() {
            readURLBanner($('#banner-file').prop('files'));
        });

        function readURLThumb(input_files) {
            if (input_files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    console.log(e.target.result);
                    $('#thumb-img').attr('src', e.target.result);
                    $('#thumb-img').attr('size', e.target.result);
                }
                reader.readAsDataURL(input_files[0]);
            }
        }

        $("#thumb-file").change(function() {
            readURLThumb($('#thumb-file').prop('files'));
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function save() {
            /*console.log($('#thumb-file').get(0).files.length);
            console.log($('#title').val());
            console.log($('#content-out').val());
            console.log($('#gallery-1-file').get(0).files.length);
            console.log($('#gallery-2-file').get(0).files.length);
            console.log($('#gallery-3-file').get(0).files.length);
            console.log($('#gallery-4-file').get(0).files.length);
            console.log($('#gallery-5-file').get(0).files.length);*/
            if(
                $('#thumb-file').get(0).files.length <= 0 ||
                $('#banner-file').get(0).files.length <= 0 ||
                $('#gallery-1-file').get(0).files.length <= 0 ||
                $('#gallery-2-file').get(0).files.length <= 0 ||
                $('#gallery-3-file').get(0).files.length <= 0 ||
                $('#gallery-4-file').get(0).files.length <= 0 ||
                $('#gallery-5-file').get(0).files.length <= 0 ||
                $('#title').val() == '' ||
                $('#content-out').val() == ''
            ) {
                $.alert({
                    title: 'Something wrong !',
                    content: 'Please complete article\' contents'
                });
            } else {
                /*$.alert({
                    title: 'Something wrong !',
                    content: 'YES COMPLETE'
                });*/
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'Article will be uploaded to website',
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
                            var formData = new FormData($('form')[0]);
                            formData.append('contentOut', $('#content-out').val());
                            formData.append('thumbFile', $('#thumb-file').prop('files')[0]);
                            formData.append('bannerFile', $('#banner-file').prop('files')[0]);
                            formData.append('gallery1File', $('#gallery-1-file').prop('files')[0]);
                            formData.append('gallery2File', $('#gallery-2-file').prop('files')[0]);
                            formData.append('gallery3File', $('#gallery-3-file').prop('files')[0]);
                            formData.append('gallery4File', $('#gallery-4-file').prop('files')[0]);
                            formData.append('gallery5File', $('#gallery-5-file').prop('files')[0]);
                            $.ajax({
                                type: "POST",
                                url: "{{url('api/save-article')}}",
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

    </script>
@endsection
