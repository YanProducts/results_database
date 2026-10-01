import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons"
export default function EditPlaceFormDivSets({pageMaxWidth,pageMinWidth,onSubmitBtnClick,placeInformation,onPlaceNameChange,placeName,isInWorkChange,onIsActiveChange,onCancelBtnClick,processing,errors}){

    return(
          <form className={`base_frame ${pageMinWidth} ${pageMaxWidth} mb-5`} onSubmit={onSubmitBtnClick}>


                {/* タイトルに職種 */}
                <div className={`base_backColor base_frame border-2 border-black ${pageMinWidth} ${pageMaxWidth} font-bold text-center  text-2xl my-2`}>営業所ID -{placeInformation?.id ? "no" + placeInformation.id : "不明な営業所"}-</div>


                  {/* フォームセット */}
                  <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>
                        <div className="each_form_div_sets border-t-2">
                            <div className="each_form_div_item_title">営業所名</div>
                            <div className="each_form_div_item_form"><input className="inline-block mx-auto h-[70%] w-[90%] border border-black rounded-sm bg-white text-center" onChange={onPlaceNameChange} value={placeName} /></div>
                          </div>

                        {/* 稼働or非稼働(checkboxがcheckされていたら変更) */}
                        <div className="each_form_div_sets">
                                <div className="each_form_div_item_title">稼働/非稼働</div>
                                <div className="each_form_div_item_form">
                                <label htmlFor="isInWork" className="cursor-pointer w-full text-center"><input id="isInWork" checked={isInWorkChange} onChange={onIsActiveChange} type="checkbox" className="mr-2"/>{placeInformation.is_active ? "非稼働":"再稼働"}</label></div>
                        </div>

                        {/* 稼働or非稼働(checkboxがcheckされていたら変更) */}
                        <div className="each_form_div_sets border-b-2 border-b-solid">
                                <div className="each_form_div_item_title">色</div>
                                <div className="each_form_div_item_form"><p className="w-full text-center">まだ作ってません</p></div>
                        </div>



                  </div>

                        <p>　</p>

                        <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} errors={errors} onSubmitBtnClick={onSubmitBtnClick} onCancelBtnClick=
                        {onCancelBtnClick} cancelSentence="戻る"/>
                </form>
    )
}
