// 報告書の編集において変更したTd要素のcssを変化
export default function useTargetChangeHandler({changedData,setChangedData,mainProjectName,assignId,subProjectId}){
        const changedTarget=changedData?.[mainProjectName]?.[assignId] || null;
        const changedProject=subProjectId ?? "main";
        if(!changedTarget?.includes(changedProject)){
            setChangedData(prev=>({
                ...prev,
                [mainProjectName]:{
                    ...(prev?.[mainProjectName] || []),
                    [assignId]:[
                        ...(prev?.[mainProjectName]?.[assignId] || []),
                        changedProject
                    ]
                }
            }));
        }
}
