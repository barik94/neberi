<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="neberi_report")
 */
class Report
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $fio;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $token;

    /**
     * @ORM\Column(type="string", type="text", nullable=true)
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="Officer", inversedBy="reports")
     * @ORM\JoinColumn(name="officer_id", referencedColumnName="id")
     */
    protected $officer;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $photo;

    protected $photoFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $media;

    protected $mediaFile;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * @param string
     * @return Report
     */
    public function setFio($fio)
    {
        $this->fio = $fio;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function setPhotoFile($file)
    {
        $this->photoFile = $file;

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getMediaFile()
    {
        return $this->mediaFile;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function setMediaFile($file)
    {
        $this->mediaFile = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param string $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * Set officer
     *
     * @param Officer $officer
     * @return Report
     */
    public function setOfficer(Officer $officer)
    {
        $this->officer = $officer;

        return $this;
    }

    /**
     * Get officer
     *
     * @return Officer
     */
    public function getOfficer()
    {
        return $this->officer;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Report
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
