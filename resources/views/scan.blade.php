<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>QRスキャン｜契約照会</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')

    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto;
            padding: 24px;
        }

        .status {
            font-size: 28px;
            font-weight: 700;
            margin: 8px 0;
        }

        .ok {
            color: #16a34a;
        }

        .warn {
            color: #ca8a04;
        }

        .bad {
            color: #dc2626;
        }

        #codeInput {
            position: absolute;
            left: -9999px;
        }

        /* 隠し入力 */
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px #0000000f;
        }

        #reader {
            width: 300px;
            margin: auto;
        }
    </style>

</head>

<body>
    <h1>契約照会（QRスキャン）</h1>
    <p>QRリーダーで読み取ってください（Enter終端推奨）。</p>
    {{-- <form id="qrForm" action="/scan-qr" method="post" enctype="multipart/form-data"> --}}
        {{-- @csrf --}}
        <input type="text" name="scanner-input" id="scanner-input" autofocus>
        {{-- <button type="submit">Scan</button> --}}

    {{-- </form> --}}
    <h1>QR Code Scanner</h1>
    {{-- <div id="reader"></div> --}}

    <div id="formArea"></div>
    <div id="result"></div>
    <script>
        let buffer = "";

        const input = document.getElementById('scanner-input');

        input.addEventListener('keydown', e => {
            if (e.key === "Enter") {
                e.preventDefault();

                const value = buffer || input.value;
                input.textContent = value;
                console.log("HID QR:", value);

                fetch("/scan-qr-by-camera", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        qr: value
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    console.log("API response:", data);
                    if (data.ok) {
                        alert("Found customer: " + data);
                    } else {
                        alert("Error: " + data);
                    }
                })
                .catch(err => console.error("Fetch error:", err));

                // reset để scan tiếp
                buffer = "";
                input.value = "";
            }
        })

        // document.getElementById('qrForm').addEventListener('submit', async function(e) {
        //     e.preventDefault();

        //     const formData = new FormData(this);

        //     // Step 1: Upload QR image → get token
        //     let res = await fetch('/scan-qr', {
        //         method: 'POST',
        //         body: formData
        //     });

        //     let data = await res.json();
        //     if (!data.qr_token) {
        //         document.getElementById('result').innerText = data.error;
        //     }
        // });
    </script>

    {{-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {

            document.getElementById('result').innerText = decodedText;
            console.log(decodedText);

            fetch("/scan-qr-by-camera", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        qr: decodedText
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    console.log("API response:", data);
                    if (data.ok) {
                        alert("Found customer: " + data);
                    } else {
                        alert("Error: " + data);
                    }
                })
                .catch(err => console.error("Fetch error:", err));

            html5QrcodeScanner.clear();
        }

        function onScanError(errorMessage) {
            // lỗi đọc QR (có thể bỏ qua)
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 20,
                qrbox: 250
            },
            false);
        html5QrcodeScanner.render(onScanSuccess, onScanError);
    </script> --}}
    {{-- <script>
        Html5Qrcode.getCameras()
            .then((devices) => {
                console.log(devices);
            })
            .catch((err) => )
    </script> --}}

</body>

</html>
