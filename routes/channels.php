<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('room-created', function ($user) {
    return $user;
});

Broadcast::channel('room.{room_id}', function ($user, $room_id) {
    return $user;
});

Broadcast::channel('room-deleted', function ($user) {
    return $user;
});

Broadcast::channel('room-deleted.{room_id}', function ($user, $room_id) {
    return $user;
});

Broadcast::channel('moved.{room_id}', function ($user, $room_id) {
    return $user;
});


Broadcast::channel('reseted.{room_id}', function ($user, $room_id) {
    return $user;
});
