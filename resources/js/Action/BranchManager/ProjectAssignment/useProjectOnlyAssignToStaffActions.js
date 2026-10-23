import React from "react";
import { route } from "ziggy-js";
import { router } from "@inertiajs/react";
import useDisappearValidation from "../../../Share/useDisappearValidation";
import popUpPositionSeeting from "../../../Support/Common/popUpPositionSetting";
import useClearErrors from "../../../Share/useClearErrors";
import useHandleDataChange from "./DataInput/useHandleDateChange";

export default function useProjectOnlyAssignToStaffActions({data,setData,post,errors,clearErrors,setValidationHidden,selectedDate,setSelectedDate,setShowAllTowns,staffInChoice,setStaffInChoice,choicedProjects,setChoicedProjects,setPopUpVisible,setIsConfirm}){

    // 確認ボタンが押されたら、確認ページへ
    React.useEffect(()=>{
        // 日付とデータのセットが終了していないor正確ではない
        if(!data || Object.keys(data).length==0 || !data?.assignDate || data.assignDate!=selectedDate){
        setIsConfirm(false);
        return;
       }
    },[data])


    //エラー自体の削除
    useClearErrors({errors,clearErrors})

    //エラーのviewの削除(内部でuseEffect使用) //Errors自体は保護
    //useDisappearValidation({errors,setValidationHidden})


    // // 日付の変更
    const onSelectedDateChange=(e)=>{
        useHandleDataChange(setSelectedDate,e);
    }

    // // 日付の初期化(全データが初期化される)
    const onClickDateReset=()=>{
        if(!confirm("入力中のデータは初期化されます。\nよろしいですか？")){
            return;
        }
        setSelectedDate("");
    }

    // 町目のサンプルから全てを表示するボタンがクリックされたとき
    const onShowAllTownsClick=({e,projectNameForView})=>{
        // どの町目リストを全部載せるか
        setShowAllTowns(projectNameForView)

        // ポップアップの場所のセット(表示はStateにセットが完了次第。先に場所のみ指定。useStateだとeが取得できない)
        popUpPositionSeeting(e,"popUpForShowAllTowns",0,"150")
    }
    // 町目のサンプルの全て表示を閉じる
    const onCloseAllTownsClick=()=>{
        setShowAllTowns("");
    }


    // 案件の選択するボタン（人の決定）
    const onProejectChoiceClick=(e,staff)=>{
        // まずはスタッフを反映
        setStaffInChoice(staff)
        // クリックポイントが必要なので、先に位置のみ指定(staffに連動してvisibleにする)
        popUpPositionSeeting(e,"popUpForMultiSort",0,-200)
    }

    // 案件の選択が押されたとき(targetの値はprojectPlannedId,staffInChoiceがスタッフのid)
    const onProjectDecide=(e)=>{
        // 選択を押されたId
        const projectPlanId=Number(e.currentTarget.value);
        // choicedProjectsに、スタッフ=>そのプロジェクトのIDが存在するか。存在していれば消し、存在していなければ入れる
        // 存在している時は、該当スタッフに対して、そのプロジェクト以外のプロジェクトを残す
        setChoicedProjects(prev=>{
            const projectsByStaff=prev[staffInChoice] ?? [];
            return({...prev,
                    [staffInChoice]:projectsByStaff.includes(projectPlanId) ? projectsByStaff.filter(eachId=>eachId!=projectPlanId) : [...projectsByStaff,projectPlanId]
                })
            })
    }


    // 案件選択を閉じる
    const onProejectChoiceClose=()=>{
            setPopUpVisible(false);
            setStaffInChoice("");
    }


   // 決定ボタンを押した際は値を変換し、その後に確認ページへ
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();



        // 解析やバリデーションのしやすさを考え、["staffId"=>"","projectPlanIds"=>["","",""](projectPlannedIdのリスト)]という配列に変更
        const filteredChoicesProjects=[];





        // 送信フォームに設定
        setData({"assignDate":selectedDate,"allData":filteredChoicesProjects})

    }

    //   確認後OKのボタンが押されたとき
    const onConfirmOkClick=(e)=>{
        e.preventDefault();
        //   processingはpostの後に発動(超高速だと間に合わない？)
        if(processing){
            return;
        }
        // バリデーションはlaravelに任せる(遷移しないため)
        post(route("branch_manager.project_only_assign_staff_post"));
    }

    // 確認キャンセルの時(重複時も同じ)
    const onConfirmCancelClick=(e)=>{

        e.preventDefault();

        // エラー後にやり直す際のことを考慮
        clearErrors();

        setData({});
        // UIを投稿に戻す処理はstateの内部で行われる
    }

    //  町目・マップ版へのリンク
    const onMovePageClick=(routeName)=>{
        if(!confirm("入力データは初期化されます\nよろしいですか？")){
            return;
        }
        router.visit(route(routeName),{
            preserveState:false
        })
    }

    return {onSelectedDateChange,onClickDateReset,onProejectChoiceClick,onProjectDecide,onProejectChoiceClose,onShowAllTownsClick,onCloseAllTownsClick,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick,onMovePageClick};
}
