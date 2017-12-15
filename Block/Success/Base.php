<?php
namespace Ebanx\Payments\Block\Success;

use Ebanx\Payments\Gateway\Http\Client\Api;
use Ebanx\Payments\Model\Resource\Order\Payment\Collection;
use Magento\Checkout\Model\Session;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\OrderFactory;

class Base extends Template
{
    protected $ebanx;

    /**
     * @var \Magento\Sales\Model\Order $order
     */
    protected $_order;

    /**
     * @var int
     */
    protected $_orderId;

    /**
     * @var OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;
    /**
     * @var \Ebanx\Payments\Model\Resource\Order\Payment\Collection
     */
    protected $_ebanxPaymentCollection;

    /**
     * Success constructor.
     *
     * @param Context $context
     * @param Session $checkoutSession
     * @param OrderFactory $orderFactory
     * @param Collection $ebanxPaymentCollection
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $checkoutSession,
        OrderFactory $orderFactory,
        Collection $ebanxPaymentCollection,
        UrlInterface $urlBuilder,
        Api $api,
        array $data = []
    ) {
        $this->_orderId = $checkoutSession->getLastRealOrderId();
        $this->_orderFactory = $orderFactory;
        $this->_ebanxPaymentCollection = $ebanxPaymentCollection;
        $this->_urlBuilder = $urlBuilder;
        $this->ebanx = $api->benjamin();
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getSuccessPaymentBlock()
    {
        return $this->getOrder()->getPayment()->getMethodInstance()->getCode();
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = $this->_orderFactory->create()->loadByIncrementId($this->_orderId);
        }
        return $this->_order;
    }
}
