<?php
// aes helper
class AesHelper {
	private $key = null;
	private $iv_size = null;
	private $iv = null;
	public function setKey($keyString) {
		$this->key = pack('H*', $keyString);

	    # create a random IV to use with CBC encoding
	    $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	    $this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
	}

	public function encript($plaintext) {
	    # creates a cipher text compatible with AES (Rijndael block size = 128)
	    # to keep the text confidential 
	    # only suitable for encoded input that never ends with value 00h
	    # (because of default zero padding)
	    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $plaintext, MCRYPT_MODE_CBC, $this->iv);

	    # prepend the IV for it to be available for decryption
	    $ciphertext = $this->iv . $ciphertext;
	    
	    # encode the resulting cipher text so it can be represented by a string
	    $ciphertext_base64 = base64_encode($ciphertext);

	    return $ciphertext_base64;
	}

	public function decript($ciphertext) {
	    $ciphertext = base64_decode($ciphertext);
	    
	    # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
	    $iv_dec = substr($ciphertext, 0, $this->iv_size);
	    
	    # retrieves the cipher text (everything except the $iv_size in the front)
	    $ciphertext = substr($ciphertext, $this->iv_size);

	    # may remove 00h valued characters from end of plain text
	    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $ciphertext, MCRYPT_MODE_CBC, $iv_dec);
	    
	    return trim($plaintext_dec);
	}
}
