<?php
namespace App\Utils;

use Composer\Pcre\Preg;

// 正規表現
class Regex{

    // パスワード
    // 大文字/小文字/数字は必ず必要(先読みアサーションで指定)
    // 全て設定されていたらtrueで返す
    public static function check_password_rule($value){
        return preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$/",$value);
    }

    // パスワード禁止文字(^を[]内部につけることで禁止にしている)
    // 1つでも含んでいたらtrue(エラーあり)で返す
    public static function check_password_forbitten_rule($value){
        return preg_match("/[^(0-9A-Za-z!@#$%^&*()\-_=+\[\]{}:;,.?\/)]/",$value);
    }

    // 半角英数字のみ(全て使用しないでも良い)
    // ユーザー名など
    public static function check_half_wides_only($value){
        return preg_match("/^[A-Za-z0-9]+$/",$value);
    }

    // 日本語の文字列で完結しているか(全角数字含む)
    public static function check_jpn_words_only($value){
        return preg_match("/^[\p{Hiragana}\p{Katakana}\p{Han}０-９]+$/u",$value);
    }

    // 全角スペースを含んでいるか
    public static function check_zenkaku_spaces($value){
        return preg_match("/[\x{3000}]/u",$value);
    }




}
