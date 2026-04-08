import {route} from 'ziggy-js';

export default function useAssignProjectToStaffActions(dateSets,projectsAndTowns,assignPlan,setAssignPlan,selectedMainProject,setSelectedMainProject,selectedMapNumber,setSelectedMapNumber,setSelectedDate){

  //useReducerで定義する

 // 初期設定
React.useEffect(()=>{
 // 表示するdateを翌日に(0番目は今日、翌日は1番目)
 setSelectedDate(Object.keys(dateSets[1]));

},[]);

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

 // mapにスタッフの選択が変わった時
const handleAssignChangeInMaps=(e,mapNumber)=>{
    // スタッフが選択されたvalueになる
    const staffId=e.currentTarget.value;
    // 選択中のメインプロジェクトの情報が入ったセット
    const selectedProjectSets=projectsAndTowns[selectedMainProject];

    // optionで選択中の地図番号を変更(表示用。handle関数には引数で渡す)
    setSelectedMapNumber(prev=>({
        prev,
        [selectedMainProject]:{
            ...selectedMapNumber[selectedMainProject],
            [mapNumber]:staffId
        }
     })
    );

    // 選択中のメインプロジェクトのセットから、地図番号が適合するものをフィルター
    const mapNumberSets=selectedProjectSets.filter((eachSet)=>eachSet.map_number==mapNumber);

    // 地図番号でフィルターされたセットから、[planId,staffId(全て同じ)]の入れ子の配列で取得。それをその後、Entriesでplanidをキーにしたオブジェクトに変更
    const planIdArray=mapNumberSets.map((eachFilteredSet)=>[eachFilteredSet.plan_id,[staffId]]);
    setAssignPlan(
        ...assignPlan,
        // 新たにできたキーとスタッフのセット
        Object.fromEntries(planIdArray)
    )



}

//  町目にスタッフの選択が変わった時
const handleAssignChangeInTowns=(e,planId)=>{
    // planごとにスタッフを配列にして入れていく

    // スタッフが選択されたvalueになる
    const staffId=e.currentTarget.value;
    // 選択中のメインプロジェクトの情報が入ったセット
    const selectedProjectSets=projectsAndTowns[selectedMainProject];

    // （表示用のみ）選択された部分のplanIdに対するスタッフリストの変化



    // （formの土台となる値）引数で渡されたplanIdを元にplanId=>[スタッフリストの形で挿入]
    setAssignPlan({

    });



}


  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();
    // バリデーションはlaravelに任せる(遷移しないため)
       post(route("branch_manager.assign_project"));
  }

  return{onSubmitBtnClick,
    onSelectedDateChange,
    onSelectedMainProjectChange,
    onChangeMapOrTown,
    handleAssignChangeInMaps,handleAssignChangeInTowns}
}
