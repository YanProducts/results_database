import React from "react";

// バリデーションエラーを消す
// useEffectを使用しているので、コンポーネントの最初で使用すること
export default function disappearValidation({errors,setValidationHidden}){
    React.useEffect(()=>{
        if(!errors || Object.keys(errors).length==0){
            return;
        }

        setValidationHidden(false); //エラーが発生したとき、まずは表示させる
        const timer=setTimeout(()=>{setValidationHidden(true)},3000) //3秒後にエラー削除

        return ()=>clearTimeout(timer);

    },[errors])
}
