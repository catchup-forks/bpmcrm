<!-- Companyid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('companyid', 'Companyid:') !!}
    {!! Form::number('companyid', null, ['class' => 'form-control']) !!}
</div>

<!-- Relationname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('relationname', 'Relationname:') !!}
    {!! Form::text('relationname', null, ['class' => 'form-control']) !!}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6">
    {!! Form::label('slug', 'Slug:') !!}
    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('crm.relations.index') !!}" class="btn btn-default">Cancel</a>
</div>
