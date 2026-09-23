// ページを自動作成(stubにて)する時のルール

// ファイルをテキストとして読み込むパッケージ
import fs from "node:fs";

// 土台のページ
const pageStub = fs.readFileSync("stubs/page.jsx.stub", "utf8");
const definitionStub = fs.readFileSync("stubs/definitions.jsx.stub", "utf8");
const actionStub = fs.readFileSync("stubs/actions.jsx.stub", "utf8");


const input = process.argv[2];
//引数はconst input = process.argv[2];のように、process.argvで取得できるが、２番目から取得。０番目がnode本体、１番目が実行しているmjs。

const parts = input.split("/");
//jsのファイルの階層を区分けするイメージ！

const pageName = parts.at(-1);
//上記の最後の1つをページの名前にする

const directories = parts.slice(0, -1);
// ページの名前を除いた部分


// stubの{{}}の文字列を変換
const pageContent = pageStub
    .replaceAll("{{BasePath}}", "../".repeat(parts.length))
    .replaceAll("{{PageName}}", pageName)
    .replaceAll("{{Directories}}", directories.join("/"));
const definitionContent=definitionStub.replaceAll("{{PageName}}",pageName);
const actionContent=actionStub
        .replaceAll("{{PageName}}",pageName)
        .replaceAll("{{BasePath}}", "../".repeat(parts.length));


// 既存のファイルがあるかを確認する。存在していればエラーに。そのためにまずファイル名を取得する
const pagePath =`resources/js/Pages/${input}.jsx`;
const definitionPath =`resources/js/Definition/${directories.join("/")}/use${pageName}Definitions.js`;
const actionPath =`resources/js/Action/${directories.join("/")}/use${pageName}Actions.js`;
if (
    fs.existsSync(pagePath) ||
    fs.existsSync(definitionPath) ||
    fs.existsSync(actionPath)
) {
    console.error("既に同名のファイルが存在するため、作成を中止しました。");//コンソールで実行しているだけなのでalertは無理(元々nodeにはない)
    process.exit(1); //関数処理の途中終了ならreturnでも良いが、これはトップレベルなのでprocessを終了させる必要がある。(1)は失敗したという意味
}

// ディレクトリがなければエラーになるので、ディレクトリを(ない場合は新規で)作成、ある場合には何も行わない
fs.mkdirSync(`resources/js/Pages/${directories.join("/")}`, { recursive: true });
fs.mkdirSync(`resources/js/Definition/${directories.join("/")}`, { recursive: true });
fs.mkdirSync(`resources/js/Action/${directories.join("/")}`, { recursive: true });


// ファイル名を指定してjsxファイルを作成
// 文字列のままだが、jsxファイルなので、文字列として記入されたら内部でjsやjsxに変換できる
fs.writeFileSync(pagePath,pageContent,"utf8");
fs.writeFileSync(definitionPath,definitionContent,"utf8");
fs.writeFileSync(actionPath,actionContent,"utf8");

