@extends('tictactoe.layouts.app')

@section('data')
    <script>
        window.config = {
            rooms: @json($rooms ?? []),
            user: @json(auth()->user() ?? []),
            url_is_main: {{ request()->is('/') ? 'true' : 'false' }},
            @if(request()->is('room/*'))
            room_id: {{ $room_id }},
            @endif
        }
    </script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Tic Tac Toe</h5>
            <p class="card-text">
                To start game create room and join by clicking to the room name.
            </p>
        </div>
    </div>
@endsection
