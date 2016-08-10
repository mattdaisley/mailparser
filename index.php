<?php

include_once 'MailParser.class.php';

$strMessagesDir = "messages/";

$arrMessages = array_diff(scandir($strMessagesDir), array('..', '.'));

if ($fp = fopen('output.txt', 'w')) {

  foreach ($arrMessages as $strFileName) {

    $mailParser = new MailParser();

    fwrite($fp, $strFileName . "\n");

    $mailParser->setFileName( $strMessagesDir . $strFileName )
                ->parseFile()
                ->setStructureFromMime()
                ->setPartsFromStructure();

    foreach ($mailParser->getPartsData() as $arrData) {
      $arrHeaders = $mailParser->getPartHeaders($arrData);

      if ( isset($arrHeaders['date'])) fwrite($fp, "Date: " . $arrHeaders['date'] . "\n");
      if ( isset($arrHeaders['from'])) fwrite($fp, "From: " . $arrHeaders['from'] . "\n");
      if ( isset($arrHeaders['subject'])) fwrite($fp, "Subject: " . $arrHeaders['subject'] . "\n");
    }

    fwrite($fp, "\n");
  }

  fclose($fp);
}

?>