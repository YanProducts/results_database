import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import useEditPlaceDefinitions from "../../Definition/WholeData/useEditPlaceDefinitions";
import useEditPlaceActions from "../../Action/WholeData/useEditPlaceActions";

export default function EditPlace({ prefix, what, type,placeInformation }) {

    // 定義セット
    const {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,placeName,setPlaceName,isInWorkChange,setIsInWorkChange,pageMinWidth,pageMaxWidth} = useEditPlaceDefinitions({placeInformation});

    // 動き
    const {onPlaceNameChange,onIsActiveChange,onSubmitBtnClick,onCancelBtnClick} = useEditPlaceActions({placeInformation,setPlaceName,isActive,setIsActive});

    return (
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>

                {/* ページ内容 */}


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
