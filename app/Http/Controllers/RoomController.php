<?php

namespace App\Http\Controllers;

use App\Events\MoveMoved;
use App\Events\Reseted;
use App\Models\Game;
use App\Models\Move;
use App\Models\Room;
use App\Events\RoomCreated;
use App\Events\RoomDeleted;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Pusher\Pusher;

class RoomController extends Controller
{
    public function reset($room_id)
    {
        $room = Room::query()->findOrFail($room_id);

        $game = $room->games()->latest('id')->first();

        $game->update([
            'game_ended' => true
        ]);

        Game::query()->create([
            'room_id' => $room_id,
        ]);

        Reseted::dispatch($room_id);
    }

    public function move(Request $request, $block_id, $room_id)
    {
        $room = Room::where('id', $room_id)->firstOrFail();

        $game = $room->games()->latest('id')->first();

        Move::create([
            'user_id' => $request->user()->id,
            'game_id' => $game->id,
            'position' => (int) $block_id,
        ]);

        MoveMoved::dispatch($room_id, $block_id, $request->get('turn'));
    }

    /**
     * @return Application|RedirectResponse|Factory|View
     */
    public function game($room_id): Application|RedirectResponse|Factory|View
    {
        $room = Room::query()->findOrFail($room_id);

        $connection = config( 'broadcasting.connections.pusher' );

        $pusher = new Pusher(
            $connection['key'],
            $connection['secret'],
            $connection['app_id'],
            [
                'cluster' => $connection['options']['cluster'],
                'useTLS'  => false,
                'host'    => $connection['options']['host'],
                'port'    => '6001',
                'scheme'  => $connection['options']['scheme'],
                'debug'   => true,
            ]
        );

        try {
            $channels = $pusher->get('/channels/presence-room.'.$room_id.'/users');

            $is_on_room = false;

            foreach ($channels->users as $user) {
                if ($user->id === auth()->user()->name) {
                    $is_on_room = true;
                }
            }

            if (count($channels->users) >= 2 && !$is_on_room) {
                return back()->with('status', 'Room is full.');
            }
        } catch (Exception $e) {}

        $game = $room->games()->with('moves')->latest('id')->first();

        return view('tictactoe.game', compact(
            'room_id',
            'room',
            'game',
        ));
    }

    /**
     * @param Request $request
     *
     * @param $room_id
     */
    public function join(Request $request, $room_id)
    {
        $room = Room::query()->findOrFail($room_id);

        if ($room->user_1_id == $request->user()->id || $room->user_2_id == $request->user()->id) {
            return response()->json([
                'success' => true,
            ]);
        } else if ($room->user_2_id) {
            return response()->json([
                'success' => false,
            ], 500);
        }

        $room->update([
            'user_2_id' => $request->user()->id
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $room_count = Room::query()->count();
        $room_name = 'room_' . $room_count;

        if (Room::query()->where('name', 'room_' . Room::query()->count())->first()) {
            $room_name = 'room_' . ($room_count + 1);
        }

        $room = Room::query()->create([
            'name' => $room_name,
            'user_1_id' => $request->user()->id,
            'turn' => 'x',
        ]);

        $game = Game::query()->create([
            'room_id' => $room->id,
            'game_ended' => false
        ]);

        $room->load('user_1');
        $room->load('user_2');

        RoomCreated::dispatch($room);

        return response()->json([
            'room' => $room,
            'game' => $game,
        ]);
    }

    /**
     * @param Request $request
     * @param $room_id
     *
     * @return bool
     */
    public function delete(Request $request, $room_id): bool
    {
        $room = Room::findOrFail($room_id);

        if ($request->user()->id === $room->user_1_id) {
            $room->delete();
        }

        RoomDeleted::dispatch($room_id);

        return true;
    }
}
