@extends('main')

@section('custom-style')
    <link href="{{url('assets/global/css/plugins.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
    <link type="text/css" rel="stylesheet" href="{{ url('vendors/bootstrap-datepicker/css/datepicker.css') }}">
@stop

@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.js') }}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{url('assets/admin/pages/scripts/form-wizard.js')}}"></script>
    <script src="{{url('js/jquery.inputmask.bundle.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $(":input").inputmask();
        });
    </script>
    <script>
        jQuery(document).ready(function() {
            FormWizard.init();
        });
    </script>
@stop

@section('content')
    <h3 class="page-title">
        Thông tin khai sinh<small> thêm mới</small>
    </h3>
    <div class="row">
        {!! Form::open(['url'=>'khaisinh','method'=>'post' , 'files'=>true, 'id' => 'create_hscb','class'=>'form-horizontal','enctype'=>'multipart/form-data']) !!}
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <div class="col-md-12">
            <div class="portlet box blue" id="form_wizard_1">
                <div class="portlet-body form" id="form_wizard">
                    <div class="form-body">
                        <ul class="nav nav-pills nav-justified steps">
                            <li><a href="#tab1" data-toggle="tab" class="step">
                                        <span class="badge badge-default">
                                        1 </span>
                                    <p class="description">Thông tin khai sinh</p>
                                </a>
                            </li>
                            <li><a href="#tab2" data-toggle="tab" class="step">
                                    <span class="badge badge-default">
                                        2 </span>
                                    <p class="description">Thông tin người khai</p></a>
                            </li>
                            <li><a href="#tab3" data-toggle="tab" class="step">
                                     <span class="badge badge-default">
                                        3 </span>
                                    <p class="description">Thông tin người khai sinh</p></a>
                            </li>
                            <li><a href="#tab4" data-toggle="tab" class="step">
                                     <span class="badge badge-default">
                                        4 </span>
                                    <p class="description">Thông tin mẹ</p></a>
                            </li>
                            <li><a href="#tab5" data-toggle="tab" class="step">
                                     <span class="badge badge-default">
                                        5 </span>
                                    <p class="description">Thông tin cha</p></a>
                            </li>
                        </ul>
                        <div id="bar" class="progress progress-striped" role="progressbar">
                            <div class="progress-bar progress-bar-blue">
                            </div>
                        </div>

                        <div class="tab-content">
                            @include('manage.khaisinh.include.hoso')
                            @include('manage.khaisinh.include.ttnguoikhai')
                            @include('manage.khaisinh.include.khaisinh')
                            @include('manage.khaisinh.include.ttme')
                            @include('manage.khaisinh.include.ttcha')
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-offset-1 col-md-1" style="text-align: left">
                                <button type="button" name="previous" value="Previous" class="btn default button-previous">
                                    <i class="fa fa-arrow-circle-o-left mrx"></i>&nbsp;Trước
                                </button>
                            </div>
                            <div class="col-md-offset-8 col-md-1" style="text-align: right">
                                <button id="btnnext" type="button" name="next" value="Next" class="btn blue button-next">
                                    Tiếp&nbsp;<i class="fa fa-arrow-circle-o-right mlx"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="text-align: center">
                <a href="{{url('khaisinh')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i>&nbsp;Nhập lại</button>
                <button type="submit" class="btn green" onclick="validateForm1()"><i class="fa fa-check"></i>Hoàn thành</button>
            </div>
            <div id="chameshow"></div>
        </div>
        {!! Form::close() !!}
    </div>
    <script>
        function validateForm(){

            var str = '',strb1='',strb2='',strb3='',strb4='',strb5='';
            var ok = true;

            if($('#ngaydangky').val()==''){
                strb1 += ' - Ngày đăng ký\n';
                ok = false;
            }
            if($('#sokhaisinh').val()==''){
                strb1 += ' - Số khai sinh\n';
                ok = false;
            }
            if($('#quyenkhaisinh').val()==''){
                strb1 += '  - Quyển khai sinh \n';
                ok = false;
            }
            if($('#nguoiky').val()==''){
                strb1 += '  - Người ký \n';
                ok = false;
            }
            if($('#chucvunguoiky').val()==''){
                strb1 += '  - Chức vụ người ký \n';
                ok = false;
            }
            if($('#nguoithuchien').val()==''){
                strb1 += '  - Người thực hiện \n';
                ok = false;
            }


            if($('#hotennk').val()==''){
                strb2 += ' - Họ tên \n';
                ok = false;
            }
            if($('#sogiaytonk').val()==''){
                strb2 += '  - Số giấy tờ \n';
                ok = false;
            }
            if($('#noicapgtnk').val()==''){
                strb2 += '  - Nơi cấp \n';
                ok = false;
            }
            if($('#ngaycapgtnk').val()==''){
                strb2 += '  - Ngày cấp \n';
                ok = false;
            }
            if($('#diachink').val()==''){
                strb2 += '  - Địa chỉ \n';
                ok = false;
            }
            //b3

            if($('#hotenks').val()==''){
                strb3 += '  - Họ tên \n';
                ok = false;
            }
            if($('#sochungsinh').val()==''){
                strb3 += '  - Số chứng sinh \n';
                ok = false;
            }
            if($('#ngaysinh').val()==''){
                strb3 += '  - Ngày sinh \n';
                ok = false;
            }
            if($('#ngaysinhksbangchu').val()==''){
                strb3 += '  - Ngày sinh bằng chữ \n';
                ok = false;
            }
            if($('#noisinh').val()==''){
                strb3 += '  - Nơi sinh \n';
                ok = false;
            }
            if($('#quequanks').val()==''){
                strb3 += '  - Quê quán \n';
                ok = false;
            }
            //B4
            if($('#hotenme').val()==''){
                strb4 += ' - Họ tên \n';
                ok = false;
            }
            if($('#sogiaytome').val()==''){
                strb4 += '  - Số giấy tờ \n';
                ok = false;
            }
            if($('#ngaysinhme').val()==''){
                strb4+= '  - Ngày sinh \n';
                ok = false;
            }
            //B5
            if($('#hotencha').val()==''){
                strb5 += ' - Họ tên \n';
                ok = false;
            }
            if($('#sogiaytocha').val()==''){
                strb5 += '  - Số giấy tờ \n';
                ok = false;
            }
            if($('#ngaysinhcha').val()==''){
                strb5+= '  - Ngày sinh \n';
                ok = false;
            }


            //Kết quả
            if ( ok == false){
                if(strb1!='')
                    str+='Bước 1: Thông tin khai sinh \n '+strb1 ;
                if(strb2!='')
                    str+='Bước 2: Thông tin người khai \n '+strb2 ;
                if(strb3!='')
                    str+='Bước 3: Thông tin người khai sinh \n '+strb3 ;
                if(strb4!='')
                    str+='Bước 4: Thông tin về mẹ \n '+strb4 ;
                if(strb5!='')
                    str+='Bước 5: Thông tin về cha \n '+strb5 ;

                alert('Thông tin không được để trống \n' + str );
                $("form").submit(function (e) {
                    e.preventDefault();
                });
            }
            else{
                $("form").unbind('submit').submit();
            }

        }
    </script>
@stop