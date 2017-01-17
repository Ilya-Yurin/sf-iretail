<?php
/**
 * Created by PhpStorm.
 * User: iyurin
 * Date: 13.11.16
 * Time: 12:30
 */

namespace VIS\Bundle\ApiBundle\Components\Http\Storage;

/**
 * Interface StorageInterface
 * @package VIS\Bundle\ApiBundle\Components\Http\Storage
 */
interface StorageInterface
{
    /**
     * @param $token
     * @param $data
     * @return mixed
     */
    public function write($token, $data);

    /**
     * @param $token
     * @return mixed
     */
    public function read($token);

    /**
     * @param $token
     * @return mixed
     */
    public function remove($token);

    /**
     * @param $user_id
     * @return mixed
     */
    public function removeByUserId($user_id);
}