// errors自体を削除する
import React from "react";
export default function useClearErrors({errors,clearErrors}){
    // エラー表示を3秒で消す
    React.useEffect(()=>{
        console.log("a");
        if(errors && Object.keys(errors).length>0){
            const clearErrorFunc=setTimeout(()=>{clearErrors()},3000);
            return ()=>{clearTimeout(clearErrorFunc)}
        }
    },[errors])
}
