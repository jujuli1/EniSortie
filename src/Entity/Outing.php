<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: OutingRepository::class)]
class Outing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le champs nom est requis")]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "le champs nom doit contenir au moins {{limit}} caractères.",
        maxMessage: "le champs nom ne doit pas dépasser {{limit}} caractères."
    )]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "le champs Date et heure du début est requis")]
    #[Assert\DateTime]
    private ?\DateTime $startDateTime = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "le champs durée est requis")]
    #[Assert\Positive(message: "la valeur de la durée ne doit etre que positive")]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "le champs Date Limite d'inscription est requis")]
    #[Assert\GreaterThan(propertyPath: "startDateTime")]
    private ?\DateTime $registrationLimitDate = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "le champs nombre maximal d'inscription est requis")]
    #[Assert\Positive(message: "la valeurnombre maximal d'inscription ne doit etre que positive")]
    private ?int $nbMaxRegistration = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "le champs infos sortie est requis")]
    #[Assert\Length(
        min: 2,
        max: 500,
        minMessage: "le champs info sur la sortie doit contenir au moins {{limit}} caractères.",
        maxMessage: "le champs info sur la sortie ne doit pas dépasser {{limit}} caractères.",
    )]
    private ?string $outingInfos = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

   #[ORM\ManyToOne(inversedBy: 'outings')]
    private ?Campus $campus = null;

  #[ORM\ManyToOne(inversedBy: 'outings')]
  #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

   #[ORM\ManyToOne(inversedBy: 'outingOrganizer')]
   #[ORM\JoinColumn(nullable: false)]
    private ?User $organizer = null;

    /**
     * @var Collection<int, User>
     */
   #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'outingsParticipants')]
    private Collection $participants;

   #[ORM\ManyToOne(inversedBy: 'outingOrganizer')]
   private ?Utilisateur $utilisateur = null;

   /**
    * @var Collection<int, Utilisateur>
    */
   #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'outingParticipants')]
   private Collection $utilisateurs;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDateTime(): ?\DateTime
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTime $startDateTime): static
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRegistrationLimitDate(): ?\DateTime
    {
        return $this->registrationLimitDate;
    }

    public function setRegistrationLimitDate(\DateTime $registrationLimitDate): static
    {
        $this->registrationLimitDate = $registrationLimitDate;

        return $this;
    }

    public function getNbMaxRegistration(): ?int
    {
        return $this->nbMaxRegistration;
    }

    public function setNbMaxRegistration(int $nbMaxRegistration): static
    {
        $this->nbMaxRegistration = $nbMaxRegistration;

        return $this;
    }

    public function getOutingInfos(): ?string
    {
        return $this->outingInfos;
    }

    public function setOutingInfos(string $outingInfos): static
    {
        $this->outingInfos = $outingInfos;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

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

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->addOutingParticipant($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            $utilisateur->removeOutingParticipant($this);
        }

        return $this;
    }

}
