
@extends('layouts.app')
@section('content')
    <style>

        tr.spaceUnder>td {
            padding-bottom: 1em;
        }

    </style>
    <div class="row">

    <div class="register-box">
        <form action="{{route('country.update')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field()}}

            @include('flash::message')

            <center> <div class="container" >

                    <div class="row" style="margin-left:-211px;">

                        <div class="col-md-6">

                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <h3>EDIT COUNTRY DETAILS</h3>
                                </div>
                                <div class="panel-body">

                                    <table>

                                        <tr>

                                            <div class="row">
                                                <input id="id" type="text" name="id" class="form-control"  value="{{ $editcountry->id}}" style="display: none">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-2">Country : </div>
                                                <div class="col-md-6"> <input type="text" class="form-control" name="country_name" id="country_name" value="{{$editcountry->country_name}}">
                                                    @if ($errors->has('country_name'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('country_name') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>


                                            </div>



                                        </tr>

                                    </table><br><br>
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4"> <center> <button type="submit" class="btn btn-primary btn-block btn-flat">Save</button>  </center>
                                        </div>
                                    </div>

                                </div>



                            </div>

                        </div>
                    </div>
                </div>
            </center>
        </form>
    </div>
    </div>
@stop
@section('javascript')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        $( document ).ready(function() {
            $( "#name" ).keypress(function(e) {
                var key = e.keyCode;
                if (key >= 48 && key <= 57) {
                    e.preventDefault();
                }
            });
        });
    </script>

@endsection
