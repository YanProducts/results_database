export default function TownPart({sameTownsData,data,pageMinWidth,pageMaxWidth,isFile}){

    return(
    <div className={`base_backColor base_frame border-2 border-black  text-left mt-5 mb-10 p-2 ${pageMaxWidth} ${pageMinWidth}`}>
        <h2 className="base_h mb-1">{!isFile ? "２：町目データの重複" : "３：ファイル内での重複" }</h2>
        {(sameTownsData && sameTownsData.length>0) ?
        <>
           <p>
            {!isFile?
            "下記の案件の下記の町目では、同案件名の最新のバージョンでは既に町目を振り終えております。\n分割したなどの特殊状況で間違いないかを確認し、決定かやり直すかを選択してください"
            :
            "今回アップロードしたファイル同士で、以下の町目が重複しております。\n問題なければ決定を押してください"
            }
            </p>
            <div className="mt-5 text-center">
                 <div className="flex border bg-orange-200 border-black border-collapse">
                        <span className="inline-block border px-5  borer-black box-border border-collapse font-bold w-2/5">案件名</span>
                        <span className="inline-block border border-black box-border border-collapse font-bold w-3/5">町丁目名</span>
                  </div>
            {
                sameTownsData.map((eachTownData,index)=>
                  <div key={index} className={`flex border bg-lime-200 border-black border-collapse ${!isFile && data.newProjects.includes(eachTownData.projectId) ? "line-through opacity-30" : "opacity-100"}`}>
                        <span className="inline-block border px-5  borer-black box-border border-collapse w-2/5">{eachTownData.projectName}</span>
                        <span className="inline-block border border-black box-border border-collapse w-3/5">{eachTownData.address}</span>
                  </div>
                )
            }
            </div>
        </>
        :
        <p>{!isFile ? "同案件の最終版には、町目が重複するデータはありません" : "今回アップロードしたファイル同士での重複はありません"}</p>
        }

      </div>
    );
}
