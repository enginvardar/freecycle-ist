<?php
/**
 * Project freecycle
 * Sends via Mandrill
 *
 * @author      Mehmet Ali Ergut <mim@taximact.com>
 * @copyright   Mehmet Ali Ergut
 * @version     0.1
 * @date        02/02/14 00:35
 * @package     00:35
 */

namespace Config;

/**
 * Class Mail
 *
 * @package Config
 */
class Mail {
	/**
	 * @param $data
	 * array('to','name','from','reply','subject','title','body');
	 * @return bool
	 */
	public function send($data)
	{
		$message = json_encode($this->create($data));
		return $this->request('messages/send.json',$message);
	}

	/**
	 * @param $data
	 *
	 * @return array
	 */
	private function create($data)
	{
		return array(
			'key'=> Config::get('key','mandrill'),
			'message'=> array(
				'html'=>$data['body'],
				'subject'=>$data['subject'],
				'from_email'=>$data['from'],
				'from_name'=>$data['name'],
				'to'=>array(
					array(
						'email'=>$data['to'],
						'name'=>$data['name'],
						'type'=>'to'
					),
				),
				'headers' => array('Reply-To'=>$data['reply']),
				'important' => true,
			),
		);
	}

	/**
	 * @param string $path
	 * @param $data
	 *
	 * @return bool
	 */
	private function request($path='messages/send.json',$data)
	{
		$url = Config::get('url','mandrill').$path;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
		$output = curl_exec($ch);
		curl_close($ch);
		var_dump($output);
		return $this->parse($output);
	}

	/**
	 * @param $data
	 *
	 * @return bool
	 */
	private function parse($data)
	{
		$data = json_decode($data);
		if(isset($data->status)){
			return false;
		} else {
			return true;
		}
	}
} 
