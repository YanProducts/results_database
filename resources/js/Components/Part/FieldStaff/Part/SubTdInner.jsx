export default function SubTdInner({isConfirm,onAssignedInputChange,assignId,subProjectId,mainProjectName,trIndex,indexInMaps,index,inputValues,inputRefs,onInputKeyDown,fromSimpleFlag,processing}){

    // keyDownイベントは現時点では実装されていない。後日、意見を考えて実装検討1
    return(
      (!isConfirm && !processing )?
        <input className="w-full text-right" onChange={(e)=>onAssignedInputChange({e,assignId,subProjectId,mainProjectName,trIndex,indexInMaps,index})} value={inputValues?.[mainProjectName]?.[assignId]?.[subProjectId] || ""}  onKeyDown={onInputKeyDown} ref={(el)=>{inputRefs.current[mainProjectName][trIndex][indexInMaps][index]=el}}/>
        :
        // 確認用(地図からのみ記入されている場合は全町確認は不要)
       inputValues?.[mainProjectName]?.[assignId]?.[subProjectId] || (!fromSimpleFlag ? "未記入" : "-" )
    )

}
