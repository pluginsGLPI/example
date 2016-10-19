<?php

/*
 -------------------------------------------------------------------------
GLPI - Gestionnaire Libre de Parc Informatique
Copyright (C) 2003-2011 by the INDEPNET Development Team.

http://indepnet.net/   http://glpi-project.org
-------------------------------------------------------------------------

LICENSE

This file is part of GLPI.

GLPI is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

GLPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
 */

/**
 * Show how to dowload a file (or any stream) from the REST API
 * as well as metatadata stored in DB
 *
 * This itemtype is designed to be the same as Document in GLPi Core
 * to focus on the file dowload and upload features
 *
 *
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginExampleDocument extends Document {

   /**
    * Return the table used to stor this object
    * @return string
    */
   public static function getTable() {
      if (empty($_SESSION['glpi_table_of'][get_called_class()])) {
         $_SESSION['glpi_table_of'][get_called_class()] = Document::getTable();
      }

      return $_SESSION['glpi_table_of'][get_called_class()];
   }

   public function prepareInputForAdd($input) {
      $input['_only_if_upload_succeed'] = true;
      if (isset($_FILES['file'])) {
         // Move the uploaded file to GLPi's tmp dir
         $destination = GLPI_TMP_DIR."/".$_FILES['file']['name'];
         move_uploaded_file($_FILES['file']['tmp_name'], $destination);
         $input['_filename'][] = $_FILES['file']['name'];
      } else {
         return false;
      }
      return parent::prepareInputForAdd($input);
   }

   public function prepareInputForUpdate($input) {
      // Do not allow update of document
      return false;
   }

   public function post_getFromDB() {
      // Check the user can view this itemtype and can view this item
      if ($this->canView() && $this->canViewItem()) {
         if (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] == 'application/octet-stream'
               || isset($_GET['alt']) && $_GET['alt'] == 'media') {
            $this->sendFile(); // and terminate script
         }
      }
   }

   protected function sendFile() {
      $streamSource = GLPI_DOC_DIR."/".$this->fields['filepath'];

      // Ensure the file exists
      if (!file_exists($streamSource)) {
         header("HTTP/1.0 404 Not Found");
         exit(0);
      }

      // Download range defaults to the full file
      // get file metadata
      $size = filesize($streamSource);
      $begin = 0;
      $end = $size - 1;
      $mimeType = 'application/octet-stream';
      $time = date('r', filemtime($streamSource));

      // Open the file
      $fileHandle = @fopen($streamSource, 'rb');
      if (!$fileHandle) {
         header ("HTTP/1.0 500 Internal Server Error");
         exit(0);
      }

      // set range if specified by the client
      if (isset($_SERVER['HTTP_RANGE'])) {
         if (preg_match('/bytes=\h*(\d+)?-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)) {
            if (!empty($matches[1])) {
               $begin = intval($matches[1]);
            }
            if (!empty($matches[2])) {
               $end = min(intval($matches[2]), $end);
            }
         }
      }

      // seek to the begining of the range
      $currentPosition = $begin;
      if (fseek($fileHandle, $begin, SEEK_SET) < 0) {
         header("HTTP/1.0 500 Internal Server Error");
         exit(0);
      }

      // send headers to ensure the client is able to detect a corrupted download
      // example : less bytes than the expected range
      // send meta data
      // setup client's cache behavior
      header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
      header('Pragma: private'); /// IE BUG + SSL
      header('Cache-control: private, must-revalidate'); /// IE BUG + SSL
      header("Content-disposition: attachment; filename=\"" . $this->fields['filename'] . "\"");
      header("Content-type: $mimeType");
      header("Last-Modified: $time");
      header('Accept-Ranges: bytes');
      header('Content-Length:' . ($end - $begin + 1));
      header("Content-Range: bytes $begin-$end/$size");
      header("Content-Transfer-Encoding: binary\n");
      header('Connection: close');

      // Prepare HTTP response
      if ($begin > 0 || $end < $size - 1) {
         header('HTTP/1.0 206 Partial Content');
      } else {
         header('HTTP/1.0 200 OK');
      }

      // Sends bytes until the end of the range or connection closed
      while (!feof($fileHandle) && $currentPosition < $end && (connection_status() == 0)) {
         // allow a few seconds to send a few KB.
         set_time_limit(10);
         print fread($fileHandle, min(1024 * 16, $end - $currentPosition + 1));
         flush();
         $currentPosition += 1024 * 16;
      }

      // End now to prevent any unwanted bytes
      exit(0);
   }

}