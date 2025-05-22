<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->get();
        return view('articles', compact('articles'));
    }
    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'title'=> 'required|max:100',
            'content'=> 'required',
        ]);
        Article::create($validate);

        return redirect()->route('articles.index')->with('success','Article créé avec succès!');
    }

    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }
    
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $article->update($validated);

        return redirect()->route('articles.index')->with('success', 'Article mis à jour avec succès!');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index')->with('success','Article supprimé avec succès');
    }

}
