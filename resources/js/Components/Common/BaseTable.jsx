// 基準となるテーブルのJSX
export default function BaseTable({tableTheme,allData,thSets,width="w-[80%]",thWidthSets=[], minWidth="min-w-150", maxWidth="max-w-600",mb="", needSort=false, sortClick=()=>{} ,children}){

    // dataにはオブジェクトをラップした配列
    // thSetsにはdataの各オブジェクトのキーをキーに持ち値を日本語とする、各列のタイトルが格納

    return(
      <>
        <div className={`bg-amber-300 ${width} ${minWidth} ${maxWidth} mx-auto border-black border-t-2 border-x-2 border-collapse`}><h3 className="mb-0 pb-0 text-center font-bold text-lg">{tableTheme}</h3></div>
        <table className={`table-fixed ${width} ${minWidth} ${maxWidth} ${mb} mx-auto base_backColor border-black border-2 border-collapse`}>
            <thead className="font-bold  text-center">
                <tr className="border-black border-2">

                    {/* thSetsの値を展開 */}
                    {Object.entries(thSets).map(([thKey,thName],index)=>
                        // thWidthSetsの要素が12以上の場合はstyleに直接挿入
                        <th className={`border-black border-2 whitespace-pre-wrap ${(thWidthSets.length>0 && thWidthSets.length<12) ? thWidthSets[index] : ""} ${needSort ? "cursor-pointer" : ""}`}
                        style={thWidthSets.length>=12 ? { width:thWidthSets[index]} :{}}
                        // クリックイベントは通常は何も生じない
                        onClick={(e)=>{sortClick(e,thKey)}} key={index}>{thName}</th>
                    )}
                </tr>
            </thead>
            <tbody className="text-center">
                {
                // コンポーネントtbodyInnerがなければ
                children ??
                allData.map((eachData,trIndex)=>
                    <tr key={trIndex}>
                    {/* thSetsのキーを取得し、そのキーとするオブジェクトをeachDataが含んでいたら値を返却 */}
                    {Object.keys(thSets).map((dataKey,trIndex)=>
                        <td  className="border-black border-2" key={trIndex}>{eachData[dataKey] ?? "-"}</td>
                    )}
                    </tr>
                )}
            </tbody>
        </table>
      </>
    )
}
