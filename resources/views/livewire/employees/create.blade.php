@extends('layouts.app')



@section('contenido')
    @livewire('employees.manage-employees', ['modoCreacion' => true])
@endsection
