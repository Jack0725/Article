<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticlesController extends Controller
{
    public function __construct(){
        $this->middleware("auth")->except("index");
    }

    public function index(){
        $articles=Article::with("user")->orderBy("id","desc")->paginate(3);
        return view("articles.index",["articles"=>$articles]);
    }

    public function show($id){
        $article=Article::find($id);
        return view("articles.show",["article"=>$article]);

    }

    public function create(){
        return view("articles.create");
    }
    public function store(Request $request){
        $content=$request->validate([
            "title"=>"required", //需填寫
            "content"=>"required|min:10" //要填入超過10個字
        ]);
        auth()->user()->articles()->create($content);
        return redirect()->route("root")->with("notice","文章新增成功!");//轉到首頁
    }

    public function edit($id){
        $article=auth()->user()->articles->find($id);

        return view("articles.edit",["article"=>$article]);

    }

    public function update(Request $request,$id){
        $article=auth()->user()->articles->find($id);

        $content=$request->validate([
            "title"=>"required", //需填寫
            "content"=>"required|min:10" //要填入超過10個字
        ]);

        $article->update($content);
        return redirect()->route("root")->with("notice","文章更新成功!");//轉到首頁
    }

    public function destroy($id){
        $article=auth()->user()->articles->find($id);
        $article->delete();
        return redirect()->route("root")->with("notice","文章已刪除");

    }


    //
}
