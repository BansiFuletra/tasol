<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NumberFormatter;
use SellingPartnerApi\Configuration;
use SellingPartnerApi\Endpoint;

class Utility extends Model
{
    use HasFactory;

    public static function getConfig()
    {
        $authentication = [
            "lwaClientId" => "amzn1.application-oa2-client.fe805265beb94630bd3932103bddae3c",
            "lwaClientSecret" => "f06e9383850bb39e310131ec1e540be3123c080500619ff7c83d0324fc465fe8",
            "lwaRefreshToken" => "Atzr|IwEBIGn4cYyh5f2chcP_yccluIARUkKCPzNVcy2uZNfn1ZMqaoHRs8zJ4sglJGCgvMEYdxKRO8qHWXHxs_77uguhh78j6zSQe_FIEokQjujo8OZt2kBQNuPAqaibro9aunKPtoegEmqByT5oWKURCMunZoEm_NgyPfu9iPPD5h6aTNfavIsEHQ2j7wlrxY9e0W1PK_eiBQv5U8lNNqM3oGyA5czuMGTcIhN9eGw9O57FxDLUZv6yD3mDp-gmhUFkj0t-LKo_AtVH7n8EX-kZon71J1ys4Ycu-FhCCaSOFywkkFfe98Cz1Vob_hPjdNK-kuwjYQY",
            "awsAccessKeyId" => "AKIASSGP7BTGHCXHF6EQ",
            "awsSecretAccessKey" => "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",
            "endpoint"           => Endpoint::NA,
            "roleArn"           => "arn:aws:iam::176529804492:role/SPAPI"
        ];

        $config = new Configuration($authentication);

        return $config;
    }

    public static function formatCurrency($currency, $currencyCode)		{
        $fmt = new NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $currency = $fmt->formatCurrency($currency,$currencyCode);
        return $currency;
    }
}
