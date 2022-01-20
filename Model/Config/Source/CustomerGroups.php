<?php

namespace LeSite\CustomBar\Model\Config\Source;

use LeSite\CustomBar\Api\Data\CustomerGroupsSourceInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Convert\DataObject as Converter;

class CustomerGroups implements CustomerGroupsSourceInterface
{
    /**
     * @var GroupManagementInterface
     */
    private $groupManagement;

    /**
     * @var Converter
     */
    private $converter;

    /**
     * @var array
     */
    private $options;

    public function __construct(
        GroupManagementInterface $groupManagement,
        Converter $converter
    ) {
        $this->groupManagement = $groupManagement;
        $this->converter = $converter;
    }

    public function toOptionArray()
    {
        if (!$this->options) {
            $groups = $this->groupManagement->getLoggedInGroups();
            array_unshift($groups, $this->groupManagement->getNotLoggedInGroup());

            $this->options = $this->converter->toOptionArray($groups, 'id', 'code');
        }

        return $this->options;
    }
}
