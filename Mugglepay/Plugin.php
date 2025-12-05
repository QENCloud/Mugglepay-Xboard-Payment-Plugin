<?php

namespace Plugin\Mugglepay;

use App\Services\Plugin\AbstractPlugin;
use App\Contracts\PaymentInterface;

class Plugin extends AbstractPlugin implements PaymentInterface
{
    // 插件基本信息
    public function info(): array
    {
        return [
            'name'        => 'MugglePay（麻瓜宝）',
            'author'      => 'qencloud.com / 适配 XBoard',
            'version'     => '2.0.0',
            'description' => 'USDT/USDC等多种支付方式，无需实名商户'
        ];
    }

    // 注册支付方式
    public function boot(): void
    {
        $this->filter('available_payment_methods', function ($methods) {
            if ($this->getConfig('enabled', true)) {
                $methods['mugglepay'] = [
                    'name'        => 'MugglePay（USDT/USDC）',
                    'icon'        => '💳',
                    'plugin_code' => $this->getPluginCode(),
                    'type'        => 'plugin'
                ];
            }
            return $methods;
        });
    }

    // 后台配置表单
    public function form(): array
    {
        return [
            'app_secret' => [
                'label'       => 'MugglePay App Secret',
                'type'        => 'string',
                'required'    => true,
                'description' => '在 MugglePay 后台 → Developer → App Secret 查看'
            ],
            'enabled' => [
                'label' => '是否启用',
                'type'  => 'switch',
                'value' => true
            ]
        ];
    }

    // 创建支付
    public function pay($order): array
    {
        $params = [
            'merchant_order_id' => $order['trade_no'],
            'price_amount'      => number_format($order['total_amount'] / 100, 2, '.', ''),
            'price_currency'    => 'CNY',
            'title'             => '充值订单 ' . $order['trade_no'],
            'description'       => 'VPS 充值 ' . ($order['total_amount'] / 100) . ' 元',
            'callback_url'      => $order['notify_url'],
            'success_url'       => $order['return_url'],
            'cancel_url'        => $order['return_url']
        ];

        $strToSign = $this->prepareSignId($params['merchant_order_id']);
        $params['token'] = $this->sign($strToSign);

        $result = $this->mprequest($params);
        $paymentUrl = $result['payment_url'] ?? false;

        if (!$paymentUrl) {
            abort(500, 'MugglePay 创建订单失败：' . json_encode($result));
        }

        return [
            'type' => 1,  // 1 = 跳转支付
            'data' => $paymentUrl
        ];
    }

    // 支付回调
    public function notify($params): array|string
    {
        $input = file_get_contents('php://input');
        $data  = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(400, 'Invalid JSON');
        }

        $strToSign = $this->prepareSignId($data['merchant_order_id']);
        if (!$this->verify($strToSign, $data['token'])) {
            abort(400, '签名验证失败');
        }

        if ($data['status'] !== 'PAID') {
            return 'pending';
        }

        return [
            'trade_no'     => $data['merchant_order_id'],
            'callback_no'  => $data['order_id']
        ];
    }

    private function prepareSignId($tradeno)
    {
        $data = [
            'merchant_order_id' => $tradeno,
            'secret'            => $this->getConfig('app_secret'),
            'type'              => 'FIAT'
        ];
        ksort($data);
        return http_build_query($data);
    }

    private function sign($data)
    {
        return strtolower(md5(md5($data) . $this->getConfig('app_secret')));
    }

    private function verify($data, $signature)
    {
        return $this->sign($data) === $signature;
    }

    private function mprequest($data)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'https://api.mugglepay.com/v1/orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'token: ' . $this->getConfig('app_secret')
            ],
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true) ?: [];
    }
}