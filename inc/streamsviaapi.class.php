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
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginExampleStreamviaapi extends CommonDBTM {

   public function prepareInputForAdd($input) {
      if (isset ($_FILES['file']['error']) && !$_FILES['file']['error'] == UPLOAD_ERR_OK) {
         // Skeleton for error handling
         switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_NO_FILE:
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
            case UPLOAD_ERR_EXTENSION:
            default:
               Session::addMessageAfterRedirect(__('Could not upload file', "storkmdm"));
         }
         $input = false;
      } else {
         // Move uploaded file somewhere
      }

      return $input;
   }

   public function prepareInputForUpdate($input) {
      if (isset ($_FILES['file']['error']) && !$_FILES['file']['error'] == UPLOAD_ERR_OK) {
         // Skeleton for error handling
         switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_NO_FILE:
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
            case UPLOAD_ERR_EXTENSION:
            default:
               Session::addMessageAfterRedirect(__('Could not upload file', "storkmdm"));
         }
         $input = false;
      } else {
         // Move uploaded file somewhere
      }

      return $input;
   }



   public function post_getFromDB() {
      // Check the user can view this itemtype and can view thisitem
      if ($this->canView() && $this->canViewItem()) {
         if (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] == 'application/octet-stream') {
            $this->sendFile(); // and terminate script
         }
      }
   }

   protected function sendFile() {
      $streamSource = STORKMDM_FILE_PATH . "/" . $this->fields['source'];

      // Ensure the file exists
      if (!file_exists($streamSource)) {
         header("HTTP/1.0 404 Not Found");
         exit(0);
      }

      // Download range defaults to the full file
      // get file metadata
      $size = filesize($streamSource);
      $begin = 0;
      $end = $size;
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
               $end = intval($matches[2]);
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
      header("Content-disposition: attachment; filename=\"" . $this->fields['name'] . "\"");
      header("Content-type: $mimeType");
      header("Last-Modified: $time");
      header('Accept-Ranges: bytes');
      header('Content-Length:' . ($end - $begin));
      header("Content-Range: bytes $begin-$end/$size");
      header("Content-Transfer-Encoding: binary\n");
      header('Connection: close');

      // Prepare HTTP response
      if ($begin > 0 || $end < $size) {
         header('HTTP/1.0 206 Partial Content');
      } else {
         header('HTTP/1.0 200 OK');
      }

      // Sends bytes until the end of the range or connection closed
      while (!feof($fileHandle) && $currentPosition < $end && (connection_status() == 0)) {
         // allow a few seconds to send a few KB.
         set_time_limit(10);
         print fread($fileHandle, min(1024 * 16, $end - $currentPosition));
         $currentPosition += 1024 * 16;
      }

      // Endnow to prevent any unwanted bytes
      exit(0);
   }

}