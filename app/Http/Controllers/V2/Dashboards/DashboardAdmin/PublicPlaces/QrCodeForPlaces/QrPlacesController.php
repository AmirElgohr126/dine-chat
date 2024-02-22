<?php
namespace App\Http\Controllers\V2\Dashboards\DashboardAdmin\PublicPlaces\QrCodeForPlaces;

use App\Models\PublicPlace;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Endroid\QrCode\Builder\Builder;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Label\Font\NotoSans;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;

/**
 * Public Places Controller class contain generate qr code for public places
 */
class QrPlacesController extends Controller
{

    /**
     * generate Qr Code for Public Places
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function generateQrCode(Request $request)
    {
        $request->validate([
            'id_public_place' => ['required', 'exists:public_places,id']
        ]);
        $id = $request->id_public_place;
        $place = PublicPlace::find($id);
        $url = self::buildUrl($place);
        $path = self::generateQr($url, $id);
        $place->qr_link = $url;
        $place->qr_path = $path;
        $place->save();
        return finalResponse('success', 200, $place);
    }


    /**
     * get qr code for restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function getQrCode(Request $request)
    {
        $request->merge(['id' => $request->id]);
        $validated = $request->validate([
            'id' => ['required', 'exists:public_places,id']
        ]);
        $place = PublicPlace::find($validated['id']);
        // Check if the place has a QR code path and if it exists
        if ($place->qr_path && Storage::disk('public')->exists($place->qr_path)) {
            // If you want to return the URL to the QR code
            $qrCodeUrl = retriveMedia().$place->qr_path;
            return finalResponse('success', 200, ['qr_path' => $qrCodeUrl]);
        } else {
            // Handle the case where QR code doesn't exist or path is null
            return finalResponse('failed', 404, null, null, 'QR code not found for the specified place.');
        }
    }

    /**
     * delete Qr Code for Public Places
     * @param \Illuminate\Http\Request $request
     * @return  jsonResponse
     */
    public function deleteQrCode(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:public_places,id',
        ]);
        $place = PublicPlace::find($request->id);

        // Check if QR code path exists and delete the file

        if ($place->qr_path && Storage::disk('public')->exists($place->qr_path)) {
            Storage::disk('public')->delete($place->qr_path);
        }

        // Reset QR code fields in the database
        $place->qr_link = null;
        $place->qr_path = null;
        $place->save();

        return finalResponse('success', 200, null, null, 'QR Code deleted successfully.');
    }



    public static function generateQr($url, $id)
    {
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->logoPath(public_path('storage/Dafaults/Logo/logo.png'))
            ->logoResizeToWidth(50)
            ->logoPunchoutBackground(true)
            ->labelText("") // Example label
            ->labelFont(new NotoSans(20))
            ->labelAlignment(LabelAlignment::Center)
            ->build();
        // Define the path where the QR code will be saved

        $folderPath = storage_path("app/public/public_places/place{$id}/Qr_code/");
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Generate a unique filename for the QR code
        $fileName = 'qr_code' . '.png';
        $filePath = $folderPath . $fileName;

        // Save the QR code image to the specified path
        $qrCode->saveToFile($filePath);

        $publicPath = "public_places/place{$id}/Qr_code/" . $fileName;

        // Return the file path or URL to access the QR code image
        return $publicPath; // Or return a URL if needed
    }


    public static function buildUrl($place)
    {
        $data = [
            'id_public_place' => $place->id,
            'latitude' => $place->latitude,
            'longitude' => $place->longitude,
        ];
        $url = $baseUrl = "https://dinechat.chat/";
        $url = $baseUrl . '?' . http_build_query($data);
        return $url;
    }

}



