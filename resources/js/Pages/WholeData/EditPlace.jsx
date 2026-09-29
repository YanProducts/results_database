import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import useEditPlaceDefinitions from "../../Definition/WholeData/useEditPlaceDefinitions";
import useEditPlaceActions from "../../Action/WholeData/useEditPlaceActions";
import checkEditPlaceProps from "../../Support/ConfirmProps/WholeData/checkEditPlaceProps";

export default function EditPlace({ prefix, what, type,placeInformation }) {

    // propsの確認
    checkEditPlaceProps({placeInformation})

    // 定義セット
    const {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,placeName,setPlaceName,colors,setColors,isInWorkChange,setIsInWorkChange,pageMinWidth,pageMaxWidth} = useEditPlaceDefinitions({placeInformation});

    // 動き
    const {onPlaceNameChange,onIsActiveChange,onColorChange,onSubmitBtnClick,onCancelBtnClick} = useEditPlaceActions({data,setData,errors,clearErrors,placeInformation,placeName,setPlaceName,isInWorkChange,setIsInWorkChange,colors,setColors});

    return (
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>

                {/* ページ内容 */}
                <form className={`base_frame ${pageMinWidth} ${pageMaxWidth} mb-5`} onSubmit={onSubmitBtnClick}>

                  {/* フォームセット */}
                  <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>
                        <div className="each_form_div_sets border-t-2">
                            <div className="each_form_div_item_title">営業所名</div>
                            <div className="each_form_div_item_form"><input className="inline-block mx-auto h-[70%] w-[90%] border border-black rounded-sm bg-white text-center" onChange={onPlaceNameChange} value={placeName} /></div>
                          </div>

                        {/* 稼働or非稼働(checkboxがcheckされていたら変更) */}
                        <div className="each_form_div_sets border-b-2 border-b-solid">
                                <div className="each_form_div_item_title">稼働/非稼働</div>
                                <div className="each_form_div_item_form">
                                <label htmlFor="isInWork" className="cursor-pointer w-full text-center"><input id="isInWork" checked={isInWorkChange} onChange={onIsActiveChange} type="checkbox" />{placeInformation.isActive ? "非稼働":"再稼働"}</label></div>
                            </div>
                  </div>

                        <p>　</p>

                        <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} errors={errors} onSubmitBtnClick={onSubmitBtnClick} onCancelBtnClick=
                        {onCancelBtnClick} cancelSentence="戻る"/>
                </form>

               {/* バリデーションエラー */}
                <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} validationHidden={validationHidden}/>

                {/* リンク */}
                <div className="mt-1">
                    <BaseLinkLine
                        routeName={`${prefix}.top_page`}
                        what="トップ"
                    />
                    <BaseLinkLine
                        routeName={`${prefix}.logout`}
                        what="ログアウト"
                    />
                </div>

                <p>　</p>
            </RoleLayout>
        </Layout>
    );
}
