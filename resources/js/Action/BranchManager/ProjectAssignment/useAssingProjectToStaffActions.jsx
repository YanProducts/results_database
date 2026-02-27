import {route} from 'ziggy-js';

export default function useAssignProjectToStaffActions(){

  //useReducerで定義する


  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();
    // バリデーションはlaravelに任せる(遷移しないため)
       post(route("branch_manager.assign_project"));
  }

  return{onSubmitBtnClick}
}
