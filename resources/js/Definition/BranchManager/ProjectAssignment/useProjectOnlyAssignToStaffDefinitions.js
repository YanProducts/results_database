import { useForm } from "@inertiajs/react";
import React from "react";

export default function useProjectOnlyAssignToStaffDefinitions({}) {
      // フォーム
      const { data, setData, post, processing, errors,clearErrors, reset}=useForm();

       // バリデーションを表示させるか
      const [validationHidden,setValidationHidden]=React.useState(false); //初期は表示させる(hiddenにさせない)

        // 選択中の日付
        const [selectedDate,setSelectedDate]=React.useState("");

        // 選択中のスタッフ(popupを出しているスタッフ)
        const [staffInChoice,setStaffInChoice]=React.useState("");

        // 選択された案件([スタッフ]=>[idセット])
        const [choicedProjects,setChoicedProjects]=React.useState({});

        // popupを表示するか
        const [popUpVisible,setPopUpVisible]=React.useState(false);

      // ページの横幅
      const [pageMinWidth,pageMaxWidth]=["min-w-120","max-w-300"];

    return {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,selectedDate,setSelectedDate,staffInChoice,setStaffInChoice,choicedProjects,setChoicedProjects,popUpVisible,setPopUpVisible,pageMinWidth,pageMaxWidth};
}
