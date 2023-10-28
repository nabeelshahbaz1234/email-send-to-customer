<?php
declare(strict_types=1);

namespace RLTSquare\OrderStatusChangeEmailSender\Observer;

use Exception;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use RLTSquare\OrderStatusChangeEmailSender\Helper\Data;
use RLTSquare\OrderStatusChangeEmailSender\Model\Email\Sender;
use Magento\Sales\Model\Order\Email\Container\InvoiceIdentity;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Catalog\Api\ProductRepositoryInterface as ProductFactory;

class CanceledEmailSend implements ObserverInterface
{
    private Sender $sender;
    private Data $templateConfigValue;
    private InvoiceIdentity $identityContainer;
    private PaymentHelper $paymentHelper;
    private Renderer $addressRenderer;
    private ProductFactory $productCollection;
    private Image $imgHelper;

    /**
     * @param Image $imageHelper
     * @param Sender $sender
     * @param Data $templateConfigValue
     * @param InvoiceIdentity $identityContainer
     * @param PaymentHelper $paymentHelper
     * @param Renderer $addressRenderer
     * @param ProductFactory $productCollection
     */
    public function __construct(
        Image           $imageHelper,
        Sender          $sender,
        Data            $templateConfigValue,
        InvoiceIdentity $identityContainer,
        PaymentHelper   $paymentHelper,
        Renderer        $addressRenderer,
        ProductFactory  $productCollection
    ){
        $this->sender = $sender;
        $this->templateConfigValue = $templateConfigValue;
        $this->identityContainer = $identityContainer;
        $this->paymentHelper = $paymentHelper;
        $this->addressRenderer = $addressRenderer;
        $this->productCollection = $productCollection;
        $this->imgHelper = $imageHelper;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $paymentCode = $order->getPayment()->getMethodInstance()->getCode();

        if ($order->getStatus() == 'canceled')
        {
            foreach($order->getItems() as $item){
                $product = $this->productCollection->getById($item->getProductId());
                $imageUrl = $this->imgHelper->init($item->getProduct(), 'small_image',['type'=>'small_image'])->keepAspectRatio(true)->resize('75', '75')->getUrl();
                //$priceChecker = ['price' => $product->getData('price')];
                //if(isset($priceChecker['price'])) {
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
            $email = $order->getBillingAddress()->getEmail();
            $id = $this->templateConfigValue->getCanceledTemplateId();
            $vars=[
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

            if ($this->templateConfigValue->getCanceledEnabled() == 1){
                if ($paymentCode == 'cashondelivery') {
                    $this->sender->sendCanceledEmail($vars, $email, (int)$id, 1);
                }

                if ($paymentCode != 'cashondelivery') {
                    $NewId = $this->templateConfigValue->getNewCanceledTemplateId();
                    $this->sender->sendNewCanceledEmail($vars, $email, (int)$NewId, 1);
                }
            }
        }
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
}
