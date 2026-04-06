// スタッフの割り当てを町目から選ぶ場合
export default function TownLists(projectAndTowns,selectedMainProject,staffs,handleAssignChangeInTowns,prefixPercent="w-[30%]",maxWidth="max-w-80", minWidth="min-w-72.5"){

    // メイン案件の取得
    const projectTownLists=projectAndTowns[selectedMainProject];



    return(
        ptojectTownLists.map(townData=>
            // townDataには町目データと[sub]配列が入っているが入っている
            // tableにして、最後にselectBoxを持ってくる
            
            // townData.address_name


            ""
        )
    )
}
