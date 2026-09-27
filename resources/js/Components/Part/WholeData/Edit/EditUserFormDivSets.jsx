import getJpnWord from "../../../../Support/Common/getJpnWord"
import getTopAndLastItemInEditUserForm from "../../../../Support/WholeData/getTopAndLastItemInEditUserForm";
import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons";

// ユーザー情報変更におけるdivとフォーム類のセット
export default function EditUserDivSets({pageMaxWidth,pageMinWidth,onSubmitBtnClick,onCancelBtnClick,userInformation,placeLists,onPlaceNameChange,selectedPlaceId,onStaffNameChange,staffName,isReset,onIsResetChange,isInWorkChange,onIsInWorkChange,errors,processing}){

    const role=userInformation.role;
    const registered=userInformation.registered;
    const {topItem}=getTopAndLastItemInEditUserForm({role,registered});

    return(
        <form className={`base_frame ${pageMinWidth} ${pageMaxWidth} mb-5`} onSubmit={onSubmitBtnClick}>

            {/* タイトルに職種 */}
            <div className={`base_backColor base_frame border-2 border-black ${pageMinWidth} ${pageMaxWidth} font-bold text-center  text-2xl my-2`}>{getJpnWord(role)} -{userInformation?.userName || "不明なユーザー"}-</div>

            {/* フォームセット */}
            <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>

                {/* 営業所名変更 */}
                {["branch_manager","field_staff"].includes(role) &&
                <div className={`each_form_div_sets border-b-dashed  ${topItem=="place" && "border-t-2"} `}>
                    <div className="each_form_div_item_title">所属営業所</div>
                    <div className="each_form_div_item_form">
                        <select className="bg-white block mx-auto w-[90%] h-9 border border-black rounded-sm text-center cursor-pointer" onChange={onPlaceNameChange} value={selectedPlaceId}>
                            {Object.entries(placeLists).map(([placeId,placeName],index)=>
                                <option key={placeId} value={placeId}>{placeName}</option>
                            )}
                        </select>
                    </div>
                </div>
                }

                {/* スタッフ名変更 */}
                {role=="field_staff" &&
                <div className="each_form_div_sets">
                    <div className="each_form_div_item_title">スタッフ名</div>
                    <div className="each_form_div_item_form"><input className="inline-block mx-auto h-[70%] w-[90%] border border-black rounded-sm bg-white text-center" onChange={onStaffNameChange} value={staffName} /></div>
                </div>
                }

                {/* 本登録終了からパスワードを忘れたときは本登録できるようにする */}
                {registered &&
                <div className={`each_form_div_sets ${topItem=="registered" && "border-t-2"} `}>
                        <div className="each_form_div_item_title">パスワードリセット</div>
                       <div className="each_form_div_item_form">
                        <label htmlFor="passReset" className="cursor-pointer w-full text-center"><input id="passReset" checked={isReset} onChange={onIsResetChange} type="checkbox" />リセットする</label></div>
                </div>
                }

                {/* 稼働終了or再開(checkboxがcheckされていたら変更) */}
                <div className={`each_form_div_sets ${topItem=="isActive" && "border-t-2"} border-b-2 border-b-solid`}>
                     <div className="each_form_div_item_title">{role=="field_staff" ? "稼働/非稼働":"在職/退職"}</div>
                     <div className="each_form_div_item_form">
                        <label htmlFor="isInWork" className="cursor-pointer w-full text-center"><input id="isInWork" checked={isInWorkChange} onChange={onIsInWorkChange} type="checkbox" />{userInformation.inWork ? (role=="field_staff" ? "非稼働" : "退職" ):"再稼働"}</label></div>
                  </div>
            </div>

            <p>　</p>

            <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} errors={errors} onSubmitBtnClick={onSubmitBtnClick} onCancelBtnClick=
            {onCancelBtnClick} cancelSentence="戻る"/>

        </form>
    )
}
