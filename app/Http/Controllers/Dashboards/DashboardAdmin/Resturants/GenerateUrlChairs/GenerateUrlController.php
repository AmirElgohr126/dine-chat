<?php
namespace App\Http\Controllers\Dashboards\DashboardAdmin\Resturants\GenerateUrlChairs;

use App\Models\Chair;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;


class GenerateUrlController extends Controller
{


    public function ChairsBasedOnRestaurant(Request $request)
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


    public function generateQRCode($restaurantId, $url, $nfcNumber)
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


    public function generateURL($restaurant, $chair)
    {
        $data = [
            'latitude' => $restaurant->latitude,
            'longitude' => $restaurant->longitude,
            'restaurant_id' => $restaurant->id,
            'nfc_number' => $chair->nfc_number,
        ];
        $url = $baseUrl = "https://dinechat.chat/";
        $url = $baseUrl . '?' . http_build_query($data);
        return $url;
    }


}
?>
