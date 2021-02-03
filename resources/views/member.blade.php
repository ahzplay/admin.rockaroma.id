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
                    <h1 class="m-0 text-dark">Member</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            {{--<div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            --}}{{--<div class="float-left d-none d-sm-inline">
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
                            </div>--}}{{--
                            <div class="float-right d-none d-sm-inline">
                                <button type="button" class="btn btn-warning" onclick="window.location = '{{url('article-add-page')}}';"><i class="fa fa-plus-square"></i> New Article </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-body table-responsive p-0">
                                <table id="member-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Phone Number</th>
                                        <th>City</th>
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

        <div id="detail-modal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Member Profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3 id="fullname-label"></h3><br>
                        <label id="location"></label><br>
                        <label id="email"></label><br>
                        <label id="phone-number"></label><br>
                        <label id="smoker-label"></label><br>
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
            table = $('#member-table').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    "url"  : "{{url('api/fetch-members')}}",
                },
                columns: [
                    {"data":"full_name"},
                    {"data":"phone_number"},
                    {"data":"city_name"},
                    {
                        "render": function (data, type, row) {
                            return "<a href='#' onclick='showModal(\""+row.full_name+"\",\""+row.province_name+"\",\""+row.city_name+"\",\""+row.email+"\",\""+row.phone_number+"\",\""+row.is_smoker_label+"\")'>Detail</a>";
                        },
                    }
                ],

            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //showModal(\""+row.full_name+"\",\""+row.province_name+"\",\""+row.city_name+"\",\""+row.email+"\",\""+row.phone_number+"\",\""+row.is_smoker_label+"\")
        function showModal(fullname, provinceName, cityName, email, phoneNumber, smokerLabel) {
            $('#fullname-label').html(fullname);
            $('#location').html('<i class="fa fa-map-marker"></i> &nbsp;&nbsp;'+provinceName + ', ' + cityName);
            $('#email').html('<i class="fa fa-mail-bulk"></i> &nbsp;&nbsp;'+email);
            $('#phone-number').html('<i class="fa fa-phone"></i> &nbsp;&nbsp;'+phoneNumber);
            $('#smoker-label').html(smokerLabel);
            $('#detail-modal').modal('show');
        }
    </script>
@endsection
