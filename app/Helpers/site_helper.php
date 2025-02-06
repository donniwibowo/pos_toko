<?php
	class EncryptionConfig {
		public $ciphering = "AES-128-CTR";
		public $options = 0;
		

		public function getEncryptionKey() {
			// return openssl_digest(php_uname(), 'MD5', TRUE);
			return 'G72FHT1O9UDJCAQB';
		}

		public function getEncryptionIv() {
			// return random_bytes(openssl_cipher_iv_length($this->ciphering));
			return '3155634379930093';
		}
	}
	

	function pos_encrypt($value) {
		$encryption_config = new EncryptionConfig;
		return openssl_encrypt($value, $encryption_config->ciphering, $encryption_config->getEncryptionKey(), $encryption_config->options, $encryption_config->getEncryptionIv());
	}

	function pos_decrypt($value) {
		$encryption_config = new EncryptionConfig;
		return openssl_decrypt($value, $encryption_config->ciphering, $encryption_config->getEncryptionKey(), $encryption_config->options, $encryption_config->getEncryptionIv());
	}
