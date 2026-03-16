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

    if(!isChecked){
        setData("newProject",[...data.newProject,targetId]);
    }else{
        setData(
            "newProject",data.newProject.filter((eachId)=>eachId!=targetId)
        );
    }
 }


  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();
    // バリデーションはlaravelに任せる(遷移しないため)
       post(route("project_operator.confrim_dispatch_post"));
  }

  // やり直すボタンを押した時
  const onCancelBtnClick=()=>{
    // 完全遷移
    // location.href="";
    router.visit(route('project_operator.dispatch_project'));
  }

  return{onProjectsCheckClick,onSubmitBtnClick,onCancelBtnClick}
}
