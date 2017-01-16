<?php
class Cammino_Automaticorderemail_Model_Observer {

	const XML_PATH_UPDATE_EMAIL_IDENTITY = 'sales_email/order_comment/identity';

	public function sendOrderEmail(Varien_Event_Observer $observer) {
		$order = $observer->getEvent()->getOrder();
		$storeId = $order->getStore()->getId();
		
		$templateId = 'sales_email_order_template';

		$emailInfo = Mage::getModel('core/email_info');
		$emailInfo->addTo($order->getCustomerEmail(), $order->getCustomerName());

		$mailer = Mage::getModel('core/email_template_mailer');
		$mailer->addEmailInfo($emailInfo);
        $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_IDENTITY, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(array(
                'order'   => $order,
                'billing' => $order->getBillingAddress()
            )
        );

        $mailreturn = $mailer->send();
	}
}
