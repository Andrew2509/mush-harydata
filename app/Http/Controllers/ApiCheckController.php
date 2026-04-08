<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiCheckController extends Controller
{
    /**
     * Game slug to Codashop config mapping
     */
    private $gameConfig = [
        'Mobile Legends' => [
            'voucherPricePoint.id' => '4150',
            'voucherPricePoint.price' => '1579.0',
            'voucherTypeName' => 'MOBILE_LEGENDS',
            'needZone' => true,
        ],
        'mobile-legends' => [
            'voucherPricePoint.id' => '4150',
            'voucherPricePoint.price' => '1579.0',
            'voucherTypeName' => 'MOBILE_LEGENDS',
            'needZone' => true,
        ],
        'Free Fire' => [
            'voucherPricePoint.id' => '8050',
            'voucherPricePoint.price' => '1000.0',
            'voucherTypeName' => 'FREEFIRE',
            'needZone' => false,
        ],
        'free-fire' => [
            'voucherPricePoint.id' => '8050',
            'voucherPricePoint.price' => '1000.0',
            'voucherTypeName' => 'FREEFIRE',
            'needZone' => false,
        ],
        'Genshin Impact' => [
            'voucherPricePoint.id' => '116054',
            'voucherPricePoint.price' => '16500.0',
            'voucherTypeName' => 'GENSHIN_IMPACT',
            'needZone' => true,
        ],
        'genshin-impact' => [
            'voucherPricePoint.id' => '116054',
            'voucherPricePoint.price' => '16500.0',
            'voucherTypeName' => 'GENSHIN_IMPACT',
            'needZone' => true,
        ],
        'Honkai Star Rail' => [
            'voucherPricePoint.id' => '855316',
            'voucherPricePoint.price' => '16000.0',
            'voucherTypeName' => 'HONKAI_STAR_RAIL',
            'needZone' => true,
        ],
        'honkai-star-rail' => [
            'voucherPricePoint.id' => '855316',
            'voucherPricePoint.price' => '16000.0',
            'voucherTypeName' => 'HONKAI_STAR_RAIL',
            'needZone' => true,
        ],
        'Valorant' => [
            'voucherPricePoint.id' => '115691',
            'voucherPricePoint.price' => '15000.0',
            'voucherTypeName' => 'VALORANT',
            'needZone' => false,
            'voucherTypeId' => '109',
            'gvtId' => '139',
        ],
        'valorant' => [
            'voucherPricePoint.id' => '115691',
            'voucherPricePoint.price' => '15000.0',
            'voucherTypeName' => 'VALORANT',
            'needZone' => false,
            'voucherTypeId' => '109',
            'gvtId' => '139',
        ],
        'Call of Duty Mobile' => [
            'voucherPricePoint.id' => '46114',
            'voucherPricePoint.price' => '5000.0',
            'voucherTypeName' => 'CALL_OF_DUTY',
            'needZone' => false,
        ],
        'Arena of Valor' => [
            'voucherPricePoint.id' => '7946',
            'voucherPricePoint.price' => '10000.0',
            'voucherTypeName' => 'AOV',
            'needZone' => false,
        ],
    ];

    public function check($user_id = null, $zone_id = null, $game = null)
    {
        $params = [
            'game'    => $game,
            'user_id' => $user_id,
            'zone_id' => $zone_id
        ];

        // Try Codashop API first (more reliable)
        $result = $this->connectCodashop($params);

        // If Codashop fails, fallback to hary.asia
        if (!$result || !isset($result['code']) || $result['code'] != 200) {
            Log::info("Codashop failed, trying hary.asia fallback for game: $game");
            $result = $this->connectHaryAsia($params);
        }

        if ($result && isset($result['code']) && $result['code'] == 200) {
            $username = isset($result['data']['username']) ? $result['data']['username'] : null;

            $data = [
                'status' => ['code' => 200],
                'data' => ['user_id' => $params['user_id']]
            ];

            if (isset($params['zone_id'])) {
                $data['data']['zone_id'] = $params['zone_id'];
            }

            if ($username) {
                $data['data']['username'] = $username;
            }

            return $data;
        } else {
            Log::warning("All API checks failed for game: $game, user: $user_id");
            
            return [
                'status' => ['code' => 1],
                'data' => [
                    'msg' => (isset($result['message']) ? $result['message'] : 'Username tidak ditemukan.')
                ]
            ];
        }
    }

    /**
     * Connect to Codashop API (primary)
     */
    private function connectCodashop($params)
    {
        $game = $params['game'];
        $userId = $params['user_id'];
        $zoneId = $params['zone_id'] ?? null;

        // Find game config
        $config = $this->gameConfig[$game] ?? null;
        
        if (!$config) {
            // Try to find by partial match
            foreach ($this->gameConfig as $key => $val) {
                if (stripos($game, str_replace('-', ' ', $key)) !== false || 
                    stripos(str_replace('-', ' ', $key), $game) !== false) {
                    $config = $val;
                    break;
                }
            }
        }

        if (!$config) {
            Log::info("Codashop: Game '$game' not supported, skipping.");
            return ['code' => 404, 'message' => 'Game tidak didukung oleh Codashop'];
        }

        // Build POST body
        $body = "voucherPricePoint.id={$config['voucherPricePoint.id']}"
            . "&voucherPricePoint.price={$config['voucherPricePoint.price']}"
            . "&voucherPricePoint.variablePrice=0"
            . "&user.userId=" . urlencode($userId);

        if ($config['needZone'] && $zoneId) {
            $body .= "&user.zoneId=" . urlencode($zoneId);
        }

        $body .= "&voucherTypeName={$config['voucherTypeName']}"
            . "&shopLang=id_ID";

        if (isset($config['voucherTypeId'])) {
            $body .= "&voucherTypeId={$config['voucherTypeId']}";
        } else {
            $body .= "&voucherTypeId=1";
        }

        if (isset($config['gvtId'])) {
            $body .= "&gvtId={$config['gvtId']}";
        } else {
            $body .= "&gvtId=1";
        }

        $endpoint = 'https://order-sg.codashop.com/initPayment.action';

        Log::info("Codashop API Request for game: $game, userId: $userId, zone: $zoneId");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            Log::error("Codashop Curl Error: " . $error);
            return ['code' => 500, 'message' => 'Codashop Error: ' . $error];
        }

        Log::info("Codashop Response: " . substr($response, 0, 300));

        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Codashop JSON Error: " . json_last_error_msg());
            return ['code' => 500, 'message' => 'Error parsing Codashop response'];
        }

        // Parse Codashop response
        if (isset($result['success']) && $result['success'] == true) {
            $username = '';
            
            if (isset($result['confirmationFields']['username'])) {
                $username = urldecode(str_replace('+', ' ', $result['confirmationFields']['username']));
            } elseif (isset($result['confirmationFields']['roles'][0]['role'])) {
                $username = $result['confirmationFields']['roles'][0]['role'];
            }

            Log::info("Codashop Username found: $username");

            return [
                'code' => 200,
                'data' => [
                    'username' => $username
                ]
            ];
        } else {
            $errorMsg = $result['errorMsg'] ?? 'User ID tidak ditemukan';
            Log::warning("Codashop: User not found - $errorMsg");
            return ['code' => 404, 'message' => $errorMsg];
        }
    }

    /**
     * Connect to hary.asia API (fallback)
     */
    private function connectHaryAsia($params)
    {
        $game = $params['game'];
        if ($game == 'Free Fire' || $game == 'free-fire') {
            $game = 'freefire-dg';
        }
        $endpoint = "/?slug=" . urlencode($game) . "&hary=rina";

        $query = '&id=' . urlencode($params['user_id']);
        if (isset($params['zone_id'])) {
            $query .= '&zone=' . urlencode($params['zone_id']);
        }

        $url = env('API_URL') . $endpoint . $query;
        Log::info("HaryAsia API URL: " . $url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, env('APP_URL'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($response === false) {
            Log::error("HaryAsia Curl Error: " . $error);
            return ['code' => 500, 'message' => 'Connection Error'];
        }

        Log::info("HaryAsia Response: " . substr($response, 0, 200));

        $result = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("HaryAsia JSON Error: " . json_last_error_msg());
            return ['code' => 500, 'message' => 'Layanan pengecekan sedang gangguan.'];
        }

        return $result;
    }
}
