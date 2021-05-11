@extends('layouts.frame')

@section('body')
@endsection

@push('styles')
<link href="{{ mix('/css/style.css') }}" rel="stylesheet"/>
<link href="{{ mix('/css/app.css') }}" rel="stylesheet"/>
@endpush

@push('scripts')
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
@endpush
