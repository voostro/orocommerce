<?php

namespace Oro\Bundle\PayPalBundle\Method\Config;

use Oro\Bundle\PayPalBundle\DependencyInjection\Configuration;
use Oro\Bundle\PayPalBundle\DependencyInjection\OroPayPalExtension;
use Oro\Bundle\PayPalBundle\PayPal\Payflow\Option;
use Oro\Bundle\PaymentBundle\Method\Config\AbstractPaymentConfig;

class PayPalPaymentsProConfig extends AbstractPaymentConfig implements PayflowGatewayConfigInterface
{
    /**
     * @return string
     */
    protected function getPaymentExtensionAlias()
    {
        return OroPayPalExtension::ALIAS;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials()
    {
        return [
            Option\Vendor::VENDOR => $this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_VENDOR_KEY),
            Option\User::USER => $this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_USER_KEY),
            Option\Password::PASSWORD => $this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_PASSWORD_KEY),
            Option\Partner::PARTNER => $this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_PARTNER_KEY),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function isTestMode()
    {
        return (bool)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_TEST_MODE_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getPurchaseAction()
    {
        return (string)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_PAYMENT_ACTION_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthorizationForRequiredAmountEnabled()
    {
        return (bool)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_AUTHORIZATION_FOR_REQUIRED_AMOUNT_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function isZeroAmountAuthorizationEnabled()
    {
        return (bool)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_ZERO_AMOUNT_AUTHORIZATION_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return (string)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_LABEL_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getShortLabel()
    {
        return (string)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_SHORT_LABEL_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedCreditCards()
    {
        return (array)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_ALLOWED_CC_TYPES_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function isRequireCvvEntryEnabled()
    {
        return (bool)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_REQUIRE_CVV_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function isUseProxyEnabled()
    {
        return (bool)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_USE_PROXY_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getProxyHost()
    {
        return (string)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_PROXY_HOST_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getProxyPort()
    {
        return (int)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_PROXY_PORT_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function isSslVerificationEnabled()
    {
        return (bool)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_ENABLE_SSL_VERIFICATION_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function isDebugModeEnabled()
    {
        return (bool)$this->getConfigValue(Configuration::PAYPAL_PAYMENTS_PRO_DEBUG_MODE_KEY);
    }
}