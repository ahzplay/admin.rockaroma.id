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
                    <h1 class="m-0 text-dark">Band Register</h1>
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
                                        <th> Band Name </th>
                                        <th> Location </th>
                                        <th> Register Date </th>
                                        <th> Action  </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($raw as $key=>$val)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$val->band_name}}</td>
                                            <td>{{$val->city->name}}</td>
                                            <td>{{$val->created_at->format('d M Y')}}</td>
                                            <td><a href="#" onclick="edit('{{$val->id}}','{{$val->band_name}}','{{$val->city->name}}','{{$val->phone_number}}','{{$val->filesUploadeds[0]->path}}','{{$val->filesUploadeds[1]->file_name}}','{{$val->filesUploadeds[1]->path}}')" style="color: blue;">Edit</a> {{--<span style="padding-right: 15px;"></span> <a href="#" onclick="destroy('{{$val->id}}','{{$val->public_id}}')" style="color: red;">Delete</a>--}}</td>
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

    <div id="add-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Band Profile</h4>
                    <br>
                    <div class="row">
                        <div class="col-md-6" style="padding-left: 25px;">
                            <h4 id="band-name"></h4>
                            <br>
                            <p>
                                <i class="fas fa-map-marker-alt"></i> &nbsp;&nbsp;&nbsp; <label id="location"></label> <br>
                                <i class="fas fa-phone"></i> &nbsp;&nbsp; <label>+62</label> <label id="phone-number"></label>
                            </p>
                            <br>
                            <p>
                                <label>File Demo :</label> <br>
                                <a href="#" id="file-demo" target="_blank"></a>
                                {{--<button type="button" class="btn btn-outline-success btn-sm" onclick="copyToClipboard()">Copy to clipboard</button>--}}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div style="border-width: 1px; border-color: #F4F6F9; background-color: #F4F6F9; width: 100%; height: 306px;">
                                <img id="image-preview" src="" width="100%" style="max-height: 306px;  min-height: 306px; ">
                            </div>
                        </div>
                    </div>
                    {{--<div class="row" style="padding-top: 25px;">
                        <div class="col-md-12">
                            <div class="float-right d-none d-sm-inline">
                                <button type="button" onclick="update()" class="btn btn-warning"> Update </button>
                            </div>
                        </div>
                    </div>--}}
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

        function edit(id, bandName, location, phoneNumber, image, fileName, path) {
            $('#band-name').html(bandName);
            $('#location').html(location);
            $('#phone-number').html(phoneNumber);
            $('#add-modal').modal('show');
            $('#file-demo').html(fileName);
            $('#file-demo').attr('href',path)
            $('#image-preview').attr('src',image)
        }

        /*function copyToClipboard() {
            /!* Get the text field *!/
            var copyText = document.getElementById("file-demo");

            /!* Select the text field *!/
            copyText.select();
            copyText.setSelectionRange(0, 99999); /!* For mobile devices *!/

            /!* Copy the text inside the text field *!/
            document.execCommand("copy");

            /!* Alert the copied text *!/
            alert("Copied the text: " + copyText.value);
        }*/
    </script>
@endsection
