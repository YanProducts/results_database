import {route} from 'ziggy-js';

export default function useSendProjectActions(){

  // 場所変化(不要なら使わないだけ)
  const onPlaceChange=(e)=>{
    setData("place",e.currentTarget.value)
  }

//  // スタッフの名前の変化（不要な時は使わなければ良いだけ）
//   const onStaffNameChange=(e)=>{
//     setData("staffName",e.currentTarget.value)
//   }

  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();
    // バリデーションはlaravelに任せる(遷移しないため)
       post(route("project_operator.dispatch_project_post"));
  }

  return{onPlaceChange,onSubmitBtnClick}
}
