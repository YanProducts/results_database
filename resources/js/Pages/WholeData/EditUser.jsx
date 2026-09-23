import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import useEditUserDefinitions from "../../Definition/WholeData/useEditUserDefinitions";
import useEditUserActions from "../../Action/WholeData/useEditUserActions";
import getJpnWord from "../../Support/Common/getJpnWord";

export default function EditUser({ prefix, what, type, userInformation, placeLists}) {

    // 定義セット
    const {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,selectedPlaceName,setSelectedPlaceName,staffName,setStaffName,isReset,setIsReset,isInWorkChange,setIsInWorkChange,pageMinWidth,pageMaxWidth} = useEditUserDefinitions({});

    // 動き
    const {onPlaceNameChange,onStaffNameChange,onIsResetChange,onIsInWorkChange,onSubmitBtnClick} = useEditUserActions({setData,post,setValidationHidden,setSelectedPlaceName,setStaffName,isReset,setIsReset,isInWorkChange,setIsInWorkChange,userInformation});

    return (
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>
                <p>　</p>
                <h1 className="base_h base_h1 min-w-100">全般統括-{type}-</h1>

                {/* バリデーションエラー */}
                <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} validationHidden={validationHidden}/>

                {/* userInformationはrole、id、userName、staffName、placeIdがある */}
                <form className={`base_frame ${pageMinWidth} ${pageMaxWidth}`} onSubmit={onSubmitBtnClick}>

                    <div className={`base_frame border-2 border-black ${pageMinWidth} ${pageMaxWidth}font-bold text-2xl`}>{getJpnWord(userInformation.role)}</div> {/*職種 */}
                    <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>
                        <div className="w-full flex border-2 border-black text-center">
                            <div>所属営業所</div>
                            <select onChange={onPlaceNameChange} value={selectedPlaceName}>
                                {Object.entries(placeLists).map(([placeId,placeName],index)=>
                                  <option key={placeId} value={placeId}>{placeName}</option>
                                )}
                            </select>
                        </div>

                        <div className="w-full flex border-2 border-black text-center">
                            <div>スタッフ名</div>
                            <div className=""><input onChange={onStaffNameChange} value={staffName} /></div>
                        </div>

                        {/* 本登録終了からパスワードを忘れたときは本登録できるようにする */}
                        {userInformation.status &&
                        <div className="w-full flex border-2 border-black text-center">
                            <div>パスワードリセット</div>
                            <div><label for="id"><input id="passReset" checked={isReset} onChange={onIsResetChange} type="checkbox" /></label></div>
                        </div>
                        }

                        {/* 稼働終了or再開(checkboxがcheckされていたら変更) */}
                        <div className="w-full flex border-2 border-black text-center">
                            <div>稼働状況</div>
                            <div><label for="id"><input id="passReset" checked={isInWorkChange} onChange={onIsInWorkChange} type="checkbox" />{userInformation.isActive ? (userInformation.role=="field_staff" ? "非稼働" : "退職" ):"再稼働"}</label></div>
                        </div>
                    </div>
                </form>

                {/* リンク */}
                <div className="mt-1">
                    <BaseLinkLine routeName="whole_data.admin_overview"  routeParams={{"type":"all"}} what="全体の確認"/>
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
