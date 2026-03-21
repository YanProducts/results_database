export default function TownPart({sameTownsData,data}){
    return(
    <div className={`base_backColor base_frame border-2 border-black  text-left mt-5 mb-10 p-2 min-w-140`}>
        <h2 className="base_h">２：町目データの重複</h2>
        {sameTownsData ?
        <>
            <p>下記の案件の下記の町目では、同案件名の最新のバージョンでは既に町目を振り終えております。<br/>分割したなどの特殊状況で間違いないかを確認し、決定かやり直すかを選択してください</p>
            <div className="mt-5 text-center">
                 <div className="flex border bg-orange-200 border-black border-collapse">
                        <span className="inline-block border px-5  borer-black box-border border-collapse font-bold w-2/5">案件名</span>
                        <span className="inline-block border border-black box-border border-collapse font-bold w-3/5">町丁目名</span>
                  </div>
            {
                sameTownsData.map((eachTownData,index)=>
                  <div key={index} className={`flex border bg-lime-200 border-black border-collapse ${data.newProjects.includes(eachTownData.projectId) ? "line-through opacity-30" : "opacity-100"}`}>
                        <span className="inline-block border px-5  borer-black box-border border-collapse w-2/5">{eachTownData.projectName}</span>
                        <span className="inline-block border border-black box-border border-collapse w-3/5">{eachTownData.address}</span>
                  </div>
                )
            }
            </div>
        </>
        :
        <p>同案件の最終版には、既に割り振りを終えている町目はありません。重複案件データはありません</p>
        }

      </div>
    );
}
