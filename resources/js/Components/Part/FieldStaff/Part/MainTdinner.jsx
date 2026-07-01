export default function MainTdInner({isConfirm,onAssignedInputChange,assignId,mainProjectName,trIndex,indexWithMaps,index,inputValues,inputRefs}){
    return(
        !isConfirm ?
        <input className="w-full text-right" onChange={(e)=>onAssignedInputChange({e,assignId,mainProjectName,trIndex,indexWithMaps,index})} value={inputValues?.[mainProjectName]?.[assignId]?.["main"] || ""}  ref={(el)=>
        {
            // 作成されていない時は作成
            if (!inputRefs.current[mainProjectName]) {
                inputRefs.current[mainProjectName] = {}
            }
            if (!inputRefs.current[mainProjectName][trIndex]) {
                inputRefs.current[mainProjectName][trIndex] = {}
            }
            if (!inputRefs.current[mainProjectName][trIndex][indexWithMaps]) {
                inputRefs.current[mainProjectName][trIndex][indexWithMaps] = {}
            }
            inputRefs.current[mainProjectName][trIndex][indexWithMaps][0]=el
        }}/>
        :
       inputValues?.[mainProjectName]?.[assignId]?.["main"] || "未記入"
    )
}
