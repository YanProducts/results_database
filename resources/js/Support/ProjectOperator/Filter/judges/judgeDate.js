// 開始日のフィルター
export default function judgeDate(projectData,hiddenFilters){

    // 開始日との差
    const interval=(new Date().setHours(0,0,0,0)-new Date(projectData).setHours(0,0,0,0))/(1000*60*60*24)

    // それぞれが条件に応じて非表示にする
    if(
     (hiddenFilters.includes("before") && interval>3) ||
     (hiddenFilters.includes("-3") && interval==3) ||
     (hiddenFilters.includes("-2") && interval==2) ||
     (hiddenFilters.includes("-1") && interval==1) ||
     (hiddenFilters.includes("0") && interval==0) ||
     (hiddenFilters.includes("1") && interval==-1) ||
     (hiddenFilters.includes("2") && interval==-2) ||
     (hiddenFilters.includes("after") && interval<-2)
    ){
        return false;
    }

    return true;
}
