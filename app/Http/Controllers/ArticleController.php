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
    
}
