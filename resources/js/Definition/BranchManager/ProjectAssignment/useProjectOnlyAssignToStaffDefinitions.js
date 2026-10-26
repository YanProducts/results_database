import { useForm } from "@inertiajs/react";
import React from "react";

export default function useProjectOnlyAssignToStaffDefinitions({}) {
      // フォーム
      const { data, setData, post, processing, errors,clearErrors, reset}=useForm();

       // バリデーションを表示させるか
      const [validationHidden,setValidationHidden]=React.useState(false); //初期は表示させる(hiddenにさせない)

        //   確認か否か
        const [isConfirm,setIsConfirm]=React.useState(false);

        // 選択中の日付
        const [selectedDate,setSelectedDate]=React.useState("");

        // 選択した日付に案件がない場合の表示部分
        const [nonDataInTheDayFlag,setNonDataInTheDayFlag]=React.useState(false)

        // 選択中のスタッフ(popupを出しているスタッフ)
        const [staffInChoice,setStaffInChoice]=React.useState("");

        // 選択された案件([スタッフ]=>[idセット])
        const [choicedProjects,setChoicedProjects]=React.useState({});

        // 例示に全町目を表示させるか
        const [showAllTowns,setShowAllTowns]=React.useState("");

        // popupを表示するか
        const [popUpVisible,setPopUpVisible]=React.useState(false);

      // ページの横幅
      const [pageMinWidth,pageMaxWidth]=["min-w-120","max-w-300"];

    return {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,isConfirm,setIsConfirm,nonDataInTheDayFlag,setNonDataInTheDayFlag,selectedDate,setSelectedDate,staffInChoice,setStaffInChoice,choicedProjects,setChoicedProjects,showAllTowns,setShowAllTowns,popUpVisible,setPopUpVisible,pageMinWidth,pageMaxWidth};
}
