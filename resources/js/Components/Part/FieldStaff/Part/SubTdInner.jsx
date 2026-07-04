export default function SubTdInner({isConfirm,onAssignedInputChange,assignId,subProjectId,mainProjectName,trIndex,indexWithMaps,index,inputValues,inputRefs,fromSimpleFlag,processing}){

    return(
      (!isConfirm && !processing )?
        <input className="w-full text-right" onChange={(e)=>onAssignedInputChange({e,assignId,subProjectId,mainProjectName,trIndex,indexWithMaps,index})} value={inputValues?.[mainProjectName]?.[assignId]?.[subProjectId] || ""}   ref={(el)=>{inputRefs.current[mainProjectName][trIndex][indexWithMaps][index]=el}}/>
        :
        // 確認用(地図からのみ記入されている場合は全町確認は不要)
       inputValues?.[mainProjectName]?.[assignId]?.[subProjectId] || (!fromSimpleFlag ? "未記入" : "-" )
    )

}
