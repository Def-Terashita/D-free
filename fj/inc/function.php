<?php


// フェーズ（表示のためのSWICH文）
function phaseStr($project_data)
{
    $phasestr = array();

    foreach ($project_data as $row)
    {
        switch ($row){
            case Planning:
                $phasestr[] = "企画・提案";
                break;
            case Requirement:
                $phasestr[] = "要件定義";
                break;
            case Basic:
                $phasestr[] = "基本設計";
                break;
            case Detail:
                $phasestr[] = "詳細設計";
                break;
            case Develop:
                $phasestr[] = "構築・開発";
                break;
            case Test:
                $phasestr[] = "テスト";
                break;
            case Operation:
                $phasestr[] = "運用";
                break;
            case OtherPhase:
                $phasestr[] = "その他";
                break;
            default:
                break;
        }
    }
    return $phasestr;
}


// エリア（表示のためのSWICH文）
function areaStr($project_data)
{

    switch ($project_data){
        case Osaka:
            $areastr = "大阪";
            break;
        case Kyoto:
            $areastr = "京都";
            break;
        case Nara:
            $areastr = "奈良";
            break;
        case Wakayama:
            $areastr = "和歌山";
            break;
        case Hyogo:
            $areastr = "兵庫";
            break;
        case Shiga:
            $areastr = "滋賀";
            break;
        default:
            break;
    }

    return $areastr;

}


// キーワード（表示のためのSWICH文）
function keywordStr($project_data)
{

    $keywordstr = array();

    foreach ($project_data as $row){

        switch ($row)
        {
            case JavaScript:
                $keywordstr[] = "JavaScript";
                break;
            case Ruby:
                $keywordstr[] = "Ruby";
                break;
            case Python:
                $keywordstr[] = "Python";
                break;
            case PHP:
                $keywordstr[] = "PHP";
                break;
            case Java:
                $keywordstr[] = "Java";
                break;
            case C:
                $keywordstr[] = "C";
                break;
            case Cplus:
                $keywordstr[] = "C++";
                break;
            case VCplus:
                $keywordstr[] = "VC++";
                break;
            case CNET:
                $keywordstr[] = "C#.NET";
                break;
            case ObjectiveC:
                $keywordstr[] = "Objective-C";
                break;
            case Swift:
                $keywordstr[] = "Swift";
                break;
            case SQL:
                $keywordstr[] = "SQL";
                break;
            case PLSQL:
                $keywordstr[] = "PL/SQL";
                break;
            case Androidjava:
                $keywordstr[] = "Android-java";
                break;
            case VB:
                $keywordstr[] = "VB";
                break;
            case VBNET:
                $keywordstr[] = "VB.NET";
                break;
            case VBA:
                $keywordstr[] = "VBA";
                break;
            case COBOL:
                $keywordstr[] = "COBOL";
                break;
            case Oracle:
                $keywordstr[] = "Oracle";
                break;
            case HTML:
                $keywordstr[] = "HTML";
                break;
            case Windows:
                $keywordstr[] = "Windows";
                break;
            case UnixLinux:
                $keywordstr[] = "Unix / Linux";
                break;
            case AIX:
                $keywordstr[] = "AIX";
                break;
            case AWS:
                $keywordstr[] = "AWS";
                break;
            case Solaris:
                $keywordstr[] = "Solaris";
                break;
            case OtherLanguage:
                $keywordstr[] = "その他";
                break;
            default:
                break;
        }
    }
    return $keywordstr;
}

// 会員フラグ
function memberdStr($project_data)
{
    switch ($project_data)
    {
        case Guest:
            $memberstr = "すべてのユーザー";
            break;
        case Member:
            $memberstr = "会員のみ";
            break;
        default:
            break;
    }

    return $memberstr;
}



// 反映フラグ
function reflectStr($project_data)
{
    switch ($project_data){
        case Release:
            $reflectstr = "反映";
            break;
        case NonRelease:
            $reflectstr = "未反映";
            break;
        default:
            break;
    }
    return $reflectstr;
}

// 入力チェック(空文字列)
function inputCheck($input){

    $check = OK;
    if (!strlen($input))
    {
        $check = NG;
    }
    return $check;

}

// 電話番号正規表現チェック
function pregCheck_tel($input)
{
    $check = NG;

    //固定電話
    if (preg_match("/^(0(?:[1-9]|[1-9]{2}\d{0,2}))-([2-9]\d{0,3})-(\d{4})$/", $input))
    {
        $check = OK;
    }
    // 携帯電話
    else if (preg_match("/^0[57-9]0-\d{4}-\d{4}$/", $input))
    {
        $check = OK;
    }

    return $check;

}

// メール正規表現チェック
function pregCheck_mail($input)
{
    $check = OK;
    $pattern = '/\A([a-z0-9_\-\+\/\?]+)(\.[a-z0-9_\-\+\/\?]+)*' .
        '@([a-z0-9\-]+\.)+[a-z]{2,6}\z/i';

    if (!preg_match($pattern, $input))
    {
        $check = NG;
    }
    return $check;

}

// 管理者ID パスワード正規表現チェック
function pregCheck($input)
{
    $check = OK;
    if (!preg_match("/^[a-zA-Z0-9]+$/", $input))
    {
        $check = NG;
    }
    return $check;

}

// 文字数チェック
function lengthCheck($input)
{
    $check = OK;
    if (strlen($input) < 8 || strlen($input) > 16)
    {// 文字数NG
        $check = NG;
    }
    return $check;

}

// 単価正規表現チェック
function pregCheck_price($input)
{
    $check = OK;
    if (!preg_match("/^[0-9]+$/", $input))
    {
        $check = NG;
    }
    return $check;

}




?>