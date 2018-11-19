<!-- Ticketkey Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ticketkey', 'Ticketkey:') !!}
    {!! Form::text('ticketkey', null, ['class' => 'form-control']) !!}
</div>

<!-- Customerid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customerid', 'Customerid:') !!}
    {!! Form::number('customerid', null, ['class' => 'form-control']) !!}
</div>

<!-- Projectid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('projectid', 'Projectid:') !!}
    {!! Form::number('projectid', null, ['class' => 'form-control']) !!}
</div>

<!-- Departmentid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('departmentid', 'Departmentid:') !!}
    {!! Form::number('departmentid', null, ['class' => 'form-control']) !!}
</div>

<!-- Tickettypeid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tickettypeid', 'Tickettypeid:') !!}
    {!! Form::number('tickettypeid', null, ['class' => 'form-control']) !!}
</div>

<!-- Ticketstatusid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ticketstatusid', 'Ticketstatusid:') !!}
    {!! Form::number('ticketstatusid', null, ['class' => 'form-control']) !!}
</div>

<!-- Priorityid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('priorityid', 'Priorityid:') !!}
    {!! Form::number('priorityid', null, ['class' => 'form-control']) !!}
</div>

<!-- Subject Field -->
<div class="form-group col-sm-6">
    {!! Form::label('subject', 'Subject:') !!}
    {!! Form::text('subject', null, ['class' => 'form-control']) !!}
</div>

<!-- Content Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('content', 'Content:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('tickets.tickets.index') !!}" class="btn btn-default">Cancel</a>
</div>
