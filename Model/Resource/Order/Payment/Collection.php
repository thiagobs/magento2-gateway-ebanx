<?php
namespace Ebanx\Payments\Model\Resource\Order\Payment;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Zend_Date;

/**
 * Billing agreements resource collection
 */
class Collection extends AbstractCollection
{

    /**
     * Collection initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ebanx\Payments\Model\Order\Payment', 'Ebanx\Payments\Model\Resource\Order\Payment');
    }

    /**
     * @param $orderId
     * @param string $format
     *
     * @return string
     */
    public function getDueDateByOrderId($orderId, $format = 'YYYY-MM-dd HH:mm:ss') {
        $date = $this->addFilter('order_id', $orderId)->getLastItem()->getDataByKey('due_date');
        $dueDate = new Zend_Date($date);
        return $dueDate->get($format);
    }

    /**
     * @param $orderId
     *
     * @return string
     */
    public function getBarCodeByOrderId($orderId) {
        return $this->addFilter('order_id', $orderId)->getLastItem()->getDataByKey('bar_code');
    }

    /**
     * @param $orderId
     *
     * @return string
     */
    public function getPaymentHashByOrderId($orderId) {
        return $this->addFilter('order_id', $orderId)->getLastItem()->getDataByKey('payment_hash');
    }

    /**
     * @param $orderId
     *
     * @return string
     */
    public function getPaymentModeByOrderId($orderId) {
        return $this->addFilter('order_id', $orderId)->getLastItem()->getDataByKey('environment');
    }
}
