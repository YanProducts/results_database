export default function useHandleProjectChange(mapMeta,selectedMainProject,setMapMeta,setSelectedMainProject,e){
        // 表示するメイン案件の変化
        const newChoiceProject=e.currentTarget.value;
        //その案件のMapMetaの地図番号のいずれかにaddTownかremoveTownが含まれていた時=つまり地図設定⇨町目設定→地図設定の順で遷移したとき
        if(mapMeta[newChoiceProject] && mapMeta[newChoiceProject].some(
            eachMap=>eachMap[removeTown].length>0 || eachMap[addTown].length>0
        )){
           if(!confirm(newChoiceProject + "の分割データは初期化されます\nよろしいですか？")){
            return;
           }
           setMapMeta(prev=>({
                ...prev,
                [newChoiceProject]:{}
           }))
        }

        setSelectedMainProject(newChoiceProject)
}
