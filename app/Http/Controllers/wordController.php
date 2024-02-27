<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Word;
use Illuminate\Http\JsonResponse;

class wordController extends Controller
{



    public function word_test(Request $req): JsonResponse
    {
        $arr = (new Word)->getAll();

        return response()->json(["arr" => $arr]);
    }
    public function addWord(Request $req): JsonResponse
    {

        try{
            $newWord = new Word();
            $newWord->user_id = $req->user()->id;
            $newWord->eng = $req->english;
            $newWord->ar = $req->arabic;
            $newWord->description = $req->description;
            $newWord->category = $req->category;
            $newWord->priority = 0;
            $newWord->save();
            return response()->json(["messege" => "success"], 200);
        }catch(err){
            return response()->json(["error" => "network error"], 501);
        }
    }

    public function deleteWord(Request $req) : JsonResponse
    {
        $word = Word::where('user_id', $req->user()->id)
                         ->where('id', $req->wordId)
                         ->first();
        $word->delete();

        return response()->json(["message" => "success"], 200);
    }

    public function getAllwords(Request $req): JsonResponse
    {
        $words = Word::where('user_id', $req->user()->id)->get();

        return response()->json(["words" => $words], 200);
    }

    public function GenerateQuiz(Request $req):JsonResponse{
        try{
            $sortedWords = Word::orderBy('priority')->get();

            $sz = $req->sz;
            if(sizeof($sortedWords) <= $sz){
                for($i = 0; $i < sizeof($sortedWords); $i++){
                    Word::where('id', $sortedWords[$i]->id)->increment('priority');
                }

                return response()->json(["words" => $sortedWords], 200);
            }else{
                $sortedWords = Word::orderBy('priority', 'asc')->take($sz)->get();
                for($i = 0; $i < $sz; $i++){
                    Word::where('id', $sortedWords[$i]->id)->increment('priority');
                }
                return response()->json(["words" => $sortedWords], 200);
            }

        }catch(err){
            return response()->json(["message" => "network error"], 505);
        }
    }


}
