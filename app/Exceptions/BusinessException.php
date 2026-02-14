<?php

namespace App\Exceptions;

use Exception;

// 自己定義の例外(処理はthrow newで投げるのと、それに応じた捕捉のみなので、内容を書かずともException継承のみでOK)
class BusinessException extends Exception
{
    //
}
