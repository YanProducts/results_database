export default function IssuedAndReturns({projectSets,mainProjectName,onIssuedOrReturnedCountsChange,state,setState,isConfirm,processing,jpnWord}){


    return(
    <tr className={`border-black border-2 base_backColor`}>
        <td className="bg-orange-100 border-x-2" colSpan={2}>{jpnWord}</td>
        {Object.values(projectSets).map((eachProjectName,index)=>
        <td className="border-x-2" key={index}>

          { (!isConfirm && !processing) ?
            <input className="text-right w-[90%]" value={state?.[mainProjectName]?.[eachProjectName] || ""} onChange={(e)=>onIssuedOrReturnedCountsChange(e,mainProjectName,eachProjectName,setState)}/>
            :
            state?.[mainProjectName]?.[eachProjectName] || "-"
          }
        </td>
        )}
    </tr>
    )
}
