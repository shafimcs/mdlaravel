
@extends('layouts.app')
@section('content')
    <br><br>
    <div class="row">
        <div class="col-xs-12">
            @include('flash::message')

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Countries</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th> Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($country as $countries)
                            <tr>
                                <td>{{ $countries->country_name }}</td>



                                <td>
                                    <a href="{{route('country.edit',$countries->id)}}" class="btn btn-warning">Edit</a><br><br>
                                    {!! Form::open(['method' => 'DELETE','onsubmit' => "return confirm('".trans("Are you sure want to delete")."');",
                                                                           'route' => ['country.destroy', $countries->id ]]) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}

                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <link rel="stylesheet" href="{{ asset('dashboard/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <script src="{{ asset('dashboard/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}} "></script>
@endsection