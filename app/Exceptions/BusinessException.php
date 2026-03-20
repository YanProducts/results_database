<?php

namespace App\Exceptions;

use Exception;
use Throwable;

// 自己定義の例外(処理はthrow newで投げるのと、それに応じた捕捉のみなので、内容を書かずともException継承のみでOK)
class BusinessException extends Exception
{
    //メッセージは親要素を継承
    // back_routeは返すroute、custom_error_flagはInertiaではないときにカスタムエラーを表示させるかどうか
    private string $back_route;
    private string $custom_error_flag;
    public function __construct($message,$back_route="top_page",$custom_error_flag=false)
    {

        parent::__construct($message);
        $this->back_route = $back_route;
        $this->custom_error_flag = $custom_error_flag;
    }

    // route名を返す
    public function get_back_route(){
        return $this->back_route;
    }
    // カスタムエラーを表示させるかを返す
    public function get_custom_error_flag(){
        return $this->custom_error_flag;
    }


}
