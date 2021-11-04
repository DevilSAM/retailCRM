<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use RetailCrm\Api\Interfaces\ClientExceptionInterface;
use RetailCrm\Api\Interfaces\ApiExceptionInterface;
use RetailCrm\Api\Enum\CountryCodeIso3166;
use RetailCrm\Api\Model\Entity\Orders\Items\OrderProduct;
use RetailCrm\Api\Model\Entity\Orders\Order;
use RetailCrm\Api\Model\Request\Orders\OrdersCreateRequest;

class PurchaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Находим товары по бренду и артикулу
     *
     * @return array
     */
    public function buy(Request $request): array
    {
        $host = "https://superposuda.retailcrm.ru/api/v5/store/products/";
        $client = new \GuzzleHttp\Client();
        $product_id = null;
        $total_pages = 100;
        $current_page = 1;
        // перебираем все страницы для указанного бренда
        while ( ($current_page <= $total_pages) && $product_id == null) {
            // получаем результаты
            $res = $client->request('GET', $host, [
                'query' => [
                    'page' => $current_page,
                    'limit' => '100',
                    'apiKey' => 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb',
                    'filter[manufacturer]' => $request->input('brand')
                ],
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Safari/605.1.15'
                ]
            ]);
            // приводим результат к json
            $result = json_decode($res->getBody()->getContents());
            $total_pages = $result->pagination->totalPageCount;
            $current_page += 1;
            // проверяем есть ли тут товар с нужным артикулом
            foreach ($result->products as $product) {
                if ($product->article == $request->input('code')) {
                    $product_id = $product->id;
                    break;
                }
            }
            // ограничим частоту запросов до 5 штук в секунду (по документации можно 10)
            if (!$product_id) usleep(200000);
        }

        // вернем в приложение данные
        $data = [
            'result'  => $result,
            'productId' => $product_id
        ];

        return $data;
    }


    /**
     * Размещаем заказ
     *
     * @return array
     */
    public function placeOrder(Request $request): array
    {
        $client = \RetailCrm\Api\Factory\SimpleClientFactory::createClient('https://superposuda.retailcrm.ru', 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb');
        $host = "https://superposuda.retailcrm.ru/api/v5/orders/create/";
        $data         = new OrdersCreateRequest();
        $order           = new Order();
        $item            = new OrderProduct();

        $order->items           = [$item];
        $order->orderType       = 'fizik';
        $order->orderMethod     = 'test';
        $order->countryIso      = CountryCodeIso3166::RUSSIAN_FEDERATION;
        $order->firstName       = $request->input('first_name');
        $order->lastName        = $request->input('last_name');
        $order->patronymic      = $request->input('patronymic');
        $order->customerComment = $request->input('comment');
        $order->status          = 'trouble';

        // $order->marketplace->code = "test";
        // $order->marketplace->orderId = "7081984";

        $data->order = $order;
        try {
            $response = $client->orders->create($data);
        } catch (ApiExceptionInterface | ClientExceptionInterface $exception) {
            echo $exception; // Every ApiExceptionInterface instance should implement __toString() method.
            exit(-1);
        }

        return ['data' => $response->id];

    }


}