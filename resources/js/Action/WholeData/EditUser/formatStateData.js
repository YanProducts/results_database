// ユーザーの情報変更の際に元データと変化しているものをチェックするために、stateの値を変更する
export default function formatStateData({isInWorkChange,staffName,selectedPlaceId,isReset,userInformation}){
        // 現在のstateをObjectにまとめる
        const dataInState={
            // 退職or休職していないか
            "isInWorkChange":isInWorkChange
        }

        // スタッフ名
        if(userInformation.role=="field_staff"){
            dataInState.staffName=staffName
        }

        // 営業所名
        if(["field_staff","branch_manager"].includes(userInformation.role)){
            dataInState.placeId=selectedPlaceId
        }

        // registerdは元が未登録の場合には何もしない
        if(userInformation.registered){
            dataInState.registered=isReset
        }
}

