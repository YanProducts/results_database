// 投稿のもとになるフォームのセット
import InputParts from "../../Common/InputParts"
export default function FormSets({formType,role,data,onUserChange,onPassChange,onPassConfirmChange,onEmailChange,onNewPassChange,onNewPassConfirmChange}){

    return(
              <div className="base_frame min-w-80 max-w-100 base_backColor md:p-3 sm:p-2 p-0 border-2 border-black rounded-sm mb-5">

                {/* dataには何もせずともerrorsで弾かれた値も入力される */}

                {/* 全てに共通 */}
                <InputParts type="text" name="userName" value={data.userName} onChange={onUserChange} prefix="ユーザー名："/>

                {/* パスワード変更で文言が変更 */}
                <InputParts type="password" name="passWord" value={data.passWord} onChange={onPassChange} prefix={formType==="PassChange" ? "現在のパスワード：":"パスワード："}/>

                {/* 登録の際は全て必要 */}
                {formType==="Register" &&
                <InputParts type="password" name="passWord_confirmation" value={data.passWord_confirmation} onChange={onPassConfirmChange} prefix={"パスワード確認："}/>
                }

                {/* 統括者の登録の際にのみ必要 */}
                {(formType==="Register" && role==="whole_data") &&
                <InputParts type="email" name="email" value={data.email} onChange={onEmailChange} prefix={"メールアドレス："}/>}

                {/* パスワード変更の際に必要 */}
                {formType==="PassChange" &&
                 <>
                    <InputParts type="password" name="newPassWord" value={data.newPassWord} onChange={onNewPassChange} prefix={"新しいパスワード："}/>
                    <InputParts type="password" name="newPassWord_confirmation" value={data.newPass_confirmation} onChange={onNewPassConfirmChange} prefix={"新しいパスワード確認："}/>
                 </>}
              </div>
    )

}
