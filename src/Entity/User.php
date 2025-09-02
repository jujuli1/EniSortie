<?php

namespace App\Entity;

use App\Repository\UserRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;


    #[ORM\Column(length: 50)]
    private ?string $phoneNumber = null;

 
    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;


    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    /**
     * @var Collection<int, Outing>
     */
    #[ORM\OneToMany(targetEntity: Outing::class, mappedBy: 'organizer')]
    private Collection $outingOrganizer;

    /**
     * @var Collection<int, Outing>
     */
    #[ORM\ManyToMany(targetEntity: Outing::class, mappedBy: 'participants')]
    private Collection $outingsParticipants;

    public function __construct()
    {
        $this->outingOrganizer = new ArrayCollection();
        $this->outingsParticipants = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }



    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }


    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $role): static
    {
        $this->roles = $role;


        return $this;
    }


    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getOutingOrganizer(): Collection
    {
        return $this->outingOrganizer;
    }

    public function addOutingOrganizer(Outing $outingOrganizer): static
    {
        if (!$this->outingOrganizer->contains($outingOrganizer)) {
            $this->outingOrganizer->add($outingOrganizer);
            $outingOrganizer->setOrganizer($this);
        }

        return $this;
    }

    public function removeOutingOrganizer(Outing $outingOrganizer): static
    {
        if ($this->outingOrganizer->removeElement($outingOrganizer)) {
            // set the owning side to null (unless already changed)
            if ($outingOrganizer->getOrganizer() === $this) {
                $outingOrganizer->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getOutingsParticipants(): Collection
    {
        return $this->outingsParticipants;
    }

    public function addOutingsParticipant(Outing $outingsParticipant): static
    {
        if (!$this->outingsParticipants->contains($outingsParticipant)) {
            $this->outingsParticipants->add($outingsParticipant);
            $outingsParticipant->addParticipant($this);
        }

        return $this;
    }

    public function removeOutingsParticipant(Outing $outingsParticipant): static
    {
        if ($this->outingsParticipants->removeElement($outingsParticipant)) {
            $outingsParticipant->removeParticipant($this);
        }

        return $this;
    }






    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }



}
