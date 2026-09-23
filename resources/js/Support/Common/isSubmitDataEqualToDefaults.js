// 提出データが以前のデータと同じかのチェック
// 同じ場合はアラートを出して提出しない
// 違うものを含む場合は、そのデータのみを提出
// 両方のキーともに同じキーを持っているオブジェクトであることが前提
export default function isSubmitDataEqualToDefaults({defaultData,dataInState}){

    // stateの方に存在するキーを1つずつ見ていき、defaultと変化しているかを調べる
    const changedData=Object.fromEntries(Object.entries(dataInState).filter(([stateKey,stateValue],index)=>
        stateValue!==defaultData?.[stateKey]
    ))

    // 全て同じ場合はアラートを出す
    if(!changedData || Object.keys(changedData).length==0){
        alert("変更がありません")
    }

    // この関数内部でreturnしても呼び出し元の処理は実行される。
    //その後の関数実行の部分の処理を必ずすること
    return{changedData}
}
