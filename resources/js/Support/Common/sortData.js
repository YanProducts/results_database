// sourceDataのsort
export default function sortData({objForDataCheck=null,ascOrDes,prioritySort,sourceData}){

    // 初期設定が完了されていない場合は何もしない
    if(!prioritySort){
        return sourceData;
    }

    // sourceDataは参照渡しにはならないが非破壊にするほうが良い
    const copied=[...sourceData];

    try{
        // 取得文字列がエラーと思われる時（元のsourceDataは正しいと仮定=ここでの形式を疑うならsomeなどで行うべきだが、冗長さを考え今回は行わない）
        if(objForDataCheck && !Object.keys(objForDataCheck).includes(prioritySort)){
            throw new Error("priority item" + prioritySort + "is unexpected");
        }

        if(ascOrDes=="asc"){
            // 昇順
            return copied.sort((a,b)=>{
                if(a[prioritySort] > b[prioritySort]) return 1;
                if(a[prioritySort] < b[prioritySort]) return -1;
                return 0;
            });
        }else if(ascOrDes=="des"){
            // 降順
            return copied.sort((a,b)=>{
                if(a[prioritySort] > b[prioritySort]) return -1;
                if(a[prioritySort] < b[prioritySort]) return 1;
                return 0;
            });
        }else{
            throw new Error("string is not asc nor des");
        }

    }catch(e){
        console.log(e.message) //開発環境のみ
        // memo内部でエラー表示は理想的ではない。ユーザーにはバグがあってはならない状態で渡すのが筋
        return copied;
    }
}
