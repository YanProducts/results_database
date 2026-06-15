import React from 'react';
import {route} from 'ziggy-js';
import { router } from '@inertiajs/react';

export default function useComfirmDispatchActions(post,data,setData){

 // プロジェクトを新しくするかどうかのボタンの変更
 const onProjectsCheckClick=(e)=>{
    // idの取得
    const targetId=e.currentTarget.value;
    // チェックされているかの取得
    const isChecked=e.currentTarget.checked;

    if(isChecked){
        // チェックされたときは今回のidを追加
        setData("newProjects",[...data.newProjects,Number(targetId)]);
    }else{
        // チェックが外れたときは今回のidを削除
        setData(
            "newProjects",data.newProjects.filter((eachId)=>Number(eachId)!=Number(targetId))
        );
    }
 }


  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();
    // バリデーションはlaravelに任せる(遷移しないため)
       post(route("project_operator.confirm_dispatch_post"));
  }

  // やり直すボタンを押した時
  const onCancelBtnClick=()=>{
    // 完全遷移
    // location.href="";
    router.visit(route('project_operator.dispatch_project'));
  }

  return{onProjectsCheckClick,onSubmitBtnClick,onCancelBtnClick}
}
