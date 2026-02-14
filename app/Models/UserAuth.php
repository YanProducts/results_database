<?php

// auth.phpの認証でデフォルトで存在するモデル。ひとまずは残す
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;

// ログイン認証用
class UserAuth extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // 1つのテーブルに対して、参照先が複数のテーブルに渡っていることの宣言。これによって、「auth」で始まるtypeをmodel名称、idをidで、その先の情報を参照してくれる。
    public function authable(){
        return $this->morphTo();
    }

    // roleというカラムを仮作成し、それをrole()というメソッドで呼び出せるようにする
    public function getRoleAttribute(){

            // Enumの配列を取得
            $role_enums=UserRole::cases();

            // 見つかったら早期リターン、見つからなければ後ほどリターン(studlyはcamelの先頭大文字版)
            foreach($role_enums as $role){
                if($role->get_model_name()==$this->authable_type){
                    return $role->value;
                }
            }
            return "unknown";
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'authable_type','authable_id','email','password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


}
