<x-mail::message>
# {{ $game->name }} is now available!

A game you added to your release alerts has been released.

**{{ $game->name }}**

Release date: {{ $game->release_date }}

<x-mail::button :url="config('app.frontend_url') . '/games/' . $game->rawg_id">
View game
</x-mail::button>

Thanks,<br>
NextPlay
</x-mail::message>
