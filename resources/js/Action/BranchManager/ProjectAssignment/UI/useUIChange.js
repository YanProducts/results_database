// UI単体の変化における初期定義
export default function useUIChange(setSelectedDate,setSelectedMainProject,setNeedNumber){
    //  表示するdateの変化
    const onSelectedDateChange=(e)=>{
        setSelectedDate(e.currentTarget.value)
    }

    // 表示するメイン案件の変化
    const onSelectedMainProjectChange=(e)=>{
        setSelectedMainProject(e.currentTarget.value)
    }

    // 表示するのはmapからかtownからか
    const onChangeMapOrTown=(e)=>{
        setNeedNumber(e.currentTarget.value);
    }

    return [onSelectedDateChange,onSelectedMainProjectChange,onChangeMapOrTown];
}
