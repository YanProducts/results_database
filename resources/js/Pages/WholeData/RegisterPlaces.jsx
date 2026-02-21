import useRegisterPlacesDefinitions from "../../Definition/WholeData/useRegisterPlacesDefinitions";
import useRegisterPlacesActions from "../../Action/WholeData/useRegisterPlacesActions";
import Layout from "../../Layout/Layout";
import InputPageHeader from "../../Components/Common/InputPageHeader";
import InputParts from "../../Components/Common/InputParts";;
import ViewValidationErrors from "../../Components/Common/ViewValidationErrors";
import BaseButton from "../../Components/Common/BaseButton";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";

// 全体統括者が、個々のユーザーを登録していくページ
export default function RegisterPlaces({what,type}){

  // 定義(フォームなど)
  const { data, setData, post, processing, errors, reset}=useRegisterPlacesDefinitions();

  console.log(errors)

  // 動き
  const {onPlaceChange,onColorValueChange,onSubmitBtnClick}=useRegisterPlacesActions(setData,post);

  return(
    <Layout title={`${what}-${type}-`}>
     <div className="h-full min-h-screen bg-lime-200">

    {/* タイトル */}
    <InputPageHeader what={what} type={type} inputWhat="下記"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick}>
             <div className="base_frame min-w-80 max-w-100 base_backColor md:p-3 sm:p-2 p-0 border-2 border-black rounded-sm mb-5">


                {/* 営業所 */}
                <InputParts type="text" name="place" value={data.place} onChange={onPlaceChange} prefix={"営業所名："} />

                {/* RGBバー */}
                <InputParts type="text" name="colorValue" value={data.colors.red} onChange={onColorValueChange} prefix={"カラー："} />

              </div>

      {/* バリデーションエラー */}
      <ViewValidationErrors errors={errors} />

      {/* 提出ボタン */}
      <BaseButton processing={processing}/>
    </form>
      <div className="mt-4">
        <BaseLinkLine routeName="whole_data.logout"  what="ログアウト"/>
      </div>
    </div>
    </Layout>
  )
}

