export default function MainTdInner({isConfirm,onAssignedInputChange,assignId,mainProjectName,trIndex,indexInMaps,index,inputValues,inputRefs,onInputKeyDown,processing,fromSimpleFlag}){

    // keyDownイベントは現時点では実装されていない。後日、意見を考えて実装検討
    return(
        (!isConfirm && !processing ) ?
        <input className="w-full text-right" onChange={(e)=>onAssignedInputChange({e,assignId,mainProjectName,trIndex,indexInMaps,index})} onKeyDown={(e)=>onInputKeyDown(e,mainProjectName,trIndex,indexInMaps,index)} value={inputValues?.[mainProjectName]?.[assignId]?.["main"] || ""}  ref={(el)=>
        {
            // 作成されていない時は作成
            if (!inputRefs.current[mainProjectName]) {
                inputRefs.current[mainProjectName] = {}
            }
            if (!inputRefs.current[mainProjectName][trIndex]) {
                inputRefs.current[mainProjectName][trIndex] = {}
            }
            if (!inputRefs.current[mainProjectName][trIndex][indexInMaps]) {
                inputRefs.current[mainProjectName][trIndex][indexInMaps] = {}
            }
            inputRefs.current[mainProjectName][trIndex][indexInMaps][0]=el
        }}/>
        :
    // 確認用(地図からのみ記入されている場合は全町確認は不要)
    inputValues?.[mainProjectName]?.[assignId]?.["main"] || (!fromSimpleFlag ? "未記入" : "-")



    )
}
