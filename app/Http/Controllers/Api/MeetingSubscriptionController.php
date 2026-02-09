<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingSubscriptionController extends Controller
{
    /**
     * Suscribir al usuario autenticado a una meeting.
     */
    public function store(Request $request, Meeting $meeting): MeetingResource
    {
        $user = $request->user();

        $meeting->users()->syncWithoutDetaching([$user->id]);

        return new MeetingResource($meeting->load(['user', 'users']));
    }

    /**
     * Desuscribir al usuario autenticado de una meeting.
     */
    public function destroy(Request $request, Meeting $meeting): MeetingResource
    {
        $user = $request->user();

        $meeting->users()->detach($user->id);

        return new MeetingResource($meeting->load(['user', 'users']));
    }
}
