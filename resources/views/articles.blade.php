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
                <div class="flex justify-between items-start">
                    <h2 class="text-xl font-semibold mb-2">{{ $article->title }}</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('articles.edit', $article) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Modifier
                        </a>
                        <form action="{{ route('articles.destroy', $article) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                <p class="text-gray-600 mb-4 whitespace-pre-line">{{ $article->content }}</p>
                <div class="text-sm text-gray-500">
                    Créé le {{ $article->created_at->format('d/m/Y H:i') }}
                    @if($article->created_at != $article->updated_at)
                        - Modifié le {{ $article->updated_at->format('d/m/Y H:i') }}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection