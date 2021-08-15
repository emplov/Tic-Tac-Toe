@extends('tictactoe.layouts.app')

@section('data')
    <script>
        window.config = {
            room: @json($room ?? []),
            user: @json(auth()->user() ?? []),
            url_is_main: {{ request()->is('/') ? 'true' : 'false' }},
            game: @json($game ?? []),
            @if(request()->is('room/*'))
            room_id: {{ $room_id }},
            @endif
        }
    </script>
@endsection

@section('content')
    <game></game>
@endsection
