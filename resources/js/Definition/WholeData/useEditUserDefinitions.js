export default function useEditUserDefinitions({userInformation}) {

      // フォーム
      const { data, setData, post, processing, errors,clearErrors, reset}=useForm({
         id:userInformation.id,
         role:userInformation.role
      });

    // 営業所変化
    const [selectedPlaceId,setSelectedPlaceId]=React.useState();

    // スタッフ変化
    const [staffName,setStaffName]=React.useState();

    // パスワードリセット
    const [isReset,setIsReset]=React.useState();

    // 休職or退職
    const [isInWorkChange,setIsInWorkChange]=React.useState();


       // バリデーションを表示させるか
      const [validationHidden,setValidationHidden]=React.useState(false); //初期は表示させる(hiddenにさせない)

      // ページの横幅
      const [pageMinWidth,pageMaxWidth]=["min-w-120","max-w-300"];


    return {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,selectedPlaceId,setSelectedPlaceId,staffName,setStaffName,isReset,setIsReset,isInWorkChange,setIsInWorkChange,pageMinWidth,pageMaxWidth};
}
