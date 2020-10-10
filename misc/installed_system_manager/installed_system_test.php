<?php

   require_once($_SERVER['DOCUMENT_ROOT'] .'/app/common/conf/main.conf.php');
   require_once($_SERVER['DOCUMENT_ROOT'] .'/app/installed_system/InstalledSystem.class.php');
   require_once($_SERVER['DOCUMENT_ROOT'] .'/app/installed_system/INOVA.conf.php');
   require_once(COMMON_CLASS_DIR          .'/Document.class.php');
   require_once(USER_CLASS);

   $thisUser = new User();

   // Checks the user authentication
   if($thisUser->isAuthenticated())
   {

      // checking load operation get methods
      $id=getUserField('id');
      $params['id']=$id;
      $anInstalledSystem= new InstalledSystem($params);

      echo $anInstalledSystem->getId()."\n";
      echo $anInstalledSystem->getCustomerId()."\n";
      echo $anInstalledSystem->getItSystemId()."\n";
      echo $anInstalledSystem->getCategoryId()."\n";
      echo $anInstalledSystem->getStatus()."\n";
      echo $anInstalledSystem->getVendor()."\n";
      echo $anInstalledSystem->getModel()."\n";
      echo $anInstalledSystem->getSerialNumber()."\n";
      echo $anInstalledSystem->getDateInstalled()."\n";
      echo $anInstalledSystem->getDateLastServiced()."\n";
      echo $anInstalledSystem->getNextMaintDate()."\n";
      echo $anInstalledSystem->getContractRenewalDate()."\n";
      echo $anInstalledSystem->getContractType()."\n";
      echo $anInstalledSystem->getPurchasePrice()."\n";
      echo $anInstalledSystem->getServiceContract()."\n";
      echo $anInstalledSystem->getWarrantyExpiration()."\n";
      echo $anInstalledSystem->getComments()."\n";

      echo '<br>';

      // checking set methods
      $anInstalledSystem->setCustomerId(2);
      $anInstalledSystem->setItSystemId(2);
      $anInstalledSystem->setCategoryId(2);
      $anInstalledSystem->setStatus('chaned status');
      $anInstalledSystem->setVendor('changed vendor');
      $anInstalledSystem->setModel('chaned model');
      $anInstalledSystem->setSerialNumber('2');
      $anInstalledSystem->setDateInstalled('1979-06-22');
      $anInstalledSystem->setDateLastServiced('1979-06-22');
      $anInstalledSystem->setNextMaintDate('1979-06-22');
      $anInstalledSystem->setContractRenewalDate('1979-06-22');
      $anInstalledSystem->setContractType('changed type');
      $anInstalledSystem->setPurchasePrice(2);
      $anInstalledSystem->setServiceContract('changed serviceContract');
      $anInstalledSystem->setWarrantyExpiration('1979-06-22');
      $anInstalledSystem->setComments('changed comments');

      echo $anInstalledSystem->getId()."\n";
      echo $anInstalledSystem->getCustomerId()."\n";
      echo $anInstalledSystem->getItSystemId()."\n";
      echo $anInstalledSystem->getCategoryId()."\n";
      echo $anInstalledSystem->getStatus()."\n";
      echo $anInstalledSystem->getVendor()."\n";
      echo $anInstalledSystem->getModel()."\n";
      echo $anInstalledSystem->getSerialNumber()."\n";
      echo $anInstalledSystem->getDateInstalled()."\n";
      echo $anInstalledSystem->getDateLastServiced()."\n";
      echo $anInstalledSystem->getNextMaintDate()."\n";
      echo $anInstalledSystem->getContractRenewalDate()."\n";
      echo $anInstalledSystem->getContractType()."\n";
      echo $anInstalledSystem->getPurchasePrice()."\n";
      echo $anInstalledSystem->getServiceContract()."\n";
      echo $anInstalledSystem->getWarrantyExpiration()."\n";
      echo $anInstalledSystem->getComments()."\n";

      echo '<br>';

      // checking load method without supplying argument
      $anInstalledSystem->load();
      echo $anInstalledSystem->getId()."\n";
      echo $anInstalledSystem->getCustomerId()."\n";
      echo $anInstalledSystem->getItSystemId()."\n";
      echo $anInstalledSystem->getCategoryId()."\n";
      echo $anInstalledSystem->getStatus()."\n";
      echo $anInstalledSystem->getVendor()."\n";
      echo $anInstalledSystem->getModel()."\n";
      echo $anInstalledSystem->getSerialNumber()."\n";
      echo $anInstalledSystem->getDateInstalled()."\n";
      echo $anInstalledSystem->getDateLastServiced()."\n";
      echo $anInstalledSystem->getNextMaintDate()."\n";
      echo $anInstalledSystem->getContractRenewalDate()."\n";
      echo $anInstalledSystem->getContractType()."\n";
      echo $anInstalledSystem->getPurchasePrice()."\n";
      echo $anInstalledSystem->getServiceContract()."\n";
      echo $anInstalledSystem->getWarrantyExpiration()."\n";
      echo $anInstalledSystem->getComments()."\n";

      echo '<br>';

      // checking update operation
      $anInstalledSystem->setCustomerId(2);
      $anInstalledSystem->setItSystemId(2);
      $anInstalledSystem->setCategoryId(2);
      $anInstalledSystem->setStatus('chaned status');
      $anInstalledSystem->setVendor('changed vendor');
      $anInstalledSystem->setModel('chaned model');
      $anInstalledSystem->setSerialNumber('2');
      $anInstalledSystem->setDateInstalled('1979-06-22');
      $anInstalledSystem->setDateLastServiced('1979-06-22');
      $anInstalledSystem->setNextMaintDate('1979-06-22');
      $anInstalledSystem->setContractRenewalDate('1979-06-22');
      $anInstalledSystem->setContractType('changed type');
      $anInstalledSystem->setPurchasePrice(2);
      $anInstalledSystem->setServiceContract('changed serviceContract');
      $anInstalledSystem->setWarrantyExpiration('1979-06-22');
      $anInstalledSystem->setComments('changed comments');

      $anInstalledSystem->update();

      // checking add operation with a blank object
      $anotherInstalledSystem = new InstalledSystem();

      $anotherInstalledSystem->setCustomerId(2);
      $anotherInstalledSystem->setItSystemId(2);
      $anotherInstalledSystem->setCategoryId(2);
      $anotherInstalledSystem->setStatus('chaned status');
      $anotherInstalledSystem->setVendor('changed vendor');
      $anotherInstalledSystem->setModel('chaned model');
      $anotherInstalledSystem->setSerialNumber('2');
      $anotherInstalledSystem->setDateInstalled('1979-06-22');
      $anotherInstalledSystem->setDateLastServiced('1979-06-22');
      $anotherInstalledSystem->setNextMaintDate('1979-06-22');
      $anotherInstalledSystem->setContractRenewalDate('1979-06-22');
      $anotherInstalledSystem->setContractType('changed type');
      $anotherInstalledSystem->setPurchasePrice(2);
      $anotherInstalledSystem->setServiceContract('changed serviceContract');
      $anotherInstalledSystem->setWarrantyExpiration('1979-06-22');
      $anotherInstalledSystem->setComments('changed comments');
      $anotherInstalledSystem->add();

      // checking add operation supplying parameters
      $params['customerId']             = 3;
      $params['categoryID']             = 3;
      $params['status']                 = 'new';
      $params['vendor']                 = 'new';
      $params['model']                  = 'new';
      $params['serialNumber']           = '101';
      $params['dateInstalled']          = '2006-01-01';
      $params['dateLastServiced']       = '2006-01-01';
      $params['nextScheduledMaintDate'] = '2006-01-01';
      $params['contractRenewalDate']    = '2006-01-01';
      $params['contractType']           = 'new';
      $params['purchasePrice']          = 'new';
      $params['serviceContract']        = 'new';
      $params['comments']               = 'new';
      $params['ITSystemID']             = 1;
      $params['warrantyExpirationDate'] = '2006-01-01';

      $thirdInstalledSystem = new InstalledSystem($params);
      $thirdInstalledSystem->add();

      // checking delete operation
      $thirdInstalledSystem->delete();


   }
   else
   {
      $thisUser->goLogin();
   }

?>

