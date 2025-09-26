<?php

namespace App\Http\Controllers;

use App\Events\QRScan;
use App\Events\Scan;
use App\Models\Contract;
use DB;
use Illuminate\Http\Request;
use Zxing\QrReader;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class QrScanController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'qr' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Lưu file tạm
        $path = $request->file('qr_image')->getRealPath();

        // Dùng QrReader để decode
        $qrcode = new QrReader($path);
        $token = $qrcode->text();

        if (!$token) {
            return response()->json(['error' => 'Không đọc được QR code'], 400);
        }

        DB::table('logs')->insert([
            'token' => $token,
        ]);

        event(new QRScan($token));

        return response()->json([
            'qr_token' => $token
        ]);
    }

    public function scan(Request $request)
    {
        $qr = $request->input('qr'); // dữ liệu chuỗi từ QR

        if (!$qr) {
            return response()->json(['ok' => false, 'msg' => 'No QR data'], 400);
        }

        // nếu QR là mã đã mã hoá bằng Crypt
        // try {
        //     $decrypted = Crypt::decryptString(base64_decode(strtr($qr, '-_', '+/')));
        //     $payload = json_decode($decrypted, true);
        //     $id = $payload['content'] ?? null;
        // } catch (\Exception $e) {
        //     return response()->json(['ok' => false, 'msg' => 'Invalid QR'], 400);
        // }
        $id = $qr;
        if (!$id) {
            return response()->json(['ok' => false, 'msg' => 'Invalid payload'], 400);
        }


        // ví dụ query DB theo id
        $customer = DB::table('customers')->find($id);
        if (!$customer) {
            return response()->json(['ok' => false, 'msg' => 'Customer not found'], 404);
        }

        event(new QRScan($id));

        return response()->json([
            'ok' => true,
            'id' => $id
        ]);
    }

    public function getContractByCustomerId($id)
    {
        $contracts = Contract::where('customer_id', $id)->get();

        if ($contracts) {
            return response()->json([
                'status' => true,
                'contracts' => $contracts
            ]);
        }
        return response()->json([
            'status' => false,
        ]);

    }

    public function processToken(Request $request)
    {
        $contract = Contract::find($request->input('id'));

        if ($contract) {
            return response()->json([
                'status' => true,
                'contract' => $contract
            ]);
        }
        return response()->json([
            'status' => false,
        ]);

    }

    public function handleCreateQR(Request $request)
    {
        $content = $request->input('content');
        $qrString = $this->_issueQr($content);

        $qrImage = QrCode::size(300)->generate($qrString);

        return view('qr_form', compact('content', 'qrImage'));
    }

    private function _issueQr($content)
    {
        // payload có thể gồm id + expire time
        $payload = json_encode([
            'content' => $content,
            'exp' => now()->addDays(1)->timestamp
        ]);

        // mã hoá payload
        $encrypted = Crypt::encryptString($payload);

        // encode thành URL-safe base64 để QR gọn gàng
        $qrString = rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');

        // sinh QR
        return $qrString;
    }
}
