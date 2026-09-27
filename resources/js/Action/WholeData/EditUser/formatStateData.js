// ユーザーの情報変更の際に元データと変化しているものをチェックするために、stateの値を変更する
export default function formatStateData({isInWorkChange,staffName,selectedPlaceId,isReset,userInformation}){

    // 現在のstateをObjectにまとめる
        const dataInState={};

        // スタッフ名
        if(userInformation.role=="field_staff"){
            dataInState.staffName=staffName;
        }

        // 営業所名
        if(["field_staff","branch_manager"].includes(userInformation.role)){
            dataInState.placeId=selectedPlaceId;
        }

        // registerdは元が未登録の場合には何もしない
        // リセットする場合はtrue、resetしない場合は何も設定しない
        if(userInformation.registered && isReset){
            dataInState.registered=true;
        }

        // 退職or休職が変更していないか
        // 変更している場合はtrue、変更なしの場合は何もしない
        if(isInWorkChange){
            dataInState.isInWorkChange=true;
        }
        
    return dataInState;
}

