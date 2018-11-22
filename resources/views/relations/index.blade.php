@extends('layouts.layout')

@section('css')
    @include('layouts.datatables_css')
@endsection


@section('content')
    <section class="content-header">
        <h1 class="pull-left">Relations</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('crm.relations.create') !!}">Add New</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        {{--@include('flash::message')--}}

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
			  <table class="table table-hover table-bordered table-striped" id="relations-table">
			    <thead>
			    <tr>
			
			      <th>Name</th>
			      <th>Company</th>
			      <th>Email</th>
			      <th>Number</th>
			      <th></th>
			      <th></th>
			    </tr>
			    </thead>
			    <tfoot>
			    <tr>
			
			      <th>Name</th>
			      <th>Compeny</th>
			      <th>Email</th>
			      <th>Number</th>
			      <th></th>
			      <th></th>
			    </tr>
			    </tfoot>
			  </table>
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

@push('scripts')
<script>
  $(function () {
    $('#relations-table').DataTable({
      processing: true,
      serverSide: true,
      "pageLength": 50,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      ajax: '{!! route('api.relations.data') !!}',
      columns: [
        {data: 'namelink', name: 'name'},
        {data: 'company_name', name: 'company_name'},
        {data: 'email', name: 'email'},
        {data: 'primary_number', name: 'primary_number'},
        {data: 'edit', name: 'edit', orderable: false, searchable: false},
        {data: 'delete', name: 'delete', orderable: false, searchable: false},
      ]
    });
  });
</script>
@endpush
