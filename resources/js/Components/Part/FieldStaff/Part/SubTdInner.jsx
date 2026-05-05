export default function SubTdInner({isConfirm,onAssignedInputChange,assignId,subProjectId,mainProjectName,trIndex,index,inputValues,inputRefs}){
    return(
      !isConfirm ?
        <input className="w-full text-right" onChange={(e)=>onAssignedInputChange({e,assignId,subProjectId,mainProjectName,trIndex,index})} value={inputValues?.[mainProjectName]?.[assignId]?.[subProjectId] || ""}   ref={(el)=>{inputRefs.current[mainProjectName][trIndex][index]=el}}/>
        :
       inputValues?.[mainProjectName]?.[assignId]?.[subProjectId] || "未記入"
    )

}
