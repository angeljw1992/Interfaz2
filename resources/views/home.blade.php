@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <center>Panel de control</center>
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                 <!--   <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab1">¿Como dar de Alta un empleado?</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab2">¿Como dar de Alta un local?</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab3">¿Como eliminar un empleado?</a>
                        </li>
                    </ul> -->

                    <!-- <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab1">
                            <!-- Contenido de la pestaña 1 
                            <h3>Contenido de la pestaña 1</h3>
                            <p>Para dar de alta un Empleado, primero se debe tener el código de empleado.</p>
							<p>Luego de esto, se va a la parte de menú, Alta empleado y se le da click al botón Nueva Alta Empleado</p>
							<p>Y se rellenan los campos. Los empleados no pueden tener ni acentos ni la letra "ñ" ya que indicará error.</p>
                        </div>
                        <div class="tab-pane fade" id="tab2">
                            <!-- Contenido de la pestaña 2 
                            <h3>Contenido de la pestaña 2</h3>
                            <p>Agrega aquí la información que deseas mostrar en la pestaña 2.</p>
                        </div>
                        <div class="tab-pane fade" id="tab3">
                            <!-- Contenido de la pestaña 3 
                            <h3>Contenido de la pestaña 3</h3>
                            <p>Agrega aquí la información que deseas mostrar en la pestaña 3.</p>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection
