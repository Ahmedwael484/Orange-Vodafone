from flask import Flask, render_template_string, request
import requests, json, hashlib, threading, os, traceback
import webbrowser

app = Flask(__name__)

BASE_HTML = """
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
                <a href="{{ url_for('index') }}">الرئيسية</a>
                <a href="{{ url_for('orange_fawazeer') }}">أورانج فوازير</a>
                <a href="{{ url_for('orange_500') }}">أورانج 500MB</a>
                <a href="{{ url_for('orange_1gb') }}">أورانج 1GB</a>
                <a href="{{ url_for('vodafone_1gb') }}">فودافون 1GB</a>
            </div>
            {{ content|safe }}
            <div class="footer">مطور بواسطة أحمد وائل © 2024</div>
        </div>
    </div>
</body>
</html>
"""

@app.route("/")
def index():
    content = """
    <form method="post" autocomplete="off">
        <p style="text-align:center;margin:0 0 15px 0;color:rgba(255,255,255,0.8)">
            اختر الخدمة المطلوبة من القائمة
        </p>
    </form>
    """
    return render_template_string(BASE_HTML, content=content)

@app.route("/orange/fawazeer", methods=["GET", "POST"])
def orange_fawazeer():
    result = ""
    if request.method == "POST":
        number = request.form.get("number","").strip()
        password = request.form.get("password","").strip()
        try:
            signin_url = "https://services.orange.eg/SignIn.svc/SignInUser"
            signin_payload = {
                "appVersion": "9.3.0",
                "channel": {"ChannelName": "MobinilAndMe", "Password": "ig3yh*mk5l42@oj7QAR8yF"},
                "dialNumber": number,
                "isAndroid": True,
                "lang": "ar",
                "password": password,
            }
            headers = {'User-Agent': "okhttp/4.10.0",'Connection': "Keep-Alive",'Accept-Encoding': "gzip",'Content-Type': "application/json; charset=UTF-8"}
            r = requests.post(signin_url, data=json.dumps(signin_payload), headers=headers, timeout=15)
            r.raise_for_status()
            token = r.json().get('SignInUserResult', {}).get('AccessToken')
            if not token:
                result = "❌ فشل تسجيل الدخول - تأكد من البيانات"
                return render_template_string(BASE_HTML, content=form_block("أورانج فوازير 250MB", result, "orange_fawazeer"))
            
            gen_url = "https://services.orange.eg/APIs/Profile/api/BasicAuthentication/Generate"
            gen_payload = {
                "ChannelName": "MobinilAndMe",
                "ChannelPassword": "ig3yh*mk5l42@oj7QAR8yF",
                "Dial": number,
                "Language": "ar",
                "Module": "0",
                "Password": password,
            }
            gen_headers = {'User-Agent': "okhttp/4.10.0",'Connection': "Keep-Alive",'Accept-Encoding': "gzip",'Content-Type': "application/json; charset=UTF-8",'AppVersion': "9.3.0",'OsVersion': "14",'IsAndroid': "true",'IsEasyLogin': "false",'Token': token}
            r2 = requests.post(gen_url, data=json.dumps(gen_payload), headers=gen_headers, timeout=15)
            r2.raise_for_status()
            tok = r2.json().get('Token')
            if not tok:
                result = "❌ فشل في توليد التوكن"
                return render_template_string(BASE_HTML, content=form_block("أورانج فوازير 250MB", result, "orange_fawazeer"))
            
            submit_url = "https://services.orange.eg/APIs/Ramadan2024/api/RamadanOffers/Fawazeer/Submit"
            submit_payload = {
                "Dial": number,
                "Language": "ar",
                "Token": tok,
                "Answers": [
                    {"QuestionId": 174, "AnswerId": 812},
                    {"QuestionId": 175, "AnswerId": 815},
                    {"QuestionId": 176, "AnswerId": 818},
                    {"QuestionId": 177, "AnswerId": 823},
                    {"QuestionId": 178, "AnswerId": 829}
                ]
            }
            submit_headers = {'User-Agent': "Mozilla/5.0 (Linux; Android 10)", 'Accept': "application/json, text/plain, */*", 'Content-Type': "application/json",'Origin': "https://services.orange.eg",'Accept-Language': "ar-EG,ar;q=0.9"}
            r3 = requests.post(submit_url, data=json.dumps(submit_payload), headers=submit_headers, timeout=15)
            r3.raise_for_status()
            status = r3.json().get("ErrorDescription", "")
            if status == "FawazeerSuccess":
                result = "✅ تم الحصول على الباقة بنجاح"
            elif status == "GiftCapped":
                result = "⚠️ لقد حصلت على الهدية من قبل"
            else:
                result = f"ℹ️ {status or r3.text}"
        except Exception as e:
            result = "⚠️ خطأ: " + str(e)
    return render_template_string(BASE_HTML, content=form_block("أورانج فوازير 250MB", result, "orange_fawazeer"))

