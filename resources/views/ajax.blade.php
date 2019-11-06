@extends('layouts.fileorg_app')

@section('css')
  <style>
    .loading {
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: rgba(0,0,0,.5);
        text-align: center;
        z-index: 2000;
        display: none;
    }

    .loading-spin{
        width: 100%;
        height: auto;
        margin-top: -50px;
        margin-left: -50px;
        
        position: fixed;
        top: 50%;
        left: 0;
        
        border-width: 30px;
        border-radius: 50%;
    }

    .form-group.required label:after {
        content: " *";
        color: red;
        font-weight: bold;
    }

    .table td {
        border: #a5a5a5 solid 1px;
    }

    .table th {
        border: #a5a5a5 solid 1px !important;
    }

  </style>
@endsection

@section('content')
<div class="card border-secondary mx-auto" style="width: 1150px;">
    <div class="card-header font-weight-bold">
        Search
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="division">Division:</label>
                    <select class="form-control col-sm-10" id="division" name="division">
                        <option value="0">All</option>
                    </select>
                </div> 
            </div>
            <div class="col-sm-6">
                <div class="row mb-3">
                    <!-- date needs to be modified -->
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="fromDate">From: <small>mm/dd/yyyy</small></label>
                                <input class="form-control mx-auto" type="date" name="fromDate" id="fromDate" autofocus>
                                <span id="error-fromDate" class="invalid-feedback"></span>
                            </div>
                            <div class="col-sm-6">
                                <label for="toDate">To: <small>mm/dd/yyyy</small></label>
                                <input class="form-control mx-auto" type="date" name="toDate" id="toDate" autofocus>
                                <span id="error-toDate" class="invalid-feedback"></span>  
                            </div>
                        </div>                                                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <button id="refreshFile" class="btn btn-outline-success offset-1" onclick="ajaxLoad('{{route('index')}}?search=')">
                                <i class="fas fa-redo"></i>
                            </button>
                            <input class="form-control col-sm-9" id="search" name="search" type="text" placeholder="Search Here" 
                            value="{{ request()->session()->get('search') }}" onkeydown="javascript:if(event.keyCode == 13){ajaxLoad('{{route('index')}}?search='+this.value+'&fromdate='+$('#fromDate').val()+'&todate='+$('#toDate').val())}"/>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-outline-primary" onclick="ajaxLoad('{{route('index')}}?search='+$('#search').val()+'&fromdate='+$('#fromDate').val()+'&todate='+$('#toDate').val())">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>                                 
            </div>            
        </div>
        <div id="content"> <!-- THIS GETS PASSED IN THE 'js/ajaxcrud.js' (ajaxLoad function) -->
        @include('index')
        </div>
    </div>
</div>
<div class="loading">
    <div class='loading-spin'>
        <i class="fas fa-spinner fa-spin fa-5x"></i>
        <br>
        <span id='loading'>Loading</span>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/ajaxcrud.js') }}"></script>
<script>
    $(document).ready(function(){
        Division = {{$division}};
        ajaxDivisionGenerateForSearch('division', Division);
        ajaxDivisionGenerate('division');
        //ajaxLoad('/');
        @if (count($errors) > 0)
            @if (session('isAdd'))
                $('#addFileModal').modal('show');
            @endif
        @endif

        document.getElementById("fromDate").valueAsDate = new Date();
        document.getElementById("toDate").valueAsDate = new Date();
    });

    $('#fromDate').change(function(){
        var from_current_date = $('#fromDate').val();
        var to_current_date = $('#toDate').val();

        $('#toDate').attr("min", from_current_date);

        if(to_current_date < from_current_date ){
            $('#toDate').val(from_current_date);            
        }        
    });

    $('#toDate').change(function(){
        var from_current_date = $('#fromDate').val();
        var to_current_date = $('#toDate').val();

        if(to_current_date < from_current_date){
            $('#fromDate').val(to_current_date);
        }        
    });

    $('#refreshFile').click(function(){
        $('#search').val('');
    });

    $('#division').change(function() { 
        if($('#division').val() == 0){
            ajaxLoad('{{route('index')}}?division=0');
        }else{
            var div_id = $('#division').val();
            ajaxLoad('{{route('index')}}?division='+div_id);
        }
    });
</script>
@endsection