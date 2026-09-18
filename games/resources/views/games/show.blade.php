@extends('base')

@section('title', '🎮 Show Game')

@section('content')

        <table class="table">
            <tr>
                <th>Game Name:</th>
                <td>{{ $game->game_name }}</td>
            </tr>
            <tr>
                <th>Platform:</th>
                <td>{{ $game->platform }}</td>
            </tr>
            <tr>
                <th>Genre:</th>
                <td>{{ $game->genre }}</td>
            </tr>
            <tr>
                <th>Rating:</th>
                <td>{{ $game->rating }}/10</td>
            </tr>
        </table>

        <a href="/games" class="btn btn-primary">Back to overview</a>

@endsection