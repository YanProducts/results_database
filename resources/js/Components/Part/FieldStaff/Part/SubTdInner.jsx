export default function SubTdInner({isConfirm,onAssignedInputChange,assignId,subProjectId,mainProjectName,trIndex,indexWithMaps,index,inputValues,inputRefs}){
    return(
      !isConfirm ?
        <input className="w-full text-right" onChange={(e)=>onAssignedInputChange({e,assignId,subProjectId,mainProjectName,trIndex,indexWithMaps,index})} value={inputValues?.[mainProjectName]?.[assignId]?.[subProjectId] || ""}   ref={(el)=>{inputRefs.current[mainProjectName][trIndex][indexWithMaps][index]=el}}/>
        :
       inputValues?.[mainProjectName]?.[assignId]?.[subProjectId] || "未記入"
    )

}
