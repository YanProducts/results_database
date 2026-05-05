// 各案件の合計数
export default function TrForSum({inputValues,mainProjectName,projectSets,widthSets}){
    return(
         <tr className="border-black border-2 base_backColor" key={"sum"}>
            <td className="border-black border-2 bg-sky-200" colSpan={2}>合計</td>
            {/* それぞれの案件の合計数 */}
             {Object.entries(projectSets).map(function(projectIdNameSet,index){
                const projectId=projectIdNameSet[0];
                const projectName=projectIdNameSet[1];
                // メインの合計数
                if(index==0){
                    return(
                    <td key={"main"} className={`border-black border-2 ${widthSets[2]}`}>
                        {inputValues[mainProjectName] ? Object.values(inputValues[mainProjectName]).reduce((nowSumValue,valueInArray)=>nowSumValue+Number(valueInArray?.main || 0),0) : 0}
                    </td>
                  )
                }else{
                    // 併配案件の合計数
                    return(
                     <td key={"sub" + index} className={`border-black border-2 ${widthSets[index+2]}`}>{inputValues[mainProjectName] ? Object.values(inputValues[mainProjectName]).reduce((nowSumValue,valueInArray)=>nowSumValue+Number(valueInArray[projectId.substring(2)] ? valueInArray[projectId.substring(2)] : 0),0) : 0}</td>
                    )
                }
             })}
        </tr>
    )
}
