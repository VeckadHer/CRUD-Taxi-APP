
<h1> {{ $modo }} empleado</h1>

@if(count($errors)>0)

    <div class="alert alert-danger" role="alert">

      @foreach($errors->all() as $error)
    <ul>
        <li> {{ $error}} </li>
        @endforeach
</ul>
        </div>

@endif


<div class="form-group">
<label for="Nombre"> Nombre </label>
<input type="text" class="form-control" name="Nombre" value="{{ isset ($empleado->Nombre)?$empleado->Nombre:old('Nombre') }}" id="Nombre">
<br>

</div>


<div class="form-group">
<label for="Apellido_Paterno"> Apellido Paterno </label>
<input type="text" class="form-control" name="Apellido_Paterno" value="{{ isset ($empleado->Apellido_Paterno)?$empleado->Apellido_Paterno:old('Apellido_Paterno') }}" id='Apellido_Paterno'>
</div>

<div class="form-group">
<label for="Apellido_Materno"> Apellido Materno </label>
<input type="text" class="form-control" name="Apellido_Materno" value="{{ isset ($empleado->Apellido_Materno)?$empleado->Apellido_Materno:old('Apellido_Materno')  }}" id="Apellido_Materno">


<div class="form-group">
<label for="Correo"> Correo </label>
<input type="text" class="form-control" name="Correo" value="{{ isset ($empleado->Correo)?$empleado->Correo:old('Correo') }}" id="Correo">

</div>

<div class="form-group">

<label for="Foto">  </label>
@if(isset($empleado->Foto))
<img class="img-thumbnail img-fluid" src="{{ asset('storage').'/'.$empleado->Foto }}" alt="">
@endif
<input type="file" class="form-control" name="Foto" value="" id="Foto">
</div>

<input class="btn btn-success" type="submit" value="{{ $modo }} Datos">

<a class="btn btn-primary" href="{{ url('empleado/') }}"> Regresar </a>


<br>
<