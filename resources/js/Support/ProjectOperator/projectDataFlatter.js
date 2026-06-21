import { formatDateForView } from "../Common/formatDateForView";

// laravelから来た入れ子のデータを平坦化する
export default function projectDataFlatter(projectData){
    let flattedData=[];

    Object.entries(projectData).forEach(function([startDate,dataByStartDate]){ //開始日
        Object.values(dataByStartDate).forEach(function(placeContents){ //営業所
        Object.entries(placeContents.plan_contents).forEach(function([mainProjectName,dataByMainContents]){ //メイン案件
            Object.entries(dataByMainContents).forEach(function([roundNumber,dataByRoundNumber]){ //roundNumber
                    flattedData.push({
                        "startDate":formatDateForView(startDate),
                        "startDateForEdit":startDate, //startDateはeditの際に本来は必要ないと思われるが、「の古い案件」と「新しい別の案件」が重なったとき(same_project_flagがあればOKだが)、日付で判断したいので、「営業所・案件名・round_number」の3つ以外にもあった方が良い
                        "placeName":placeContents.place_name,
                        "placeIdForEdit":placeContents.place_id,
                        "mainProjectName":mainProjectName,
                        "mainProjectIdForEdit":"",
                        "roundNumber":parseInt(roundNumber) +1,
                        "subLists":dataByRoundNumber.sub_lists,
                        "cityLists":dataByRoundNumber.city_lists,
                        "endDate":formatDateForView(dataByRoundNumber.end_date)
                    })
            })//roundNumber //その内部に併配セット、市リスト、終了日
        }) //メイン案件
      })//営業所
    }) //開始日
    return flattedData;
}
