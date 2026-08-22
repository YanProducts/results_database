import { formatDateForView } from "../Common/formatDateForView";

// laravelから来た入れ子のデータを平坦化する
export default function projectDataFlatter(projectData){
    let flattedData=[];

    Object.entries(projectData).forEach(function([startDate,dataByStartDate]){ //開始日
        Object.entries(dataByStartDate).forEach(function([placeName,dataByPlaceName]){ //営業所
        Object.entries(dataByPlaceName).forEach(function([mainProjectName,dataByMainProject]){ //メイン案件
                    flattedData.push({
                        "startDate":formatDateForView(startDate),
                        "placeName":placeName,
                        "mainProjectName":mainProjectName,
                        "counts":dataByMainProject.counts,
                        "subLists":dataByMainProject.sub_sets,
                        "cityLists":dataByMainProject.city_lists,
                        "endDate":formatDateForView(dataByMainProject.end_date)
                        // 編集用にidセットを設けるかどうか
                    })
        }) //メイン案件
      })//営業所
    }) //開始日
    return flattedData;
}
