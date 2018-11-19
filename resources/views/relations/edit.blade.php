@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Relation
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($relation, ['route' => ['crm.relations.update', $relation->id], 'method' => 'patch']) !!}

                        @include('crm.relations.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection