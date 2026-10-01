import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import useEditPlaceDefinitions from "../../Definition/WholeData/useEditPlaceDefinitions";
import useEditPlaceActions from "../../Action/WholeData/useEditPlaceActions";
import checkEditPlaceProps from "../../Support/ConfirmProps/WholeData/checkEditPlaceProps";
import ViewValidationErrors from "../../Components/Common/ViewValidationErrors";
import SubmitOrBackButtons from "../../Components/Common/SubmitOrBackButtons";
import getJpnWord from "../../Support/Common/getJpnWord";
import EditPlaceFormDivSets from "../../Components/Part/WholeData/Edit/EditPlaceFormDivSets";

export default function EditPlace({ prefix, what, type,placeInformation }) {

    // propsの確認
    checkEditPlaceProps({placeInformation})

    // 定義セット
    const {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,placeName,setPlaceName,colors,setColors,isInWorkChange,setIsInWorkChange,pageMinWidth,pageMaxWidth} = useEditPlaceDefinitions({placeInformation});

    // 動き
    const {onPlaceNameChange,onIsActiveChange,onColorChange,onSubmitBtnClick,onCancelBtnClick} = useEditPlaceActions({data,setData,post,errors,clearErrors,placeInformation,placeName,setPlaceName,isInWorkChange,setIsInWorkChange,colors,setColors});

    return (
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>

                <p>　</p>
                <h1 className="base_h base_h1 min-w-100">全般統括-{type}-</h1>

                {/* 営業所のフォーム */}
                <EditPlaceFormDivSets {...{pageMaxWidth,pageMinWidth,onSubmitBtnClick,placeInformation,onPlaceNameChange,placeName,isInWorkChange,onIsActiveChange,onCancelBtnClick,processing,errors}} />


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
