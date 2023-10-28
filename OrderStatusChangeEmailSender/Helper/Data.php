<?php
declare(strict_types=1);

namespace RLTSquare\OrderStatusChangeEmailSender\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * @class Data
 */
class Data extends AbstractHelper
{
    public const XML_PATH_PENDING_PAYMENT_EMAIL_TEMPLATE = 'email_section/pending/email_template';
    public const XML_PATH_PENDING_PAYMENT_SENDER_EMAIL_IDENTITY = 'email_section/pending/send_from';
    public const XML_PATH_PENDING_PAYMENT_ENABLED = 'email_section/pending/enabled';
    public const XML_PATH_NO_PRODUCT_EMAIL_TEMPLATE = 'email_section/no_product/email_template';
    public const XML_PATH_NO_PRODUCT_SENDER_EMAIL_IDENTITY = 'email_section/no_product/sender';
    public const XML_PATH_NO_PRODUCT_CUSTOM_MESSAGE = 'email_section/no_product/message';
    public const XML_PATH_NO_PRODUCT_ENABLED = 'email_section/no_product/enabled';
    public const XML_PATH_INCOMPLETE_EMAIL_TEMPLATE = 'email_section/incomplete/email_template';
    public const XML_PATH_INCOMPLETE_SENDER_EMAIL_IDENTITY = 'email_section/incomplete/sender';
    public const XML_PATH_INCOMPLETE_CUSTOM_MESSAGE = 'email_section/incomplete/message';
    public const XML_PATH_INCOMPLETE_ENABLED = 'email_section/incomplete/enabled';
    public const XML_PATH_UNCONFIRMED_EMAIL_TEMPLATE = 'email_section/unconfirmed/email_template';
    public const XML_PATH_UNCONFIRMED_SENDER_EMAIL_IDENTITY = 'email_section/unconfirmed/sender';
    public const XML_PATH_UNCONFIRMED_CUSTOM_MESSAGE = 'email_section/unconfirmed/message';
    public const XML_PATH_UNCONFIRMED_ENABLED = 'email_section/unconfirmed/enabled';
    public const XML_PATH_CANCELED_EMAIL_TEMPLATE = 'email_section/canceled/email_template';
    public const XML_PATH_NEW_CANCELED_EMAIL_TEMPLATE = 'email_section/canceled/new_email_template';
    public const XML_PATH_CANCELED_SENDER_EMAIL_IDENTITY = 'email_section/canceled/send_from';
    public const XML_PATH_CANCELED_ENABLED = 'email_section/canceled/enabled';

    /**
     * Get Pending Payment email_template value.
     * @return string
     */
    public function getPendingPaymentTemplateId(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_PENDING_PAYMENT_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Pending Payment Sender Email value
     * @return string
     */
    public function getPendingPaymentSenderEmail(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_PENDING_PAYMENT_SENDER_EMAIL_IDENTITY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Pending Payment Enabled value
     * @return string
     */
    public function getPendingPaymentEnabled(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_PENDING_PAYMENT_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get No Product email_template value.
     * @return string
     */
    public function getNoProductTemplateId(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_NO_PRODUCT_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get No Product Sender Email value
     * @return string
     */
    public function getNoProductSenderEmail(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_NO_PRODUCT_SENDER_EMAIL_IDENTITY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get No Product Custom Message value
     * @return string
     */
    public function getNoProductCustomMessage(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_NO_PRODUCT_CUSTOM_MESSAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get No Product Enabled value
     * @return string
     */
    public function getNoProductEnabled(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_NO_PRODUCT_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Incomplete email_template value.
     * @return string
     */
    public function getIncompleteTemplateId(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_INCOMPLETE_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Incomplete Sender Email value
     * @return string
     */
    public function getIncompleteSenderEmail(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_INCOMPLETE_SENDER_EMAIL_IDENTITY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Incomplete Enabled value
     * @return string
     */
    public function getIncompleteEnabled(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_INCOMPLETE_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Unconfirmed email_template value.
     * @return string
     */
    public function getUnConfirmedTemplateId(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_UNCONFIRMED_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Unconfirmed Sender Email value
     * @return string
     */
    public function getUnConfirmedSenderEmail(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_UNCONFIRMED_SENDER_EMAIL_IDENTITY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Unconfirmed Custom Message value
     * @return string
     */
    public function getUnConfirmedCustomMessage(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_UNCONFIRMED_CUSTOM_MESSAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Unconfirmed Enabled value
     * @return string
     */
    public function getUnConfirmedEnabled(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_UNCONFIRMED_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Canceled email_template value.
     * @return string
     */
    public function getCanceledTemplateId(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_CANCELED_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get New Canceled email_template value.
     * @return string
     */
    public function getNewCanceledTemplateId(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_NEW_CANCELED_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Canceled Sender Email value
     * @return string
     */
    public function getCanceledSenderEmail(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_CANCELED_SENDER_EMAIL_IDENTITY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Canceled Enabled value
     * @return string
     */
    public function getCanceledEnabled(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_CANCELED_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

}
