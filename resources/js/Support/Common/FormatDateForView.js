// Y-m-d型のdateをn月j日に変換する
export function formatDateForView(date){
    const dateFromServer=new Date(date);
    return `${dateFromServer.getMonth() +1}月${dateFromServer.getDate()}日`;
}

// Y-m-d型のdateをY年n月に変換する
export function formatYearAndMonthForView(date){
    const dateFromServer=new Date(date);
    return `${String((dateFromServer.getFullYear())).substring(2)}年${dateFromServer.getMonth() + 1}月`;
}

// Y-m-d型を取得(serverで使う用)
export function formatDateForServer(date) {
    const year = date.getFullYear();
    // padStartで1桁の場合は0埋めする
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}
