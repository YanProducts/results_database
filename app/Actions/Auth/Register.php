<?php

// 登録に関する操作
namespace App\Actions\Auth;
use Illuminate\Support\Str;
use App\Models\UserAuth;
use App\Models\WholeData;
use App\Models\OnetimeWholeData;
use App\Support\Auth\CheckUserInLists;
use App\Support\Auth\WholeDataOnetimeCheck;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Register{

    // 全般統括者以外の登録(try~catchはすでに設定済み)
    public static function register_user_data($role,$request){
            // モデルの取得
            $model= "App\\Models\\" .Str::studly($role)."List";

            // 複数回登場するので格納(パスワードも一緒に格納)
            $user_name=$request->userName;
            $password=$request->passWord;

            // そのユーザーのモデル内でのidを返却(存在しない場合はエラーが返る)
            try{
                $auth_id=CheckUserInLists::get_user_auth_id($model,$user_name)->id;
            }catch(\Throwable $e){
                // 外側のtry~catchにエラーを投げる
                throw new \Error("まだ作成権限のないユーザーです");
            }

            // try~catch内部には既にいる
            self::store_user_data($auth_id,$model,$user_name,$password);
    }

    // 全般統括者の仮登録(既にtry~catch内部にいる)
   public static function register_onetime_whole_data_flow($request){

                // 手続きの間に作成されていたらエラー
                if(WholeData::exists()){
                    throw new \Error("既に登録されています");
                }

                // ワンタイムトークンの設定
                $onetime_token=bin2hex(random_bytes(32));

                // ワンタイムトークンの保存期間は１時間
                $now=new \Datetime();
                $expired_at=$now->add(new \DateInterval("PT1H"));

                // sql登録
                self::store_onetime_whole_data($request,$onetime_token,$expired_at);

                // URLの作成(tokenを付与)
                $url=config("app.url")."/whole_data/create_user?token=".$onetime_token;
                Log::info($url);

                // メールの送信
                mb_send_mail($request->email,"仮登録完了","仮登録頂きありがとうございます。\nまだ本登録は完了しておりません。\n本登録を行うには、下記のURLをクリックしてください\n".$url);
    }

    // whole_data以外のユーザーのsqlへの登録
    public static function store_user_data($auth_id,$model,$user_name,$password){
            DB::transaction(function()use($auth_id,$model,$user_name,$password){
                $data=new UserAuth();
                $data->auth_id=$auth_id;
                $data->auth_type=$model;
                $data->user_name=$user_name;
                $data->password=Hash::make($password);
                $data->save();
            });
    }

    // 仮登録(try~catch内部にいる)
    public static function store_onetime_whole_data($request,$onetime_token,$expired_at){
        DB::transaction(function()use($request,$onetime_token,$expired_at){
                // 仮登録データがあったら削除
                 DB::table("onetime_whole_datas")->delete();
                // onetimeテーブルに情報格納
                $onetime_data=new OnetimeWholeData();
                $onetime_data->user_name=$request->userName;
                // パスワードはハッシュ化
                $onetime_data->password=Hash::make($request->passWord);
                $onetime_data->email=$request->email;
                // 上記で設定のトークン
                $onetime_data->onetime_token=$onetime_token;
                // 保存期間
                $onetime_data->expired_at=$expired_at;
                $onetime_data->save();
            });
    }


    // 全般統括者の本登録
    public static function register_whole_data($request){
        // 本登録データが作成されていたらアウト
        if(WholeData::exists()){
            throw new \Error("既に登録されています");
        }

        //tokenの成否と期限のチェック。違っていたエラーページへ(Getパラメータから捕捉しerrorページに投げるため、バリデーションでは行わない)
        // 存在していな場合も同様にエラーへ
        // 内部でエラーを投げる
        WholeDataOnetimeCheck::token_check($request->token);

        // 仮データを取得（1行しか存在しない）
        $onetime_data=OnetimeWholeData::query()->first();

        DB::transaction(function()use($onetime_data){
            // 本登録データのインスタンス作成
            $whole_data=new WholeData();
            $whole_data->user_name=$onetime_data->user_name;
            // 既にハッシュ化されている
            $whole_data->password=$onetime_data->password;
            $whole_data->email=$onetime_data->email;
            $whole_data->remember_token=bin2hex(random_bytes(32));
            $whole_data->save();

            // 仮登録データ削除
            DB::table("onetime_whole_datas")->delete();
        });
    }
}
