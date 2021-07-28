
<!-------------------------------------------------------------------------------------------------------------------------->

<!-------------------------------------------------------------------------------------------------------------------------->

@extends('layouts.app')

<style>

    tr.spaceUnder>td {
        padding-bottom: 1em;
    }

</style>

@section('content')
    <br><br>
    <div class="row">

        <div class="register-box">
            {{ csrf_field()}}

            @include('flash::message')

            <center> <div class="container" >

                    <div class="row" style="margin-left:-211px;">

                        <div class="col-md-6">

                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <h3>ENTER COUNTRY DETAILS</h3>
                                </div>
                                <div class="panel-body">
                                    <form action="{{route('country.store')}}" method="post" enctype="multipart/form-data">

                                        <table>

                                            <tr>

                                                <div class="row"></div>
                                                <div class="row">

                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-2">Country : </div>
                                                    <div class="col-md-6"> <input type="text" class="form-control" name="country_name" id="country_name" required>
                                                        @if ($errors->has('country_name'))
                                                            <span class="help-block">
                                                    <strong>{{ $errors->first('country_name') }}</strong>
                                                    </span>
                                                        @endif
                                                    </div>


                                                </div>



                                            </tr>

                                        </table>
                                    </form><br><br>
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