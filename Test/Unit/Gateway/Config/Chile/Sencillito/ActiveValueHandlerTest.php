<?php
namespace DigitalHub\Ebanx\Test\Unit\Gateway\Config\Chile\Sencillito;

use DigitalHub\Ebanx\Gateway\Config\Chile\Sencillito\ActiveValueHandler;

use DigitalHub\Ebanx\Helper\Data;
use Magento\Checkout\Model\Session;
use DigitalHub\Ebanx\Logger\Logger;

class ActiveValueHandlerTest extends \PHPUnit\Framework\TestCase
{

    /**
    * @dataProvider handleDataProvider
    */
    public function testHandle($expectedConfig, $expectedResult)
    {
        $subject = [];
        $storeId = $expectedConfig['storeId'];
        $enabled_payments = $expectedConfig['config_enabled_payments'];

        $expectation = $expectedResult;

        $ebanxHelper = $this->getMockBuilder(Data::class)
            ->setMethods(['getConfigData'])
            ->disableOriginalConstructor()
            ->getMock();
        $session = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $logger = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ebanxHelper->expects($this->any())
            ->method('getConfigData')
            ->withConsecutive(
                ['digitalhub_ebanx_global', 'active', $storeId],
                ['digitalhub_ebanx_global', 'payments_chile', $storeId]
            )
            ->willReturnOnConsecutiveCalls(true, $enabled_payments);

        $valueHandler = new ActiveValueHandler($ebanxHelper, $session, $logger);
        $this->assertEquals(
            $expectation,
            $valueHandler->handle($subject, $storeId)
        );
    }

    public function handleDataProvider()
    {
        return [
            [
                'expectedConfig' => [
                    'storeId' => 1,
                    'config_enabled_payments' => 'servipag,multicaja,sencillito'
                ],
                'expectedResult' => true
            ],
            [
                'expectedConfig' => [
                    'storeId' => 2,
                    'config_enabled_payments' => 'sencillito,servipag'
                ],
                'expectedResult' => true
            ],
            [
                'expectedConfig' => [
                    'storeId' => 1,
                    'config_enabled_payments' => 'multicaja'
                ],
                'expectedResult' => false
            ]
        ];
    }
}
