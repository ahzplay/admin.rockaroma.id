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
                            {{--<div class="float-left d-none d-sm-inline">
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
                            </div>--}}
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
                                <table id="article-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Article Title</th>
                                        <th>Post Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
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

            table = $('#article-table').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    "url"  : "{{url('api/article-fetch')}}",
                    "data" : {
                        "responseWish" : 'datatables',
                    }
                },
                columns: [
                    {"data":"title"},
                    {"data":"createdAt"},
                    {
                        "render": function (data, type, row) {
                            return "<a href='#' onclick='edit("+row.id+")'>Edit</a> <span style='padding-right: 15px;'></span> <a href='#' onclick='destroy("+row.id+")'>Delete</a>";
                            //return "";
                        },
                    }
                ],

            });

            setDateRangePicker(".startdate", ".enddate")
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


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function edit(id) {
            window.location = "{{url('article-edit-page/')}}/" + id;
        }

        function destroy(id) {
            $.confirm({
                title: 'Are you sure ?',
                content: 'Article will be deleted permanently',
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
                            url: "{{url('api/article-destroy')}}",
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

    </script>
@endsection
