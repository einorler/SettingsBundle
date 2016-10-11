<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\SettingsBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\FilterManagerBundle\SerializableInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ES\Document(type="experiment")
 */
class Experiment implements SerializableInterface
{
    /**
     * @var string
     *
     * @ES\Id()
     */
    private $id;

    /**
     * @var string
     *
     * @ES\Property(
     *  type="string",
     *  options={
     *    "fields"={
     *        "raw"={"type"="string", "index"="not_analyzed"},
     *        "name"={"type"="string"}
     *    }
     *  }
     * )
     */
    private $name;

    /**
     * @var array
     *
     * @ES\Property(
     *  type="string",
     *  options={
     *    "fields"={
     *        "raw"={"type"="string", "index"="not_analyzed"},
     *        "profile"={"type"="string"}
     *    }
     *  }
     * )
     */
    private $profile = [];

    /**
     * @var array
     *
     * @ES\Property(type="string", options={"index"="not_analyzed"})
     */
    private $os = [];

    /**
     * @var array
     *
     * @ES\Property(type="string", options={"index"="not_analyzed"})
     */
    private $client = [];

    /**
     * @var array
     *
     * @ES\Property(type="string", options={"index"="not_analyzed"})
     */
    private $device = [];

    /**
     * @var bool
     *
     * @ES\Property(type="bool")
     */
    private $active;

    /**
     * @var string
     *
     * @ES\Property(type="date")
     */
    private $createdAt;

    /**
     * Experiment constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param array $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return array
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @param array $os
     */
    public function setOs($os)
    {
        $this->os = $os;
    }

    /**
     * @return array
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param array $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param array $device
     */
    public function setDevice($device)
    {
        $this->device = $device;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = new DateTime($createdAt);
    }

    public function getSerializableData()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'profile' => $this->getProfile(),
            'os' => $this->getOs(),
            'client' => $this->getClient(),
            'device' => $this->getDevice()
        ];
    }
}
