// 作業過程が違うため、町目版と案件版&地図版で仕様が違う
export default function useHandleDataChange({setSelectedDate,e,dateProjectsIndex,setNonDataInTheDayFlag}){
    const date=e.currentTarget.value;

    //案件版とマップ版は「案件が届いていません」を、この形式で捕捉(町目版は別の方法で捕捉済)
    if(dateProjectsIndex){
        if(dateProjectsIndex[date] && dateProjectsIndex[date].length==0){
        setNonDataInTheDayFlag(true)
        return;
        }
      setNonDataInTheDayFlag(false)
    }


    //  表示するdateの変化
    setSelectedDate(date);
}
