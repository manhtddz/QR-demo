<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Generate QR</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    </style>

</head>

<body>
    <h1>QR Code Generator</h1>

    <form method="POST" action="/generate-qr">
        @csrf
        <input type="text" name="content" value="{{ $content ?? '' }}" placeholder="Nhập nội dung">
        <button type="submit">Generate QR</button>
    </form>

    @if (!empty($qrImage))
        <div style="margin-top:20px;">
            <h3>Nội dung: {{ $content }}</h3>
            {!! $qrImage !!}
        </div>
    @endif
    <!-- <script>
        document.getElementById('qrForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Step 1: Upload QR image → get token
            let res = await fetch('/scan-qr', {
                method: 'POST',
                body: formData
            });

            let data = await res.json();
            if (!data.qr_token) {
                document.getElementById('result').innerText = data.error;
            }
        });
    </script> -->



</body>

</html>
