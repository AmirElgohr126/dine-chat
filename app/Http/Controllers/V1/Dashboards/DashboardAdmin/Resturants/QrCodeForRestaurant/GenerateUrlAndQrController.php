<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Resturants\QrCodeForRestaurant;

use App\Models\Chair;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Illuminate\Http\JsonResponse;

class GenerateUrlAndQrController extends Controller
{


    /**
     * Generate URL and QR code for each chair based on restaurant
     * @param Request $request
     * @return JsonResponse
     */
    public function chairsBasedOnRestaurant(Request $request): JsonResponse
    {
        $chairs = Chair::where('restaurant_id', $request->restaurant_id)->get();
        $restaurant = Restaurant::find($request->restaurant_id);

        $urls = [];
        foreach ($chairs as $chair) {
            $url = $this->generateURL($restaurant, $chair);
            $qrCodeLink = $this->generateQRCode($restaurant->id, $url, $chair->nfc_number);
            $urls[$chair->nfc_number] = [
                'url' => $url,
                'qr_codeLink' => $qrCodeLink
            ];
        }
        return finalResponse('success', 200, $urls);
    }



    /**
     * Generate QR code for each chair
     * @param int $restaurantId
     * @param string $url
     * @param string $nfcNumber
     * @return string
     */
    public function generateQRCode($restaurantId, $url, $nfcNumber) : string
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
            ->logoPath(public_path('storage/Dafaults/Logo/logo.png')) // Make sure this path is correct
            ->logoResizeToWidth(50)
            ->logoPunchoutBackground(true)
            ->labelText("NFC : $nfcNumber") // Example label
            ->labelFont(new NotoSans(20))
            ->labelAlignment(LabelAlignment::Center)
            ->build();

        // Define the path where the QR code will be saved

        $folderPath = storage_path("app/public/restaurant_$restaurantId/Qr_codes/");
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Generate a unique filename for the QR code
        $fileName = 'qr_code_' . $restaurantId . '_' . $nfcNumber . '.png';
        $filePath = $folderPath . $fileName;

        // Save the QR code image to the specified path
        $qrCode->saveToFile($filePath);

        $publicPath = asset("storage/restaurant_$restaurantId/Qr_codes/" . $fileName);

        // Return the file path or URL to access the QR code image
        return $publicPath; // Or return a URL if needed
    }


    /**
     * Generate URL for each chair
     * @param Restaurant $restaurant
     * @param Chair $chair
     * @return string
     */
    public function generateURL($restaurant, $chair) : string
    {
        $data = [
            'type' => 'restaurant',
            'latitude' => $restaurant->latitude,
            'longitude' => $restaurant->longitude,
            'restaurant_id' => $restaurant->id,
            'nfc_number' => $chair->nfc_number,
        ];
        $baseUrl = "https://dinechat.chat/";
        return $baseUrl . '?' . http_build_query($data);
    }


}
