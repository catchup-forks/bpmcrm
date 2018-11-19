@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Ticket
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($ticket, ['route' => ['tickets.tickets.update', $ticket->id], 'method' => 'patch']) !!}

                        @include('tickets.tickets.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection