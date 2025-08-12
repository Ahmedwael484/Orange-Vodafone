<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$BASE_HTML = <<<HTML
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dev Ahmed Wael ✨</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: #fff;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #4fc3f7;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(79, 195, 247, 0.5);
        }
        .nav {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .nav a {
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            background: rgba(0, 150, 255, 0.2);
            border: 1px solid rgba(79, 195, 247, 0.3);
            color: #4fc3f7;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .nav a:hover {
            background: rgba(79, 195, 247, 0.3);
            transform: translateY(-2px);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.2);
            color: #fff;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        input:focus {
            outline: none;
            border-color: #4fc3f7;
            box-shadow: 0 0 0 2px rgba(79, 195, 247, 0.3);
        }
        button {
            padding: 12px;
            border-radius: 8px;
            border: none;
            background: linear-gradient(135deg, #4fc3f7, #1976d2);
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 150, 255, 0.4);
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.5;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>Dev Ahmed Wael ✨</h1>
            </div>
            <div class="nav">
                <a href="?page=home">الرئيسية</a>
                <a href="?page=orange_fawazeer">أورانج فوازير</a>
                <a href="?page=orange_500">أورانج 500MB</a>
                <a href="?page=orange_1gb">أورانج 1GB</a>
                <a href="?page=vodafone_1gb">فودافون 1GB</a>
            </div>
            {{CONTENT}}
            <div class="footer">مطور بواسطة أحمد وائل © 2024</div>
        </div>
    </div>
</body>
</html>
HTML;

function form_block($title, $result_text, $endpoint_name) {
    $needs_password = in_array($endpoint_name, ["orange_fawazeer","orange_500","vodafone_1gb"]);
    $pwd_field = $needs_password ? '<input name="password" type="password" placeholder="كلمة المرور" required>' : '';
    
    return <<<HTML
    <h2 style="text-align:center;margin:0 0 15px 0;color:#4fc3f7">$title</h2>
    <form method="post" autocomplete="off">
      <input name="number" type="text" placeholder="رقم الهاتف" required>
      $pwd_field
      <button type="submit">تشغيل</button>
    </form>
    <div class="result">$result_text</div>
HTML;
}

function index() {
    global $BASE_HTML;
    $content = <<<HTML
    <form method="post" autocomplete="off">
        <p style="text-align:center;margin:0 0 15px 0;color:rgba(255,255,255,0.8)">
            اختر الخدمة المطلوبة من القائمة
        </p>
    </form>
HTML;
    return str_replace('{{CONTENT}}', $content, $BASE_HTML);
}

function orange_fawazeer() {
    global $BASE_HTML;
    $result = "";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $number = isset($_POST['number']) ? trim($_POST['number']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        
        try {
            // SignIn User
            $signin_url = "https://services.orange.eg/SignIn.svc/SignInUser";
            $signin_payload = [
                "appVersion" => "9.3.0",
                "channel" => ["ChannelName" => "MobinilAndMe", "Password" => "ig3yh*mk5l42@oj7QAR8yF"],
                "dialNumber" => $number,
                "isAndroid" => true,
                "lang" => "ar",
                "password" => $password,
            ];
            
            $headers = [
                'User-Agent: okhttp/4.10.0',
                'Connection: Keep-Alive',
                'Accept-Encoding: gzip',
                'Content-Type: application/json; charset=UTF-8'
            ];
            
            $ch = curl_init($signin_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($signin_payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpcode != 200) {
                $result = "❌ فشل تسجيل الدخول - تأكد من البيانات";
                return str_replace('{{CONTENT}}', form_block("أورانج فوازير 250MB", $result, "orange_fawazeer"), $BASE_HTML);
            }
            
            $response_data = json_decode($response, true);
            $token = $response_data['SignInUserResult']['AccessToken'] ?? '';
            
            if (empty($token)) {
                $result = "❌ فشل تسجيل الدخول - تأكد من البيانات";
                return str_replace('{{CONTENT}}', form_block("أورانج فوازير 250MB", $result, "orange_fawazeer"), $BASE_HTML);
            }
            
            // Generate Token
            $gen_url = "https://services.orange.eg/APIs/Profile/api/BasicAuthentication/Generate";
            $gen_payload = [
                "ChannelName" => "MobinilAndMe",
                "ChannelPassword" => "ig3yh*mk5l42@oj7QAR8yF",
                "Dial" => $number,
                "Language" => "ar",
                "Module" => "0",
                "Password" => $password,
            ];
            
            $gen_headers = [
                'User-Agent: okhttp/4.10.0',
                'Connection: Keep-Alive',
                'Accept-Encoding: gzip',
                'Content-Type: application/json; charset=UTF-8',
                'AppVersion: 9.3.0',
                'OsVersion: 14',
                'IsAndroid: true',
                'IsEasyLogin: false',
                'Token: ' . $token
            ];
            
            $ch = curl_init($gen_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($gen_payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $gen_headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $response2 = curl_exec($ch);
            $httpcode2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $response_data2 = json_decode($response2, true);
            $tok = $response_data2['Token'] ?? '';
            
            if (empty($tok)) {
                $result = "❌ فشل في توليد التوكن";
                return str_replace('{{CONTENT}}', form_block("أورانج فوازير 250MB", $result, "orange_fawazeer"), $BASE_HTML);
            }
            
            // Submit Answers
            $submit_url = "https://services.orange.eg/APIs/Ramadan2024/api/RamadanOffers/Fawazeer/Submit";
            $submit_payload = [
                "Dial" => $number,
                "Language" => "ar",
                "Token" => $tok,
                "Answers" => [
                    ["QuestionId" => 174, "AnswerId" => 812],
                    ["QuestionId" => 175, "AnswerId" => 815],
                    ["QuestionId" => 176, "AnswerId" => 818],
                    ["QuestionId" => 177, "AnswerId" => 823],
                    ["QuestionId" => 178, "AnswerId" => 829]
                ]
            ];
            
            $submit_headers = [
                'User-Agent: Mozilla/5.0 (Linux; Android 10)',
                'Accept: application/json, text/plain, */*',
                'Content-Type: application/json',
                'Origin: https://services.orange.eg',
                'Accept-Language: ar-EG,ar;q=0.9'
            ];
            
            $ch = curl_init($submit_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($submit_payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $submit_headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $response3 = curl_exec($ch);
            $httpcode3 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $response_data3 = json_decode($response3, true);
            $status = $response_data3["ErrorDescription"] ?? "";
            
            if ($status == "FawazeerSuccess") {
                $result = "✅ تم الحصول على الباقة بنجاح";
            } elseif ($status == "GiftCapped") {
                $result = "⚠️ لقد حصلت على الهدية من قبل";
            } else {
                $result = "ℹ️ " . ($status ?: $response3);
            }
        } catch (Exception $e) {
            $result = "⚠️ خطأ: " . $e->getMessage();
        }
    }
    
    $content = form_block("أورانج فوازير 250MB", $result, "orange_fawazeer");
    return str_replace('{{CONTENT}}', $content, $BASE_HTML);
}

function orange_500() {
    global $BASE_HTML;
    $result = "";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $number = isset($_POST['number']) ? trim($_POST['number']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        
        try {
            // SignIn User
            $signin_url = "https://services.orange.eg/SignIn.svc/SignInUser";
            $payload = [
                "appVersion" => "9.0.0",
                "channel" => ["ChannelName" => "MobinilAndMe", "Password" => "ig3yh*mk5l42@oj7QAR8yF"],
                "dialNumber" => $number,
                "isAndroid" => true,
                "lang" => "ar",
                "password" => $password,
            ];
            
            $headers = [
                'User-Agent: okhttp/4.10.0',
                'Connection: Keep-Alive',
                'Accept-Encoding: gzip',
                'Content-Type: application/json; charset=UTF-8'
            ];
            
            $ch = curl_init($signin_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $response_data = json_decode($response, true);
            $user_id = $response_data['SignInUserResult']['UserData']['UserID'] ?? '';
            
            if (empty($user_id)) {
                $result = "❌ فشل تسجيل الدخول - تأكد من البيانات";
                return str_replace('{{CONTENT}}', form_block("أورانج 500MB", $result, "orange_500"), $BASE_HTML);
            }
            
            // Generate Token
            $token_url = "https://services.orange.eg/GetToken.svc/GenerateToken";
            $token_headers = [
                "Content-Type: application/json; charset=UTF-8",
                "Host: services.orange.eg",
                "User-Agent: okhttp/3.14.9"
            ];
            $token_data = '{"channel":{"ChannelName":"MobinilAndMe","Password":"ig3yh*mk5l42@oj7QAR8yF"}}';
            
            $ch = curl_init($token_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $token_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $token_headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $token_response = curl_exec($ch);
            $token_httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $token_data = json_decode($token_response, true);
            $ctv = $token_data['GenerateTokenResult']['Token'] ?? '';
            
            if (empty($ctv)) {
                $result = "❌ فشل في توليد التوكن";
                return str_replace('{{CONTENT}}', form_block("أورانج 500MB", $result, "orange_500"), $BASE_HTML);
            }
            
            $secret = ",{.c][o^uecnlkijh*.iomv:QzCFRcd;drof/zx}w;ls.e85T^#ASwa?=(lk";
            $htv = strtoupper(hash('sha256', $ctv . $secret));
            
            // Redeem
            $redeem_url = "https://services.orange.eg/APIs/Promotions/api/CAF/Redeem";
            $redeem_headers = [
                "_ctv: $ctv",
                "_htv: $htv",
                "isEasyLogin: false",
                "UserId: $user_id",
                "Content-Type: application/json; charset=UTF-8",
                "Host: services.orange.eg",
                "User-Agent: okhttpwhitepro/3.12.1"
            ];
            
            $redeem_json = [
                "Language" => "ar",
                "OSVersion" => "Android7.0",
                "PromoCode" => "رمضان كريم",
                "dial" => $number,
                "password" => $password,
                "Channelname" => "MobinilAndMe",
                "ChannelPassword" => "ig3yh*mk5l42@oj7QAR8yF"
            ];
            
            $ch = curl_init($redeem_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($redeem_json));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $redeem_headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $redeem_response = curl_exec($ch);
            $redeem_httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $resp = json_decode($redeem_response, true);
            $desc = $resp['ErrorDescription'] ?? $resp['Message'] ?? json_encode($resp);
            
            if ($desc == "Success") {
                $result = "✅ تم إرسال طلب 500MB بنجاح";
            } elseif (strpos($desc, "User is redeemed before") !== false || strpos($desc, "You take 500MG before") !== false) {
                $result = "⚠️ حصلت على العرض من قبل";
            } else {
                $result = "ℹ️ " . $desc;
            }
        } catch (Exception $e) {
            $result = "⚠️ خطأ: " . $e->getMessage();
        }
    }
    
    $content = form_block("أورانج 500MB", $result, "orange_500");
    return str_replace('{{CONTENT}}', $content, $BASE_HTML);
}

function orange_1gb() {
    global $BASE_HTML;
    $result = "";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $number = isset($_POST['number']) ? trim($_POST['number']) : '';
        $outputs = [];
        
        // Since PHP doesn't have native threading, we'll make sequential requests
        for ($i = 0; $i < 3; $i++) {
            try {
                $url = "https://api.meridagame.com/api/speedRedeemOffer";
                $payload = ['msisdn' => $number, 'lang' => "ar"];
                $headers = [
                    'User-Agent: Mozilla/5.0 (Linux; Android 13)',
                    'Accept: application/json, text/javascript, */*; q=0.01',
                    'Accept-Encoding: gzip, deflate, br, zstd',
                    'Origin: https://speed.meridagame.com',
                    'X-Requested-With: com.orange.mobinilandmf',
                    'Referer: https://speed.meridagame.com/',
                    'Accept-Language: ar,en-US;q=0.9'
                ];
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 12);
                $response = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpcode == 200) {
                    $j = json_decode($response, true);
                    if (isset($j["data"]["redeemOutputs"]['RedeemErrorDoc']['errDesc'])) {
                        $outputs[] = "🎁 " . $j["data"]["redeemOutputs"]['RedeemErrorDoc']['errDesc'];
                    } else {
                        $outputs[] = $response;
                    }
                } else {
                    $outputs[] = "❌ خطأ: $httpcode";
                }
            } catch (Exception $e) {
                $outputs[] = "❌ خطأ: " . $e->getMessage();
            }
        }
        
        $result = !empty($outputs) ? implode("\n", $outputs) : "⚠️ لا توجد استجابة";
    }
    
    $content = form_block("أورانج 1GB", $result, "orange_1gb");
    return str_replace('{{CONTENT}}', $content, $BASE_HTML);
}

function vodafone_1gb() {
    global $BASE_HTML;
    $result = "";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $number = isset($_POST['number']) ? trim($_POST['number']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        
        try {
            // Get Token
            $token_url = "https://mobile.vodafone.com.eg/auth/realms/vf-realm/protocol/openid-connect/token";
            $payload = [
                'grant_type' => "password",
                'username' => $number,
                'password' => $password,
                'client_secret' => "95fd95fb-7489-4958-8ae6-d31a525cd20a",
                'client_id' => "ana-vodafone-app"
            ];
            
            $headers = [
                'User-Agent: okhttp/4.11.0',
                'Accept: application/json, text/plain, */*',
                'Accept-Encoding: gzip',
                'silentLogin: false',
                'x-agent-operatingsystem: 13',
                'clientId: AnaVodafoneAndroid',
                'Accept-Language: ar',
                'x-agent-device: Xiaomi 21061119AG',
                'x-agent-version: 2024.12.1',
                'x-agent-build: 946',
                'digitalId: 28RI9U7IINOOB'
            ];
            
            $ch = curl_init($token_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $token_response = curl_exec($ch);
            $token_httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $token_data = json_decode($token_response, true);
            $tok = $token_data['access_token'] ?? '';
            
            if (empty($tok)) {
                $result = "❌ فشل تسجيل الدخول - تأكد من البيانات";
                return str_replace('{{CONTENT}}', form_block("فودافون 1GB", $result, "vodafone_1gb"), $BASE_HTML);
            }
            
            // Check Offer
            $check_url = "https://web.vodafone.com.eg/services/dxl/promo/promotion?@type=Promo&$.context.type=massSummerPromo25&$.characteristics%5B@name%3Dparam1%5D.value=0";
            $headers2 = [
                'User-Agent: Mozilla/5.0 (Linux; Android 10)',
                'Accept: application/json',
                'Accept-Encoding: gzip, deflate, br, zstd',
                'sec-ch-ua-platform: "Android"',
                'Authorization: Bearer ' . $tok,
                'Accept-Language: AR',
                'msisdn: ' . $number,
                'clientId: WebsiteConsumer',
                'sec-ch-ua-mobile: ?1',
                'channel: APP_PORTAL',
                'Content-Type: application/json',
                'Referer: https://web.vodafone.com.eg/portal/bf/massSummer25',
            ];
            
            $ch = curl_init($check_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $check_response = curl_exec($ch);
            $check_httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($check_httpcode == 404) {
                $result = "⚠️ قد تكون حصلت على العرض من قبل";
                return str_replace('{{CONTENT}}', form_block("فودافون 1GB", $result, "vodafone_1gb"), $BASE_HTML);
            }
            
            $data = json_decode($check_response, true);
            try {
                $Type = $data[1]["@type"] ?? null;
                $Category = $data[1]["category"] ?? 0;
                $Channel = $data[1]["channel"]["id"] ?? 5;
                $Amount = $data[1]["characteristics"][0]["value"] ?? "1GB";
            } catch (Exception $e) {
                $Type = null;
                $Category = 0;
                $Channel = 5;
                $Amount = "1GB";
            }
            
            // Redeem Offer
            $promo_url = "https://web.vodafone.com.eg/services/dxl/promo/promotion";
            $promo_payload = [
                "@type" => "Promo",
                "channel" => ["id" => $Channel],
                "context" => ["type" => "massSummerPromo25"],
                "pattern" => [[
                    "characteristics" => [
                        ["name" => "numberOfFaces", "value" => $Category],
                        ["name" => "giftId", "value" => $Type]
                    ]
                ]]
            ];
            
            $ch = curl_init($promo_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($promo_payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $promo_response = curl_exec($ch);
            $promo_httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if (in_array($promo_httpcode, [200, 201])) {
                $result = "✅ تم إرسال الطلب بنجاح";
            } else {
                if ($promo_httpcode == 404) {
                    $result = "✅ تم إرسال الطلب (استجابة 404 متوقعة)";
                } else {
                    $result = "❌ خطأ: $promo_httpcode";
                }
            }
        } catch (Exception $e) {
            $result = "⚠️ خطأ: " . $e->getMessage();
        }
    }
    
    $content = form_block("فودافون 1GB", $result, "vodafone_1gb");
    return str_replace('{{CONTENT}}', $content, $BASE_HTML);
}

// Main Router
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'orange_fawazeer':
        echo orange_fawazeer();
        break;
    case 'orange_500':
        echo orange_500();
        break;
    case 'orange_1gb':
        echo orange_1gb();
        break;
    case 'vodafone_1gb':
        echo vodafone_1gb();
        break;
    default:
        echo index();
        break;
}
?>
