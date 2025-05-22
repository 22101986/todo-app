@extends('layouts.app')

@section('content')
<a class="nav-link btn btn-dark" href="{{ route('tasks.index') }}">To do list</a>
<br>
<a class="nav-link btn btn-dark" href="{{ route('calendar.index') }}">Calendrier</a>
<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Liste des articles</h1>
        <a href="{{ route('articles.create') }}" 
           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Créer un article
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        @foreach($articles as $article)
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-2">{{ $article->title }}</h2>
                <p class="text-gray-600 mb-4">{{ $article->content }}</p>
                <div class="text-sm text-gray-500">
                    Créé le {{ $article->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection