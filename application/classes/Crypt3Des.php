<?php
class Crypt3Des {
	private $key = "";
	private $iv = "";

	function __construct($key, $iv) {
		if (empty ( $key ) || empty ( $iv )) {
			echo 'key and iv is not valid';
			exit ();
		}
		$this->key = $key;
		$this->iv = $iv;
	}
	//加密
	public function encrypt($value) {
		$td = mcrypt_module_open ( MCRYPT_3DES, '', MCRYPT_MODE_CBC, '' );
		$iv = ($this->iv);
		$value = $this->PaddingPKCS7 ( $value );
		$key = ($this->key);
		mcrypt_generic_init ( $td, $key, $iv );
		$ret = base64_encode ( mcrypt_generic ( $td, $value ) );
		mcrypt_generic_deinit ( $td );
		mcrypt_module_close ( $td );
		return $ret;
	}
	//解密
	public function decrypt($value) {
		$td = mcrypt_module_open ( MCRYPT_3DES, '', MCRYPT_MODE_CBC, '' );
		$iv = ($this->iv);
		$key = ($this->key);
		mcrypt_generic_init ( $td, $key, $iv );
		$ret = trim ( mdecrypt_generic ( $td, base64_decode ( $value ) ) );
		$ret = $this->UnPaddingPKCS7 ( $ret );
		mcrypt_generic_deinit ( $td );
		mcrypt_module_close ( $td );
		return $ret;
	}
	private function PaddingPKCS7($data) {
		$block_size = mcrypt_get_block_size ( 'tripledes', 'cbc' );
		$padding_char = $block_size - (strlen ( $data ) % $block_size);
		$data .= str_repeat ( chr ( $padding_char ), $padding_char );
		return $data;
	}
	private function UnPaddingPKCS7($text) {
		$pad = ord ( $text {strlen ( $text ) - 1} );
		if ($pad > strlen ( $text )) {
			return false;
		}
		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad) {
			return false;
		}
		return substr ( $text, 0, - 1 * $pad );
	}
}
?>