<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;
use function App\timeago;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contract;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apply;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $requirements;

    /**
     * @ORM\Column(type="text")
     */
    private $role;

    /**
     * @ORM\Column(type="text")
     */
    private $requirementsDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $responsibilities;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userId;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getContract(): ?string
    {
        return $this->contract;
    }

    public function setContract(string $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getApply(): ?string
    {
        return $this->apply;
    }

    public function setApply(string $apply): self
    {
        $this->apply = $apply;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRequirements(): ?string
    {
        return $this->requirements;
    }

    public function setRequirements(string $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRequirementsDescription(): ?string
    {
        return $this->requirementsDescription;
    }

    public function setRequirementsDescription(string $requirementsDescription): self
    {
        $this->requirementsDescription = $requirementsDescription;

        return $this;
    }

    public function getResponsibilities(): ?string
    {
        return $this->responsibilities;
    }

    public function setResponsibilities(string $responsibilities): self
    {
        $this->responsibilities = $responsibilities;

        return $this;
    }

    public function setData($data)
    {
        foreach ($data as $key => $value) {
            if ($key != 'postedAt') $this->$key = $value;
        }
    }

    public function getPostedAt(): ?string
    {
        return $this->postedAt;
    }

    public function setPostedAt(): self
    {
        $this->postedAt = date('Y-m-d h:i:sa', time());;

        return $this;
    }

    public function getTimeago()
    {
        return timeago($this->postedAt);
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }


    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }


}
