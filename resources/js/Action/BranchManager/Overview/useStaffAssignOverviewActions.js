import React from "react";
import { route } from "ziggy-js";
import useDisappearValidation from "../../Share/useDisappearValidation";
import useClearErrors from "../../Share/useClearErrors";
import { router } from "@inertiajs/react";

export default function useStaffAssignOverviewActions({data,setData,post,errors,errorFromRouter,clearErrors,setValidationHidden}) {

    //エラー自体の削除 //これはrouterでは効かないか？
    useClearErrors({errors,clearErrors})

    //エラーのviewの削除(内部でuseEffect使用) //Errors自体は保護 //これはrouterでは効かないか？
    useDisappearValidation({errors,setValidationHidden})

    // 編集
    const onEditBtnClick=(date)=>{
        alert("実装しよう！")
        return;
        // 編集ボタンへ(dateを変数で渡してget処理)
        router.visit(route("branch_manager.edit_assign",{date:date}));
    }

    // リセット
    const onResetBtnClick=(date)=>{
        if(!confirm("リセットします。よろしいですか？")){
            return;
        }
        // リセットするだけなのでrouterでpost
        router.post(route("branch_manager.reset_assign",{
            "date":date
        }));
    }

    return {onEditBtnClick,onResetBtnClick};
}
