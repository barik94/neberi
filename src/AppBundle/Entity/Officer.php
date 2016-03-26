<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="neberi_officer")
 */
class Officer
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
     * @ORM\OneToMany(targetEntity="Report", mappedBy="officer")
     */
    protected $reports;

    /**
     * @ORM\Column(name="amount", type="integer", nullable=false, options={"default"=1})
     */
    protected $amountComplaints = 1;

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
     * @return Officer
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
     * @return integer
     */
    public function getAmountComplaints()
    {
        return $this->amountComplaints;
    }

    /**
     * @param integer $amount
     */
    public function setAmountComplaints($amount)
    {
        $this->amountComplaints = $amount;
    }

    public function incrementAmount()
    {
        $this->amountComplaints++;
    }

    /**
     * Add report
     *
     * @param Report $report
     * @return Officer
     */
    public function addReport(Report $report)
    {
        $this->reports[] = $report;

        return $this;
    }

    /**
     * Remove report
     *
     * @param Report $report
     * @return Officer
     */
    public function removeReport(Report $report)
    {
        $this->reports->removeElement($report);

        return $this;
    }

    /**
     * Get reports
     *
     * @return Collection|Report[]
     */
    public function getReports()
    {
        return $this->reports;
    }
}