@app.route("/orange/500", methods=["GET", "POST"])
def orange_500():
    result = ""
    if request.method == "POST":
        number = request.form.get("number","").strip()
        password = request.form.get("password","").strip()
        try:
            signin_url = "https://services.orange.eg/SignIn.svc/SignInUser"
            payload = {
              "appVersion": "9.0.0",
              "channel": {"ChannelName": "MobinilAndMe", "Password": "ig3yh*mk5l42@oj7QAR8yF"},
              "dialNumber": number,
              "isAndroid": True,
              "lang": "ar",
              "password": password,
            }
            headers = {'User-Agent': "okhttp/4.10.0",'Connection': "Keep-Alive",'Accept-Encoding': "gzip",'Content-Type': "application/json; charset=UTF-8"}
            r = requests.post(signin_url, data=json.dumps(payload), headers=headers, timeout=15)
            r.raise_for_status()
            user_id = r.json().get('SignInUserResult', {}).get('UserData', {}).get('UserID')
            if not user_id:
                result = "❌ فشل تسجيل الدخول - تأكد من البيانات"
                return render_template_string(BASE_HTML, content=form_block("أورانج 500MB", result, "orange_500"))
            
            token_url = "https://services.orange.eg/GetToken.svc/GenerateToken"
            token_headers = {"Content-Type": "application/json; charset=UTF-8", "Host": "services.orange.eg", "User-Agent": "okhttp/3.14.9"}
            token_data = '{"channel":{"ChannelName":"MobinilAndMe","Password":"ig3yh*mk5l42@oj7QAR8yF"}}'
            rt = requests.post(token_url, headers=token_headers, data=token_data, timeout=15)
            rt.raise_for_status()
            ctv = rt.json().get('GenerateTokenResult', {}).get('Token')
            if not ctv:
                result = "❌ فشل في توليد التوكن"
                return render_template_string(BASE_HTML, content=form_block("أورانج 500MB", result, "orange_500"))
            
            secret = ",{.c][o^uecnlkijh*.iomv:QzCFRcd;drof/zx}w;ls.e85T^#ASwa?=(lk"
            htv = hashlib.sha256((ctv + secret).encode()).hexdigest().upper()
            
            redeem_url = "https://services.orange.eg/APIs/Promotions/api/CAF/Redeem"
            redeem_headers = {
                "_ctv": ctv,
                "_htv": htv,
                "isEasyLogin": "false",
                "UserId": user_id,
                "Content-Type": "application/json; charset=UTF-8",
                "Host": "services.orange.eg",
                "User-Agent": "okhttpwhitepro/3.12.1"
            }
            redeem_json = {
                "Language": "ar",
                "OSVersion": "Android7.0",
                "PromoCode": "رمضان كريم",
                "dial": number,
                "password": password,
                "Channelname": "MobinilAndMe",
                "ChannelPassword": "ig3yh*mk5l42@oj7QAR8yF"
            }
            rr = requests.post(redeem_url, headers=redeem_headers, json=redeem_json, timeout=15)
            rr.raise_for_status()
            resp = rr.json()
            desc = resp.get('ErrorDescription') or resp.get('Message') or str(resp)
            if desc == "Success":
                result = "✅ تم إرسال طلب 500MB بنجاح"
            elif "User is redeemed before" in desc or "You take 500MG before" in desc:
                result = "⚠️ حصلت على العرض من قبل"
            else:
                result = "ℹ️ " + desc
        except Exception as e:
            result = "⚠️ خطأ: " + str(e)
    return render_template_string(BASE_HTML, content=form_block("أورانج 500MB", result, "orange_500"))

@app.route("/orange/1gb", methods=["GET", "POST"])
def orange_1gb():
    result = ""
    if request.method == "POST":
        number = request.form.get("number","").strip()
        outputs = []
        def worker():
            try:
                url = "https://api.meridagame.com/api/speedRedeemOffer"
                payload = {'msisdn': number, 'lang': "ar"}
                headers = {
                    'User-Agent': "Mozilla/5.0 (Linux; Android 13)",
                    'Accept': "application/json, text/javascript, */*; q=0.01",
                    'Accept-Encoding': "gzip, deflate, br, zstd",
                    'Origin': "https://speed.meridagame.com",
                    'X-Requested-With': "com.orange.mobinilandmf",
                    'Referer': "https://speed.meridagame.com/",
                    'Accept-Language': "ar,en-US;q=0.9"
                }
                r = requests.post(url, data=payload, headers=headers, timeout=12)
                if r.status_code == 200:
                    j = r.json()
                    try:
                        msg = j["data"]["redeemOutputs"]['RedeemErrorDoc']['errDesc']
                        outputs.append("🎁 " + str(msg))
                    except Exception:
                        outputs.append(str(j))
                else:
                    outputs.append(f"❌ خطأ: {r.status_code}")
            except Exception as e:
                outputs.append("❌ خطأ: " + str(e))
        try:
            threads = []
            for _ in range(3):
                t = threading.Thread(target=worker)
                t.start()
                threads.append(t)
            for t in threads:
                t.join()
            result = "\n".join(outputs) if outputs else "⚠️ لا توجد استجابة"
        except Exception as e:
            result = "⚠️ خطأ: " + str(e)
    return render_template_string(BASE_HTML, content=form_block("أورانج 1GB", result, "orange_1gb"))

