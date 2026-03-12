<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ArticleController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public static function middleware(): array {
        return [
            new Middleware('permission:view articles', only:['index']),
            new Middleware('permission:edit articles', only:['edit']),
            new Middleware('permission:update articles', only:['update']),
            new Middleware('permission:create articles', only:['create']),
            new Middleware('permission:delete articles', only:['destroy']),
        ];
    }
    public function index()
    {
        $articles = Article::orderBy('id', 'ASC')->paginate(10);

        return view('articles.index', [
            'articles' =>$articles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|unique:articles|min:5',
            'text' => 'required|min:5',
            'author' => 'required|unique:articles|min:5' 
        ]);

        if($validator->passes()) {
            Article::create([
                'title' =>$request->title,
                'text' => $request->text,
                'author' => $request->author
            ]);
            return redirect()->route('articles.index')->with('success', 'Article Created successfully.');
        }else {
            return redirect()->route('articles.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        return view('articles.edit', [
            'article' => $article
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|unique:articles,title,'.$id.',id',
            'text' => 'required|min:5|unique:articles,text,'.$id.',id' ,
            'author' => 'required|min:5|unique:articles,author,'.$id.',id'
        ]);
        if($validator->passes()) {
            $article->title = $request->title;
            $article->text = $request->text;
            $article->author = $request->author;

            $article->save();

            return redirect()->route('articles.index')->with('success', 'Article Updated successfully.');
        }else{
            return redirect()->route('articles.edit', $id)->withInput()->withErros($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::find($id);

        if($article == null){
            session()->flash('error','Article not found');
            return response()->json([
                'status' => false
            ]);
        }
        $article->delete();

        session()->flash('success', 'Article deleted successfully.');

        return response()->json([
            'status' =>true
        ]);
    }
}
