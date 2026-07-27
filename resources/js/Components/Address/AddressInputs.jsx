// 住所をリストに貼り付けるパターン
export default function AddressInputs({textName,onAddressListsChange,townDataByLists}){
    return(
        <textarea name={textName} onChange={onAddressListsChange} value={townDataByLists}/>
    )

}
