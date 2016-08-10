<?php

class MailParser {

  private $strFileName;
  private $rscMIME;
  private $arrStructure;
  private $arrPartsData;

  public function __construct() { }

  public function __destruct() {
    if ( isset($this->rscMIME) ) {
      mailparse_msg_free($this->rscMIME);
    }
  }

  /*
   * Class Methods
  */

  public function parseFile() {
    // verify the file path is properly set
    if ( isset($this->strFileName) && file_exists($this->strFileName)) {
      // parse the file into the mime resource
      $this->setMime(mailparse_msg_parse_file($this->strFileName));
      return $this;
    } else {
      throw new Exception('Please set a valid file name');
      return false;
    }
  }

  public function setStructureFromMime() {
    if ( isset($this->rscMIME) ) {
      // get the structure from the mime resource
      $this->setStructure(mailparse_msg_get_structure($this->rscMIME));
      return $this;
    } else {
      throw new Exception('Parse the file first');
      return false;
    }
  } 

  public function setPartsFromStructure() {
    if ( isset($this->arrStructure) ) {

      foreach($this->arrStructure as $intPartId) { 
        // store each part from the the current message structure
        $rscSection = mailparse_msg_get_part($this->rscMIME, $intPartId); 
        $arrData = mailparse_msg_get_part_data($rscSection); 
        $this->addPartsData($arrData, $intPartId);
      } 
      return $this;
    } else {
      throw new Exception('Set structrure from mime first');
      return false;
    }
  }

  public function getPartHeaders($arrPartData) {
    if ( isset($arrPartData['headers']) ) {
      return $arrPartData['headers'];
    }
    return false;
  }

  /*
   * Getters and Setters
  */

  public function setFileName( $strFileName ) {
    $this->strFileName = $strFileName;
    return $this;
  }

  public function getFileName() {
    return $this->strFileName;
  }

  private function setMime( $rscMIME ) {
    $this->rscMIME = $rscMIME;
    return $this;
  }

  public function getMime() {
    return $this->rscMIME;
  }

  private function setStructure( $arrStructure ) {
    $this->arrStructure = $arrStructure;
    return $this;
  }

  public function getStructure() {
    return $this->arrStructure;
  }

  private function addPartsData( $arrPartData, $intPartId ) {

    if ( !isset($this->arrPartsData) ) {
      $this->arrPartsData = array();
    }
    $this->arrPartsData[$intPartId] = $arrPartData;
    return $this;
  }

  public function getPartsData() {
    return $this->arrPartsData;
  }
}

?>