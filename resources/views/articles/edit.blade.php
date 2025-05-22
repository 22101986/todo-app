@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-2xl font-bold mb-6">Modifier l'article</h1>
    
    <form method="POST" action="{{ route('articles.update', $article) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
            <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   required>
        </div>
        
        <div class="mb-4">
            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Contenu</label>
            <textarea name="content" id="content" rows="6"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      required>{{ old('content', $article->content) }}</textarea>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Mettre à jour
            </button>
            <a href="{{ route('articles.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection