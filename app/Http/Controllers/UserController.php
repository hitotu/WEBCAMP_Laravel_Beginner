<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginPostRequest;
use Illuminate\Support\Facades\User;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRegisterPostRequest;
use App\Models\Task as TaskModel;
use Illuminate\Support\Facades\DB;
use App\Models\CompletedTask as CompletedTaskModel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.register');
    }
    
     /**
     * 新規登録
     */
    public function register(UserRegisterPostRequest $request)
    {
        // validate済みのデータの取得
        $datum = $request->validated();
        var_dump($datum); exit;
        //
        //$user = Auth::user();
        //$id = Auth::id();
        //var_dump($datum, $user, $id); exit;

        // user_id の追加
        $datum['user_id'] = User::id();

        // テーブルへのINSERT
        try {
            $r = UserModel::create($datum);
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;
        }
        
        // 認証
        if (User::attempt($datum) === false) {
            return back()
                   ->withInput() // 入力値の保持
                   ->withErrors(['user' => 'emailかパスワードに誤りがあります。',]) // エラーメッセージの出力
                   ;
        }
        
        
        
        //ハッシュ化
        $datum['password'] = Hash::make($datum['password']);
        
         // ユーザー登録成功
        $request->session()->flash('front.user_register_success', true);

        //
        $request->session()->regenerate();
        return redirect()->intended('/task/list');
    }
}