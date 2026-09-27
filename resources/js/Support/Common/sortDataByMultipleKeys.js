//複数キーでsortする時のfunc
// 後に単数の共通関数もここに集約
export default function sortDataByMultipleKeys({objForDataCheck=null,prioritySets,sourceData}){

    // 初期設定が完了されていない場合は何もしない
    if(!prioritySets || prioritySets.length === 0){
        return sourceData;
    }

    // 配列は内部で変更されてしまうので非破壊にするほうが良い
    const copied=[...sourceData];

    try{
        // 取得文字列がエラーと思われる時（元のsourceDataは正しいと仮定=ここでの形式を疑うならsomeなどで行うべきだが、冗長さを考え今回は行わない）
        if(objForDataCheck && prioritySets.some(eachPriority=>!Object.keys(objForDataCheck).includes(eachPriority.priority))){
            throw new Error("ソート項目が想定されるものではありません");
        }

    return copied.sort(function(a,b){
        // 優先順位セットを1つずつ見ていき、同順位ではないものは並びをreturn、同順位のものは次を検証()
        for(const eachPriority of prioritySets){

            // キーの名前がpriorityプロパティに入っている
            const priorityKeyName=eachPriority.priority;

            // 同順位の場合は次のキーを検証
            if(a[priorityKeyName]==b[priorityKeyName]){
                continue;
            }

            // 違う順位の場合はそのキーに対応するのが昇順か降順かでセット(文字列の場合は引き算できないので、大小でチェックする)
            if(eachPriority.ascOrDes==="asc"){
                return a[priorityKeyName] > b[priorityKeyName] ? 1 : -1;
            }
            if(eachPriority.ascOrDes==="des"){
                return a[priorityKeyName] < b[priorityKeyName] ? 1 : -1;
            }

         }
            // 全て同じ場合は初期の並びのまま
            return 0;
    })


    }catch(e){
        console.log(e.message) //開発環境のみ
        // memo内部でエラー表示は理想的ではない。ユーザーにはバグがあってはならない状態で渡すのが筋
        return copied;
    }

}
