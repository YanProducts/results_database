// 基準となるテーブルのJSX
export default function BaseTable({tableTheme,allData,thSets,width="w-[80%]", minWidth="min-w-150", maxWidth="max-w-600",children}){
    // dataにはオブジェクトをラップした配列
    // thSetsにはdataの各オブジェクトのキーをキーに持ち値を日本語とする、各列のタイトルが格納

    return(
      <>
        <div className={`bg-amber-300 ${width} ${minWidth} ${maxWidth} mx-auto border-black border-t-2 border-x-2 border-collapse`}><h3 className="mb-0 pb-0 text-center font-bold text-lg">{tableTheme}</h3></div>
        <table className={`${width} ${minWidth} ${maxWidth} mx-auto base_backColor border-black border-2 border-collapse`}>
            <thead className="font-bold  text-center">
                <tr className="border-black border-2">
                    {/* thSetsの値を展開 */}
                    {Object.values(thSets).map((thName,index)=>
                        <th className="border-black border-2" key={index}>{thName}</th>
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
                    {Object.keys(thSets).map((dataKey,tdIndex)=>
                        <td  className="border-black border-2" key={tdIndex}>{eachData[dataKey] ?? "-"}</td>
                    )}
                    </tr>
                )}
            </tbody>
        </table>
      </>
    )
}
