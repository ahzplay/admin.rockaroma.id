@extends('layouts.base')

@section('css-add-on')
    <link rel="stylesheet" href="{{asset('css/file-upload.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="{{asset('css/loading-modal.css')}}">
@endsection

@section('content')
    {{Session::get('email')}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Shop</h1>
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
                            <div class="float-left">
                                {{--<div class="input-group">
                                    <select class="form-control" >
                                        <option>Status</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                    <div style="padding-right: 5px;"></div>
                                    <select class="form-control" >
                                        <option>Category</option>
                                    </select>
                                </div>--}}
                            </div>
                            <div class="float-right d-none d-sm-inline">
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target=".bd-category-modal-lg"><i class="fa fa-plus-square"></i> Category</button>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg" id="product-modal"><i class="fa fa-plus-square"></i> New Product</button>
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
                                <table id="product-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right d-none d-sm-inline">
                                {{--{{Request::segment(2)}}--}}
                                {{--<nav aria-label="Page navigation">
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
                                </nav>--}}
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
                    <h4>Product Info</h4>
                    <br>
                    <div id="form-alert" class="row" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <strong>Something wrong !</strong>
                                <p>
                                    Please complete product info.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div style="border-width: 1px; border-color: #F4F6F9; background-color: #F4F6F9; width: 246px; height: 313px;">
                                <img id="img-preview" src="" width="100%" style="max-height: 313px; min-height: 313px;">
                            </div>
                            <div style="padding-top: 10px;">
                                <label class="file-upload btn btn-primary btn-sm btn-block" style="color: black; background-color: #FFC108; border-color: #FFC108;">
                                    Upload Thumbnail
                                    <input type="file" id="product-image" name="videoThumb" accept="image/gif,image/jpeg, image/png" />
                                </label>
                            </div>
                            <div style="padding-top: 10px;">
                                <label style="font-size: 12px;">
                                    Upload your best image for Product Image. For best result please use photo ratio 16:9 with jpg, jpeg or png format
                                </label>
                            </div>
                        </div>
                        <div class="col-md-8" style="padding-left: 15px;">
                            <form id="formData" method="post" action="{{url('api/video-save')}}" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Item Name</label>
                                    <input type="text" class="form-control" name="productName" id="product-name">
                                    {{--<input type="file" id="product-image" name="videoThumb" accept="image/gif,image/jpeg, image/png" />--}}
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Price</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">Rp. </span>
                                        </div>
                                        <input type="text" class="form-control" id="price" name="price" aria-describedby="basic-addon3">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Category</label>
                                    <select class="form-control" id="category" name="category">
                                        <option>-- Select Below --</option>
                                        @foreach($categories as $val)
                                            <option id="{{$val->id}}"> {{$val->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tokopedia Link</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fa fa-link"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="tokopedia-link" name="tokopediaLink" aria-describedby="basic-addon3">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Shopee Link</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fa fa-link"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="shopee-link" name="shopeeLink" aria-describedby="basic-addon3">
                                    </div>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status-ready" value="1">
                                    <label class="form-check-label" for="inlineRadio1">Ready</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status-sold" value="0">
                                    <label class="form-check-label" for="inlineRadio2">Sold Out</label>
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

    <div id="category-modal" class="modal fade bd-category-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Categories</h4>
                    <hr>
                    <button type="button" id="add-category" onclick="addCategory()" class="btn btn-warning"> <i class="fa fa-plus-square"></i> Add New Category </button>
                    <div class="row" >
                        <div class="col-md-6">
                            <form class="form-inline" id="div-category" style="display: none;">
                                <div class="form-group" style="padding-right: 10px;">
                                    <input type="text" class="form-control" id="category-name" placeholder="Category Name"> <div style="width: 15px;"></div>
                                    <button type="button" id="save-category" onclick="saveCategory()" class="btn btn-warning" style="display: none;"> <i class="fa fa-save"></i> Save </button> <div style="width: 5px;"></div>
                                    <button type="button" id="cancel-category" onclick="cancelCategory()" class="btn btn-danger" style="display: none;"> <i class="fa fa-close"></i> Cancel </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="category-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-add-on')
    <script src="{{asset('js/file-upload.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://www.jqueryscript.net/demo/Fullscreen-Loading-Modal-Indicator-Plugin-For-jQuery-loadingModal/js/jquery.loadingModal.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            table = $('#category-table').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    "url"  : "{{url('api/categories-fetch')}}",
                    "data" : {
                        "responseWish" : 'datatables',
                    }
                },
                columns: [
                    {"data":"id"},
                    {"data":"name"},
                    {
                        "render": function (data, type, row) {
                            return "<a href='#' onclick='destroyCategory("+row.id+")'>Delete</a>";
                        },
                    }
                ],

            });

            tableProduct = $('#product-table').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    "url"  : "{{url('api/fetch-products')}}",
                    "data" : {
                        "responseWish" : 'datatables',
                    }
                },
                columns: [
                    {
                        "render": function (data, type, row) {
                            return "<table style='border-width: 0px; background-color: transparent;'>" +
                                "<tr >" +
                                "<td style='border-width: 0px; background-color: transparent;'>" +
                                "<img src='"+row.secureUrl+"' width='50'>" +
                                "</td>" +
                                "<td style='border-width: 0px; background-color: transparent;'>" +
                                "<strong style='font-size: 18px'>"+row.name +"</strong>"+
                                "<br>" +
                                "<div style='font-size: 14px'>" + row.categoryName + "</div>"
                                "</td>" +
                                "</tr>" +
                                "</table>";
                        },
                    },
                    {
                        "render": function (data, type, row) {
                            if(row.status == 1)
                                return "<strong style='color: green;'>Ready</strong>";
                            else
                                return "<strong style='color: grey;'>Sold Out</strong>";
                        },
                    },

                    {
                        "render": function (data, type, row) {
                            return '<a href="#" onclick="edit('+ row.id +')" style="color: blue;">Edit</a> <span style="padding-right: 15px;"></span> <a href="#" onclick="destroy('+ row.id +')" style="color: red;">Delete</a>';
                        },
                    },

                ],

            });

            setDateRangePicker(".startdate", ".enddate");

            $("#add-modal").on('hidden.bs.modal', function (e) {
                $('#form-alert').hide();
                $('#save-button').show();
                $('#update-button').hide();
                $('#img-preview').attr('src', '');
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
                    $('#img-preview').attr('src', e.target.result);
                    $('#img-preview').attr('size', e.target.result);
                }
                var base64 = reader.readAsDataURL(input_files[0]);
                console.log(base64); // convert to base64 string
            }
        }

        $(".file-upload").change(function() {
            readURL($('#product-image').prop('files'));
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function addCategory() {
            $('#add-category').hide();
            $('#save-category').show();
            $('#cancel-category').show();
            $('#div-category').show();
        }

        function cancelCategory() {
            $('#add-category').show();
            $('#save-category').hide();
            $('#cancel-category').hide();
            $('#category-name').val('');
            $('#div-category').hide();
        }

        function saveCategory() {
            if($('#category-name').val() == '') {
                alert("Category Name cannot be empty !");
            } else {
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'Shop Category will be added',
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
                                url: "{{url('api/add-shop-category')}}",
                                timeout: 150000,
                                data: 'categoryName='+$('#category-name').val(),
                                success: function(response){
                                    console.log(response);
                                    $('body').loadingModal('destroy') ;
                                    if(response.status == 'success') {
                                        $.confirm({
                                            title: 'Succeded !!',
                                            content: response.message,
                                            buttons: {
                                                confirm: function() {
                                                    table.draw();
                                                    cancelCategory();
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
                                        content: 'Save failed, please make sure your internet connection is stable'
                                    });
                                },
                            });
                        },
                        cancel: function () {},
                    }
                })
            }
        }

        function destroyCategory(id) {
            $.confirm({
                title: 'Are you sure ?',
                content: 'Shop Category will be added',
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
                            url: "{{url('api/destroy-shop-category')}}",
                            timeout: 150000,
                            data: 'id='+id,
                            success: function(response){
                                console.log(response);
                                $('body').loadingModal('destroy') ;
                                if(response.status == 'success') {
                                    $.confirm({
                                        title: 'Succeded !!',
                                        content: response.message,
                                        buttons: {
                                            confirm: function() {
                                                table.draw();
                                                cancelCategory();
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
                                    content: 'Delete failed, please make sure your internet connection is stable'
                                });
                            },
                        });
                    },
                    cancel: function () {},
                }
            });
        }

        function save() {
            if(
                $('#product-image').get(0).files.length <= 0 ||
                $('#product-name').val() == '' ||
                $('#price').val() == '' ||
                $('#category option:selected').attr('id') == '' ||
                $('#tokopedia-link').val() == '' ||
                $('#shopee-link').val() == '' ||
                $('#status').val() == ''
            ) {
                $('#form-alert').show();
            } else {
                $('#form-alert').hide();
                $('#save-button').show();
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'Product will be uploaded to website',
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
                            console.log($('#product-image').prop('files')[0]);
                            var formData = new FormData($('form')[0]);
                            formData.append('productImage', $('#product-image').prop('files')[0]);
                            formData.append('categoryId', $('#category option:selected').attr('id'));
                            $.ajax({
                                type: "POST",
                                url: "{{url('api/save-product')}}",
                                contentType: false,
                                cache: false,
                                processData:false,
                                timeout: 300000,
                                data: formData,
                                success: function(response){
                                    $('body').loadingModal('destroy') ;
                                    if(response.status == 'success') {
                                        $('#formData').trigger("reset");
                                        $('#img-preview').attr('src', '');
                                        $.confirm({
                                            title: 'Succeded !!',
                                            content: response.message,
                                            buttons: {
                                                confirm: function() {
                                                    table.draw();
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

        function destroy(id) {
            $.confirm({
                title: 'Are you sure ?',
                content: 'Product will be deleted permanently',
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
                            url: "{{url('api/destroy-product')}}",
                            data: 'id='+id,
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

        function showCategory(id) {
            $('#category-modal').modal('show');
        }

        function edit(id) {
            console.log(id);
            $('<input>').attr({
                type: 'hidden',
                id: 'productId',
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
                url: "{{url('api/get-product')}}",
                timeout: 300000,
                data: 'id='+id,
                success: function(response) {
                    $('body').loadingModal('destroy');
                    console.log(response);
                    $('#add-modal').modal('show');
                    $('#img-preview').attr('src', response[0].secure_url);
                    $('#product-name').val(response[0].name);
                    $('#price').val(response[0].price);
                    $('#tokopedia-link').val(response[0].tokopedia_url);
                    $('#shopee-link').val(response[0].shopee_url);
                    document.getElementById(response[0].category_id).selected = "true";
                    if(response[0].status == 1)
                        $("#status-ready").prop("checked", true);
                    else if(response[0].status == 0)
                        $("#status-sold").prop("checked", true);
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

        function update() {
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
                        formData.append('productImage', $('#product-image').prop('files')[0]);
                        formData.append('categoryId', $('#category option:selected').attr('id'));
                        $.ajax({
                            type: "POST",
                            url: "{{url('api/update-product')}}",
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
                                    $('#img-preview').attr('src', '');
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
