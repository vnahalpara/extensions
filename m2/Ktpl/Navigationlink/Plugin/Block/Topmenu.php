<?php 

namespace Ktpl\Navigationlink\Plugin\Block;

use Magento\Framework\UrlInterface;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Store\Model\StoreManagerInterface;

class Topmenu
{
    /**
     * @var NodeFactory
     */
    protected $nodeFactory;
    protected $urlBuilder;
    protected $_storeManager;

    public function __construct(
        UrlInterface $urlBuilder,
        NodeFactory $nodeFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->nodeFactory = $nodeFactory;
        $this->_storeManager = $storeManager;
    }

    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        if($this->getStoreCode() == 'store_id'):
        $productNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Products','products'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $stockistsNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Stockists','stockists'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $ourstoryNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Our Story','ourstory'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $contactsNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Customer Care','contacts'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        /******* contacts's child *******/
        $warrantyRegistrationNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Warranty Registration','warranty-registration'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $faqNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Frequently Asked Questions','faq'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $ourProductGuaranteeNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Our Product Guarantee','our-product-guarantee'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $warrantiesNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Warranties, Repairs & Spare Parts','warranties-repairs-spare-parts'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $termsNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Terms & Conditions','terms-and-conditions'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $privacyPolicyNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Our Privacy Policy','privacy-policy'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $bookNode = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray('Book A Viewing','book-a-viewing'),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );

        $contactsNode->addChild($warrantyRegistrationNode);
        $contactsNode->addChild($faqNode);
        $contactsNode->addChild($ourProductGuaranteeNode);
        $contactsNode->addChild($warrantiesNode);
        $contactsNode->addChild($termsNode);
        $contactsNode->addChild($privacyPolicyNode);
        $contactsNode->addChild($bookNode);
        /******* end contacts's child *******/

        $subject->getMenu()->addChild($productNode);
        $subject->getMenu()->addChild($stockistsNode);
        $subject->getMenu()->addChild($ourstoryNode);
        $subject->getMenu()->addChild($contactsNode);
        endif;
    }

    protected function getNodeAsArray($name,$id)
    {
        return [
            'name' => __($name),
            'id' => $id,
            'url' => $this->urlBuilder->getUrl($id),
            'has_active' => false,
            'is_active' => false // (expression to determine if menu item is selected or not)
        ];
    }

    public function getStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }
}