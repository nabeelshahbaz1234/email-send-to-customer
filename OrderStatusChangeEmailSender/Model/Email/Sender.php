<?php
declare(strict_types=1);

namespace RLTSquare\OrderStatusChangeEmailSender\Model\Email;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Area;
use RLTSquare\OrderStatusChangeEmailSender\Helper\Data;

/**
 * Class
 */
class Sender
{
    /**
     * @var TransportBuilder
     */
    private TransportBuilder $transportBuilder;
    /**
     * @var Data
     */
    private Data $templateConfigValue;

    /**
     * @param TransportBuilder $transportBuilder
     * @param Data $templateConfigValue
     */
    public function __construct
    (
        TransportBuilder $transportBuilder,
        Data $templateConfigValue
    ){
        $this->transportBuilder = $transportBuilder;
        $this->templateConfigValue = $templateConfigValue;

    }

    /**
     * @param array $order
     * @param $sendTo
     * @param int $templateId
     * @param null $storeId
     * @return void
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendPendingEmail(array $order, $sendTo, int $templateId = 1, $storeId = null)
    {
        // Sender Name and Email Id
        $sender = [
            'name' => 'J. Junaid Jamshed',
            'email' => $this->templateConfigValue->getPendingPaymentSenderEmail(),
        ];
        if($templateId == 0)
            $templateId = 1;
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $storeId])
            ->setTemplateVars($order)
            ->setFromByScope($sender)
            ->addTo($sendTo)
            ->getTransport();
        $transport->sendMessage();
    }

    /**
     * @param $order
     * @param $sendTo
     * @param int $templateId
     * @param null $storeId
     * @return void
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendNoProductEmail($order, $sendTo, int $templateId = 1, $storeId = null)
    {
        // Sender Name and Email Id
        $sender = [
            'name' => 'J. Junaid Jamshed',
            'email' => $this->templateConfigValue->getNoProductSenderEmail(),
        ];
        if($templateId == 0)
            $templateId = 1;
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $storeId])
            ->setTemplateVars($order)
            ->setFromByScope($sender)
            ->addTo($sendTo)
            ->getTransport();
        $transport->sendMessage();
    }

    /**
     * @param $order
     * @param $sendTo
     * @param int $templateId
     * @param null $storeId
     * @return void
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendIncompleteEmail($order, $sendTo, int $templateId = 1, $storeId = null)
    {
        // Sender Name and Email Id
        $sender = [
            'name' => 'J. Junaid Jamshed',
            'email' => $this->templateConfigValue->getIncompleteSenderEmail(),
        ];
        if($templateId == 0)
            $templateId = 1;
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $storeId])
            ->setTemplateVars($order)
            ->setFromByScope($sender)
            ->addTo($sendTo)
            ->getTransport();
        $transport->sendMessage();
    }

    /**
     * @param $order
     * @param $sendTo
     * @param int $templateId
     * @param null $storeId
     * @return void
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendUnconfirmedEmail($order, $sendTo, int $templateId = 1, $storeId = null)
    {
        // Sender Name and Email Id
        $sender = [
            'name' => 'J. Junaid Jamshed',
            'email' => $this->templateConfigValue->getUnConfirmedSenderEmail(),
        ];
        if($templateId == 0)
            $templateId = 1;
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $storeId])
            ->setTemplateVars($order)
            ->setFromByScope($sender)
            ->addTo($sendTo)
            ->getTransport();
        $transport->sendMessage();
    }

    /**
     * @param $order
     * @param $sendTo
     * @param int $templateId
     * @param null $storeId
     * @return void
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendCanceledEmail($order, $sendTo, int $templateId = 1, $storeId = null)
    {
        // Sender Name and Email Id
        $sender = [
            'name' => 'J. Junaid Jamshed',
            'email' => $this->templateConfigValue->getCanceledSenderEmail(),
        ];
        if($templateId == 0)
            $templateId = 1;
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $storeId])
            ->setTemplateVars($order)
            ->setFromByScope($sender)
            ->addTo($sendTo)
            ->getTransport();
        $transport->sendMessage();
    }

    /**
     * @param $order
     * @param $sendTo
     * @param int $templateId
     * @param null $storeId
     * @return void
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendNewCanceledEmail($order, $sendTo, int $templateId = 1, $storeId = null)
    {
        // Sender Name and Email Id
        $sender = [
            'name' => 'J. Junaid Jamshed',
            'email' => $this->templateConfigValue->getCanceledSenderEmail(),
        ];
        if($templateId == 0)
            $templateId = 1;
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => $storeId])
            ->setTemplateVars($order)
            ->setFromByScope($sender)
            ->addTo($sendTo)
            ->getTransport();
        $transport->sendMessage();
    }
}
