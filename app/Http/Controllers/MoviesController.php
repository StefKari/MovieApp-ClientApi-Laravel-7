<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Facade\Ignition\Exceptions\ViewException;

class MoviesController extends Controller
{
    /**
     * Display a listing of the data from TMDB Api.
     *
     * @param  void
     * @return object
     */
    public function index()
    {
        $popularMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/popular')
            ->json()['results'];

        $nowPlayingMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/now_playing')
            ->json()['results'];

        $genresArray = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json()['genres'];

        $genres = collect($genresArray)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });

        return (view('index', [
            'popularMovies' => $popularMovies,
            'nowPlayingMovies' => $nowPlayingMovies,
            'genres' => $genres,
        ]));

    }

    /**
     * Display the specified data.
     *
     * @param  int  $id
     * @return object
     */
    public function show($id)
    {
      $movie = Http::withToken(config('services.tmdb.token'))
          ->get('https://api.themoviedb.org/3/movie/'.$id.'?append_to_response=credits,videos,images')
          ->json();


      return view('show', [
          'movie' => $movie,
      ]);
    }


}
