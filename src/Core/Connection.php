<?php
/**
 * @author clivern <hello@clivern.com>
 */

namespace Clivern\Imap\Core;

use Clivern\Imap\Core\Exception\ConnectionError;

/**
 * Connection Class
 *
 * @package Clivern\Imap\Core
 */
class Connection
{
	/**
	 * @var string
	 */
	protected $server;

	/**
	 * @var integer
	 */
	protected $port;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string
	 */
	protected $flag;

	/**
	 * @var string
	 */
	protected $folder;

	/**
	 * @var mixed
	 */
	protected $stream;

	/**
	 * Class Constructor
	 *
	 * @param string $server
	 * @param string $port
	 * @param string $email
	 * @param string $password
	 * @param string $flag
	 * @param string $folder
	 * @return void
	 */
	public function __construct($server, $port, $email, $password, $flag = "/ssl", $folder = "INBOX")
	{
		$this->server = $server;
		$this->port = $port;
		$this->email = $email;
		$this->password = $password;
		$this->flag = $flag;
		$this->folder = $folder;
	}

	/**
	 * Connect to IMAP Email
	 *
	 * @return Connection
	 * @throws ConnectionError
	 */
	public function connect()
	{
		try {
			$this->stream = imap_open("{" . $this->server . ":" . $this->port . $this->flag . "}" . $this->folder, $this->email, $this->password);
		} catch (\Exception $e) {
			throw new ConnectionError("Error! Connecting to Imap Email.");
		}

		return $this;
	}

	/**
	 * Get Stream
	 *
	 * @return mixed
	 */
	public function getStream()
	{
		return $this->stream;
	}

	/**
	 * Get Server
	 *
	 * @return string
	 */
	public function getServer()
	{
		return $this->server;
	}

	/**
	 * Check Connection
	 *
	 * @return boolean
	 */
	public function checkConnection()
	{
		return (!is_null($this->stream) && imap_ping($this->stream));
	}

	/**
	 * Disconnect
	 *
	 * @param integer $flag
	 * @return boolean
	 */
	public function disconnect($flag = 0)
	{
		if( !is_null($this->stream) && imap_ping($this->stream) ){
			if( imap_close($this->stream, $flag) ){
				$this->stream = null;
				return true;
			}else{
				return false;
			}
		}

		return false;
	}
}