@app.route("/vodafone/1gb", methods=["GET", "POST"])
def vodafone_1gb():
    result = ""
    if request.method == "POST":
        number = request.form.get("number","").strip()
        password = request.form.get("password","").strip()
        try:
            token_url = "https://mobile.vodafone.com.eg/auth/realms/vf-realm/protocol/openid-connect/token"
            payload = {
              'grant_type': "password",
              'username': number,
              'password': password,
              'client_secret': "95fd95fb-7489-4958-8ae6-d31a525cd20a",
              'client_id': "ana-vodafone-app"
            }
            headers = {
              'User-Agent': "okhttp/4.11.0",
              'Accept': "application/json, text/plain, */*",
              'Accept-Encoding': "gzip",
              'silentLogin': "false",
              'x-agent-operatingsystem': "13",
              'clientId': "AnaVodafoneAndroid",
              'Accept-Language': "ar",
              'x-agent-device': "Xiaomi 21061119AG",
              'x-agent-version': "2024.12.1",
              'x-agent-build': "946",
              'digitalId': "28RI9U7IINOOB"
            }
            r = requests.post(token_url, data=payload, headers=headers, timeout=15)
            r.raise_for_status()
            tok = r.json().get('access_token')
            if not tok:
                result = "❌ فشل تسجيل الدخول - تأكد من البيانات"
                return render_template_string(BASE_HTML, content=form_block("فودافون 1GB", result, "vodafone_1gb"))
            
            check_url = ("https://web.vodafone.com.eg/services/dxl/promo/promotion"
                         "?@type=Promo&$.context.type=massSummerPromo25&$.characteristics%5B@name%3Dparam1%5D.value=0")
            headers2 = {
              'User-Agent': "Mozilla/5.0 (Linux; Android 10)",
              'Accept': "application/json",
              'Accept-Encoding': "gzip, deflate, br, zstd",
              'sec-ch-ua-platform': "\"Android\"",
              'Authorization': f"Bearer {tok}",
              'Accept-Language': "AR",
              'msisdn': number,
              'clientId': "WebsiteConsumer",
              'sec-ch-ua-mobile': "?1",
              'channel': "APP_PORTAL",
              'Content-Type': "application/json",
              'Referer': "https://web.vodafone.com.eg/portal/bf/massSummer25",
            }
            r2 = requests.get(check_url, headers=headers2, timeout=15)
            if r2.status_code == 404:
                result = "⚠️ قد تكون حصلت على العرض من قبل"
                return render_template_string(BASE_HTML, content=form_block("فودافون 1GB", result, "vodafone_1gb"))
            r2.raise_for_status()
            data = r2.json()
            try:
                Type = data[1]["@type"]
                Category = data[1]["category"]
                Channel = data[1]["channel"]["id"]
                Amount = data[1]["characteristics"][0]["value"]
            except Exception:
                Type = None
                Category = 0
                Channel = 5
                Amount = "1GB"
            promo_url = "https://web.vodafone.com.eg/services/dxl/promo/promotion"
            payload = {
              "@type": "Promo",
              "channel": {"id": Channel},
              "context": {"type": "massSummerPromo25"},
              "pattern": [{
                "characteristics": [
                  {"name": "numberOfFaces", "value": Category},
                  {"name": "giftId", "value": Type}
                ]
              }]
            }
            r3 = requests.post(promo_url, data=json.dumps(payload), headers=headers2, timeout=15)
            if r3.status_code in (200,201):
                result = "✅ تم إرسال الطلب بنجاح"
            else:
                if r3.status_code == 404:
                    result = "✅ تم إرسال الطلب (استجابة 404 متوقعة)"
                else:
                    result = f"❌ خطأ: {r3.status_code}"
        except Exception as e:
            result = "⚠️ خطأ: " + str(e)
    return render_template_string(BASE_HTML, content=form_block("فودافون 1GB", result, "vodafone_1gb"))

def form_block(title, result_text, endpoint_name):
    needs_password = endpoint_name in ("orange_fawazeer","orange_500","vodafone_1gb")
    pwd_field = '<input name="password" type="password" placeholder="كلمة المرور" required>' if needs_password else ''
    return f"""
    <h2 style="text-align:center;margin:0 0 15px 0;color:#4fc3f7">{title}</h2>
    <form method="post" autocomplete="off">
      <input name="number" type="text" placeholder="رقم الهاتف" required>
      {pwd_field}
      <button type="submit">تشغيل</button>
    </form>
    <div class="result">{(result_text or '')}</div>
    """

if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    url = f"http://127.0.0.1:{port}"
    print(f"جارٍ تشغيل السيرفر على: {url}")
    webbrowser.open(url)
    app.run(host="0.0.0.0", port=port, debug=False)
