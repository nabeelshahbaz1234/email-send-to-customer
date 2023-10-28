<?php
declare(strict_types=1);

namespace RLTSquare\OrderStatusChangeEmailSender\Observer;

use Exception;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use RLTSquare\OrderStatusChangeEmailSender\Helper\Data;
use RLTSquare\OrderStatusChangeEmailSender\Model\Email\Sender;
use Magento\Sales\Model\Order\Email\Container\InvoiceIdentity;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Catalog\Api\ProductRepositoryInterface as ProductFactory;

class OrderStatusEmailSend implements ObserverInterface
{

    /**
     * @var OrderSender
     */
    protected OrderSender $orderSender;
    /**
     * @var Data
     */
    private Data $templateConfigValue;
    /**
     * @var Sender
     */
    private Sender $sender;
    /**
     * @var InvoiceIdentity
     */
    private InvoiceIdentity $identityContainer;
    /**
     * @var PaymentHelper
     */
    private PaymentHelper $paymentHelper;
    /**
     * @var Renderer
     */
    private Renderer $addressRenderer;
    /**
     * @var ProductFactory
     */
    private ProductFactory $productCollection;
    private \Magento\Catalog\Helper\Image $imgHelper;


    /**
     * @param Image $imageHelper
     * @param OrderSender $orderSender
     * @param Sender $sender
     * @param Data $templateConfigValue
     * @param InvoiceIdentity $identityContainer
     * @param PaymentHelper $paymentHelper
     * @param Renderer $addressRenderer
     * @param ProductFactory $productCollection
     */
    public function __construct(
        \Magento\Catalog\Helper\Image $imageHelper,
        OrderSender         $orderSender,
        Sender              $sender,
        Data                $templateConfigValue,
        InvoiceIdentity     $identityContainer,
        PaymentHelper       $paymentHelper,
        Renderer            $addressRenderer,
        ProductFactory      $productCollection
    ){
        $this->imgHelper = $imageHelper;
        $this->sender = $sender;
        $this->orderSender = $orderSender;
        $this->templateConfigValue = $templateConfigValue;
        $this->identityContainer = $identityContainer;
        $this->paymentHelper = $paymentHelper;
        $this->addressRenderer = $addressRenderer;
        $this->productCollection = $productCollection;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        if(!isset($_GET['namespace']) && !isset($_GET['filters']))
        {
            $order = $observer->getEvent()->getOrder();
            $paymentCode = $order->getPayment()->getMethodInstance()->getCode();
            $idPending = $this->templateConfigValue->getPendingPaymentTemplateId();
            $idNoProduct = $this->templateConfigValue->getNoProductTemplateId();
            $idIncomplete = $this->templateConfigValue->getIncompleteTemplateId();
            $idUnconfirmed = $this->templateConfigValue->getUnConfirmedTemplateId();
            $idCanceled = $this->templateConfigValue->getCanceledTemplateId();
            $email = $order->getBillingAddress()->getEmail();

            foreach ($order->getItems() as $item) {
                $product = $this->productCollection->getById($item->getProductId());
                $imageUrl = $this->imgHelper->init($item->getProduct(), 'small_image', ['type' => 'small_image'])->keepAspectRatio(true)->resize('75', '75')->getUrl();
                //$priceChecker = ['price' => $product->getData('price')];
                //if (isset($priceChecker['price'])) {
                if ($product->getTypeId() == 'simple'){
                    $productdata[] = [
                        'name' => $product->getData('name'),
                        'sku' => $product->getData('sku'),
                        'price' => number_format((float)$product->getData('price'), 2, '.', ''),
                        'qty_ordered' => number_format((float)$item->getQtyOrdered(), 2, '.', ''),
                        'product_image' => $imageUrl
                    ];
                }
            }
            $vars = [
                'order' => $order,
                'order_id' => $order->getId(),
                'billing' => $order->getBillingAddress(),
                'increment_id' => $order->getIncrementId(),
                'description' => $order->getShippingDescription(),
                'payment_html' => $this->getPaymentHtml($order),
                'store' => $order->getStore(),
                'create' => $order->getCreatedAtFormatted(1),
                'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
                'formattedBillingAddress' => $this->getFormattedBillingAddress($order),
                'customer_name' => $order->getCustomerName(),
                'virtual' => $order->getIsNotVirtual(),
                'note' => $order->getEmailCustomerNote(),
                'discount_amount' => number_format((float)$order->getBaseDiscountAmount(), 2, '.', ''),
                'shipping_amount' => number_format((float)$order->getBaseShippingAmount(), 2, '.', ''),
                'grand_total' => number_format((float)$order->getBaseGrandTotal(), 2, '.', ''),
                'sub_total' => number_format((float)$order->getSubtotal(), 2, '.', ''),
                'items' => ($productdata ?? [])
            ];

            if ($order->getStatus() == 'pending_payment') {
                if ($this->templateConfigValue->getPendingPaymentEnabled() == 1) {
                    if ($order->getOrderCancelReason() == null && $order->getOrderCancelDescription() == null) {
                        $this->sender->sendPendingEmail($vars, $email, (int)$idPending, 1);
                    }
                }
            }

            if ($order->getStatus() == 'productnotavailable') {
                if ($this->templateConfigValue->getNoProductEnabled() == 1) {
                    if ($order->getOrderCancelReason() == null && $order->getOrderCancelDescription() == null) {
                        $this->sender->sendNoProductEmail($vars, $email, (int)$idNoProduct, 1);
                    }
                }
            }

            if ($order->getStatus() == 'unconfirmed_1' || $order->getStatus() == 'unconfirmed_2' || $order->getStatus() == 'unconfirmed_3') {
                if ($paymentCode == 'cashondelivery') {
                    if ($this->templateConfigValue->getUnConfirmedEnabled() == 1) {
                        if ($order->getOrderCancelReason() == null && $order->getOrderCancelDescription() == null) {
                            $this->sender->sendUnconfirmedEmail($vars, $email, (int)$idUnconfirmed, 1);
                        }
                    }
                }
            }

            if ($order->getStatus() == 'canceled') {
                if ($this->templateConfigValue->getCanceledEnabled() == 1) {
                    if ($order->getOrderCancelReason() == null && $order->getOrderCancelDescription() == null) {
                        if ($order->getState() != 'canceled') {
                            $this->sender->sendCanceledEmail($vars, $email, (int)$idCanceled, 1);
                            $order->registerCancellation();
                        }
                    }
                }
            }

            if ($order->getStatus() == 'under_incomplete') {
                foreach ($order->getItems() as $item) {
                    $product = $this->productCollection->getById($item->getProductId());
                    $imageUrl = $this->imgHelper->init($item->getProduct(), 'small_image', ['type' => 'small_image'])->keepAspectRatio(true)->resize('75', '75')->getUrl();


                    if (!$product->getData('quantity_and_stock_status')['is_in_stock']) {
                        $data[] = [
                            'name' => $product->getData('name'),
                            'price' => number_format((float)$product->getData('price'), 2, '.', ''),
                            'qty_ordered' => number_format((float)$item->getQtyOrdered(), 2, '.', ''),
                            'product_image' => $imageUrl
                        ];
                    }
                }
                if (!(isset($data))) {
                    $data = [];
                }

                $var = [
                    'order' => $order,
                    'order_id' => $order->getId(),
                    'billing' => $order->getBillingAddress(),
                    'increment_id' => $order->getIncrementId(),
                    'description' => $order->getShippingDescription(),
                    'payment_html' => $this->getPaymentHtml($order),
                    'store' => $order->getStore(),
                    'create' => $order->getCreatedAtFormatted(1),
                    'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
                    'formattedBillingAddress' => $this->getFormattedBillingAddress($order),
                    'customer_name' => $order->getCustomerName(),
                    'virtual' => $order->getIsNotVirtual(),
                    'note' => $order->getEmailCustomerNote(),
                    'discount_amount' => number_format((float)$order->getBaseDiscountAmount(), 2, '.', ''),
                    'shipping_amount' => number_format((float)$order->getBaseShippingAmount(), 2, '.', ''),
                    'grand_total' => number_format((float)$order->getBaseGrandTotal(), 2, '.', ''),
                    'sub_total' => number_format((float)$order->getSubtotal(), 2, '.', ''),
                    'items' => $data
                ];

                if ($data) {
                    if ($this->templateConfigValue->getIncompleteEnabled() == 1) {
                        if ($order->getOrderCancelReason() == null && $order->getOrderCancelDescription() == null) {
                            $this->sender->sendIncompleteEmail($var, $email, (int)$idIncomplete, 1);
                        }
                    }
                }
            }
        }
    }

    /**
     * Return payment info block as html
     *
     * @param $order
     * @return string
     * @throws Exception
     */
    protected function getPaymentHtml($order)
    {
        return $this->paymentHelper->getInfoBlockHtml(
            $order->getPayment(),
            $this->identityContainer->getStore()->getStoreId()
        );
    }

    /**
     * Render shipping address into html.
     *
     * @param $order
     * @return string|null
     */
    protected function getFormattedShippingAddress($order)
    {
        return $order->getIsVirtual()
            ? null
            : $this->addressRenderer->format($order->getShippingAddress(), 'html');
    }

    /**
     * Render billing address into html.
     *
     * @param $order
     * @return string|null
     */
    protected function getFormattedBillingAddress($order)
    {
        return $this->addressRenderer->format($order->getBillingAddress(), 'html');
    }

}