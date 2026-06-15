// Y-m-d型のdateをn月j日に変換する
export function formatDateForView(date){
    const dateFromServer=new Date(date);
    return `${dateFromServer.getMonth() +1}月${dateFromServer.getDate()}日`;
}
