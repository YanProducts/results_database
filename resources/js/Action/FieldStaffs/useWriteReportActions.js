import React from "react"

export default function useWriteReportActions({selectedDate,setSelectedDate,setData,post}){

    // 日付が変更されたらpost用のデータにセット(他のデータは自動的に初期化)
    React.useEffect(()=>{
        setData({
            "date":selectedDate,
            "reportData":[]
        })
    },[selectedDate])

    // 日付の変更
    const onSelectedDateChange=(e)=>{
        // UI
        setSelectedDate(e.currentTarget.value)
    }

    // 入力された部数が変化したとき
    const onAssignedInputChange=(e,planId)=>{
        setData(prev=>({
            ...prev,
            "reportData":[
                ...prev.reportData,
                {
                    "planId":planId,
                    "counts":e.target.value,
                }
            ]
        }))
    }

    // 決定ボタンを押した際は確認ページを表示する
    const onSubmitBtnClick=(e)=>{
            e.preventDefault();
            post()
    }

    return {onSelectedDateChange,onAssignedInputChange,onSubmitBtnClick}

}
