import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import ViewValidationErrors from "../../Components/Common/ViewValidationErrors";
import useEditUserDefinitions from "../../Definition/WholeData/useEditUserDefinitions";
import useEditUserActions from "../../Action/WholeData/useEditUserActions";
import getJpnWord from "../../Support/Common/getJpnWord";
import EditUserDivSets from "../../Components/Part/WholeData/Edit/EditUserFormDivSets";

export default function EditUser({ prefix, what, type, userInformation, placeLists}) {

    // 定義セット
    const {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,selectedPlaceId,setSelectedPlaceId,staffName,setStaffName,isReset,setIsReset,isInWorkChange,setIsInWorkChange,pageMinWidth,pageMaxWidth} = useEditUserDefinitions({userInformation});

    // 動き
    const {onPlaceNameChange,onStaffNameChange,onIsResetChange,onIsInWorkChange,onSubmitBtnClick,onCancelBtnClick} = useEditUserActions({data,setData,errors,clearErrors,post,setValidationHidden,selectedPlaceId,setSelectedPlaceId,staffName,setStaffName,isReset,setIsReset,isInWorkChange,setIsInWorkChange,userInformation});

    const role=userInformation.role

    return (
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>
                <p>　</p>
                <h1 className="base_h base_h1 min-w-100">全般統括-{type}-</h1>

                {/* フォームのセット */}
                <EditUserDivSets {...{pageMaxWidth,pageMinWidth,onSubmitBtnClick,onCancelBtnClick,userInformation,placeLists,onPlaceNameChange,selectedPlaceId,onStaffNameChange,staffName,isReset,onIsResetChange,isInWorkChange,onIsInWorkChange,errors,processing}}/>

                {/* バリデーションエラー */}
                <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} validationHidden={validationHidden}/>

                {/* リンク */}
                <div className="mt-1">
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
