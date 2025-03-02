<?php

if (!function_exists('write_log')) {
  function write_log($log) {
    try {
      file_put_contents(WP_CONTENT_DIR.'/nguyen.log', date("r").":\n". $log ."\n---\n", FILE_APPEND);

    } catch (Exception $e) {
      $this->throwError($e->getMessage());
    }
  }
}
