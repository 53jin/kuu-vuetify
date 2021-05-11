@extends('layouts.app')

@section('content')
    @php
        $data = [
            'email' => old('email'),
        ];
    @endphp
    <kuu-auth-login :data='{!! json_encode($data) !!}' :errors='{!! (!empty($errors->toArray())) ? $errors : 'false' !!}'/>
@endsection
