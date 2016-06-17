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

/**
 * Stores admin settings.
 *
 * @ES\Document(type="setting")
 */
class Setting implements SerializableInterface
{
    /**
     * @var string
     *
     * @ES\Id()
     */
    private $id;

    /**
     * @const TYPE_STRING setting model of string type
     */
    const TYPE_STRING = 'string';

    /**
     * @const TYPE_ARRAY setting model of array type
     */
    const TYPE_ARRAY = 'array';

    /**
     * @const TYPE_BOOLEAN setting model of boolean type
     */
    const TYPE_BOOLEAN = 'bool';

    /**
     * @const TYPE_OBJECT setting model of object type
     */
    const TYPE_OBJECT = 'object';

    /**
     * @var string
     *
     * @ES\Property(type="string", options={"analyzer"="standard"})
     */
    private $name;

    /**
     * @var string
     *
     * @ES\Property(type="string", options={"analyzer"="standard"})
     */
    private $description;

    /**
     * @var string
     *
     * @ES\Property(type="string", options={"analyzer"="standard"})
     */
    private $profile;

    /**
     * @var string
     *
     * @ES\Property(type="string", options={"analyzer"="standard"})
     */
    private $type;

    /**
     * @var string
     *
     * @ES\Property(type="string", options={"index"="not_analyzed"})
     */
    private $value;

    /**
     * @var string
     *
     * @ES\Property(type="string", options={"index"="not_analyzed"})
     */
    private $salt;

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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get profile.
     *
     * @return string|array
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set profile.
     *
     * @param string|array $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return mixed Data which can be serialized by json_encode.
     */
    public function getSerializableData()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'profile' => $this->getProfile(),
            'type' => $this->getType(),
            'value' => $this->getValue(),
        ];
    }
}
