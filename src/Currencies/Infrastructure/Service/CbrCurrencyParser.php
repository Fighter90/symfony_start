<?php

declare(strict_types=1);

namespace App\Currencies\Infrastructure\Service;

use App\Currencies\Infrastructure\DTO\CurrencyDTO;
use App\Currencies\Infrastructure\Service\Interfaces\CurrencyParserInterface;

class CbrCurrencyParser implements CurrencyParserInterface
{
    private const CBR_URL = 'http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL';

    /**
     * @return CurrencyDTO[]
     *
     * @throws \SoapFault
     */
    public function parse(?string $date): array
    {
        $currencies = [];
        $cbr = new \SoapClient(self::CBR_URL, ['soap_version' => SOAP_1_2, 'exceptions' => true]);
        $date = $date ? $date : $cbr->GetLatestDateTime()->GetLatestDateTimeResult;
        $result = $cbr->GetCursOnDateXML(['On_date' => $date]);

        if ($result->GetCursOnDateXMLResult->any) {
            $xml = new \SimpleXMLElement($result->GetCursOnDateXMLResult->any);
            $dateParse = $xml->attributes()->OnDate ? (string) $xml->attributes()->OnDate : null;
            foreach ($xml->ValuteCursOnDate as $currency) {
                $currencies[] = new CurrencyDTO(
                    (string) $currency->VchCode,
                    (int) $currency->Vnom,
                    (float) $currency->Vcurs,
                    (int) $currency->Vcode,
                    (new \DateTime($dateParse))
                );
            }
        }

        return $currencies;
    }
}
