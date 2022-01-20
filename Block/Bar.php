<?php

namespace LeSite\CustomBar\Block;

use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Model\Context;
use Magento\Customer\Model\GroupRegistry;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context as TemplateContext;
use \Magento\Framework\App\Http\Context as HttpContext;

class Bar extends Template
{
    const CUSTOMER_GROUP_CONFIG_PATH = 'custombar/settings/customer_group';

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;

    /**
     * @var GroupRegistry
     */
    private $groupRegistry;

    /**
     * @param TemplateContext $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        HttpContext $httpContext,
        GroupRegistry $groupRegistry,
        array $data = []
    ) {
        $this->httpContext = $httpContext;
        $this->groupRegistry = $groupRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Get current customer group from http context.
     * Returns null in case we were unable to retrieve customer group
     *
     * @return \Magento\Customer\Model\Group|void
     */
    public function getCurrentCustomerGroup()
    {
        try {
            $group = $this->groupRegistry->retrieve($this->httpContext->getValue(Context::CONTEXT_GROUP));
        } catch (NoSuchEntityException $e) {
            return;
        }

        return $group;
    }

    /**
     * Checks if we should show the message for current customer based on customer group
     *
     * @return bool
     */
    public function isActive()
    {
        $group = $this->getCurrentCustomerGroup();

        if (!$group) {
            return false;
        }

        $configValues = $this->_scopeConfig->getValue(static::CUSTOMER_GROUP_CONFIG_PATH);

        if ($configValues === null) {
            return false;
        }

        $configValues = explode(',', $configValues);

        return in_array($group->getId(), $configValues);
    }

    /**
     * @inheridoc
     */
    public function getCacheKeyInfo()
    {
        // Varnish FPC is already varying by customer group, so we need just regenerate specific block.
        // We add a customer group to block cache keys,
        // and this block will be generated (and cached) for each customer group separately
        // (and the whole page will be cached for each customer group separately)

        return [
            'BLOCK_TPL',
            $this->_storeManager->getStore()->getCode(),
            $this->getTemplateFile(),
            'base_url' => $this->getBaseUrl(),
            'template' => $this->getTemplate(),
            $this->httpContext->getValue(Context::CONTEXT_GROUP)
        ];
    }
}
